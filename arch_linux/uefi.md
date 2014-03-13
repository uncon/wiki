# Arch Linux Installation on UEFI Systems

## Arch Installation

1. Log in as root
2. Set root password

		passwd

3. Setup SSH

		systemctl start sshd

4. Find IP

		ip addr

5. Log in via SSH from a remote workstation
6. Clean and partition the disk

		sgdisk --zap-all /dev/sda
		cgdisk /dev/sda

1. Format partitions
    - EFI

			mkfs.vfat -F32 -n "EFI System Partition" /dev/sda1

    - Root

			mkfs.ext4 -L "Arch Linux Root" /dev/sda2

    - Swap

			mkswap -L "Arch Linux Swap" /dev/sda3

2. Mount the partitions
    - Root

			mount /dev/sda2 /mnt

    - EFI

			mkdir -p /mnt/boot
			mount /dev/sda1 /mnt/boot

1. Install the base system

		pacstrap /mnt base base-devel efibootmgr dosfstools netctl openssh

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

    - Install [Gummiboot](https///wiki.archlinux.org/index.php/Gummiboot)

			pacman -S gummiboot
			gummiboot install
			UUID=$(dumpe2fs -h $(grep " / " /etc/mtab | grep -v "rootfs\|arch_root-image" | awk '{ print $1 }') | grep "Filesystem UUID" | awk '{ print $3 }'); printf "title\tArch Linux\nlinux\t/vmlinuz-linux\ninitrd\t/initramfs-linux.img\noptions\troot=UUID=${UUID} console=tty0 console=ttyS0,115200n8 rw\n" > /boot/loader/entries/arch.conf

    - Enable DHCP

			INT=eno1; sed -e "s/^\(Description=\).*$/\1'DHCP on ${INT}'/" -e "s/^\(Interface=\).*$/\1${INT}/" /etc/netctl/examples/ethernet-dhcp > "/etc/netctl/${INT}"; netctl enable "${INT}"

    - Enable SSH

			ln -s '/usr/lib/systemd/system/sshd.service' '/etc/systemd/system/multi-user.target.wants/sshd.service'

    - Exit

			exit

1. Unmount and Reboot

		umount /mnt/{boot,}
		systemctl reboot

