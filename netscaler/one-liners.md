# NetScaler One-Liners

## Find NetScaler version and build from a vmcore file

	# strings vmcore.0 | grep "NetScaler" | grep "Build"

## Minicore backtrace

	# nsmct -K /symbol/kernel_sym.[version]_[build] -k nsminicore.0 -d trace

	# nsmct -K kernel.0 -k nsminicore.0 -d [ crashtime | version | panicstr | procname | trapregs | trace | nsfeature ]

## Read binary output from nsprofmon

	# nsprofmon -K /netscaler/nsppe/nsppe-[version]-[build] -k newproflog_cpu_[X].out -d alltexthitsym

##Find policy hits
	# nsconmsg -g pol_hits -d current

## Find executed commands

	# grep CMD_EXECUTED ns.log | sed -e 's/^\(.*\) <.*\( Command \)/\1 -\2/'

## Counter timeline from newnslog

	# cntr="lb_tot_session_timeout_server"; nsconmsg91 -K newnslog -s disptime=1 -g $cntr -d past | grep "$cntr\|^  Index"

## Find time of counter spikes

	# nsconmsg -K /var/nslog/newnslog -s disptime=1 -g err -g fail -d maxrate

## Find errors in newnslog

	# nsconmsg -K /var/nslog/newnslog -g err -g fail -s disptime=1 -s deltacount=1 -s deltacountlow=-1 -d current

## Find failover in newnslog

	# nsconmsg -K /var/nslog/newnslog -g ha_cur_system_state -g ha_cur_nodes_num -g ha_cur_master_state -s disptime=1 -s deltacount=1 -s deltacountlow=-1 -d current
## Find monitor events in newnslog

	# nsconmsg -K /var/nslog/newnslog -d event | grep "DOWN; Last response:\|UP; Last response:" | sed -e 's/^.* PPE-[0-9]*//g' | awk -F " (Sun|Mon|Tue|Wed|Thu|Fri|Sat) " '{ print $2 "\t" $1 }'
## Find HA events in newnslog

	# nsconmsg -K /var/nslog/newnslog -d event | grep " node\|heartbeat\|interface" | awk -F " (Sun|Mon|Tue|Wed|Thu|Fri|Sat) " '{ print $2 "\t" $1 }'

## Find CPU use > 60% in newnslog

	# nsconmsg -K /var/nslog/newnslog -s totalcount=600 -g master_cpu_use -s disptime=1 -d current | grep -v "^Displaying\|^Performance\|^reltime\|Index\|^$" | awk '{ print $8, $9, $11, $10 " - " $3 / 10 "%" }'
	
## Format events in newnslog

	# nsconmsg -K /var/nslog/newnslog -d event | sed -e 's/^.* PPE-[0-9]*//g' | awk -F " (Sun|Mon|Tue|Wed|Thu|Fri|Sat) " '{ print $2 "\t" $1 }'

## Find Authentication Failure Count by Hour

	# cat ns.log.* ns.log | grep "LOGIN_FAILED" | awk -F: '{ print "#" $1 ":00," }' | uniq -c | awk -F# '{ print $2 $1 }' | sort -M
	
## View raw HTTP(S) data

	# wget -O - -S --no-check-certificate http://domain.com

or

	# curl -k -i http://domain.com

## Dump newnslog data to log files

	# gunzip newnslog.*.gz

or

	# for file in *.tar.gz; do tar zxvf "${file}"; done

then

	# VER="101"; \
	for file in $(find . -name "newnslog*" -maxdepth 1 | grep -v "\.tar$\|\.tar\.gz" | sed -e 's#^./##g'); do \
	  nsconmsg$VER -K $file -d setime > nsconmsg-setime-$file; \
	  nsconmsg$VER -K $file -d event > nsconmsg-event-$file; \
	  nsconmsg$VER -K $file -d consmsg > nsconmsg-consmsg-$file; \
	  nsconmsg$VER -K $file -d auditedcmd > nsconmsg-auditedcmd-$file; \
	  nsconmsg$VER -K $file -s disptime=1 -g ssl_err -g cvm -g fips -d past > nsconmsg-ssl-$file; \
	  nsconmsg$VER -K $file -g ha_cur_system_state -g ha_cur_nodes_num -g ha_cur_master_state -s disptime=1 -s deltacount=1 -s deltacountlow=-1 -d current > nsconmsg-ha-$file; \
	  nsconmsg$VER -K $file -s disptime=1 -g err -g fail -d maxrate > nsconmsg-err_maxrate-$file; \
	  nsconmsg$VER -K $file -g err -g fail -d statswt0 \
		| grep -v "^Displaying\|^Performance\|^reltime\|Index\|^NetScaler" \
		| awk '{ print $3 "\t" $4 }' \
		| sort -n > nsconmsg-err_counters-$file; \
	done
