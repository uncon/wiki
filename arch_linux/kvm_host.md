# KVM Host on Arch Linux

The following process can be used to setup a [KVM](https///wiki.archlinux.org/index.php/Kvm) host using [libvirt](https///wiki.archlinux.org/index.php/Libvirt).

## libvirt

1. Install KVM

		pacman -S libvirt urlgrabber qemu dnsmasq ebtables bridge-utils

2. Enable and start services

		systemctl enable libvirt-guests
		systemctl start libvirt-guests
		systemctl enable libvirtd
		systemctl start libvirtd


### NFS Support

1. Install NFS support

		pacman -S nfs-utils

2. Enable and start services

		systemctl enable nfs-client.target
		systemctl start nfs-client.target


### Bridge Network Connection

1. Create bridge connection

		INT=eno1; printf "Description=\"Bridge connection for KVM\"\nInterface=virbr1\nConnection=bridge\nBindsToInterfaces=(${INT})\nIP=dhcp\n" > /etc/netctl/virbr1

2. Disable IP on physical connection

		sed -i.orig -e 's/^[#\s]*IP=.*/IP=no/g' /etc/netctl/eno1

3. Enable connection

		netctl enable virbr1

4. Reboot

		systemctl reboot

### GUI Tools

1. Enable X forwarding

		sed -i.orig -e 's/^[#\s]*X11Forwarding .*$/X11Forwarding yes/g' /etc/ssh/sshd_config

2. Restart SSH

		systemctl restart sshd

3. Install tools

		pacman -S virtviewer virt-manager xorg-xauth gnome-themes-standard


### Enable User Access

1. Add group

		groupadd libvirt

2. Add user to group

		gpasswd -a [user] libvirt
		gpasswd -a [user] kvm

1. Setup PolicyKit

		printf 'polkit.addRule(function(action, subject) {\n\tif (action.id == "org.libvirt.unix.manage" &&\n\t\tsubject.isInGroup("libvirt")) {\n\t\t\treturn polkit.Result.YES;\n\t\t}\n});\n' > /etc/polkit-1/rules.d/50-org.libvirt.unix.manage.rules

