# NetScaler VPX on KVM

## Provisioning

1. Change to directory with extracted archive

2. Patch XML file

		sed -e "s#eth0#virbr1#g" -e "s#`<source file='\(NSVPX-KVM.*\)'/>`#`<source file='${PWD}/\1'/>`#" NSVPX-KVM.xml > NSVPX-KVM-patched.xml

3. Import VM

		virsh define ./NSVPX-KVM-patched.xml

## First Boot

Due to some bug (maybe?), the initial configuration wizard is not invoked on first boot for KVM instances.

1. Invoke network wizard

		config ns
