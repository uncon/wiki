# Arch Linux Citrix Software

##  Receiver for Linux
This was last tested with Receiver for Linux 13.2.0.322243 on 64-bit Arch (2015-07-01).

1. Optional Dependencies

	The main client binary (wfica) will work without these (using the Receiver for Web).  These (legacy) dependencies are required for the Receiver UI (selfservice).

		# sudo pacman -Sy webkitgtk2

1. Download [Receiver for Linux](https://www.citrix.com/downloads/citrix-receiver/linux.html)

1. Extract Receiver for Linux archive

1. Run installer

		# ./setupwfc

1. Extra Credit

	1. Find missing libraries

			# find "${HOME}/ICAClient" -type f -executable -exec ldd "{}" \; | grep " not found$" | awk '{ print $1 }' | sort -u
			
	1. Install them
