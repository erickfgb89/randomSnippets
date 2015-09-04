<?php

include('connect-db.php');
$row_name = $_GET['rowname'];
$cab_name = $_GET['cabname'];
$site_name = $_GET['sitename'];
$serial_number = $_GET['serial_number'];
$system_name = $_GET['system_name'];

mysql_query("DELETE FROM deploy_system_status WHERE system_serial_number = '$serial_number'") or die(mysql_error());

$ilo_name = 'ilo-' . $system_name;
exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/Get_Host_Data.xml |grep -o -E '([[:xdigit:]]{1,2}-){5}[[:xdigit:]]{1,2}' |tr '[:upper:]' '[:lower:]'", $output);

foreach(array_slice($output,0,count($output)) as $mac) {

exec("/bin/rm -f /var/lib/tftpboot/pxelinux.cfg/01-$mac");

}

header("Location: working_set.php?rowname=$row_name&cabname=$cab_name&sitename=$site_name");

?>
