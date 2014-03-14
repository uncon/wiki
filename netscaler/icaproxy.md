# ICA Proxy Traffic Flow

1. The client sends request to FQDN of vpn vserver (HTTP `GET`)

1. The vpn vserver determines the type of the incoming connection to be unauthenticated HTTP and redirects to the authentication page (`HTTP 302`; `Location: /vpn/index.html`)

1. The client sends credentials to the vpn vserver (HTTP `POST /cgi/login`)

1. The vpn vserver sets authentication cookies and redirects to set client type based on the HTTP User-Agent header from the client (`HTTP 302`; `Location: /cgi/setclient?cvpn`)

1. The client sends a request (HTTP `GET /cgi/setclient?cvpn`)

1. The vpn vserver determines the type of incoming connection to be authenticated HTTP and redirects to `/vpns/cvpnpage.html` (`HTTP 302`)

1. The client sends a request (HTTP `GET /vpns/cvpnpage.html`)

1. The vpn vserver redirects again to StoreFront (SF) or Web Interface (WI) page via Clienteles VPN: `/cvpn/https/storefront.internal.com/Citrix/StoreWeb` (`HTTP 302`)
 
1. The client sends a request (HTTP `GET /cvpn/https/storefront.internal.com/Citrix/StoreWeb`)

1. The SF/WI server performs a SSO call-back to the vpn vserver (HTTP `POST /CitrixAuthService/AuthService.asmx`; `<GetAccessInformation [...]>`)

1. The vpn vserver responds to SF/WI including any Smart Access information (`HTTP 200`; `<GetAccessInformationResult>`)

1. The SF/WI server enumerates the user's applications and presents them to the client via via Clienteles VPN  (`HTTP 200`)

1. The user launches (clicks on) an application
	- Store Front: `/cvpn/https/storefront.internal.com/Citrix/StoreWeb/Resources/LaunchIca/[...]`
	- Web Interface: `/cvpn/https/webinterface.internal.com/Citrix/XenApp/site/launcher.aspx[...]`

1. The SF/WI server constructs and sends an ICA file (`HTTP 200`)
	- `Address` contains the STA ticket
	- `SSLProxyHost` contains the FQDN and TCP port number of the gateway

1.  Receiver is launched with the ICA file and sends a SOCKSv5 `CONNECT` or CGP request to the server.
	- The address type of the SOCKSv5 `CONNECT` request is `DOMAINNAME` and the `DST.ADDR` bits contain the STA ticket.

1. The vpn vserver determines the type of incoming connection to be SOCKSv5 or CGP and handles the proxy connection.
	- The NetScaler inspects the connection data to determine the type of connection (protocol) based on a byte sequence signature.
	- If the inspected bytes match a protocol signature, the handler registered with that signature is called.
	- These handles consist of HTTP, SOCKSv5, and CGP.
	- The current implementation assumes that SOCKSv5 and CGP connections are coming from an ICA client.

1. The NetScaler performs a data request by sending the STA ticket back to the STA server and requesting its corresponding data. (`<RequestData>`)

1. The STA server forwards the original data to the gateway. (notably, `<Value name="CGPAddress">` and `<Value name="ICAAddress">`)

1. This data is used to complete the subsequent proxy requests from Receiver via SOCKSv5 or CGP.