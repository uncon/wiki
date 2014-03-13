# NetScaler Layout File

## Force Single NSPPE Core
1. Create layout file

		# printf 'num_nsppes = 1\nnsppe_location = /netscaler/nsppe\nprocess:\n[\n {\n  name = nsppe\n  peid = 0\n  cpuid = 1\n  weight = 128\n }\n]\n' > /var/layout

2. Reboot

		# reboot

## Revert Changes

1. Remove layout file

		# rm /var/layout

2. Reboot

		# reboot
