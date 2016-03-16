# NetScaler SDX

## Serial Console to VPX Instance
To connect to the serial console of a VPX instance, follow the process below.

1. Find the ID of the desired VM

		xl list

2. Connect to the console

		xl console <ID>

3. Exit the console by pressing CTRL-]

## Appliance Factory Reset
To reset the entire appliance to factory defaults, run the following commands from the XenServer shell and reboot.

    sfdisk --change-id /dev/sda 1 c
    sfdisk /dev/sda -A 1

After rebooting, you will want to configure the SVM using [CTX130496](http://support.citrix.com/article/CTX130496).
