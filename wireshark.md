# Tips for Wireshark

## Preferences File
`~/.wireshark/preferences`

	# Columns
	gui.column.format:
        	"No.", "%m",
        	"Time", "%Aut",
        	"Latency", "%Cus:tcp.time_delta:0:U",
        	"Source", "%s",
        	"Destination", "%d",
        	"Protocol", "%p",
        	"NS PcbDevNo", "%Cus:nstrace.pdevno:0:U",
        	"NS NIC", "%Cus:nstrace.nicno:0:R",
        	"TCP Stream", "%Cus:tcp.stream:0:U",
        	"ACK To", "%Cus:tcp.analysis.acks_frame:0:R",
        	"Length", "%L",
        	"Info", "%i"
	
	# Filter Bookmarks
	gui.filter_expressions.label: Bad TCP
	gui.filter_expressions.enabled: TRUE
	gui.filter_expressions.expr: tcp.analysis.flags && !tcp.analysis.window_update
	gui.filter_expressions.label: Conn Est
	gui.filter_expressions.enabled: TRUE
	gui.filter_expressions.expr: tcp.connection.syn

	# Time format
	time.display_time_type: UTC

	# Name Resolution
	nameres.transport_name: FALSE

	# TCP Options
	tcp.relative_sequence_numbers: FALSE
	tcp.calculate_timestamps: TRUE

## Mac OS X
*  Fix the fonts in Wireshark's (horrible) GTK theme.

		sed -i.orig -e 's/gtk-font-name="Lucida Grande 12"/gtk-font-name="Lucida Grande 9"/g' -e 's/gtk-icon-sizes = "gtk-menu=16,16:gtk-dialog=48,48:gtk-dnd=32,32:gtk-button=20,20:gtk-large-toolbar=24,24:gtk-small-toolbar=16,16:inkscape-decoration=12,12"/gtk-icon-sizes = "gtk-menu=16,16:gtk-dialog=24,24:gtk-dnd=32,32:gtk-button=20,20:gtk-large-toolbar=16,16:gtk-small-toolbar=10,10:inkscape-decoration=6,6"/g' /Applications/Wireshark.app/Contents/Resources/themes/Clearlooks-Quicksilver-OSX/gtk-2.0/pre_gtkrc
