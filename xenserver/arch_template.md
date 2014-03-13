# PV Arch Linux Installation via Template

## Create The Template
1. Create `arch.sh` on Dom0 and run it.
	
		#!/bin/sh
		baseUUID=$(xe template-list name-label="Ubuntu Precise Pangolin 12.04 (64-bit)" --minimal)
		newUUID=$(xe vm-clone uuid=$baseUUID new-name-label="Arch Linux (64-bit)")
		xe template-param-set uuid=$newUUID \
			name-description="Template that allows VM installation of Arch Linux." \
			memory-static-max=536870912 \
			memory-dynamic-max=536870912 \
			memory-dynamic-min=536870912 \
			other-config:default_template=false \
			other-config:install-distro=pygrub \
			other-config:install-methods=http,ftp \
			other-config:install-repository=http://mirrors.kernel.org/archlinux/iso/latest/arch/boot/x86_64/ \
			other-config:install-kernel=vmlinuz \
			other-config:install-ramdisk=archiso.img \
			PV-args="-- archiso_http_srv=http://mirrors.kernel.org/archlinux/iso/latest/ archisobasedir=arch checksum=y ip=dhcp console=hvc0"
		xe template-param-remove uuid=$newUUID \
			param-name=other-config param-key=debian-release \
			param-name=base_template_name
		xe template-list uuid=$newUUID

## Create VM

1. Create a new VM using the newly created template.

## Arch Installation

1. Log in as root.

2. Set root password.

		 passwd
 
3. Setup SSH.

		systemctl start sshd

4. Find VM IP.

		ip addr

5. Log in via SSH from a remote workstation.

6. Partition the disk.

		cfdisk /dev/xvda

7. Format partitions.

8. Mount the partitions.

9. Install the base system.

		pacstrap /mnt base base-devel netctl openssh xe-guest-utilities

10. Create a `menu.lst` file.

		UUID=$(dumpe2fs -h $(grep " /mnt " /etc/mtab | grep -v "rootfs\|arch_root-image" | awk '{ print $1 }') | grep "Filesystem UUID" | awk '{ print $3 }'); mkdir -p /mnt/boot/grub/; printf "timeout\t5\ndefault\t0\ncolor\tlight-blue/black light-cyan/blue\n\n# (0) Arch Linux\ntitle\tArch Linux\nroot\t(hd0,0)\nkernel\t/vmlinuz-linux root=/dev/disk/by-uuid/${UUID} rw\ninitrd\t/initramfs-linux.img\n\n# (1) Arch Linux\ntitle\tArch Linux Fallback\nroot\t(hd0,0)\nkernel\t/vmlinuz-linux root=/dev/disk/by-uuid/${UUID} rw\ninitrd\t/initramfs-linux-fallback.img\n" > /mnt/boot/grub/menu.lst

11. Configure the system [ArchWiki](https///wiki.archlinux.org/index.php/Installation_Guide#Configure_the_system).
    - Generate fstab

			genfstab -U /mnt >> /mnt/etc/fstab

    - Change root

			arch-chroot /mnt

    - Set hostname

			echo "arch" > /etc/hostname

    - Set time zone

			ln -s /usr/share/zoneinfo/US/Central /etc/localtime

    - Set locale

			sed -i.orig -e 's/^#\(en_US.*$\)/\1/g' /etc/locale.gen
			locale-gen
			echo -e "LANG=en_US.UTF-8\nLC_COLLATE=C" > /etc/locale.conf

    - Add `xen-blkfront` to `MODULES` in `/etc/mkinitcpio.conf`.

			sed -i.orig -e 's/\(^MODULES=\).*$/\1"xen-blkfront"/g' /etc/mkinitcpio.conf

    - Create init RAM disk

			mkinitcpio -p linux

    - Set root password

			passwd

    - Enable DHCP

			INT=eth0; sed -e "s/^\(Description=\).*$/\1'DHCP on ${INT}'/" -e "s/^\(Interface=\).*$/\1${INT}/" /etc/netctl/examples/ethernet-dhcp > "/etc/netctl/${INT}"; netctl enable "${INT}"

    - Enable SSH

			ln -s '/usr/lib/systemd/system/sshd.service' '/etc/systemd/system/multi-user.target.wants/sshd.service'

    - Enable XenServer tools

			ln -s '/usr/lib/systemd/system/xe-linux-distribution.service' '/etc/systemd/system/multi-user.target.wants/xe-linux-distribution.service'

    - Exit

			exit

12. Unmount partitions and reboot the system.

		umount /mnt/{boot,}
		systemctl reboot
