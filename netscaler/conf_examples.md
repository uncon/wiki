# Configuration Examples

## Redirect HTTP to SSL
This responder policy and action will redirect http traffic to an SSL vserver while maintaining the URL.

	add responder action https_redir_act respondwith q{"HTTP/1.1 301 Moved Permanently\r\n" + "Location: https://" + HTTP.REQ.HOSTNAME + HTTP.REQ.URL.PATH_AND_QUERY.HTTP_URL_SAFE + "\r\n\r\n"}
	add responder policy https_redir_pol CLIENT.SSL.IS_SSL.NOT https_redir_act

## AGEE: Rewrite To Insert Domain Cookie
This config will modify the login.js file on the fly. The rewrite will extract the domain (all text prior to a \ or all text after a @ in the user name field) and place it in a cookie named 'Domain' with a 2 hour expiration.

You can then match on this cookie in a sessionPolicy (`REQ.HTTP.HEADER Cookie CONTAINS Domain=MyDomain`) that specifies the SSO domain.

You will likely have to clear the cache on the NetScaler (`flush cache contentgroup ALL`) to see the effects of this modification.

### DOMAIN\sAMAccountName
	add rewrite action domain_extract_act insert_after "HTTP.RES.BODY(1024).REGEX_SELECT(re/function ns_check\\(\\).*return false;\\W*}/)" "\"\n\tvar domain = login.replace(/\\\\\\\\.*/, \\\"\\\");\n\tvar expiry = new Date(+new Date + 7200000); // +2 hours\n\tdocument.cookie = \\\"Domain=\\\" + escape(domain) + \\\"; path=/; expires=\\\" + expiry.toGMTString();\"" -bypassSafetyCheck YES
	add rewrite policy domain_extract_pol "HTTP.REQ.URL.PATH.ENDSWITH(\"vpn/login.js\")" domain_extract_act
	bind vpn vserver agee_vserver -policy domain_extract_pol -priority 100 -gotoPriorityExpression END -type RESPONSE

The configuration above will send the login field as-is.  If you need to strip the domain from the field prior to authentication, you will need to make the following change.
	
	add rewrite action domain_extract_act insert_after "HTTP.RES.BODY(1024).REGEX_SELECT(re/function ns_check\\(\\).*return false;\\W*}/)" "\"\n\tvar domain = login.replace(/\\\\\\\\.*/, \\\"\\\");\n\tdocument.forms[\'vpnForm\'].login.value = login.replace(/.*\\\\\\\\/, \\\"\\\");\n\tvar expiry = new Date(+new Date + 7200000); // 2hr\n\tdocument.cookie = \\\"Domain=\\\" + escape(domain) + \\\"; path=/; expires=\\\" + expiry.toGMTString();\"" -bypassSafetyCheck YES

### userPrincipalName
	add rewrite action domain_extract_act insert_after "HTTP.RES.BODY(1024).REGEX_SELECT(re/function ns_check\\(\\).*return false;\\W*}/)" "\"\n\tvar domain = login.replace(/.*@/, \\\"\\\");\n\tvar expiry = new Date(+new Date + 7200000); // +2 hours\n\tdocument.cookie = \\\"Domain=\\\" + escape(domain) + \\\"; path=/; expires=\\\" + expiry.toGMTString();\"" -bypassSafetyCheck YES
	add rewrite policy domain_extract_pol "HTTP.REQ.URL.PATH.ENDSWITH(\"vpn/login.js\")" domain_extract_act
	bind vpn vserver agee_vserver -policy domain_extract_pol -priority 100 -gotoPriorityExpression END -type RESPONSE

## Group Persistence While Honoring TCP Port
This will encrypt the IP and port with AES256 and a random key and set it in a cookie.  (You can find more detail in eDocs: [Encrypting and Decrypting Text](http://www.google.com/url?q=http%3A%2F%2Fsupport.citrix.com%2Fproddocs%2Ftopic%2Fnetscaler-policy-configuration-93-map%2Fns-pi-adv-exp-eval-txt-encrypt-decrypt-txt-con.html&sa=D&sntz=1&usg=AFQjCNEJuqY-AKwJa30Blf3UAN3fzvGjWg).)

	add service svc1 192.168.34.50 HTTP 5080 -CustomServerID 192.168.34.50:5080
	add service svc2 192.168.34.50 HTTP 5081 -CustomServerID 192.168.34.50:5081
	add service svc3 192.168.34.50 HTTP 5082 -CustomServerID 192.168.34.50:5082
	add rewrite action SetCustomServerID-act insert_http_header Set-Cookie "\"CustomServerID=\" + SERVER.IP.SRC.TYPECAST_TEXT_T.APPEND(\":\").APPEND(SERVER.TCP.SRCPORT.TYPECAST_TEXT_T).ENCRYPT + \";path=/;httponly\""
	add rewrite policy SetCustomServerID-pol TRUE SetCustomServerID-act
	add lb vserver lbvs1 HTTP 192.168.34.76 80 -persistenceType CUSTOMSERVERID -rule "HTTP.REQ.COOKIE.VALUE(\"CustomServerID\").DECRYPT" -cltTimeout 180
	bind lb vserver lbvs1 svc1
	bind lb vserver lbvs1 svc2
	bind lb vserver lbvs1 svc3
	bind lb vserver lbvs1 -policyName SetCustomServerID-pol -priority 100 -gotoPriorityExpression END -type RESPONSE

The set cookie header will be similar to the following.
	
	Set-Cookie: CustomServerID=AAAAAAU+FPLUYrtDMHx7iRAPs3JjpXM1tEWwpYUQBXly2N+ENqUPkos8prJu2FMbAC3Qm90=;path=/;httponly

Persistence will be honored based on CustomServerID.

## Insert Debuging HTTP Header
Insert an HTTP header (`NSDbg`) containing the backend server IP and TCP port number for a specific subnet
	
	add rewrite action nsdbg_rw_act insert_http_header NSDbg "SERVER.IP.SRC + \":\" +  SERVER.TCP.SRCPORT"
	add rewrite policy nsdbg_rw_pol "CLIENT.IP.SRC.IN_SUBNET(10.198.4.0/24)" nsdbg_rw_act
	bind lb vserver [lb_vserver] -policyName nsdbg_rw_pol -priority 100 -gotoPriorityExpression END -type RESPONSE

## Remove HTTP Header
	add rewrite action rm_jsessionid_cookie_act replace "HTTP.REQ.HEADER(\"Cookie\").REGEX_SELECT(re/(\?i) JSESSIONID=\\S*;/)" "\"\""
	add rewrite policy rm_jsessionid_cookie_pol TRUE rm_jsessionid_cookie_act

