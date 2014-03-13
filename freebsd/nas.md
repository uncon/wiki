# FreeBSD as a NAS Server

See [FreeBSD Tips](freebsd/tips).
## ZFS

1. Enable ZFS

		echo 'zfs_enable="YES"' >> /etc/rc.conf
		/etc/rc.d/zfs start

1. Enable ZFS status updates

		echo 'daily_status_zfs_enable="YES"' >> /etc/periodic.conf

2. Enable periodic scrub

		echo 'daily_scrub_zfs_enable="YES"' >> /etc/periodic.conf

3. Find disk device names

		camcontrol devlist

4. Create ZFS pool

		zpool create storage raidz2 da2 da3 da4 da5 da6 da7

5. Create a new file system in the pool for /home

		zfs create storage/home

6. Set the mount point

		zfs set mountpoint=/usr/home storage/home

## Samba

1. Install Samba

		cd /usr/ports/net/samba36
		make install distclean

1. Configure Samba

		vi /usr/local/etc/smb.conf

2. Enable Samba at boot

		printf 'samba_enable="YES"\nwinbindd_enable="YES"\n' >> /etc/rc.conf

3. Start Samba

		/usr/local/etc/rc.d/samba start

4. Add users to Samba

		pdbedit -a [username]


## Enable NFS for XenServer

1. Create a new file system

		zfs create storage/xenserver

2. Enable NFS

		touch /etc/exports
		printf 'rpcbind_enable="YES"\nnfs_server_enable="YES"\nmountd_flags="-r"\n' >> /etc/rc.conf
		zfs sharenfs='maproot=root -alldirs -network [XenServerIP] -mask 255.255.255.255' storage/xenserver

1. Start NFS

		rpcbind
		nfsd -u -t -n 4
		mountd -r

1. Verify mount

		showmount -e


## TFTP (PXE)

1. Install tftp-hpa

		cd /usr/ports/ftp/tftp-hpa
		make install distclean

1. Enable TFTP server at boot

		printf 'tftpd_enable="YES"\ntftpd_flags="-p -s /storage/software/OS/PXE -B 1024 --ipv4"\n' >> /etc/rc.conf

2. Start TFTP server

		/usr/local/etc/rc.d/tftpd start


## MiniDLNA

1. Install MiniDLNA

		cd /usr/ports/net/minidlna
		make install distclean

1. Edit the configuration file

		vim /usr/local/etc/minidlna.conf

2. Make the dlna user the owner of the /var/db/minidlna directory

		mkdir -p /var/db/minidlna
		chown dlna:dlna /var/db/minidlna

1. Enable MiniDLNA at boot

		echo 'minidlna_enable="YES"' >> /etc/rc.conf

2. Start MiniDLNA

		/usr/local/etc/rc.d/minidlna onestart


## Optional Tools

1. Install zfs-periodic

		cd /usr/ports/sysutils/zfs-periodic
		make install distclean

