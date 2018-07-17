# Arch Linux Installation on UEFI Systems

## Arch Installation

1. Boot (UEFI) from the installation media
1. Set root password

		passwd

1. Setup SSH

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

	- Home

			mkfs.ext4 -L "Arch Linux Home" /dev/sda3

	- Swap

			mkswap -L "Arch Linux Swap" /dev/sda4

2. Mount the partitions
	- Root

			mount /dev/sda2 /mnt

	- Home

			mkdir -p /mnt/home
			mount /dev/sda3 /mnt/home

	- EFI

			mkdir -p /mnt/boot
			mount /dev/sda1 /mnt/boot

1. Install the base system

		pacstrap /mnt base base-devel intel-ucode efibootmgr dosfstools networkmanager openssh net-tools bind-tools sudo wget git vim tmux zsh

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

	- Configure locale

			sed -i.orig -e 's/^#\(en_US.*$\)/\1/g' /etc/locale.gen
			locale-gen

	- Create init RAM disk

			mkinitcpio -p linux

	- Set root password

			passwd

	- Install systemd-boot to the ESP and EFI variables

			bootctl install
			UUID=$(dumpe2fs -h $(grep " / " /etc/mtab | grep -v "rootfs\|arch_root-image" | awk '{ print $1 }') | grep "Filesystem UUID" | awk '{ print $3 }'); printf "title\tArch Linux\nlinux\t/vmlinuz-linux\ninitrd\t/intel-ucode.img\ninitrd\t/initramfs-linux.img\noptions\troot=UUID=${UUID} rw\n" > /boot/loader/entries/arch.conf

	- Enable Network Manager

			systemctl enable NetworkManager

	- Enable SSH

			systemctl enable sshd

	- Enable timesyncd

			systemctl enable systemd-timesyncd

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

		umount /mnt/{boot,home,}
		systemctl reboot

## Post-Installation

1. Set locale

		localectl set-locale LANG=en_US.UTF-8

1. Install [pacaur](https://github.com/rmarquis/pacaur)

		mkdir ~/aur
		cd ~/aur
		PKG="cower" && git clone "https://aur.archlinux.org/${PKG}.git/" && cd "${PKG}" && makepkg -i -s -r --skippgpcheck
		cd..
		PKG="pacaur" && git clone "https://aur.archlinux.org/${PKG}.git/" && cd "${PKG}" && makepkg -i -s -r

1. Install Google Chrome

		pacaur -Sy google-chrome

1. Install [tlp](https://wiki.archlinux.org/index.php/TLP)

		sudo pacman -Sy tlp x86_energy_perf_policy smartmontools ethtool
		sudo systemctl enable tlp.service
		sudo systemctl enable tlp-sleep.service

1. Install [Insync](https://www.insynchq.com/)

		pacaur -Sy insync
