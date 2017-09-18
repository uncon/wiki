# nsconmsg

## Useful Commands

### Display start and end time information
time zone is the same as the system running nsconmsg

	# nsconmsg -K newnslog -d setime

### Display event information
	# nsconmsg -K newnslog -d event

### Display console message
	# nsconmsg -K newnslog -d consmsg

### Display load balancing performance information
	# nsconmsg -K newnslog -s ConLb=3 -d oldconmsg

### Display load balancing performance information for a specific vserver
	# nsconmsg -K newnslog -s ConLb=3 -i vserver -d oldconmsg

### Display past counter value information
	# nsconmsg -K newnslog -d past

### Display current counter value information
	# nsconmsg -K newnslog -d current

### Display current counter value information excluding counters with 0 value
	# nsconmsg -K newnslog -d statswt0

### Display debug performance information (level 2)
	# nsconmsg -K newnslog -s ConDebug=2 -d oldconmsg

### Display CPU usage, memory usage, uptime and boot time
	# nsconmsg -K newnslog -d oldconmsg

### Display memory details
	# nsconmsg -K newnslog -s ConMEM=2 -d oldconmsg

### Display performance information where NIC bandwidth exceeds 100 Mb/s
	# nsconmsg -K newnslog -s ratecount=100 -g mbits -d past

### Display performance information where CPU usage exceeds 50%
	# nsconmsg -K newnslog -s totalcount=500 -g cpu_use -d past

### Display audited command information
	# nsconmsg -K newnslog -d auditedcmd

### Display current counter values
	# nsconmsg -K newnslog -g [counter-name] -d current

### Dump counter values in CSV format
	# nsconmsg -K newnslog -s csv=1 -g [counter-name] -d current

### Display shared memory errors
	# nsconmsg -K newnslog -f shmem0_err_allocfailed -f shmem_err_allocfailed -f shhmem0_cur_allocsize -f shmem_cur_allocsize -s disptime=1 -d current

## Useful Counters

### System
	cpu_use
	mem_tot_freesize
	cur_syshealth_disk0_avail
	cur_syshealth_disk0_used
	cur_syshealth_disk1_avail
	cur_syshealth_disk1_used

### Hardware
	cur_syshealth_fancpu0
	cur_syshealth_fancpu1
	cur_syshealth_tcpu0
	cur_syshealth_tcpu1
	cur_syshealth_tint
	
### High Availability

	ha_err
	ha_cur_system_state
	ha_cur_master_state
	ha_tot_cfgsyncs
	ha_tot_pkt_tx
	ha_tot_pkt_rx
	
#### ha_cur_system_state

* 0 - UNKNOWN
* 1 - INIT
* 2 - DOWN
* 3 - UP
* 4 - PARTIAL_FAIL
* 5 - COMPLETE_FAIL
* 6 - DUMB
* 7 - DISABLED
* 8 - PARTIAL_FAIL_SSL
* 9 - ROUTEMONITOR_FAIL

#### ha_cur_master_state

* 0 - INACTIVE
* 1 - CLAIMING
* 2 - ACTIVE
* 3 - ALWAYS_SECONDARY
* 4 - FORCE_CHANGE

### GSLB

	gslb_tot_gslb_msgs_rcvd
	gslb_tot_gslb_msg_sent  

### Network

	tcp_tot_rxMbits
	tcp_tot_txMbits
	tcp_err_ip_portalloc
	tcp_err
	nic_err_rl_pkt_drops
	nic_err_rl_rate_pkt_drops
	nic_err_rl_pps_pkt_drops

### Web

	http_tot_Requests
	http_tot_Responses
	ssl_tot_session_inuse
	ssl_tot_sslInfo_SPCBInUseCount

### Servers

	http_err_server_busy
	si_tot_svr_busy_err
	dht_err_update_didnt_find_entry

### AAA

	aaa_tot_maxconn_hit
