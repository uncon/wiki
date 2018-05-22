# ROM / ROM Cart Organization

## Format SD
Format SD card as FAT32

    sudo mkfs.vfat -F32 -n "ROMS" /dev/sdb1

## Mount SD
Mount SD card

    sudo mount /dev/sdb1 /mnt

## Nintendo DS
Remove numbering

    find . -name "[xz0-9][0-9][0-9][0-9] - *" -type f | while read -r FILE; do mv "${FILE}" "$(echo "${FILE}" | sed -e 's/^\.\/[xz0-9][0-9][0-9][0-9] - /\.\//')"; done

## Mass Extract
Extact and delete all 7z files into their own directories

    find . -maxdepth 1 -type f -name "*.7z" | while read -r FILE; do mkdir "${FILE%.7z}"; 7z x -o"${FILE%.7z}" "${FILE}" && rm "${FILE}"; done

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

## Unmount SD
Unmount, verify, and sort SD card

    sudo umount /mnt
    sudo fsck.msdos -f -v /dev/sdb1 && sudo fatsort -n -c /dev/sdb1 -X EDFC -X TBED
