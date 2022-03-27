# Install Arch Linux on Chrome OS (via Crostini)

1. Enable Linux support: **Settings** > **Developers** > **Linux development environment** > **Turn on**

1. Open a new crosh terminal in Chrome: **Ctrl** + **Alt** + **T**

1. Remove the Debian container

	```
	vmc destroy termina
	vmc start termina
	exit
	```

1. Create an Arch Linux container

	```
	vmc container termina penguin https://images.linuxcontainers.org archlinux/current
	```

	**Note**: The following error is expected and can be ignored

	```
	ERROR vsh: [vsh.cc(150)] Failed to launch vshd for termina:penguin: requested container does not exist: penguin
	```

1. Ensure the container was created successfully

	```
	vsh termina
	lxc list
	```
	**Note**: If the container did not start, start it

	```
	lxc start arch
	```

1. Start a Bash shell in the container

	```
	lxc exec penguin -- bash
	```

1. Configure user account

	```
	pkill -9 -u $(grep 1000:1000 /etc/passwd | cut -d ':' -f 1)
	groupmod -n uncon $(grep 1000:1000 /etc/passwd | cut -d ':' -f 1)
	usermod -d /home/uncon -l uncon -m -c uncon $(grep 1000:1000 /etc/passwd | cut -d ':' -f 1)
	passwd uncon
	visudo
	usermod -aG wheel uncon
	exit
	```

1. Log on to the container using the new user account

	```
	lxc console penguin
	```

1. Configure system

	```
	sudo pacman -Sy
	sudo pacman -S base-devel git wayland xorg-xwayland ttf-croscore noto-fonts noto-fonts-cjk noto-fonts-emoji noto-fonts-extra
	mkdir -p ~/aur
	cd ~/aur
	PKG="paru-bin" && git clone "https://aur.archlinux.org/${PKG}.git/" && cd "${PKG}" && makepkg -i -s -r --skippgpcheck && cd && rm -fr ~/aur
	paru -S cros-container-guest-tools-git
	cp -r /etc/skel/.config/pulse ~/.config
	sudo systemctl reboot
	lxc restart penguin
	```
