# Arch Linux Installation on a Dell XPS 13 (9343)

These steps are specify for my my Dell XPS 13 (9343) but may be useful for others as well.

## Arch Installation

1. Set root password

		passwd

3. Setup SSH
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

		pacstrap /mnt base base-devel intel-ucode efibootmgr dosfstools openssh dnsutils sudo wget git htop tmux zsh xf86-video-intel mesa-libgl libva-intel-driver libva xorg-server xorg-server-utils xorg-server-xwayland xorg-utils xf86-input-synaptics gnome gnome-extra gvfs-smb gdm gvim cups gutenprint

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

		umount /mnt/{boot,home,}
		systemctl reboot

## Post-Installation

1. Install [Infinality](http://bohoomil.com)

		printf '\n# Infinality Bundle & Fonts\n[infinality-bundle]\nServer = http://bohoomil.com/repo/$arch\n\n[infinality-bundle-multilib]\nServer = http://bohoomil.com/repo/multilib/$arch\n\n[infinality-bundle-fonts]\nServer = http://bohoomil.com/repo/fonts\n\n' >> /etc/pacman.conf
		pacman-key -r 962DDE58
		pacman-key -f 962DDE58
		pacman-key --lsign-key 962DDE58
		pacman -Sy infinality-bundle

1. Install [Aura](https://github.com/aurapm/aura)

		wget "https://aur.archlinux.org/packages/au/aura-bin/aura-bin.tar.gz"
		tar xf aura-bin.tar.gz
		cd aura-bin
		makepkg -s
		sudo pacman -U aura-bin-*.pkg.tar.xz

1. Install Wireless Drivers

	This is only required if using the DW 1560 (Broadcom) wifi card.

		sudo aura -A broadcom-wl 

1. Install Google Chrome

		sudo aura -A google-chrome

1. Install [tlp](https://wiki.archlinux.org/index.php/TLP)

		sudo pacman -Sy tlp x86_energy_perf_policy smartmontools ethtool
		sudo systemctl mask systemd-rfkill
		sudo systemctl enable tlp.service
		sudo systemctl enable tlp-sleep.service

1. Install [Insync](https://www.insynchq.com/)

		sudo pacman -Sy gsettings-desktop-schemas
		sudo aura -A insync
		sudo systemctl enable insync@uncon
		sudo systemctl start insync@uncon

1. Fix touchpad and sound

	The following puts the touchpad in PS2 (not I2C) mode and soundcard in HDA (not I2S) mode as well as impliments a workaround to disable resetting the touchpad driver on recieving invalid packets.

		sudo sed -i.orig -e 's/^\(options\W.*\) rw$/\1 acpi_osi="!Windows 2013" psmouse.resetafter=0 rw/g' /boot/loader/entries/arch.conf

1. Disable HiDPI

	This is optional and provides better consistency untill there is more wide-spread support for HiDPI.
		
		gsettings set org.gnome.desktop.interface scaling-factor 1

## KVM and libvirt

1. Install packages

		sudo pacman -S libvirt urlgrabber qemu libvirt virtviewer virt-manager xorg-xauth dnsmasq ebtables bridge-utils

1. Enable and start services

		sudo systemctl enable libvirt-guests
		sudo systemctl start libvirt-guests
		sudo systemctl enable libvirtd
		sudo systemctl start libvirtd

### Enable User Access

1. Add group

		sudo groupadd libvirt

1. Add user to group

		sudo gpasswd -a uncon libvirt
		sudo gpasswd -a uncon kvm

1. Setup PolicyKit

		sudo -i 
		printf 'polkit.addRule(function(action, subject) {\n\tif (action.id == "org.libvirt.unix.manage" &&\n\t\tsubject.isInGroup("libvirt")) {\n\t\t\treturn polkit.Result.YES;\n\t\t}\n});\n' > /etc/polkit-1/rules.d/50-org.libvirt.unix.manage.rules
		exit
