# Arch Linux Installation on UEFI Systems with Disk Encryption

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

		sgdisk --zap-all /dev/nvme0n1
		sgdisk --clear \
		--new=1:0:+550MiB --typecode=1:ef00 --change-name=1:EFI \
		--new=2:0:0 --typecode=2:8300 --change-name=2:LUKS \
		/dev/nvme0n1

1. Setup encryption and LUKS

		cryptsetup luksFormat --type luks2 /dev/nvme0n1p2
		cryptsetup luksOpen /dev/nvme0n1p2 luks
		pvcreate /dev/mapper/luks
		vgcreate vg0 /dev/mapper/luks
		lvcreate -L 24G vg0 -n swap
		lvcreate -l +100%FREE vg0 -n root

1. Format partitions

	- EFI

			mkfs.vfat -F32 -n "EFI System Partition" /dev/nvme0n1p1

	- Root

			mkfs.ext4 -L "Arch Linux Root" /dev/mapper/vg0-root

	- Swap

			mkswap -L "Arch Linux Swap" /dev/mapper/vg0-swap

1. Mount the partitions

	- Root

			mount /dev/mapper/vg0-root /mnt

	- EFI

			mkdir -p /mnt/boot
			mount /dev/nvme0n1p1 /mnt/boot

	- Swap

			swapon /dev/mapper/vg0-swap

1. Install the base system

		pacstrap /mnt base linux linux-firmware base-devel intel-ucode efibootmgr dosfstools networkmanager openssh net-tools bind-tools sudo wget git vim tmux zsh p7zip
		
	Optionally, append the following packages. 
	- GNOME
	
			gdm gnome gnome-power-manager gnome-tweaks aspell-en gst-libav gst-plugins-base gst-plugins-good gst-plugins-ugly

1. Configure the system (see [ArchWiki](https://wiki.archlinux.org/index.php/Installation_Guide#Configure_the_system))

	- Generate fstab

			genfstab -U /mnt >> /mnt/etc/fstab

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

			MyHostName=arch; printf "127.0.0.1\tlocalhost\n::1\t\tlocalhost\n127.0.1.1\t${MyHostName}.localdomain ${MyHostName}\n" >> /etc/hosts; echo "${MyHostName}" > /etc/hostname

	- Add encryption support to mkinitcpio
	
			sed -i.orig -e 's/^\(HOOKS=.* block \)\(.*\)$/\1encrypt lvm2 \2/g' /etc/mkinitcpio.conf

	- Create init RAM disk

			mkinitcpio -p linux

	- Set root password

			passwd

	- Install systemd-boot to the ESP and EFI variables

			bootctl --path=/boot install
			printf "title\tArch Linux\nlinux\t/vmlinuz-linux\ninitrd\t/intel-ucode.img\ninitrd\t/initramfs-linux.img\noptions\tcryptdevice=UUID=$(blkid /dev/nvme0n1p2 -o value | head -n1):cryptlvm root=/dev/mapper/vg0-root resume=/dev/mapper/vg0-swap rw\n" > /boot/loader/entries/arch.conf

	- Enable (periodic) TRIM

			systemctl enable fstrim.timer

	- Enable Network Manager

			systemctl enable NetworkManager.service

	- Enable Bluetooth

			systemctl enable bluetooth.service

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

1. Configure locale

		sudo localectl set-locale LANG=en_US.UTF-8

1. Install Intel hardware video acceleration and Vulkan support

		sudo pacman -S intel-media-driver vulkan-intel

1. Disable tap-and-drag (GNOME)

		gsettings set org.gnome.desktop.peripherals.touchpad tap-and-drag false

1. Add helpful key bindings (GNOME)

		gsettings set org.gnome.desktop.wm.keybindings close "['<Super>w']"
		
		gsettings set org.gnome.settings-daemon.plugins.media-keys www "['<Super>b']"
		gsettings set org.gnome.settings-daemon.plugins.media-keys home "['<Super>f']"

		gsettings set org.gnome.settings-daemon.plugins.media-keys.custom-keybinding:/org/gnome/settings-daemon/plugins/media-keys/custom-keybindings/custom0/ name "Terminal"
		gsettings set org.gnome.settings-daemon.plugins.media-keys.custom-keybinding:/org/gnome/settings-daemon/plugins/media-keys/custom-keybindings/custom0/ command "tilix"
		gsettings set org.gnome.settings-daemon.plugins.media-keys.custom-keybinding:/org/gnome/settings-daemon/plugins/media-keys/custom-keybindings/custom0/ binding "'<Super>t'"
		gsettings set org.gnome.settings-daemon.plugins.media-keys custom-keybindings "['/org/gnome/settings-daemon/plugins/media-keys/custom-keybindings/custom0/']"

1. Install CUPS

		sudo pacman -S cups cups-pdf system-config-printer foomatic-db-engine foomatic-db foomatic-db-ppds foomatic-db-nonfree-ppds foomatic-db-gutenprint-ppds
		sudo systemctl enable --now org.cups.cupsd.service

1. Install [yay](https://github.com/Jguer/yay)

		mkdir -p ~/aur
		cd ~/aur
		PKG="yay" && git clone "https://aur.archlinux.org/${PKG}.git/" && cd "${PKG}" && makepkg -i -s -r --skippgpcheck

1. Install Google Chrome

		yay -S google-chrome

	- Disable media key control

			chrome://flags/#hardware-media-key-handling
	
	- Enable screen sharing with Wayland

			chrome://flags/#enable-webrtc-pipewire-capturer

1. Install [tlp](https://wiki.archlinux.org/index.php/TLP)

		sudo pacman -S tlp x86_energy_perf_policy smartmontools ethtool
		sudo systemctl enable --now tlp.service
		sudo systemctl mask systemd-rfkill.service
		sudo systemctl mask systemd-rfkill.socket

1. Install a GNOME dock extension

	- [Dash to Dock](https://micheleg.github.io/dash-to-dock/)

			yay -S gnome-shell-extension-dash-to-dock
			gnome-extensions enable dash-to-dock@micxgx.gmail.com

	- [Dash to Panel](https://github.com/jderose9/dash-to-panel)

			yay -S gnome-shell-extension-dash-to-panel
			gnome-extensions enable dash-to-panel@jderose9.github.com

1. Install Microsoft Windows 10 TrueType fonts

	Download [Windows Evaluation](http://www.microsoft.com/en-us/evalcenter/evaluate-windows-10-enterprise) ISO

		mkdir -p ~/aur
		cd ~/aur
		git clone 'https://aur.archlinux.org/ttf-ms-win10.git/'
		cd ttf-ms-win10
		7z e ~/Downloads/<WIN10>.iso 'sources/install.wim'
		7z e install.wim 'Windows/Fonts/*.ttf' 'Windows/Fonts/*.ttc' 'Windows/System32/Licenses/neutral/*/*/license.rtf'
		makepkg -i -s -r --skipchecksums

1. Install libu2f-host (for U2F support)

		sudo pacman -S libu2f-host
