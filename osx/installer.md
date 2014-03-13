# OS X Installer

The following process will build a bootable .iso file for installing OS X.  ([source](http://www.contrib.andrew.cmu.edu/~somlo/OSXKVM/MakeMavericksDVD.sh))
1. Mount the installer image \\ `hdiutil attach /Applications/Install\ OS\ X\ Mavericks.app/Contents/SharedSupport/InstallESD.dmg -noverify -nobrowse -mountpoint /Volumes/install_app`
2. Convert the boot image to a sparse bundle \\ `hdiutil convert /Volumes/install_app/BaseSystem.dmg -format UDSP -o /tmp/Mavericks`
3. Increase the sparse bundle capacity to accommodate the packages \\ `hdiutil resize -size 8g /tmp/Mavericks.sparseimage`
4. Mount the sparse bundle for package addition \\ `hdiutil attach /tmp/Mavericks.sparseimage -noverify -nobrowse -mountpoint /Volumes/install_build`
5. Remove Package link and replace with actual files \\ `<code>`rm /Volumes/install_build/System/Installation/Packages
cp -rp /Volumes/install_app/Packages /Volumes/install_build/System/Installation/`</code>`
1. Unmount both the installer image and the sparse bundle \\ `<code>`hdiutil detach /Volumes/install_app
hdiutil detach /Volumes/install_build`</code>`
1. Resize the partition in the sparse bundle to remove any free space \\ `hdiutil resize -size $(hdiutil resize -limits /tmp/Mavericks.sparseimage | tail -n 1 | awk '{ print $1 }')b /tmp/Mavericks.sparseimage`
2. Convert the sparse bundle to ISO/CD master \\ `hdiutil convert /tmp/Mavericks.sparseimage -format UDTO -o /tmp/Mavericks`
3. Remove the sparse bundle \\ `rm /tmp/Mavericks.sparseimage`
4. Rename the ISO and move it to the desktop \\ `mv /tmp/Mavericks.cdr ~/Desktop/Mavericks.iso`
