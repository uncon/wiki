# Arch Linux Tips

## GNOME

### Disable HiDPI

	# gsettings set org.gnome.desktop.interface scaling-factor 1

## Cinnamon

### Disable HiDPI

	# gsettings set org.cinnamon.desktop.interface scaling-factor 1

### OS X Style Lock Keybinding

	# gsettings set org.cinnamon.keybindings custom-list "['custom0']"
	# gsettings set org.cinnamon.keybindings.custom-keybinding:/org/cinnamon/keybindings/custom-keybindings/custom0/ name 'Lock'
	# gsettings set org.cinnamon.keybindings.custom-keybinding:/org/cinnamon/keybindings/custom-keybindings/custom0/ command "/bin/sh -c 'cinnamon-screensaver-command --lock && sleep 3 && xset dpms force standby'"
	# gsettings set org.cinnamon.keybindings.custom-keybinding:/org/cinnamon/keybindings/custom-keybindings/custom0/ binding '<Primary><Alt>l'

## Google Chrome

### Enable WebGL for blacklisted GPU's

	sudo sed -i.orig -e 's#^Exec=/usr/bin/google-chrome-stable#Exec=/usr/bin/google-chrome-stable --ignore-gpu-blacklist#g' /usr/share/applications/google-chrome.desktop

## Maintenance

### Remove all packages no longer required as dependencies (orphans)

	# pacman -R $(pacman -Qdt | awk '{ print $1 }' | tr '\n' ' ')

### Update mirror list

	# sudo su -c "curl 'https://www.archlinux.org/mirrorlist/?country=US&protocol=http&ip_version=4&ip_version=6&use_mirror_status=on' | sed -e 's/^#Server = /Server = /g' | grep -v '^#' | head -n 6 > ~/mirrorlist.tmp && rankmirrors -n 6 ~/mirrorlist.tmp > /etc/pacman.d/mirrorlist && rm ~/mirrorlist.tmp"

### Remove package cache

	# pacman -Sc
