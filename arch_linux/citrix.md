# Arch Linux Citrix Software

##  Receiver for Linux
This was last tested with Receiver for Linux 13.0.0.256735 on 64-bit Arch (2014-05-14).

**Note**: Technically, Receiver for Linux is exclusively 32-bit for the time being.  You must enable the multilib repository in `/etc/pacman.conf`.

1. Prerequisites

		# sudo pacman -S lib32-gtk2 lib32-alsa-plugins lib32-libpulse lib32-libxml2 lib32-libpng12 lib32-gstreamer0.10 lib32-speex

1. Download [Receiver for Linux](http://receiver.citrix.com/)

	**Note**: The .tar.gz installer package is under 32-bit systems.  (The 64-bit packages merely depend on the required 32-bit libraries.)

1. Extract Receiver for Linux archive

1. Run installer

		# ./setupwfc

1. Extra Credit

	1. Find missing libraries

			# find . -type f -executable -exec ldd "{}" \; | grep " not found$" | awk '{ print $1 }' | sort -u
			
	1. Install them (32-bit)
