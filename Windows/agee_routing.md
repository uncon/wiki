# Windows AGEE VPN Client Routing

Assuming your AGEE implementation is configured to assign you an Intranet IP (IIP), you can configure Windows to route and NAT from your LAN to your Intranet.

## LAN Router / Firewall Configuration

1. Configure a static route for your Intranet network with your Windows machine as the gateway.

2. Configure an authoritative DNS server for your Intranet domain(s).  This should be the default gateway of the Citrix Virtual Adapter on the Windows machine.

## Windows Configuration

1. Disable the Windows Firewall

		netsh advfirewall set allprofiles state off

2. Enable Routing (Forwarding)

		sc config RemoteAccess start=auto
		sc start RemoteAccess
		netsh interface ipv4 set interface interface="<LAN_INTERFACE>" forwarding=enabled

3. Enable NAT

		netsh routing ip nat install
		netsh routing ip nat add interface "<VPN_INTERFACE>" full
		netsh routing ip nat add interface "<LAN_INTERFACE>" private
