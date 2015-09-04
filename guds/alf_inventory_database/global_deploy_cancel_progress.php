<?php

include('connect-db.php');
$serial_number = $_GET['serial_number'];
$system_name = $_GET['system_name'];

mysql_query("DELETE FROM deploy_system_status WHERE system_serial_number = '$serial_number'") or die(mysql_error());

$ilo_name = 'ilo-' . $system_name;
exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/Get_Host_Data.xml |grep -o -E '([[:xdigit:]]{1,2}-){5}[[:xdigit:]]{1,2}' |tr '[:upper:]' '[:lower:]'", $output);

foreach(array_slice($output,0,count($output)) as $mac) {

exec("/bin/rm -f /var/lib/tftpboot/pxelinux.cfg/01-$mac");

}

header("Location: deploy_view_jobs.php");

?>
