There are some applications that require disabling connection multiplexing (e.g., the application does not accept multiple clients on the same TCP connection). This can be accomplished by either disabling `conMultiplex` (connection multiplexing) in an HTTP profile or setting `maxReq` (maximum requests) to 1 in an HTTP profile on on the applicable services.

There is a subtle difference between these two methods.

* `maxReq 1` - The backend connection is torn down after a single response is served.
* `conMultiplex DISABLED` - The backend connection is not torn down after a single response is served. However, the NetScaler may reuse the server connection for subsequent requests from the same client connection. (This behavior improves performance.)
