# Arch Linux Tips

## Maintenance

### Update mirror list

	sudo su -c "curl -L 'https://archlinux.org/mirrorlist/?country=US&protocol=https&ip_version=4&ip_version=6&use_mirror_status=on' | sed -e 's/^#Server = /Server = /g' -e '/^#/d' | head -n 6 > /etc/pacman.d/mirrorlist"

### Remove all packages no longer required as dependencies (orphans)

	pacman -R $(pacman -Qdt | awk '{ print $1 }' | tr '\n' ' ')

### Remove package cache

	pacman -Sc
