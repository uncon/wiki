# FreeBSD Installation on VMware

## VM Creation
1. Download vmdk image from [here](http://ftp.freebsd.org/pub/FreeBSD/snapshots/VM-IMAGES/10.0-RELEASE/amd64/Latest/).
2. Configure VM using downloaded image.
    - **OS type**: Linux
    - **Version**: Other Linux 3.x kernel 64-bit``

## Initial Boot

1. Set root password.

		passwd

2. Configure system in `/etc/rc.conf`.

		hostname=freebsd
		sshd_enable="YES"
		ifconfig_vtnet0="DHCP"

1. Install base package.

		pkg update
		pkg install bash zsh tmux vim-lite sudo

1. Add user.

		adduser

