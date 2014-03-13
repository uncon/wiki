# MacPorts

## Installation

*  [Installing MacPorts](http://www.macports.org/install.php)

*  Useful ports:

		sudo port install coreutils xorg vim tmux p7zip htop ipcalc wireshark

*  X11 Setup (must logout and log back in):

		launchctl load -w /Library/LaunchAgents/org.macports.startx.plist

## Update
	
	sudo port selfupdate
	sudo port upgrade outdated
	sudo port uninstall inactive

## Uninstall Unused Ports

*  Read [Keep your MacPorts installation lean by defining leaves as requested ports](http://guide.macports.org/#using.common-tasks.keeplean)

*  List

		port echo leaves

*  Clean

		sudo port uninstall leaves

## File Association

1. Create a new Application using Automator.app

2. Add a new 'Run Shell Script' Action
    - Shell: `/bin/bash`
    - Pass input: as arguments
    - Script: `/opt/local/bin/[BINARY_NAME] "$@" &`

3. Save into `~/Applications`

4. Associate files with the newly created .app

## GTK2 Theme

1. Install GTK Theme Switch and (optionally) the Murrine GTK2 engine

		sudo port install gtk-theme-switch gtk2-murrine
		
2. Run it

		switch2

3. Install a theme (e.g., [Zukitwo](http://lassekongo83.deviantart.com/art/Zukitwo-203936861))
