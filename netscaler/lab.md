# Quick NetScaler Lab Configuration

## Network
	
	set ns config -IPAddress 192.168.34.20 -netmask 255.255.255.0
	enable ns mode USNIP
	set ns hostName ns1
	add ns ip 192.168.34.21 255.255.255.0 -vServer DISABLED
	add dns nameServer 192.168.34.1
	add route 0.0.0.0 0.0.0.0 192.168.34.1

## Load Balancing
	
	enable ns feature LB SSL
	
	create ssl rsakey self-signed.key 1024 -exponent F4 -keyform PEM
	create ssl certreq self-signed.req -keyfile self-signed.key -keyform PEM -countryName US -stateName Florida -localityName "Fort Lauderdale" -organizationName "Citrix Systems Inc." -organizationUnitName Support
	create ssl cert self-signed.cert self-signed.req ROOT_CERT -keyFile self-signed.key -keyForm PEM -days 3650
	add ssl certKey self-signed -cert self-signed.cert -key self-signed.key
	
	add service svc-http-1 192.168.34.50 HTTP 80
	add service svc-http-2 192.168.34.51 HTTP 80
	add service svc-http-3 192.168.34.52 HTTP 80
	bind service svc-http-1 -monitorName http
	bind service svc-http-2 -monitorName http
	bind service svc-http-3 -monitorName http
	
	add lb vserver lbvs-http-1 HTTP 192.168.34.70 80
	bind lb vserver lbvs-http-1 svc-http-1
	bind lb vserver lbvs-http-1 svc-http-2
	bind lb vserver lbvs-http-1 svc-http-3
	
	add lb vserver lbvs-ssl-1 SSL 192.168.34.70 443
	bind ssl vserver lbvs-ssl-1 -certkeyName self-signed
	bind lb vserver lbvs-ssl-1 svc-http-1
	bind lb vserver lbvs-ssl-1 svc-http-2
	bind lb vserver lbvs-ssl-1 svc-http-3

