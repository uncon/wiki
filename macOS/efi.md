# Mac OS X UEFI Tips

## Non-OS X Default Boot
1. Mount ESP

		mkdir /Volumes/ESP
		sudo mount -t msdos /dev/disk0s1 /Volumes/ESP

2. Bless

		sudo bless --mount /Volumes/ESP --setBoot --file /Volumes/ESP/EFI/gummiboot/gummibootx64.efi/

3. Clean up

		sudo umount /Volumes/ESP
		rmdir /Volumes/ESP
