# LDAP on NetScaler

## Debugging

### aaa.debug
	> shell
	# cat /tmp/aaad.debug

### Test Queries
You can use ldapsearch on the NetSCaler to test LDAP queries.

	> shell
	# ldapsearch -H ldap://10.54.160.130/ -D "silverbeta\CitrixAdmin" -w "Password1" -b "OU=Domain Users,DC=silverbeta,DC=local" "(& (userPrincipalName=scone@silverbeta.local) (objectClass=*))" userPrincipalName