# NetScaler MIP vs SNIP

## Mapped IP Address
A mapped IP (MIP) address is used for external connections from the NetScaler system. MIP addresses are used for connectivity in the absence of a SNIP address. For example, the MIP address is the proxy IP address of last resort. MIP addresses, like SNIP addresses, are used as the proxy address for NetScaler system-to-server communication. MIP addresses are still used even when the USNIP mode is globally disabled.

The MIP address should be available across all subnets and should never be bound to a VLAN. It is only active on the primary unit of a high-availability pair—like every other IP address on the system other than the NSIP address—and shows as passive on the secondary unit.

When both a MIP address and a SNIP address are configured on the same subnet, the NetScaler system will use the SNIP address to communicate with servers by default (since USNIP mode is enabled). If USNIP mode is disabled, the MIP address will be used.

If multiple MIP addresses are present on a subnet, the NetScaler will use the MIP addresses in a round-robin fashion.

## Subnet IP Address
The subnet IP (SNIP) address is used in connection management and server monitoring. A SNIP address provides the NetScaler system with an Address Resolution Protocol (ARP) presence in subnets to which the system may not be directly connected.

A NetScaler system should have a SNIP address configured for every directly connected subnet. When a SNIP is added to a NetScaler system, a static route entry is automatically added to the NetScaler system routing table; this route identifies the SNIP address as the default gateway on the NetScaler system for the corresponding subnet.

The Use Subnet IP (USNIP) mode can affect how the SNIP address is used by the NetScaler system to communicate with servers. USNIP mode is enabled by default. When USNIP mode is enabled, the SNIP address functions as a proxy IP and is used by the NetScaler system for NetScaler-system-to-server communication. In this mode, the server will see the SNIP address as the source IP address in packets received from the NetScaler system.

If USNIP mode is disabled, the SNIP address is not used to send traffic from the NetScaler system to the servers. Instead, a mapped IP address must be available. In most environments, USNIP mode is left enabled.

Individual SNIP addresses can be enabled to allow management access. When management access is enabled, connections to the NetScaler command-line interface over SSH and connections to the web-based configuration utility can be made using the SNIP address (as if it were an NSIP address). Using management-enabled SNIP addresses allows you to connect to the NetScaler system from a subnet other than the one where the NSIP is located. It also simplifies managing NetScaler systems in a high-availability configuration, since only the primary unit will respond to the SNIP. Management access is not enabled by default. Unlike the NSIP address (but like every other type of IP address), SNIP addresses are only active on the primary unit of a high-availability pair and show as passive on the secondary unit.

If multiple SNIP addresses are present on a subnet, the NetScaler will alternate between the SNIP addresses in round-robin manner when communicating with servers.

