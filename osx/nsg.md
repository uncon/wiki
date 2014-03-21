# NetScaler Gateway Wrapper for OS X

`nsg.sh`
	
	#!/bin/sh
	NSG_FQDN='nsg.domain.com'
	
	DNS_HOST='192.168.1.1'
	DNS_USER='root'
	
	NSG_UA='Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.74.9 (KHTML, like Gecko) Version/7.0.2 Safari/537.74.9'
	
	localAuth() {
		echo "- Local authentication"
		sudo -v
	}
	
	getHosts() {
		echo "- Getting remote DNS hosts"
		echo "- ${DNS_USER}@${DNS_HOST} authentication"
		sudo sh -c "ssh ${DNS_USER}@${DNS_HOST} cat /etc/hosts | grep "^192\.168\." >> /etc/hosts"
	}
	
	cleanHosts() {
		echo "- Cleaning remote DNS hosts"
		sudo sed -i .bak -e '/^192\.168\..*$/d' -e '/^$/d' /etc/hosts
	}	
	
	restartDNS() {
		echo "- Restarting mDNSResponder"
		sudo killall -HUP mDNSResponder
	}
	
	NSGStart() {
		echo "- Launching Access Gateway"
		open "/Applications/Citrix/Access Gateway.app"
	}
	
	NSGAuth() {
		echo "- Authenticating"
		printf 'Username: '
		read NSG_USER
		printf 'Password: '
		read -s NSG_PASSWD
		echo
		printf 'Token: '
		read -s NSG_PASSWD2
		echo
		
		rm "${HOME}/.nsg_cookies" &> /dev/null
		
		HTTP_RESP=$(curl -w "%{http_code}" -s -c "${HOME}/.nsg_cookies" "https://${NSG_FQDN}/cgi/login" -H 'Content-Type: application/x-www-form-urlencoded' -H "Referer: https://${NSG_FQDN}/vpn/index.html" -H "User-Agent: ${NSG_UA}" --data "login=${NSG_USER}&passwd=${NSG_PASSWD}&passwd1=${NSG_PASSWD2}" -o /dev/null)
		if [ ${HTTP_RESP} -ne 302 ]; then
			echo "Expected 302 response but got ${HTTP_RESP}.  Continuing."
		fi
	
		# Check For An Error
		NSG_ERR=$(grep 'NSC_VPNERR' "${HOME}/.nsg_cookies" | cut -f 7)
		if [ "${NSG_ERR}" ]; then
			echo "Authentication Error (${NSG_ERR})"
			cleanHosts
			exit
		fi
	
		# Set Client
		HTTP_RESP=$(curl -w "%{http_code}" -s -b "${HOME}/.nsg_cookies" "https://${NSG_FQDN}/cgi/setclient?macc" -H "Referer: https://${NSG_FQDN}/vpn/index.html" -H "User-Agent: ${NSG_UA}" -o /dev/null)
		if [ ${HTTP_RESP} -ne 302 ]; then
			echo "Expected 302 response but got ${HTTP_RESP}.  Continuing."
		fi
	
		# Grab AAA Cookie
		NSG_AAAC=$(grep "NSC_AAAC" "${HOME}/.nsg_cookies" | cut -f 7)	
	
		# Verify AAA Cookie
		if [ "${NSC_AAAC}" = "xyz" ]; then
			echo "Failed to get AAA cookie"
			cleanHosts
			exit
		fi
	}
	
	NSGLaunch() {
		echo "- Launching client connection"
		HTTP_RESP=$(curl -w "%{http_code}" -s "http://localhost:3148/svc?NSC_AAAC=${NSG_AAAC}&nsloc=https://${NSG_FQDN}/vpns/m_services.html&nsversion=10,1,120,1316&nstrace=DEBUG&nsvip=255.255.255.255" -o /dev/null)
		if [ ${HTTP_RESP} -ne 200 ]; then
			echo "Expected 200 response but got ${HTTP_RESP}.  Continuing."
		fi
	}
	
	NSGStop() {
		echo "- Stopping Access Gateway"
		HTTP_RESP=$(curl -w "%{http_code}" -s -b "${HOME}/.nsg_cookies" "https://${NSG_FQDN}/cgi/logout" -o /dev/null)
		if [ ${HTTP_RESP} -ne 302 ]; then
			echo "Expected 302 response but got ${HTTP_RESP}.  Continuing."
		fi
		killall "Access Gateway"
		rm "${HOME}/.nsg_cookies" &> /dev/null
	}
	
	case "$1" in
		start)
			echo "Connecting..."
			localAuth
			getHosts
			NSGStart
			NSGAuth
			NSGLaunch
			;;
		restart)
			echo "Connecting..."
			NSGStop
			NSGStart
			NSGAuth
			NSGLaunch
			;;
		stop)
			echo "Disconnecting..."
			localAuth
			NSGStop
			cleanHosts
			restartDNS
			;;
		reconnect)
			echo "Reconnecting..."
			NSGLaunch
			;;
		refresh)
			echo "Refreshing DNS..."
			localAuth
			cleanHosts
			getHosts
			restartDNS
			;;
		*)
			echo "Usage: $0 [start|stop|reconnect|refresh|restart]"
			exit 1
			;;
	esac
