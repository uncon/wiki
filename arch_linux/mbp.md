# Arch Linux Installation on MacBookPro10,1 Systems

## Arch Installation

1. Log in as root
1. Set root password

		passwd

3. Setup SSH1
		systemctl start sshd

1. Find IP

		ip addr

1. Log in via SSH from a remote workstation
1. Clean and partition the disk

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

		pacstrap /mnt base base-devel intel-ucode efibootmgr dosfstools openssh dnsutils sudo wget zsh nvidia xorg-server xorg-server-utils xf86-input-synaptics gnome gnome-extra gvfs-smb gdm gvim cups gutenprint

2. Configure the system [ArchWiki](https///wiki.archlinux.org/index.php/Installation_Guide#Configure_the_system)
    - Generate fstab

			genfstab -U /mnt >> /mnt/etc/fstab

    - Update fstab

		(TODO: automate this!) Add 'discard' to all vfat and ext4 fstab entries (/mnt/etc/fstab)

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
			UUID=$(dumpe2fs -h $(grep " / " /etc/mtab | grep -v "rootfs\|arch_root-image" | awk '{ print $1 }') | grep "Filesystem UUID" | awk '{ print $3 }'); printf "title\tArch Linux\nlinux\t/vmlinuz-linux\ninitrd\t/intel-ucode.img\ninitrd\t/initramfs-linux.img\noptions\troot=UUID=${UUID} rw\n" > /boot/loader/entries/arch.conf

    - Enable Network Manager

			systemctl enable NetworkManager

    - Enable SSH

			systemctl enable sshd

    - Enable timesyncd

			systemctl enable systemd-timesyncd

    - Enable CUPS

			systemctl enable org.cups.cupsd

    - Enable Bluetooth

			systemctl enable bluetooth

    - Enable GDM

			systemctl enable gdm

    - Add user

			useradd -m -g users -G wheel -s /bin/zsh uncon
			chfn uncon
			passwd uncon

    - Configure sudo

			visudo

		Uncomment the following line.

			## Uncomment to allow members of group wheel to execute any command
			%wheel ALL=(ALL) ALL

    - Exit

			exit

1. Unmount and Reboot

		umount /mnt/{boot,}
		systemctl reboot

## Post-Installation

1. Install [Aura](https://github.com/aurapm/aura)

		wget "https://aur.archlinux.org/packages/au/aura-bin/aura-bin.tar.gz"
		tar xf aura-bin.tar.gz
		cd aura-bin
		makepkg -s
		sudo pacman -U aura-bin-*.pkg.tar.xz

1. Isntall Wireless Drivers

		sudo aura -A broadcom-wl 

1. Install Google Chrome

		sudo aura -A google-chrome

1. Install powertop

		sudo aura -A powertop-autotune-systemd
		sudo powertop --calibrate
		sudo systemctl enable powertop-autotune
		sudo systemctl start powertop-autotune

1. Disable HiDPI

	This is necesary for consistency untill there is more wide-spread support for HiDPI
		
		gsettings set org.gnome.desktop.interface scaling-factor 1
