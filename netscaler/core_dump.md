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

1. Set PitBoss policy to defaults.

		# pb_policy -d

1. Set PitBoss policy to abort other processes when something cannot be restarted.

		# pb_policy -o abort

1. Kill the NSPPE process.

		# killall -m -SIGABRT 'NSPPE-[0-9]*'

1. After all the cores have been dumped, the system should reboot.

## Reboot

In order to stop the NetScaler when it panics execute the following commands from the NetScaler shell.

1. Configure the system to drop to the debugger instead of dump a core

		sysctl debug.debugger_on_panic=1

1. Wait for the crash

1. Print backtrace (at the 'db>' prompt)

		t

1. Continue dump (at the 'db>' prompt)

		call nspanic

1. Press a key to reboot the system
