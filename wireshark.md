# Tips for Wireshark

## Latency Column

1. Edit -> Preferences -> Protocols -> TCP
	- Enable "Calculate conversation timestamps"
2. User Interface -> Columns -> Add
	- **Title**: Latency
	- **Type**: Custom
	- **Field name**: tcp.time_delta

## Mac OS X
*  Fix the fonts in Wireshark's (horrible) GTK theme

		sed -i.orig -e 's/gtk-font-name="Lucida Grande 12"/gtk-font-name="Lucida Grande 9"/g' -e 's/gtk-icon-sizes = "gtk-menu=16,16:gtk-dialog=48,48:gtk-dnd=32,32:gtk-button=20,20:gtk-large-toolbar=24,24:gtk-small-toolbar=16,16:inkscape-decoration=12,12"/gtk-icon-sizes = "gtk-menu=16,16:gtk-dialog=24,24:gtk-dnd=32,32:gtk-button=20,20:gtk-large-toolbar=16,16:gtk-small-toolbar=10,10:inkscape-decoration=6,6"/g' /Applications/Wireshark.app/Contents/Resources/themes/Clearlooks-Quicksilver-OSX/gtk-2.0/pre_gtkrc
