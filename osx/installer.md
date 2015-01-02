# OS X Installer

## USB
1. Format a USB drive with a 'Mac OS Extened (Journaled)' partition using Disk Utility

1. Create Installation media

		sudo /Applications/Install\ OS\ X\ Mavericks.app/Contents/Resources/createinstallmedia --volume /Volumes/Untitled --applicationpath /Applications/Install\ OS\ X\ Mavericks.app --nointeraction

## ISO (Yosemite)
The following process will build a bootable .iso file for installing OS X 10.10.

1. Mount the installer image

		hdiutil attach /Applications/Install\ OS\ X\ Yosemite.app/Contents/SharedSupport/InstallESD.dmg -noverify -nobrowse -mountpoint /Volumes/install_app

1. Convert the boot image to a sparse bundle

		hdiutil convert /Volumes/install_app/BaseSystem.dmg -format UDSP -o /tmp/Yosemite

1. Increase the sparse bundle capacity to accommodate the packages

		hdiutil resize -size 8g /tmp/Yosemite.sparseimage

1. Mount the sparse bundle for package addition

		hdiutil attach /tmp/Yosemite.sparseimage -noverify -nobrowse -mountpoint /Volumes/install_build

1. Remove Package link and replace with actual files

		rm /Volumes/install_build/System/Installation/Packages
		cp -rp /Volumes/install_app/Packages /Volumes/install_build/System/Installation/

1. Copy BaseSystem

		cp -rp /Volumes/install_app/BaseSystem.dmg /Volumes/install_app/BaseSystem.chunklist /Volumes/install_build		

1. Unmount both the installer image and the sparse bundle

		hdiutil detach /Volumes/install_app
		hdiutil detach /Volumes/install_build

1. Resize the partition in the sparse bundle to remove any free space

		hdiutil resize -size $(hdiutil resize -limits /tmp/Yosemite.sparseimage | tail -n 1 | awk '{ print $1 }')b /tmp/Yosemite.sparseimage

1. Convert the sparse bundle to ISO/CD master

		hdiutil convert /tmp/Yosemite.sparseimage -format UDTO -o /tmp/Yosemite

1. Remove the sparse bundle

		rm /tmp/Yosemite.sparseimage

1. Rename the ISO and move it to the desktop

		mv /tmp/Yosemite.cdr ~/Desktop/Yosemite.iso
