# XenServer Build

These steps are specify for my lab XenServer.

## Installation

1. Be sure to check "Enable thin provisioning" during installation.

  * This is required for configuring a local ISO repository.
  * This will cause XenServer to use ext3 instead of LVM for its Local Storage 

## Post Installation

1. Login via SSH.

1. Install [citrix_xenserver_patcher](https://github.com/dalgibbard/citrix_xenserver_patcher).

		wget --no-check-certificate -O patcher.py https://raw.github.com/dalgibbard/citrix_xenserver_patcher/master/patcher.py
		chmod a+x patcher.py

1. Install updates

  * Standalone node

          ./patcher.py

  * Pool Master node

          ./patcher.py -p

1. Setup local ISO repository.

		mkdir /var/run/sr-mount/<SR-UUID>/ISO_Storage
		xe sr-create name-label="Local ISO storage" type=iso device-config:location=/var/run/sr-mount/<SR-UUID>/ISO_Storage device-config:legacy_mode=true content-type=iso

1. Upload ISO's.

  Upload ISO files to `/var/run/sr-mount/<SR-UUID>/ISO_Storage`.
