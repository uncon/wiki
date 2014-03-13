# How To Force a Core Dump

## Unresponsive / Hang

### Appliance
1. Use the NMI button

### Appliance or VPX

1. Connect the console

2. Type in sequence CR ~ Control+b (alias CR~^b) to enter into kernel debugger prompt
    - Press **Enter** 
    - Press **~** (**Shift** and **`**)
    - Press **Ctrl** and **b**

3. You should see a `db>` prompt

4. Enter `trace`
	- You should see some data on the screen. Copy this output and send it over for analysis.

5. Enter `call nspanic`

6. Press a key to reboot the system
	- This will force a core dump in the (/var/crash) directory and reboot.  

## NSPPE Core Dump

**Note**: It may not be necessary to change the PitBoss policy settings in these commands since we disable PitBoss.

1. Find NSPPE PID's.

		# nsp query

2. Set PitBoss policy to defaults.

		# pb_policy -d

3. Set PitBoss policy to abort other processes when something cannot be restarted.

		# pb_policy -o abort

4. Disable PitBoss.

		# sysctl netscaler_pitbosspolicy=0

5. Kill the NSPPE process.

		# kill SIGSTOP <NSPPE_PID>

6. After all the cores have been dumped, reboot the system.

		# reboot

7. Set PitBoss policy to defaults.

		# pb_policy -d

## Reboot

In order to stop the NetScaler when it panics execute the following commands from the NetScaler shell.

1. Configure the system to drop to the debugger instead of dump a core

		sysctl debug.debugger_on_panic=1

2. Wait for the crash

3. Print backtrace (at the 'db>' prompt)

		t

4. Continue dump (at the 'db>' prompt)

		call nspanic

5. Press a key to reboot the system

## Live System

In order to force a core dump on a running system, execute the following commands from the NetScaler shell. 

1. Synchronize the filesystems

		sync

2. Configure the system to dump a core instead of drop to the debugger

		nsapimgr -B "w debugger_on_panic 0"

3. Force panic

		nsapimgr -B "call nspanic"
