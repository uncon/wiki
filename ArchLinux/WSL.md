# Arch Linux on WSL

## Preparation
1. Run the following command in an **administrator** PowerShell or Windows Command Prompt to enable the required optional components, download the latest Linux kernel, set WSL 2 as your default, and install a Linux distribution for you (Ubuntu, which you can remove later)
	```
	wsl --install
	```
2. Download the latest `base-devel` rootfs release of archlinux-docker: [Package Registry](https://gitlab.archlinux.org/archlinux/archlinux-docker/-/packages/597)
3. Extract the tar file from the XZ archive to `C:\WSL\` (WSL does not support this format)

## WSL Import
Run the following command in a PowerShell or Windows Command Prompt

1. Change directory
	```
	cd C:\WSL\
	```
2. Import Arch
	```
	wsl --import Arch Arch base-devel.tar
	````

# Arch Setup
For a root shell, from a PowerShell or Windows Command Prompt, run Arch: `wsl --distribution Arch`

1. Set root password
	```
	passwd
	```
2. Install packages
	```
	pacman -Syu
	pacman -S openssh net-tools bind-tools sudo wget git vim tmux zsh p7zip
	```
3. Add user
	```
	useradd -m -g users -G wheel -s /bin/zsh uncon
	chfn uncon
	passwd uncon
	```
4. Configure sudo
	```
	EDITOR=vim visudo
	```
	Uncomment the following line.
	```
	## Uncomment to allow members of group wheel to execute any command
	%wheel ALL=(ALL) ALL
	```
5. Set the default user
	```
	echo -e '[user]\ndefault = uncon' > /etc/wsl.conf
	```
6. Exit
	```
	exit
	```

## WSL Setup
Run the following command in a PowerShell or Windows Command Prompt

1. Set Arch as the default distribution
	```
	wsl --set-default Arch
	````
