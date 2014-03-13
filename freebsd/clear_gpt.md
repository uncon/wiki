# Clear GPT Partition

	sysctl kern.geom.debugflags=17
	for disk in /dev/ada[0-5]; do dd if=/dev/zero of=${disk} bs=512 count=1; dd if=/dev/zero of=${disk} bs=512 seek=$(( $(diskinfo -v ${disk} | grep "mediasize in sectors" | awk '{ print $1 }') - 1 )); done
	sysctl kern.geom.debugflags=0
