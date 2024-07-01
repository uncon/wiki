# Arch Linux Installation

## Arch Installation

1. Boot from the installation media

1. Optionally, for an easier (because copy and paste) remote installation...

	1. Set root password
		```
		passwd
		```

	1. Setup SSH
		```
		systemctl start sshd.service
		```

	1. Find IP
		```
		ip addr
		```

	1. Log in via SSH from a remote workstation to continue

1. Complete installation with [archinstall](https://wiki.archlinux.org/title/archinstall)

1. Reboot
	```
	systemctl reboot
	```

## Post-Installation

1. Install additional packages.
	```
	sudo pacman -S net-tools bind-tools git tmux zsh p7zip gnome-firmware aspell aspell-en noto-fonts noto-fonts-cjk noto-fonts-emoji
	```

1. Enable Bluetooth
	```
	systemctl enable --now bluetooth.service
	```

1. Enable SSH
	```
	systemctl enable --now sshd.service
	```

1. Enable timesyncd
	```
	systemctl enable --now systemd-timesyncd.service
	```

1. Optionally, install Intel hardware video acceleration and Vulkan support
	```
	sudo pacman -S intel-media-driver libva-intel-driver vulkan-intel vulkan-mesa-layers
	```

1. Optionally, install NVIDIA hardware video acceleration and Vulkan support
	```
	sudo pacman -S nvidia-open nvidia-settings libva-utils
	sudo systemctl enable nvidia-hibernate
	sudo systemctl enable nvidia-resume
	sudo systemctl enable nvidia-suspend
	sudo ln -s /dev/null /etc/udev/rules.d/61-gdm.rules
	sudo sed -i -e 's/^\(options .*\)$/\1 nvidia-drm.modeset=1/' /boot/loader/entries/*_linux.conf
	```

1. Disable tap-and-drag (GNOME)
	```
	gsettings set org.gnome.desktop.peripherals.touchpad tap-and-drag false
	```

1. Add helpful key bindings (GNOME)
	```
	gsettings set org.gnome.desktop.wm.keybindings close "['<Super>w']"
	gsettings set org.gnome.settings-daemon.plugins.media-keys www "['<Super>b']"
	gsettings set org.gnome.settings-daemon.plugins.media-keys home "['<Super>f']"
	```

	```
	gsettings set org.gnome.settings-daemon.plugins.media-keys.custom-keybinding:/org/gnome/settings-daemon/plugins/media-keys/custom-keybindings/custom0/ name "Terminal"
	gsettings set org.gnome.settings-daemon.plugins.media-keys.custom-keybinding:/org/gnome/settings-daemon/plugins/media-keys/custom-keybindings/custom0/ command "kgx"
	gsettings set org.gnome.settings-daemon.plugins.media-keys.custom-keybinding:/org/gnome/settings-daemon/plugins/media-keys/custom-keybindings/custom0/ binding "'<Super>t'"
	gsettings set org.gnome.settings-daemon.plugins.media-keys custom-keybindings "['/org/gnome/settings-daemon/plugins/media-keys/custom-keybindings/custom0/']"
	```

1. Install CUPS
	```
	sudo pacman -S cups cups-pdf system-config-printer foomatic-db-engine foomatic-db foomatic-db-ppds foomatic-db-nonfree-ppds foomatic-db-gutenprint-ppds
	sudo systemctl enable --now cups.service
	```

1. Install [paru](https://github.com/Morganamilo/paru)
	```
	mkdir -p ~/aur
	cd ~/aur
	PKG="paru-bin" && git clone "https://aur.archlinux.org/${PKG}.git/" && cd "${PKG}" && makepkg -i -s -r --skippgpcheck
	```

1. Install Google Chrome
	```
	paru -S google-chrome
	```

	- Disable media key control - `chrome://flags/#hardware-media-key-handling`
	- Enable screen sharing with Wayland - `chrome://flags/#enable-webrtc-pipewire-capturer`
