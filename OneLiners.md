# One-Liners
## Convert flac to mp3
	find . -type f -iname "*.flac" | while read -r FILE; do flac -cd "${FILE}" | lame -V 0 - "${FILE%.flac}.mp3"; done

## Re-encode flac
	find . -type f -iname "*.flac" | while read -r FILE; do mv "${FILE}" "${FILE%.flac}-OLD.flac"; flac --delete-input-file -8 -V -o "${FILE}" "${FILE%.flac}-OLD.flac"; done

## Bulk extract .zip files into directories
	find . -type f -iname "*.zip" | while read -r FILE; do mkdir "${FILE%.zip}"; 7z x "${FILE}" -o"${FILE%.zip}" && rm "${FILE}"; done

## Create Archive
	tar -c -v -f archive.tar --mode='a+rw' --owner=0 --group=0 . &> archive.log

## Install Certificate
	certutil -d sql:${HOME}/.pki/nssdb -A -t "C,," -n "unconnet CA" -i pfSense-CA.crt

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

## Remove Litter from Citrix Receiver for Linux
	find "${@:-$PWD}" \( \
	-type f -name '.access^' -o \
	-type f -name '.attribute^' \
	\) -print0 | xargs -0 rm

## Remove all events from Windows logs
	for /f %x in ('wevtutil el') do wevtutil cl "%x"

## Install identity.pub to a remote machine
1. Generate key

		ssh-keygen

2. Upload key

		ssh-copy-id -i ~/.ssh/id_rsa.pub user@host

	or

		cat ~/.ssh/id_rsa.pub | ssh user@host "mkdir ~/.ssh; cat >> ~/.ssh/authorized_keys"

## Convert all filenames to uppercase
	find . -type f -mindepth 1 | while read -r FILE; do mv -f "${FILE}" $(echo "${FILE}" | tr 'a-z' 'A-Z'); done

## Convert all filenames to lowercase
	find . -type f -mindepth 1 | while read -r FILE; do mv -f "${FILE}" $(echo "${FILE}" | tr 'A-Z' 'a-z'); done

## Rename files based on EXIF date
	jhead -autorot -nf../Pictures/%Y/%Y-%m/%Y-%m-%d_%H-%M-%S *.[j\|J][p\|P][g\|G]

## List directories by size
	du -xk -d 1 . | sort -n | awk ' BEGIN { split("K,M,G,T", Units, ","); } { u = 1; while ($1 >= 1024) { $1 = $1 / 1024; u += 1; } $1 = sprintf("%.1f%s\t", $1, Units[u]); print $0; } '

## Remove all but latest 3 files
	(ls -t | head -n 3; ls) | sort | uniq -u | tr '\n' '\0' | xargs -0 rm

## List Hierarchical Permissions
	FILE="/path/to/file"; while [ ${FILE} ]( ${FILE} ); do echo -ne "${FILE}\0"; FILE=${FILE%/*}; done | xargs -0 ls -ld

## Network Debug
	tcpdump -nnvvS host 192.168.1.22 and port 80

## Stupid Web Server
	while [ true ]; do nc -e 'cat << EOF
	HTTP/1.1 200 OK
	Date: Mon, 23 May 2005 22:38:34 GMT
	Server: Apache/1.3.3.7 (Unix) (Red-Hat/Linux)
	Content-Type: text/html; charset=UTF-8
	Content-Length: 131
	Connection: close
	
	<html>
	<head>
	<title>`An Example Page`</title>
	</head>
	<body>
	Hello World, this is a very simple HTML document.
	</body>
	</html>
	
	EOF' -l -p 80; echo Hit; sleep 2; done
