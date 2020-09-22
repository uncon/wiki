# CalDigit TS3 Plus 

## Firmware Update
Until CalDigit provides a better method to update the firmware, we can use the firmware blob from the macOS Firmware Updater.

1. [Download](https://downloads.caldigit.com/) the macOS Firmware Updater

1. Extract the firmware blob (e.g., `CalDigit_TS3_Plus_44.01.bin`) from the official macOS updater using 7-Zip.

1. Find the device ID

		sudo fwupdtool get-devices

1. Update the firmware using the extracted blob and device ID

		sudo fwupdtool install-blob <Firmware Blob> <TS3 Plus Device ID>
