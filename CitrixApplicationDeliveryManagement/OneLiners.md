# Citrix ADM One-Liners

## Support Bundle
If there is an issue with ADM that is causing authentication to fail, you can collect a support bundle using the following command after logging in via SSH using the "nsrecover" user with the nsroot password. The script will create a support bundle in `/var/mps/tech_support/`.

    /mps/scripts/techsupport.pl

## Device Inventory
To dump a device inventory in CSV format, run the following command from the shell.

    /mps/db_pgsql/bin/psql -U mpsroot -p 5454 mpsdb -c "SET ROLE 'Owner'; SET SCHEMA 'Owner'; COPY (SELECT hostname,ip_address,type,model_id,sysid,serialnumber,version,description FROM managed_device) TO stdout WITH CSV DELIMITER ',';"

As of Citrix ADM 13.0, the available fields are as follows.

    id, ip_address, type, profile_name, user_driven, ping_state, status, reason, name, netmask, gateway, hostname, description, device_family, seq_no, version, instance_state, gateway_deployment, geo_support, serialnumber, encoded_serialnumber, model_id, systemname, location, contactperson, sysservices, agent_id, is_pooled_license, is_grace, license_edition, plt_bw_total, plt_bw_config, plt_bw_available, ent_bw_total, ent_bw_config, ent_bw_available, std_bw_total, std_bw_config, std_bw_available, instance_total, instance_config, instance_available, sslvpn_total, sslvpn_config, last_updated_time, mgmt_ip_address, display_name, autoprovisioned, ha_master_state, ha_ip_address, ha_sync, is_ha_configured, ipv4_address, ipv6_address, gateway_ipv6, config_type, nexthop, nexthop_v6, tenant_id, partition_name, sysid, is_managed, upsince, uptime, node_id, instance_mode, device_finger_print, partition_id, trust_id, isolation_policy, servicepackage, discovery_time, host_id, device_uuid, manufacturedate, cpufrequncy, bmcrevision, vcpu_config, datacenter_id, mastools_version, cpu_license_type, do_config, geo_location, cloud, region, zone, vpc_id, instance_type, public_dns, public_ip, private_dns, private_ip, ami_id, security_group, subnet_id, internal_ip_address, instance_unique_id, provision_request_id, is_autoscale_group, if_internal_ip_enabled, instance_classifier, internal_annotation, httpxforwardedfor, template_interval

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
