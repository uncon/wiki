# Tips for Wireshark

## Preferences File
`~/.config/wireshark/preferences` or `%APPDATA%\Wireshark\preferences`

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

	# SSL Options
	tls.keylog_file: nstrace.sslkeys

## TShark
*  Filter a trace into a new file

		tshark -r input.pcapng -n -2 -R "ip.addr == 192.168.0.1" -w output.pcapng

*  Filter a NetScaler trace into a new file

		tshark -r nstrace1.cap -n -2 -R "ip.addr == 192.168.0.1" -F nstrace30 -w nstrace1-filtered.cap

*  Display trace statistics

		capinfos nstrace1.cap

*  Display trace conversations
	* Ethernet

			tshark -r nstrace1.cap -q -z conv,eth

	* IP

			tshark -r nstrace1.cap -q -z conv,ip

	* TCP

			tshark -r nstrace1.cap -q -z conv,tcp

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
