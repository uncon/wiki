# Quick NetScaler Lab Configuration

## Network
	
	set ns config -IPAddress 192.168.34.40 -netmask 255.255.255.0
	enable ns mode USNIP
	set ns hostName ns1
	add ns ip 192.168.34.41 255.255.255.0 -vServer DISABLED
	add dns nameServer 192.168.34.1
	add route 0.0.0.0 0.0.0.0 192.168.34.1

## Load Balancing
	
	enable ns feature LB SSL
	
	create ssl rsakey self-signed.key 1024 -exponent F4 -keyform PEM
	create ssl certreq self-signed.req -keyfile self-signed.key -keyform PEM -countryName US -stateName Florida -localityName "Fort Lauderdale" -organizationName "Citrix Systems Inc." -organizationUnitName Support
	create ssl cert self-signed.cert self-signed.req ROOT_CERT -keyFile self-signed.key -keyForm PEM -days 3650
	add ssl certKey self-signed -cert self-signed.cert -key self-signed.key
	
	add service svc-http-blue 192.168.34.81 HTTP 80
	add service svc-http-red 192.168.34.82 HTTP 80
	add service svc-http-green 192.168.34.83 HTTP 80
	add service svc-http-maroon 192.168.34.84 HTTP 80
	bind service svc-http-blue -monitorName http
	bind service svc-http-red -monitorName http
	bind service svc-http-green -monitorName http
	bind service svc-http-maroon -monitorName http

	add service svc-ssl-blue 192.168.34.81 SSL 80
	add service svc-ssl-red 192.168.34.82 SSL 80
	add service svc-ssl-green 192.168.34.83 SSL 80
	add service svc-ssl-maroon 192.168.34.84 SSL 80
	bind service svc-ssl-blue -monitorName https
	bind service svc-ssl-red -monitorName https
	bind service svc-ssl-green -monitorName https
	bind service svc-ssl-maroon -monitorName https

	add lb vserver lbvs-http-1 HTTP 192.168.34.70 80
	bind lb vserver lbvs-http-1 svc-http-blue
	bind lb vserver lbvs-http-1 svc-http-red
	bind lb vserver lbvs-http-1 svc-http-green
	bind lb vserver lbvs-http-1 svc-http-maroon
	
	add lb vserver lbvs-ssl-1 SSL 192.168.34.70 443
	bind ssl vserver lbvs-ssl-1 -certkeyName self-signed
	bind lb vserver lbvs-ssl-1 svc-http-blue
	bind lb vserver lbvs-ssl-1 svc-http-red
	bind lb vserver lbvs-ssl-1 svc-http-green
	bind lb vserver lbvs-ssl-1 svc-http-maroon
