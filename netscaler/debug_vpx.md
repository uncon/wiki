# How to Build a Debugging VPX Instance

## Initial Boot
1. Exit wizard with ctrl-c

2. Login with `nsrecover` : `nsroot`

3. Enable developer mode

		# touch /flash/nsconfig/.developer

4. Reboot

		# reboot

## Initial Network Configuration

1. Configur IP address

		# ifconfig 1/1 192.168.1.62 netmask 255.255.255.0 up

2. Configur default route

		# route add default 192.168.1.1

3. Configur DNS

		# echo "nameserver 192.168.1.21" > /etc/resolv.conf

## Install FreeBSD Base

1. Download installation files

		# cd /var/tmp
		# mkdir fbsd
		# cd fbsd
		# ftp ftp-archive.freebsd.org

		ftp> cd /mirror/FreeBSD-Archive/old-releases/amd64/6.3-RELEASE/base/
		ftp> mget

1. Create installation directory

		mkdir /var/fbsd

2. Install

		cat base.?? | tar --unlink -xpzf - -C /var/fbsd

3. Clean up

		cd
		rm -fr /var/tmp/fbsd

## Network Configuration

1. Create /flash/nsconfig/nsfirst.sh

		ifconfig 1/1 192.168.1.63 netmask 255.255.255.0 up
		route add default 192.168.1.1
		echo "nameserver 192.168.1.21" > /etc/resolv.conf

## FreeBSD chroot

1. Enter FreeBSD environment

		chroot /var/fbsd /bin/sh
