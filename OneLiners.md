# One-Liners

## Copy with rsync
	rsync -avzh /path/directory/ /otherPath/directory

## Write Raw Image to Device
	dd oflag=sync bs=4M status=progress if=in.bin of=/dev/mmcblk0

## Convert FLAC to MP3
	find . -type f -name "*.flac" | while read -r FILE; do flac -cd "${FILE}" | lame -V 0 - "${FILE%.flac}.mp3"; done

## Re-Encode FLAC
	find . -type f -name "*.flac" | while read -r FILE; do metaflac --export-tags-to="${FILE%.flac}.txt" --export-picture-to="${FILE%.flac}.jpg" "${FILE}" && flac -d -o "${FILE%.flac}.wav" "${FILE}" && rm "${FILE}" && flac --delete-input-file --best -V "${FILE%.flac}.wav" && metaflac --import-tags-from="${FILE%.flac}.txt" --dont-use-padding --import-picture-from="3||||${FILE%.flac}.jpg" "${FILE}" && rm "${FILE%.flac}.txt" "${FILE%.flac}.jpg"; done

## Copy an Audio CD (to FLAC)
	cdda2wav -vall cddb=0 speed=4 -paranoia paraopts=proof -B -D /dev/sr0 && find . -type f -iname "*.wav" | while read -r FILE; do flac "${FILE}" && rm "${FILE}"; done

## Copy a DVD (to MKV)
	for FILE in /mnt/VIDEO_TS/*.VOB; do ffmpeg -fflags +genpts -i "${FILE}" -c:v copy -c:a copy "${HOME}/tmp/$(basename ${FILE%.VOB}.mkv)"; done

## Bulk Extract .zip Files into Directories
	find . -type f -iname "*.zip" | while read -r FILE; do mkdir "${FILE%.zip}"; 7z x "${FILE}" -o"${FILE%.zip}" && rm "${FILE}"; done

## Create Archive
	tar --create --one-file-system --verbose --file="backup_$(date +%Y-%m-%d).tar" --mode='a+rw' --owner=0 --group=0 . &> "backup_$(date +%Y-%m-%d).log"

## Install Certificate
	sudo trust anchor unconnet-CA.crt

## Remove Litter from Windows and OS X
	find "${@:-$PWD}" \( \
	-type f -name '.DS_Store' -o \
	-type d -name '__MACOSX' -o \
	-type f -name '.apdisk' -o \
	-type d -name 'Network Trash Folder' -o \
	-type d -name 'Temporary Items' -o \
	-type f -name '.AppleDouble' -o \
	-type d -name '.TemporaryItems' -o \
	-type d -name '.Trashes' -o \
	-type d -name '.Spotlight-V100' -o \
	-type d -name '.DocumentRevisions-V100' -o \
	-type d -name '.fseventsd' -o \
	-type f -name 'Thumbs.db' -o \
	-type f -name '*:Zone.Identifier:$DATA' \
	\) -print0 | xargs -0 rm -fr

## Remove Litter from Citrix Workspace App for Linux
	find "${@:-$PWD}" \( \
	-type f -name '.access^' -o \
	-type f -name '.attribute^' \
	\) -print0 | xargs -0 rm

## Remove All Events from Windows Logs
	for /f %x in ('wevtutil el') do wevtutil cl "%x"

## Install identity.pub to a Remote Machine
1. Generate key

		ssh-keygen

2. Upload key

		ssh-copy-id -i ~/.ssh/id_rsa.pub user@host

	or

		cat ~/.ssh/id_rsa.pub | ssh user@host "mkdir ~/.ssh; cat >> ~/.ssh/authorized_keys"

## Convert All Filenames to Uppercase
	find . -mindepth 1 -type f | while read -r FILE; do mv "${FILE}" "${FILE}_"; mv -f "${FILE}_" "$(echo "${FILE}" | tr 'a-z' 'A-Z')"; done

## Convert All Filenames to Lowercase
	find . -mindepth 1 -type f | while read -r FILE; do mv "${FILE}" "${FILE}_"; mv -f "${FILE}_" "$(echo "${FILE}" | tr 'A-Z' 'a-z')"; done

## Rename Files Based on EXIF Date
	jhead -autorot -nf../Pictures/%Y/%Y-%m/%Y-%m-%d_%H-%M-%S *.[j\|J][p\|P][g\|G]

## List Directories by Size
	du -xk -d 1 . | sort -n | awk ' BEGIN { split("K,M,G,T", Units, ","); } { u = 1; while ($1 >= 1024) { $1 = $1 / 1024; u += 1; } $1 = sprintf("%.1f%s\t", $1, Units[u]); print $0; } '

## Remove All but Latest 3 Files
	(ls -t | head -n 3; ls) | sort | uniq -u | tr '\n' '\0' | xargs -0 rm

## List Hierarchical Permissions
	FILE="/path/to/file"; while [ ${FILE} ]( ${FILE} ); do echo -ne "${FILE}\0"; FILE=${FILE%/*}; done | xargs -0 ls -ld

## Network Debug
	tcpdump -nnvvS host 192.168.1.22 and port 80

## Check Site Response Times
	for IP in $(host www.google.com | grep " has address " | cut -d ' ' -f 4 | tr '\n' ' '); do echo "${IP}"; curl --insecure --silent --output /dev/null --write-out '\tConnection Time: %{time_connect} s\n\tTTFB: %{time_starttransfer} s\n\tTotal Time: %{time_total} s\n' "https://${IP}/"; done
