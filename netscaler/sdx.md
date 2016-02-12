# NetScaler SDX

## Factory Reset
To reset the entire appliance to factory defaults, run the following commands from the XenServer shell and reboot.

    sfdisk --change-id /dev/sda 1 c
    sfdisk /dev/sda -A 1

After rebooting, you will want to configure the SVM using [CTX130496](http://support.citrix.com/article/CTX130496).
