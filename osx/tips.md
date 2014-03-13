# OS X Tips

## Temporarily Prevent Sleep

The following command creates a PM assertion to prevent idle sleep (while running).  Hit ctrl-c to cancel.

	pmset noidle

## Set Hostname

	sudo scutil --set HostName <hostname>

## Reset Backgrounds

	rm "~/Library/Application Support/Dock/desktoppicture.db" && killall Dock

## Search With Google

1. Create a new Service using Automator.app

2. Add a new 'Run Shell Script' Action
    - Shell: `/usr/bin/ruby`
    - Pass input: to stdin
    - Script:

    		require 'cgi'
    		open 'http://www.google.com/search?q=#{CGI.escape(STDIN.read.chomp)}

3. Save as `Search With Google`.

## Mac OS X Installer Package (.pkg) Removal

*  List Packages

		pkgutil --pkgs

*  Run script

		PKG="[PACKAGE-ID]"
		PKG_VOLUME="$(pkgutil --pkg-info "${PKG}" | grep "^volume:" | sed 's/^volume: //')"
		PKG_LOCATION="$(pkgutil --pkg-info "${PKG}" | grep "^location:" | sed 's/^location: //')"
		PKG_PATH="${PKG_VOLUME}${PKG_LOCATION}/"
		pkgutil --only-files --files "${PKG}" | sed "s#^#${PKG_PATH}#" | tr '\n' '\0' | xargs -n 1 -0 sudo rm
		pkgutil --only-dirs --files "${PKG}" | sed "s#^#${PKG_PATH}#" | tr '\n' '\0' | xargs -n 1 -0 sudo rmdir
		sudo pkgutil --forget "${PKG}"

## Disable .DS_Store On Network Shares

	defaults write com.apple.desktopservices DSDontWriteNetworkStores true

## Clear All Extended Attributes On A File

	xattr -c file

## Reset 'Open With' Menu

Rebuild the Launch Services database

		/System/Library/Frameworks/CoreServices.framework/Versions/A/Frameworks/LaunchServices.framework/Versions/A/Support/lsregister -kill -r -domain local -domain system -domain user`

## Terminal.app

Solarized OS X Terminal.App Color Theme

		curl "https://raw.github.com/altercation/solarized/master/osx-terminal.app-colors-solarized/xterm-256color/Solarized%20Dark%20xterm-256color.terminal" -o "Solarized Dark xterm-256color.terminal"
		curl "https://raw.github.com/altercation/solarized/master/osx-terminal.app-colors-solarized/xterm-256color/Solarized%20Light%20xterm-256color.terminal" -o "Solarized Light xterm-256color.terminal"

## iTerm

Solarized [iTerm 2](http://iterm2.com/) Color Theme

		curl "https://raw.github.com/altercation/solarized/master/iterm2-colors-solarized/Solarized%20Dark.itermcolors" -o "Solarized Dark.itermcolors"
		curl "https://raw.github.com/altercation/solarized/master/iterm2-colors-solarized/Solarized%20Light.itermcolors" -o "Solarized Light.itermcolors"
