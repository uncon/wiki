# Citrix Gateway Plug-in for Linux (NSGClient)
This guide can be used to setup an LXC container to run NSGClient on an Arch Linux host. Aside from the initial host setup, this process is largely portable to other Linux distributions as well.

## Setup LXC
1. Install the required packages

		sudo pacman -Sy lxc dnsmasq

1. Setup a host network per [these instructions](https://wiki.archlinux.org/index.php/Linux_Containers#Host_network_configuration)

1. Enable and start the LXC network service

		sudo systemctl enable --now lxc-net.service

## Setup a Container
1. Create a new container with Ubuntu 16.04 (Xenial Xerus)

		sudo lxc-create -t download -n nsgclient -- --dist ubuntu --release xenial --arch amd64

1. Configure the container for tun device support

		printf '\n# Allow tun device\nlxc.mount.entry = /dev/net dev/net none bind,create=dir\nlxc.cgroup.devices.allow = c 10:200 rwm\n' | sudo tee -a '/var/lib/lxc/nsgclient/config'

1. Start the container
		sudo lxc-start -n nsgclient

1. Attach to a root prompt inside the container (bypasses login)

		sudo lxc-attach -n nsgclient

1. Add a use to the container and exit the console

		/usr/sbin/adduser uncon
		/usr/sbin/usermod -aG sudo uncon
		exit

1. Connect to the container's console

		sudo lxc-console -n nsgclient

1. Download and install NSGClient and its dependencies

		scp nsroot@192.168.86.230:/var/netscaler/gui/vpn/scripts/linux/nsgclient64.deb .
		sudo dpkg -i nsgclient64.deb
		sudo apt-get install -f
		sudo apt-get install libcurl3 dbus-x11

1. Configure NSG Client

		sudo sed -i.orig '1s/^/Citrix_VA\n/' '/etc/resolvconf/interface-order'
		mkdir "${HOME}/.citrix"
		sed -e 's_^\(userName =\).*$_\1 myusername_' -e 's_^\(gatewayAddress =\).*$_\1 https://mygateway.mydomain.com_' -e 's_^\(gatewayPort =\).*$_\1 443_' -e 's_^\(proxyIP =\).*$_\1 direct://_' -e 's_^\(certdebug =\).*$_\1 1_' -e 's_^\(ignoreCertErrors =\).*$_\1 0_' /opt/Citrix/NSGClient/userConfiguration.conf > "${HOME}/.citrix/userConfiguration.conf"

1. Create a script to start and stop the connection

		sh -c "cat > ${HOME}/nsgclient.sh" << EOF
		#/bin/sh
		case "\$1" in
		  start)
		    export \$(dbus-launch)
		    /opt/Citrix/NSGClient/bin/NSGClient -c
		    printf "nameserver 10.0.0.2\nsearch citrite.net\n" | sudo resolvconf -a Citrix_VA
		    sudo resolvconf -u
		    ;;
		  stop)
		    pkill NSGClient
		    sudo resolvconf -d Citrix_VA
		    sudo resolvconf -u
		    ;;
		  *)
		    echo "Usage: \$(basename "\$0") [start|stop]"
		    exit 1
		    ;;
		esac
		EOF
		chmod +x "${HOME}/nsgclient.sh"

1. Install OpenSSH and Privoxy

		sudo apt-get install privoxy openssh-server
		sed -e 's/^\(listen-address\s*\).*:/\1192.168.89.83:/g' -e 's/^\(toggle\s*\).*$/\10/g' -e 's/^#\(debug\s*1 \)/\1/g' /etc/privoxy/config > "${HOME}/config"

1. Exit the container's console by pressing ctrl-a then q

# Usage

1. Start the container (if it's not already running)

		sudo lxc-start -n nsgclient

1. SSH into the container

		ssh 192.168.89.83

1. Start the connection

		./nsgclient.sh start

1. Start Privoxy

		privoxy --no-daemon

1. Configure your host to use the container's IP as an HTTP proxy
