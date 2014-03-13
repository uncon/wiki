# How to Build a Debugging chroot Environment

The following guide can be used to create a chroot environment for debugging NetScaler on FreeBSD.

## Extraction

1. Download KVM build

2. Unzip

3. Extract

		cat NSVPX-KVM-10.1-118.7_nc | tar -xvf -

4. Attach disk

		mdconfig -a -t vnode -f NSVPX-KVM-10.1-118.7_nc.raw

5. Mount flash

		mount /dev/md0s1a /mnt

6. Copy kernel

		cp /mnt/ns-10.1-118.7.gz .

7. Unmount flash

		umount /mnt

8. Extract

		gunzip ns-10.1-118.7.gz

9. Find UFS

		binwalk -m ufs.magic ns-10.1-118.7 | grep "netscaler_mdroot"

10. Extract UFS

		dd bs=1 skip=11387512 if=ns-10.1-118.7 of=ns-10.1-118.7-md.bin

## Attach

1. [Optional] Attach disk

		mdconfig -a -t vnode -f NSVPX-KVM-10.1-118.7_nc.raw

2. Attach md

		mdconfig -a -t vnode -f ns-10.1-118.7-md.bin

3. Mount root

		mount /dev/md1 /mnt

4. Mount var

		mount /dev/md0s1e /mnt/var

5. [Optional] Mount external paths

		mount -t nullfs -o ro /upload/ftp /mnt/upload/ftp; mount -t nullfs -o ro /supdata/NS-Tools/nsppe /mnt/nsppe

6. Change root

		chroot /mnt /usr/bin/bash

7. Set path

		export PATH=/netscaler:/bin:/sbin:/usr/bin:/usr/sbin:/usr/libexec:/usr/local/bin:/usr/local/sbin:.

## Cleanup

1. Unmount

		umount /mnt/var /mnt

2. Detach disks

		mdconfig -d -u 0; mdconfig -d -u 1

## ufs.magic
	
	9564    lelong          0x00011954      Unix Fast File system [v1] (little-endian),
	>8404   string          x               last mounted on %s,
	#>9504  ledate          x               last checked at %s,
	>8224   ledate          x               last written at %s,
	>8401   byte            x               clean flag %d,
	>8228   lelong          x               number of blocks %d,
	>8232   lelong          x               number of data blocks %d,
	>8236   lelong          x               number of cylinder groups %d,
	>8240   lelong          x               block size %d,
	>8244   lelong          x               fragment size %d,
	>8252   lelong          x               minimum percentage of free blocks %d,
	>8256   lelong          x               rotational delay %dms,
	>8260   lelong          x               disk rotational speed %drps,
	>8320   lelong          0               TIME optimization
	>8320   lelong          1               SPACE optimization
	
	
	42332   lelong          0x19540119      Unix Fast File system [v2] (little-endian)
	42332   lelong          0x01191954      UFS2 wtf?
	>&-1164 string          x               last mounted on %s,
	>&-696  string          >\0             volume name %s,
	>&-304  leqldate        x               last written at %s,
	>&-1167 byte            x               clean flag %d,
	>&-1168 byte            x               readonly flag %d,
	>&-296  lequad          x               number of blocks %lld,
	>&-288  lequad          x               number of data blocks %lld,
	>&-1332 lelong          x               number of cylinder groups %d,
	>&-1328 lelong          x               block size %d,
	>&-1324 lelong          x               fragment size %d,
	>&-180  lelong          x               average file size %d,
	>&-176  lelong          x               average number of files in dir %d,
	>&-272  lequad          x               pending blocks to free %lld,
	>&-264  lelong          x               pending inodes to free %ld,
	>&-664  lequad          x               system-wide uuid %0llx,
	>&-1316 lelong          x               minimum percentage of free blocks %d,
	>&-1248 lelong          0               TIME optimization
	>&-1248 lelong          1               SPACE optimization
	
	
	66908   lelong          0x19540119      Unix Fast File system [v2] (little-endian)
	>&-1164 string          x               last mounted on %s,
	>&-696  string          >\0             volume name %s,
	>&-304  leqldate        x               last written at %s,
	>&-1167 byte            x               clean flag %d,
	>&-1168 byte            x               readonly flag %d,
	>&-296  lequad          x               number of blocks %lld,
	>&-288  lequad          x               number of data blocks %lld,
	>&-1332 lelong          x               number of cylinder groups %d,
	>&-1328 lelong          x               block size %d,
	>&-1324 lelong          x               fragment size %d,
	>&-180  lelong          x               average file size %d,
	>&-176  lelong          x               average number of files in dir %d,
	>&-272  lequad          x               pending blocks to free %lld,
	>&-264  lelong          x               pending inodes to free %ld,
	>&-664  lequad          x               system-wide uuid %0llx,
	>&-1316 lelong          x               minimum percentage of free blocks %d,
	>&-1248 lelong          0               TIME optimization
	>&-1248 lelong          1               SPACE optimization
	
	
	9564    belong          0x00011954      Unix Fast File system [v1] (big-endian),
	>7168   belong          0x4c41424c      Apple UFS Volume
	>>7186  string          x               named %s,
	>>7176  belong          x               volume label version %d,
	>>7180  bedate          x               created on %s,
	>8404   string          x               last mounted on %s,
	#>9504  bedate          x               last checked at %s,
	>8224   bedate          x               last written at %s,
	>8401   byte            x               clean flag %d,
	>8228   belong          x               number of blocks %d,
	>8232   belong          x               number of data blocks %d,
	>8236   belong          x               number of cylinder groups %d,
	>8240   belong          x               block size %d,
	>8244   belong          x               fragment size %d,
	>8252   belong          x               minimum percentage of free blocks %d,
	>8256   belong          x               rotational delay %dms,
	>8260   belong          x               disk rotational speed %drps,
	>8320   belong          0               TIME optimization
	>8320   belong          1               SPACE optimization
	
	42332   belong          0x19540119      Unix Fast File system [v2] (big-endian)
	>&-1164 string          x               last mounted on %s,
	>&-696  string          >\0             volume name %s,
	>&-304  beqldate        x               last written at %s,
	>&-1167 byte            x               clean flag %d,
	>&-1168 byte            x               readonly flag %d,
	>&-296  bequad          x               number of blocks %lld,
	>&-288  bequad          x               number of data blocks %lld,
	>&-1332 belong          x               number of cylinder groups %d,
	>&-1328 belong          x               block size %d,
	>&-1324 belong          x               fragment size %d,
	>&-180  belong          x               average file size %d,
	>&-176  belong          x               average number of files in dir %d,
	>&-272  bequad          x               pending blocks to free %lld,
	>&-264  belong          x               pending inodes to free %ld,
	>&-664  bequad          x               system-wide uuid %0llx,
	>&-1316 belong          x               minimum percentage of free blocks %d,
	>&-1248 belong          0               TIME optimization
	>&-1248 belong          1               SPACE optimization
	
	
	66908   belong          0x19540119      Unix Fast File system [v2] (big-endian)
	>&-1164 string          x               last mounted on %s,
	>&-696  string          >\0             volume name %s,
	>&-304  beqldate        x               last written at %s,
	>&-1167 byte            x               clean flag %d,
	>&-1168 byte            x               readonly flag %d,
	>&-296  bequad          x               number of blocks %lld,
	>&-288  bequad          x               number of data blocks %lld,
	>&-1332 belong          x               number of cylinder groups %d,
	>&-1328 belong          x               block size %d,
	>&-1324 belong          x               fragment size %d,
	>&-180  belong          x               average file size %d,
	>&-176  belong          x               average number of files in dir %d,
	>&-272  bequad          x               pending blocks to free %lld,
	>&-264  belong          x               pending inodes to free %ld,
	>&-664  bequad          x               system-wide uuid %0llx,
	>&-1316 belong          x               minimum percentage of free blocks %d,
	>&-1248 belong          0               TIME optimization
	>&-1248 belong          1               SPACE optimization


