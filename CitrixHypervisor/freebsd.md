# FreeBSD Configuration for Paravirtualization

## FreeBSD Install (HVM)

1. Perform a standard installation

## Paravirtualization Configuration

1. [**This step is not necessary in 9.1+**] In order to build the kernel **and** modules, unset `MODULES_OVERRIDE` by commenting out the `MODULES_OVERRIDE` line in `/usr/src/sys/amd64/conf/XENHVM`.

		sed -i .orig -e 's/^\(makeoptions.*MODULES_OVERRIDE\)/#\1/' /usr/src/sys/amd64/conf/XENHVM`

2. Rebuild Kernel for XenServer.

		cd /usr/src
		make buildkernel KERNCONF=XENHVM
		make installkernel KERNCONF=XENHVM

3. Edit `/etc/fstab` and replace `ada0` with `ad0`.

		sed -i .orig -e 's/ada0/ad0/g' /etc/fstab
		
4. Edit `/etc/rc.conf` and replace `ifconfig_re0` with `ifconfig_xn0`.

		sed -i .orig -e 's/ifconfig_re0/ifconfig_xn0/g' /etc/rc.conf`

## Paravirtualization Tweaking
It may be necessary (notably on XenServer 6.1) to remove the CD-ROM in order to fully boot the VM.  (The console may report `run_interrupt_driven_hooks: still waiting after X seconds for xenbusb_nop_confighook_cb` continuously.)

1. Find the UUID of the VM.

		xe vm-list name-label=[VM_Name]

2. Find the UUID of the virtual CD drive (VBD) for the VM (The `device` is probably `hdd`).

		xe vbd-list vm-uuid=[VM_UUID] device=hdd params=uuid

3. Destroy the virtual CD drive (VBD).

		xe vbd-destroy uuid=[VBD_UUID]

## Miscellaneous

1. Disable X11

		echo 'WITHOUT_X11=yes' >> /etc/make.conf

2. Install Xen tools scripts

		cd /usr/ports/sysutils/xe-guest-utilities
		make install distclean

## Final

1. Reboot

		reboot
