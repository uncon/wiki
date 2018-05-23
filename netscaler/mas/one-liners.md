# NetScaler MAS One-Liners

## Support Bundle
If there is an issue with MAS that is causing authentication to fail, you can collect a support bundle using the following command after loging in via SSH using the "nsrecover" user.

    perl /mps/scripts/techsupport.pl

## Database

### Connect to Database
To connected to the MAS database, run the following command from shell.

    /mps/db_pgsql/bin/psql -U mpsroot -p 5454 mpsdb

### Determine Database Size
To find the disk space used by the MAS database, run the following command from postgres.

    select pg_database_size('mpsdb');
