# Quick NetScaler Lab Configuration

## Network
	
	set ns config -IPAddress 10.25.180.10 -netmask 255.255.255.192
	enable ns feature LB CS SSL GSLB SSLVPN REWRITE RESPONDER
	enable ns mode USNIP
	set ns hostName ns1
	add ns ip 10.25.180.11 255.255.255.192 -vServer DISABLED
	add dns nameServer 10.25.180.1
	add route 0.0.0.0 0.0.0.0 10.25.180.1

## SSL

	enable ns feature SSL
	
	create ssl rsakey test-cert-root.key 512 -exponent F4 -keyform PEM
	create ssl certReq test-cert-root.req -keyFile test-cert-root.key -keyform PEM -countryName US -stateName California -organizationName "NetScaler Inc." 
	create ssl cert test-cert-root.cert test-cert-root.req ROOT_CERT -keyFile test-cert-root.key -keyform PEM -days 365 -certForm PEM -CAcertForm PEM -CAkeyForm PEM

	create ssl rsakey test-cert.key 512 -exponent F4 -keyform PEM
	create ssl certReq test-cert.req -keyFile test-cert.key -keyform PEM -countryName US -stateName California -organizationName "NetScaler Inc." -organizationUnitName "SSL Acceleration" -localityName "Santa Clara" -commonName test-cert
	create ssl cert test-cert.cert test-cert.req SRVR_CERT -keyform PEM -days 365 -certForm PEM -CAcert test-cert-root.cert -CAcertForm PEM -CAkey test-cert-root.key -CAkeyForm PEM -CAserial CASerial

	add ssl certKey test-cert -cert test-cert.cert -key test-cert.key -inform PEM -expiryMonitor ENABLED -notificationPeriod 30 -bundle NO

## Load Balancing

	enable ns feature LB SSL

	add service svc-http-black 10.25.180.20 HTTP 80
	add service svc-http-blue 10.25.180.21 HTTP 80
	add service svc-http-red 10.25.180.22 HTTP 80
	add service svc-http-green 10.25.180.23 HTTP 80
	bind service svc-http-black -monitorName http
	bind service svc-http-blue -monitorName http
	bind service svc-http-red -monitorName http
	bind service svc-http-green -monitorName http

	add service svc-ssl-black 10.25.180.20 SSL 443
	add service svc-ssl-blue 10.25.180.21 SSL 443
	add service svc-ssl-red 10.25.180.22 SSL 443
	add service svc-ssl-green 10.25.180.23 SSL 443
	bind service svc-ssl-black -monitorName https
	bind service svc-ssl-blue -monitorName https
	bind service svc-ssl-red -monitorName https
	bind service svc-ssl-green -monitorName https

	add lb vserver lbvs-http-1 HTTP 10.25.180.30 80
	bind lb vserver lbvs-http-1 svc-http-black
	bind lb vserver lbvs-http-1 svc-http-blue
	bind lb vserver lbvs-http-1 svc-http-red
	bind lb vserver lbvs-http-1 svc-http-green

	add lb vserver lbvs-ssl-1 SSL 10.25.180.30 443
	bind ssl vserver lbvs-ssl-1 -certkeyName test-cert
	bind lb vserver lbvs-ssl-1 svc-http-black
	bind lb vserver lbvs-ssl-1 svc-http-blue
	bind lb vserver lbvs-ssl-1 svc-http-red
	bind lb vserver lbvs-ssl-1 svc-http-green
