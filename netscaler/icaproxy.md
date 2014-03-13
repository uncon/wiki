# ICA Proxy Traffic Flow

1. Client sends request to FQDN of vpn vserver (`HTTP GET`)

2. The vpn vserver determines the type of the incoming connection to be unauthenticated HTTP and redirects to the authentication page at `/vpn/index.html` (`HTTP 302`)

3. Client sends credentials to `/cgi/login` (`HTTP POST`)

4. NetScaler sets authentication cookies and redirects to `/cgi/setclient?cvpn` (`HTTP 302`)

5. Client sends request to `/cgi/setclient?cvpn` (`HTTP GET`)

6. The vpn vserver determines the type of incoming connection to be authenticated HTTP and redirects to `/vpns/cvpnpage.html` (`HTTP 302`)

7. NetScaler redirects to StoreFront (SF) or Web Interface (WI) page via Clienteles VPN: `/cvpn/https/go.citrite.net/Citrix/StoreWeb` (`HTTP 302`)

8. When the user launches an application, the SF/WI server constructs an ICA file and inserts an STA ticket in the `Address` field of the ICA file.

9.  Receiver is launched with the ICA file and sends a SOCKSv5 `CONNECT` or CGP request to the server.
	- The address type of the SOCKSv5 `CONNECT` request is `DOMAINNAME` and the `DST.ADDR` bits contain the STA ticket.

11. The vpn vserver determines the type of incoming connection to be SOCKSv5 or CGP and handles the proxy connection.
	- The NetScaler inspects the connection data to determine the type of connection (protocol) based on a byte sequence signature.
	- If the inspected bytes match a protocol signature, the handler registered with that signature is called.
	- These handles consist of HTTP, SOCKSv5, and CGP.
	- The current implementation assumes that SOCKSv5 and CGP connections are coming from an ICA client.

12. The NetScaler performs a data request by sending the STA ticket back to the STA server and requesting its corresponding data. (`<RequestData>`)

13. The STA server forwards the original data to the gateway. (notably, `<Value name="CGPAddress">` and `<Value name="ICAAddress">`)

14. This data is used to complete the subsequent proxy requests from Receiver via SOCKSv5 or CGP.