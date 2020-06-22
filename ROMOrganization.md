# ROM / ROM Cart Organization

## Format SD
Format SD card as FAT32

	sudo mkfs.vfat -F32 -n "ROMS" /dev/mmcblk0p1

## Mount SD
Mount SD card

	sudo mount -o uid=$(id -u),gid=$(id -g) /dev/mmcblk0p1 /mnt

## Mass Extract
Extact and delete all 7z files into their own directories

	find . -maxdepth 1 -type f -name "*.7z" | while read -r FILE; do mkdir "${FILE%.7z}"; 7z e -o"${FILE%.7z}" "${FILE}" && rm "${FILE}"; done

or

	find . -maxdepth 1 -type f -name "*.zip" | while read -r FILE; do mkdir "${FILE%.zip}"; 7z e -o"${FILE%.zip}" "${FILE}" && rm "${FILE}"; done

## Do The Magic

	for FILE in *.zip; do 7z x "${FILE}" && rm -f "${FILE}"; done && for TYPE in Beta Proto Promo Demo Sample Unl; do mkdir "_${TYPE}"; find . -maxdepth 1 -type f -name "*(*${TYPE}*)*" -exec mv "{}" "./_${TYPE}/" \; ; done && for CHAR in A B C D E F G H I J K L M N O P Q R S T U V W X Y Z; do mkdir ${CHAR}; mv ${CHAR}* ${CHAR}/ 2> /dev/null; mv $(echo ${CHAR} | tr '[A-Z]' '[a-z]')* ${CHAR}/; done; mkdir 0-9; mv [0-9]* 0-9/; mkdir \[BIOS\]; mv \[BIOS\]\ * \[BIOS\]/; find . -type d -empty -delete

## Unmount SD
Unmount, sort, and verify SD card

	sudo umount /dev/mmcblk0p1
	sudo fsck.fat -v -V -w /dev/mmcblk0p1

# ROM / ROM Cart Organization (Step by Step)

## Extract
Extact and delete all zip files

	for FILE in *.zip; do 7z x "${FILE}" && rm -f "${FILE}"; done

## Organize
Move files into category and letter directories

	for TYPE in Beta Proto Promo Demo Sample Unl; do mkdir "_${TYPE}"; find . -maxdepth 1 -type f -name "*(*${TYPE}*)*" -exec mv "{}" "./_${TYPE}/" \; ; done
	for CHAR in A B C D E F G H I J K L M N O P Q R S T U V W X Y Z; do mkdir ${CHAR}; mv ${CHAR}* ${CHAR}/ 2> /dev/null; mv $(echo ${CHAR} | tr '[A-Z]' '[a-z]')* ${CHAR}/ 2> /dev/null; done; mkdir 0-9; mv [0-9]* 0-9/ 2> /dev/null
	mkdir \[BIOS\]; mv \[BIOS\]\ * \[BIOS\]/

## Clean Up
Remove empty directories

	find . -type d -empty -delete
