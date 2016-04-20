# Tips for Wireshark

## Preferences File
`~/.config/wireshark/preferences`

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
	gui.filter_expressions.expr: (tcp.analysis.flags && !tcp.analysis.window_update) || tcp.flags.reset eq 1
	gui.filter_expressions.label: Conn Est
	gui.filter_expressions.enabled: TRUE
	gui.filter_expressions.expr: tcp.connection.syn
	gui.filter_expressions.label: NS Mon
	gui.filter_expressions.enabled: TRUE
	gui.filter_expressions.expr: tcp.connection.syn && tcp.window_size == 8188
	gui.filter_expressions.label: Slow
	gui.filter_expressions.enabled: TRUE
	gui.filter_expressions.expr: tcp.time_delta > .3 && (tcp.flags.fin == 0 && tcp.flags.reset == 0)
	gui.filter_expressions.label: Zero/Full
	gui.filter_expressions.enabled: TRUE
	gui.filter_expressions.expr: tcp.analysis.zero_window or tcp.analysis.window_full
	gui.filter_expressions.label: Jumbo
	gui.filter_expressions.enabled: TRUE
	gui.filter_expressions.expr: tcp.options.mss_val > 1500

	# Time format
	time.display_time_type: UTC

	# Name Resolution
	nameres.transport_name: FALSE

	# TCP Options
	tcp.relative_sequence_numbers: FALSE
	tcp.calculate_timestamps: TRUE

## TShark
*  Filter a trace into a new file

		tshark -r input.pcapng -n -2 -R "ip.addr == 192.168.0.1" -w output.pcapng

*  Filter a NetScaler trace into a new file

		tshark -r nstrace1.cap -n -2 -R "ip.addr == 192.168.0.1" -F nstrace30 -w nstrace1-filtered.cap

* Display latency statistics from a specific IP

		tshark -r nstrace1.cap -o tcp.calculate_timestamps:true -T fields -e tcp.time_delta -Y "ip.src == 192.168.0.1" | sort -n | awk '
		  BEGIN {
		    c = 0;
		    sum = 0;
		  }
		  $1 ~ /^[0-9]*(\.[0-9]*)?$/ {
		    a[c++] = $1;
		    sum += $1;
		  }
		  END {
		    ave = sum / c;
		    if( (c % 2) == 1 ) {
		      median = a[ int(c/2) ];
		    } else {
		      median = ( a[c/2] + a[c/2-1] ) / 2;
		    }
		    OFS="\n";
		    print "Sum: " sum, "Count: " c, "Average: " ave, "Median: " median, "Min: " a[0], "Max: " a[c-1];
		  }
		'

## Mac OS X
*  Fix the fonts in Wireshark's (horrible) GTK theme.

		sed -i.orig -e 's/gtk-font-name="Lucida Grande 12"/gtk-font-name="Lucida Grande 9"/g' -e 's/gtk-icon-sizes = "gtk-menu=16,16:gtk-dialog=48,48:gtk-dnd=32,32:gtk-button=20,20:gtk-large-toolbar=24,24:gtk-small-toolbar=16,16:inkscape-decoration=12,12"/gtk-icon-sizes = "gtk-menu=16,16:gtk-dialog=24,24:gtk-dnd=32,32:gtk-button=20,20:gtk-large-toolbar=16,16:gtk-small-toolbar=10,10:inkscape-decoration=6,6"/g' /Applications/Wireshark.app/Contents/Resources/themes/Clearlooks-Quicksilver-OSX/gtk-2.0/pre_gtkrc
