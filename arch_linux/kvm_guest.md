# Arch Linux Installation on KVM

## Arch Installation
1. Log in as root
2. Set root password

		passwd

3. Setup network

		systemctl start dhcpcd@eth0

4. Setup SSH

		systemctl start sshd

5. Find IP

		ip addr

6. Log in via SSH from a remote workstation
7. Partition the disk

		cgdisk /dev/vda

8. Format partitions
    - Boot

			mkfs.ext4 -L "Arch Linux Boot" /dev/vda1

    - Root

			mkfs.ext4 -L "Arch Linux Root" /dev/vda2

    - Swap

			mkswap -L "Arch Linux Swap" /dev/vda3

9. Mount the partitions
    - Root

			mount /dev/vda2 /mnt

    - Boot

			mkdir -p /mnt/boot
			mount /dev/vda1 /mnt/boot

1. Install the base system

		pacstrap /mnt base base-devel netctl openssh

2. Configure the system [ArchWiki](https///wiki.archlinux.org/index.php/Installation_Guide#Configure_the_system)
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

    - Create init RAM disk

			mkinitcpio -p linux

    - Set root password

			passwd

    - Install [Syslinux](https///wiki.archlinux.org/index.php/Syslinux)

			pacman -S gptfdisk syslinux
			syslinux-install_update -i -a -m
			UUID=$(dumpe2fs -h $(grep " / " /etc/mtab | grep -v "rootfs\|arch_root-image" | awk '{ print $1 }') | grep "Filesystem UUID" | awk '{ print $3 }'); sed -i.orig -e "s_APPEND root=/dev/sda3 _APPEND root=UUID=${UUID} _g" /boot/syslinux/syslinux.cfg

    - Enable DHCP

			INT=eth0; sed -e "s/^\(Description=\).*$/\1'DHCP on ${INT}'/" -e "s/^\(Interface=\).*$/\1${INT}/" /etc/netctl/examples/ethernet-dhcp > "/etc/netctl/${INT}"; netctl enable "${INT}"

    - Enable timesyncd

			ln -s '/usr/lib/systemd/system/systemd-timesyncd.service' '/etc/systemd/system/sysinit.target.wants/systemd-timesyncd.service'

    - Enable SSH

			ln -s '/usr/lib/systemd/system/sshd.service' '/etc/systemd/system/multi-user.target.wants/sshd.service'

    - Exit

			exit

1. Unmount and Reboot

		umount /mnt/{boot,}
		systemctl reboot

