# Arch Linux Citrix Software

##  Receiver for Linux
This was last tested with Receiver for Linux 13.1.0.285639 on 64-bit Arch (2014-12-08).

**Note**: Technically, Receiver for Linux is 32-bit for the time being.  You must enable the multilib repository in `/etc/pacman.conf`.

1. Prerequisites

		# sudo pacman -S lib32-gtk2 lib32-alsa-plugins lib32-libpulse webkitgtk2

1. Optional Dependencies

	These are required for Receiver (selfservice), but the main client binary (wfica) will work without them.  Unfortunately, as libxerces-c is missing, the selfservice binary will not actually work.

		# sudo aura -A libpng12 

1. Download [Receiver for Linux](http://receiver.citrix.com/)

1. Extract Receiver for Linux archive

1. Run installer

		# ./setupwfc

1. Extra Credit

	1. Find missing libraries

			# find "${HOME}/ICAClient" -type f -executable -exec ldd "{}" \; | grep " not found$" | awk '{ print $1 }' | sort -u
			
	1. Install them
