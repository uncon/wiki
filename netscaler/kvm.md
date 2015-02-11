# NetScaler VPX on KVM

## Provisioning

1. Change to directory with extracted archive

2. Patch XML file

		ssed -e "s#eth0#virbr1#g" -e "s#^\(\s*\)<source file='\(NSVPX-KVM.*\)'/>\$#\1<source file='${PWD}/\2'/>#" NSVPX-KVM.xml > NSVPX-KVM-patched.xml

3. Import VM

		virsh define ./NSVPX-KVM-patched.xml

## First Boot

Due to some bug (maybe?), the initial configuration wizard is not invoked on first boot for KVM instances.

1. Invoke network wizard

		config ns
