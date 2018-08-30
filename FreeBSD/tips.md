# FreeBSD Tips

## Post-Installation

1. Install ports.

		portsnap fetch
		portsnap extract

1. Disable X11.

		echo 'WITHOUT_X11=yes' >> /etc/make.conf

1. Enable Serial Console.
    - Modify `boot.config` and `loader.conf`.

			echo '-Dh' > /boot.config
			printf 'boot_multicons="YES"\nboot_serial="YES"\ncomconsole_speed="115200"\nconsole="comconsole,vidconsole"\n' >> /boot/loader.conf

    - Edit `/etc/ttys` and change `9600` to `115200`, `off` to `on`, and `dialup` to `vt100` for the `ttyu0` entry.

			sed -i .bak -e 's_^\(ttyu0.*\)\(std.9600\)_\1std.115200_' -e 's_^\(ttyu0.*\)\(dialup\)_\1vt100_' -e 's_^\(ttyu0.*\)\(off secure\)_\1on  secure_' /etc/ttys

1. Make some SSH clients behave better.

		printf "\"\\\e[1~\": beginning-of-line\n\"\\\e[4~\": end-of-line\n\"\\\e[3~\": delete-char\n" > ~/.inputrc

## Enable mail via Gmail

1. Disable sendmail.

		printf 'sendmail_enable="NO"\nsendmail_submit_enable="NO"\nsendmail_outbound_enable="NO"\nsendmail_msp_queue_enable="NO"\n' >> /etc/rc.conf
		killall sendmail

2. Install sSMTP.

		cd /usr/ports/mail/ssmtp
		make install replace distclean

3. Edit `/usr/local/etc/ssmtp/ssmtp.conf`.

		root=youremail@gmail.com
		mailhub=smtp.gmail.com:587
		AuthUser=youremail@gmail.com
		AuthPass=yourpassword
		UseSTARTTLS=YES

## Enable SMART Monitoring

1. Install S.M.A.R.T. Monitoring Tools.

		cd /usr/ports/sysutils/smartmontools
		make install distclean

1. Include drive health information in daily status reports.

		echo 'daily_status_smart_devices="AUTO"' >> /etc/periodic.conf

## Enable SNMP

1. Install net-snmp.

		cd /usr/ports/net-mgmt/net-snmp
		make install distclean

1. Enable snmpd.

		printf 'snmpd_enable="YES"\nsnmpd_flags="-a"\nsnmpd_conffile="/usr/local/share/snmp/snmpd.conf"\n' >> /etc/rc.conf

1. Edit snmpd configuration.

		vim /usr/local/share/snmp/snmpd.conf

1. Start snmpd.

		/usr/local/etc/rc.d/snmpd start

## Optional Tools

1. Install Zsh

		cd /usr/ports/shells/zsh
		make install distclean

1. Install Bash

		cd /usr/ports/shells/bash
		make install distclean

1. Install tmux

		cd /usr/ports/sysutils/tmux
		make install distclean

1. Install vim

		cd /usr/ports/editors/vim
		make install distclean

1. Install portupgrade

		cd /usr/ports/ports-mgmt/portupgrade
		make install distclean

## Maintenance

### Ports Updates 

	portsnap fetch
	portsnap update
	portupgrade -ai

### Package Updates

	pkg update
	pkg upgrade
	pkg audit -F

### FreeBSD Updates

	freebsd-update fetch
	freebsd-update install

