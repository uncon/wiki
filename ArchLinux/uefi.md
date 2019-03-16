# Arch Linux Installation on UEFI Systems

## Arch Installation

1. Boot (UEFI) from the installation media
	
1. Optionally, for an easier (because copy and paste) remote installation...

	1. Set root password

			passwd

	1. Setup SSH

			systemctl start sshd.service

	1. Find IP

			ip addr

	1. Log in via SSH from a remote workstation to continue

1. Clean and partition the disk

		sgdisk --zap-all /dev/sda
		cgdisk /dev/sda

1. Format partitions
	- EFI (550 MiB)

			mkfs.vfat -F32 -n "EFI System Partition" /dev/sda1

	- Root (23 - 32 GiB)

			mkfs.ext4 -L "Arch Linux Root" /dev/sda2

	- Home (Optional)

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
		
	Optionally, append the following packages. 
	- GNOME
	
			gdm gnome gnome-power-manager gnome-tweaks aspell-en

2. Configure the system (see [ArchWiki](https://wiki.archlinux.org/index.php/Installation_Guide#Configure_the_system))
	- Generate fstab

			genfstab -U /mnt >> /mnt/etc/fstab

	- Update fstab

		(TODO: automate this!) Add 'discard' to all vfat and ext4 fstab entries (/mnt/etc/fstab)

	- Change root

			arch-chroot /mnt

	- Set time zone

			ln -sf /usr/share/zoneinfo/US/Central /etc/localtime
			hwclock --systohc

	- Configure locale

			sed -i.orig -e 's/^#\(en_US\.UTF-8 .*$\)/\1/g' /etc/locale.gen
			locale-gen
			echo "LANG=en_US.UTF-8" > /etc/locale.conf

	- Set hostname

			HOSTNAME=arch; printf "127.0.0.1\tlocalhost\n::1\t\tlocalhost\n127.0.1.1\t${HOSTNAME}.localdomain ${HOSTNAME}\n" >> /etc/hosts; echo "${HOSTNAME}" > /etc/hostname

	- Create init RAM disk

			mkinitcpio -p linux

	- Set root password

			passwd

	- Install systemd-boot to the ESP and EFI variables

			bootctl install
			UUID=$(dumpe2fs -h $(grep " / " /etc/mtab | grep -v "rootfs\|arch_root-image" | awk '{ print $1 }') | grep "Filesystem UUID" | awk '{ print $3 }'); printf "title\tArch Linux\nlinux\t/vmlinuz-linux\ninitrd\t/intel-ucode.img\ninitrd\t/initramfs-linux.img\noptions\troot=UUID=${UUID} rw\n" > /boot/loader/entries/arch.conf

	- Enable Network Manager

			systemctl enable NetworkManager.service

	- Enable SSH

			systemctl enable sshd.service

	- Enable timesyncd

			systemctl enable systemd-timesyncd.service

	- Add user

			useradd -m -g users -G wheel -s /bin/zsh uncon
			chfn uncon
			passwd uncon

	- Configure sudo

			visudo

		Uncomment the following line.

			## Uncomment to allow members of group wheel to execute any command
			%wheel ALL=(ALL) ALL

	- Enable GDM (Optional)

			sudo systemctl enable gdm.service

	- Exit

			exit

1. Unmount and Reboot

		umount -R /mnt
		systemctl reboot

## Post-Installation

1. Set locale

		localectl set-locale LANG=en_US.UTF-8

1. Install CUPS

		sudo pacman -Sy cups cups-pdf system-config-printer foomatic-db-engine foomatic-db foomatic-db-ppds foomatic-db-nonfree-ppds foomatic-db-gutenprint-ppds
		sudo systemctl enable --now org.cups.cupsd.service

1. Install [yay](https://github.com/Jguer/yay)

		mkdir ~/aur
		cd ~/aur
		PKG="yay" && git clone "https://aur.archlinux.org/${PKG}.git/" && cd "${PKG}" && makepkg -i -s -r --skippgpcheck

1. Install VMware Tools

		sudo pacman -Sy open-vm-tools
		sudo systemctl enable --now vmtoolsd.service

1. Install Google Chrome

		yay -Sy google-chrome

1. Install [tlp](https://wiki.archlinux.org/index.php/TLP)

		sudo pacman -Sy tlp x86_energy_perf_policy smartmontools ethtool
		sudo systemctl enable --now tlp.service
		sudo systemctl enable --now tlp-sleep.service

1. Install [Insync](https://www.insynchq.com/)

		yay -Sy insync insync-nautilus

1. Install [u2f-hidraw-policy](https://github.com/amluto/u2f-hidraw-policy)

		yay -Sy u2f-hidraw-policy
