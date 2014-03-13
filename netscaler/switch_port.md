# Recommended Switch Port Settings

## Cisco

	interface FastEthernet0/3
	 description NetScaler
	 switchport mode access
	 no keepalive
	 spanning-tree portfast
	 spanning-tree bpdufilter enable
	!
