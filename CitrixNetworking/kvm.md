# NetScaler VPX on KVM

## Provisioning

1. Change to directory with extracted archive

2. Patch XML file

		sed -e "s#eth0#virbr1#g" -e "s#^\(\s*\)<source file='\(NSVPX-KVM.*\)'/>\$#\1<source file='${PWD}/\2'/>#" NSVPX-KVM.xml > NSVPX-KVM-patched.xml

3. Import VM

		virsh define ./NSVPX-KVM-patched.xml

## First Boot

The initial configuration wizard may not be automatically invoked on the first boot for KVM instances.

1. Invoke network wizard

		config ns
