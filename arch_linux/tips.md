# Arch Linux Tips

## Maintenance
### Remove all packages no longer required as dependencies (orphans)

	pacman -R $(pacman -Qdt | awk '{ print $1 }' | tr '\n' ' ')

### Update mirror list

	curl "https://www.archlinux.org/mirrorlist/?country=US&protocol=http&ip_version=4&use_mirror_status=on" | sed -e 's/^#Server = /Server = /g' > /etc/pacman.d/mirrorlist

### Remove package cache

	rm /var/cache/pacman/pkg/*.pkg.tar.xz
