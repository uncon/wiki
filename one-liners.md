# One-Liners
## Convert flac to mp3
	find . -type f -iname "*.flac" | while read -r file; do flac -cd "${file}" | lame -V 0 - "${file%.flac}.mp3"; done

## Re-encode flac
	find . -type f -iname "*.flac" | while read -r file; do mv "${file}" "${file%.flac}-OLD.flac"; flac --delete-input-file -8 -V -o "${file}" "${file%.flac}-OLD.flac"; done

## Remove Litter from Windows and OS X
	find "${@:-$PWD}" \( \
	-type f -name '._*' -o \
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
	\) -print0 | xargs -0 rm -fr

## Remove all events from Windows logs
	for /f %x in ('wevtutil el') do wevtutil cl "%x"

## Remove old kernels in Ubuntu/Debian
	dpkg -l 'linux-*' | sed '/^ii/!d;/'"$(uname -r | sed "s/\(.*\)-\([^0-9]\+\)/\1/")"'/d;s/^[^ ]* [^ ]* \([^ ]*\).*/\1/;/[0-9]/!d' | xargs sudo apt-get -y purge

## Install identity.pub to a remote machine
1. Generate key

		ssh-keygen

2. Upload key

		ssh-copy-id  -i ~/.ssh/id_rsa.pub user@host

	or

		cat ~/.ssh/id_rsa.pub | ssh user@host "mkdir ~/.ssh; cat >> ~/.ssh/authorized_keys"

## Convert all filenames to lowercase
	for file in *; do newFile="$(expr "xxx$file" : 'xxx\(.*\)' | tr '[A-Z]' '[a-z]')"; mv "$file" "$newFile"; done

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

## Copy USA ROMs From No-Intro Sets
	mkdir ../USA; ls | grep -v "\[BIOS\]\|(Asia)\|(Beta.*)\|(Proto.*)\|(Japan)\|(Japan, Europe)\|(Japan, Korea)\|(France)\|(Europe)\|(Germany)\|(Australia)\|(Spain)\|(Korea)\|(Brazil)\|(Italy)\|(Canada)\|(Netherlands)\|(Hong Kong)\|(Sweden)\|(China)\|(Taiwan)" | while read FILE; do mv "${FILE}" ../USA; done

## Organize Files (WIP)
	for CHAR in A B C D E F G H I J K L M N O P Q R S T U V W X Y Z; do mkdir ${CHAR}; mv ${CHAR}* ${CHAR}/ 2> /dev/null; mv $(echo ${CHAR} | tr '[A-Z]' '[a-z]')* ${CHAR}/ 2> /dev/null; done; mkdir 0-9; mv [0-9]* 0-9/ 2> /dev/null

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
