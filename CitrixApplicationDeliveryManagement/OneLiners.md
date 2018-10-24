# Citrix ADM One-Liners

## Support Bundle
If there is an issue with ADM that is causing authentication to fail, you can collect a support bundle using the following command after logging in via SSH using the "nsrecover" user.

    perl /mps/scripts/techsupport.pl

## Device Inventory
To dump a device inventory in CSV format, run the following command from the shell.

    /mps/db_pgsql/bin/psql -U mpsroot -p 5454 mpsdb -c "SET ROLE 'Owner'; SET SCHEMA 'Owner'; COPY (SELECT hostname,ip_address,type,model_id,sysid,serialnumber,version FROM managed_device) TO stdout WITH CSV DELIMITER ',';"

## Database

### Connect to Database
To connect to the MAS database, run the following command from the shell.

    /mps/db_pgsql/bin/psql -U mpsroot -p 5454 mpsdb

Alternatively, you can run an SQL command directly from the shell using the following command.

    /mps/db_pgsql/bin/psql -U mpsroot -p 5454 mpsdb -c "<COMMAND>"

You will also generally need to run the following command before anything else.

    SET ROLE 'Owner'; SET SCHEMA 'Owner';

### Determine Database Size
To find the size (in bytes) of the MAS database, run the following SQL command.

    SELECT pg_database_size('mpsdb');

### List NetScaler Virtual Servers
To list details about NetSCaler Virtual Servers, run one of the following SQL commands.

    SELECT * FROM ns_authenticationvserver;
    SELECT * FROM ns_crvserver;
    SELECT * FROM ns_csvserver;
    SELECT * FROM ns_gslbvserver;
    SELECT * FROM ns_lbvserver;
    SELECT * FROM ns_sslvserver;
    SELECT * FROM ns_vpnvserver;
