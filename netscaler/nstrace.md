# NetScaler Network Tracing
To capture a network trace, issue the following command to the NetScaler CLI.

    > start nstrace -size 0

You can also filter the captured traffic.  Below are some examples.

    > start nstrace -size 0 -filter "CONNECTION.LB_VSERVER.NAME.EQ(\"<VirtualServerName>\")" -link ENABLED
    > start nstrace -size 0 -filter "CONNECTION.IP.EQ(192.168.0.1) && CONNECTION.IP.EQ(192.168.0.2) && CONNECTION.PORT.EQ(443)" -link ENABLED

The NetScaler will start capturing a network trace in a date and time stamped directory in `/var/nstrace`. To stop the trace, issue the following command.

    > stop nstrace

## SSL Decryption

### NetScaler Firmware >= 11.0-66.11
Use `-capsslkeys ENABLED` and be sure to get the `nstrace.sslkeys` file.
You may need to disable SSL session reuse: `set ssl vserver <VirtualServerName> -sessReuse DISABLED`.

### NetScaler Firmware >= 11.0
Use `-mode TXB,NEW_RX,SSLPLAIN` to have the NetScaler include decrypted traffic in the trace.

### NetScaler Firmware < 11.0
Ensure that ECC, Session Reuse and DH Param are disabled / removed from the virtual server before the trace is captured.
([CTX135889](https://support.citrix.com/article/CTX135889))

## Rolling Trace
Use the `-nf` and `-time` parameters to capture a rolling network trace.  Below is an example.

    > start nstrace -nf 6 -time 300 -size 0

This will start capturing a series of 6 rolling network traces with a total of 30 minutes of historical network trace data. Each file will contain 5 minutes of data, and after the last 5 minutes, the first file will be overwritten.
