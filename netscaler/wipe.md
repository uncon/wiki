# Wipe a NetScaler

There should be a set of scripts present in the /flash/.recovery directory that can facilitate wiping a NetScaler appliance.

	> shell
	# cd /flash/.recovery
	# sh rc.system_wipe_and_reset [num_flash_loops [num_disk_loops]]

* The first parameter (num_flash_loops) is the number of times to loop through the flash, zeroing all sectors. The default is 0. Acceptable values are from 0 to 16, inclusive.

* The second parameter (num_disk_loops) is the number of times to loop through the hard drive, zeroing all sectors. The default is 0. Acceptable values are from 0 to 16, inclusive.

* Note that the second parameter cannot be specified without also giving the first. If it is desired to zero the disk without zeroing the flash, the flash parameter must be 0.
