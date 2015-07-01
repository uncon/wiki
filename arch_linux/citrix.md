# Arch Linux Citrix Software

##  Receiver for Linux
This was last tested with Receiver for Linux 13.2.0.322243 on 64-bit Arch (2015-07-01).

**Note**: Technically, Receiver for Linux is currently 32-bit.  Thusly, you must enable the multilib repository in `/etc/pacman.conf`.

1. Prerequisites

		# sudo pacman -Sy lib32-gtk2 lib32-alsa-plugins lib32-libpulse lib32-speex lib32-libvorbis webkitgtk2

1. Optional Dependencies

	The main client binary (wfica) will work without these (using the Receiver for Web).  These dependencies would be required for the Receiver UI (selfservice).  However, as libxerces-c is missing, the Receiver UI will not actually work anyway.

		# sudo aura -A libpng12 

1. Download [Receiver for Linux](https://www.citrix.com/downloads/citrix-receiver/linux.html)

1. Extract Receiver for Linux archive

1. Run installer

		# ./setupwfc

1. Extra Credit

	1. Find missing libraries

			# find "${HOME}/ICAClient" -type f -executable -exec ldd "{}" \; | grep " not found$" | awk '{ print $1 }' | sort -u
			
	1. Install them
