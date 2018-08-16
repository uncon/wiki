# NetScaler MAS One-Liners

## Support Bundle
If there is an issue with MAS that is causing authentication to fail, you can collect a support bundle using the following command after loging in via SSH using the "nsrecover" user.

    perl /mps/scripts/techsupport.pl

## Database

### Connect to Database
To connected to the MAS database, run the following command FROM the shell.

    /mps/db_pgsql/bin/psql -U mpsroot -p 5454 mpsdb

Alternatively, you can run an SQL command directly form the shell using the following command.

    /mps/db_pgsql/bin/psql -U mpsroot -p 5454 mpsdb -c "<COMMAND>"

### Determine Database Size
To find the size (in bytes) of the MAS database, run the following command FROM the shell.

    SELECT pg_database_size('mpsdb');

### List NetScaler Virtual Servers
To list details about NetSCaler Virtual Servers, run one of the following commands FROM the shell.

    SET ROLE 'Owner'; SET SCHEMA 'Owner'; SELECT * FROM ns_authenticationvserver;
    SET ROLE 'Owner'; SET SCHEMA 'Owner'; SELECT * FROM ns_crvserver;
    SET ROLE 'Owner'; SET SCHEMA 'Owner'; SELECT * FROM ns_csvserver;
    SET ROLE 'Owner'; SET SCHEMA 'Owner'; SELECT * FROM ns_gslbvserver;
    SET ROLE 'Owner'; SET SCHEMA 'Owner'; SELECT * FROM ns_lbvserver;
    SET ROLE 'Owner'; SET SCHEMA 'Owner'; SELECT * FROM ns_sslvserver;
    SET ROLE 'Owner'; SET SCHEMA 'Owner'; SELECT * FROM ns_vpnvserver;
