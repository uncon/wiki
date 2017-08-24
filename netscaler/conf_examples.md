# Configuration Examples

## Redirect HTTP to SSL
This responder policy and action will redirect http traffic to an SSL vserver while maintaining the URL.

	add responder action https_redir_act respondwith q{"HTTP/1.1 301 Moved Permanently\r\n" + "Location: https://" + HTTP.REQ.HOSTNAME + HTTP.REQ.URL.PATH_AND_QUERY.HTTP_URL_SAFE + "\r\nConnection: close\r\n\r\n"}
	add responder policy https_redir_pol CLIENT.SSL.IS_SSL.NOT https_redir_act

## AGEE: Rewrite To Insert Domain Cookie
This config will modify the login.js file on the fly. The rewrite will extract the domain (all text prior to a \ or all text after a @ in the user name field) and place it in a cookie named 'Domain' with a 2 hour expiration.

You can then match on this cookie in a sessionPolicy (`REQ.HTTP.HEADER Cookie CONTAINS Domain=MyDomain`) that specifies the SSO domain.

You will likely have to clear the cache on the NetScaler (`flush cache contentgroup ALL`) to see the effects of this modification.

### DOMAIN\sAMAccountName
	add rewrite action domain_extract_act insert_after "HTTP.RES.BODY(1024).REGEX_SELECT(re/function ns_check\\(\\).*return false;\\W*}/)" "\"\n\tvar domain = login.replace(/\\\\\\\\.*/, \\\"\\\");\n\tvar expiry = new Date(+new Date + 7200000); // +2 hours\n\tdocument.cookie = \\\"Domain=\\\" + escape(domain) + \\\"; path=/; expires=\\\" + expiry.toGMTString();\"" -bypassSafetyCheck YES
	add rewrite policy domain_extract_pol "HTTP.REQ.URL.PATH.ENDSWITH(\"vpn/login.js\")" domain_extract_act
	bind vpn vserver vpnvs-1 -policy domain_extract_pol -priority 100 -gotoPriorityExpression END -type RESPONSE

The configuration above will send the login field as-is.  If you need to strip the domain from the field prior to authentication, you will need to make the following change.
	
	add rewrite action domain_extract_act insert_after "HTTP.RES.BODY(1024).REGEX_SELECT(re/function ns_check\\(\\).*return false;\\W*}/)" "\"\n\tvar domain = login.replace(/\\\\\\\\.*/, \\\"\\\");\n\tdocument.forms[\'vpnForm\'].login.value = login.replace(/.*\\\\\\\\/, \\\"\\\");\n\tvar expiry = new Date(+new Date + 7200000); // 2hr\n\tdocument.cookie = \\\"Domain=\\\" + escape(domain) + \\\"; path=/; expires=\\\" + expiry.toGMTString();\"" -bypassSafetyCheck YES

### userPrincipalName
	add rewrite action domain_extract_act insert_after "HTTP.RES.BODY(1024).REGEX_SELECT(re/function ns_check\\(\\).*return false;\\W*}/)" "\"\n\tvar domain = login.replace(/.*@/, \\\"\\\");\n\tvar expiry = new Date(+new Date + 7200000); // +2 hours\n\tdocument.cookie = \\\"Domain=\\\" + escape(domain) + \\\"; path=/; expires=\\\" + expiry.toGMTString();\"" -bypassSafetyCheck YES
	add rewrite policy domain_extract_pol "HTTP.REQ.URL.PATH.ENDSWITH(\"vpn/login.js\")" domain_extract_act
	bind vpn vserver vpnvs-1 -policy domain_extract_pol -priority 100 -gotoPriorityExpression END -type RESPONSE

## Obfuscate Authentication Failures
This rewrite policy and action will obfuscate Enhanced Authentication Feedback (`set aaa parameter -enableEnhancedAuthFeedback YES`) so that only certain failure messages are reported to the client.  You can find the definitions of the error codes in `/resources/en.xml` (e.g., `<String id="errorMessageLabel4001">Incorrect credentials. Try again.</String>`).

The example below will only allow error codes 4008, 4014, and 4016 to be reported to the client.  All other errors will be reported as 4001 ("Incorrect credentials. Try again.").

	add rewrite action dropCookie_rwact replace "HTTP.RES.SET_COOKIE.COOKIE(\"NSC_VPNERR\")" "\"NSC_VPNERR=4001;Path=/;Secure\""
	add rewrite policy dropCookie_rwpol "HTTP.RES.SET_COOKIE.COOKIE(\"NSC_VPNERR\").VALUE(\"NSC_VPNERR\").TYPECAST_NUM_AT.NE(4008) && HTTP.RES.SET_COOKIE.COOKIE(\"NSC_VPNERR\").VALUE(\"NSC_VPNERR\").TYPECAST_NUM_AT.NE(4014) && HTTP.RES.SET_COOKIE.COOKIE(\"NSC_VPNERR\").VALUE(\"NSC_VPNERR\").TYPECAST_NUM_AT.NE(4016)" dropCookie_rwact
	bind vpn vserver vpnvs-1 -policy dropCookie_rwpol -priority 100 -gotoPriorityExpression NEXT -type RESPONSE


## Group Persistence While Honoring TCP Port
This will encrypt the IP and port with AES256 and a random key and set it in a cookie.

	add service svc-1 192.168.34.50 HTTP 5080 -CustomServerID 192.168.34.50:5080
	add service svc-2 192.168.34.50 HTTP 5081 -CustomServerID 192.168.34.50:5081
	add service svc-3 192.168.34.50 HTTP 5082 -CustomServerID 192.168.34.50:5082
	add rewrite action SetCustomServerID-act insert_http_header Set-Cookie "\"CustomServerID=\" + SERVER.IP.SRC.TYPECAST_TEXT_T.APPEND(\":\").APPEND(SERVER.TCP.SRCPORT.TYPECAST_TEXT_T).ENCRYPT + \";path=/;httponly\""
	add rewrite policy SetCustomServerID-pol TRUE SetCustomServerID-act
	add lb vserver lbvs-1 HTTP 192.168.34.76 80 -persistenceType CUSTOMSERVERID -rule "HTTP.REQ.COOKIE.VALUE(\"CustomServerID\").DECRYPT" -cltTimeout 180
	bind lb vserver lbvs-1 svc-1
	bind lb vserver lbvs-1 svc-2
	bind lb vserver lbvs-1 svc-3
	bind lb vserver lbvs-1 -policyName SetCustomServerID-pol -priority 100 -gotoPriorityExpression END -type RESPONSE

The set cookie header will be similar to the following.
	
	Set-Cookie: CustomServerID=AAAAAAU+FPLUYrtDMHx7iRAPs3JjpXM1tEWwpYUQBXly2N+ENqUPkos8prJu2FMbAC3Qm90=;path=/;httponly

Persistence will be honored based on CustomServerID.

## Insert Debuging HTTP Header
Insert an HTTP header (`NSDbg`) containing the backend server IP and TCP port number for a specific subnet
	
	add rewrite action nsdbg_rw_act insert_http_header NSDbg "SERVER.IP.SRC + \":\" +  SERVER.TCP.SRCPORT"
	add rewrite policy nsdbg_rw_pol "CLIENT.IP.SRC.IN_SUBNET(10.198.4.0/24)" nsdbg_rw_act
	bind lb vserver lbvs-1 -policyName nsdbg_rw_pol -priority 100 -gotoPriorityExpression END -type RESPONSE

## Insert HTTP Header
	add rewrite action XFrameOpt_rw_act insert_http_header X-Frame-Options "\"SAMEORIGIN\""
	add rewrite policy XFrameOpt_rw_pol TRUE XFrameOpt_rw_act
	bind vpn vserver vpnvs-1 -policy XFrameOpt_rw_pol -priority 100 -gotoPriorityExpression NEXT -type RESPONSE

## Remove HTTP Header
	add rewrite action rm_jsessionid_cookie_act replace "HTTP.REQ.HEADER(\"Cookie\").REGEX_SELECT(re/(\?i) JSESSIONID=\\S*;/)" "\"\""
	add rewrite policy rm_jsessionid_cookie_pol TRUE rm_jsessionid_cookie_act
