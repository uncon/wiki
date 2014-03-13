# Access VM Console from XenServer

## Serial Console
1. Find the ID of the desired VM

		xl list

2. Connect to the console

		xl console <ID>

3. Exit the console by pressing CTRL-]

## Video Console
1. Find the ID of the desired VM

		xl list

2. Find the VNC port of the VM

		xenstore-ls /local/domain/<ID>/console

3. Use ssh to forward VNC traffic to XenServer host

		ssh <XENSERVER> -l root -L 5901:127.0.0.1:<VNC-PORT>

4. Connect your VNC client to `localhost:5901`