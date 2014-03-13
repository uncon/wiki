# ZFS One-Liners

## Remove all snapshots
	for snapshot in $(zfs list -H -t snapshot | grep "^storage/xenserver" | awk '{print $1}'); do zfs destroy "${snapshot}"; done`
