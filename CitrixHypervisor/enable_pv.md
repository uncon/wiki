# How To Enable PV Mode

## Prepare the Guest
The following is to be completed on the guest VM.

1. Verify the following kernel options

		CONFIG_KERNEL_GZIP=y
		# CONFIG_DEFAULT_CFQ is not set
		
		CONFIG_DEFAULT_DEADLINE=y
		CONFIG_DEFAULT_IOSCHED="deadline"
		# CONFIG_INTEL_IDLE is not set
		
		CONFIG_MEMORY_HOTPLUG=y
		CONFIG_NR_CPUS=64
		CONFIG_PREEMPT_NONE=y
		# CONFIG_PREEMPT_VOLUNTARY is not set
		
		CONFIG_XEN_BLKDEV_FRONTEND=y
		CONFIG_XEN_NETDEV_FRONTEND=y
		CONFIG_XEN_XENBUS_FRONTEND=y
		CONFIG_XEN_FBDEV_FRONTEND=y
		CONFIG_INPUT_XEN_KBDDEV_FRONTEND=y

2. Ensure that partitions are refered to by UUID in `/etc/fstab` and `/boot/grub/menu.lst`.

3. Rename `tty1` to `hvc0` in `/etc/inittab`.

## Change XenServer Configuration
The following is to be completed on the XenServer host.

1. Find the UUID of the VM

		xe vm-list name-label=[VM_Name]

2. Switch from HVM to PV mode

		xe vm-param-clear uuid=[VM_UUID] param-name=HVM-boot-policy
		xe vm-param-set uuid=[VM_UUID] PV-bootloader="pygrub"

3. Find the UUID of the virtual drive (VBD) for the VM

		xe vm-disk-list vm=[VM_Name]`

4. Set boot flag for the virtual disk of the VM

		xe vbd-param-set uuid=[VBD_UUID] bootable=true`

5. Reboot the VM
