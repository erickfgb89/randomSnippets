<?php
include('connect-db.php');
$result = mysql_query("select * from deploy_system_status where TIMESTAMPDIFF(MINUTE, deploy_start_time, now()) > 10") or die(mysql_error());

while($row = mysql_fetch_array( $result )) {

$id = $row['id'];
$ilo_name = 'ilo-' . trim($row['system_name']);
exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/Get_Host_Data.xml |grep -o -E '([[:xdigit:]]{1,2}-){5}[[:xdigit:]]{1,2}' |tr '[:upper:]' '[:lower:]'", $output);

foreach(array_slice($output,0,count($output)) as $mac) {

exec("/bin/rm -f /var/lib/tftpboot/pxelinux.cfg/01-$mac");
	}

}

$result = mysql_query("select * from deploy_system_status where TIMESTAMPDIFF(MINUTE, deploy_start_time, now()) > 120") or die(mysql_error());

while($row = mysql_fetch_array( $result )) {
$id = $row['id'];
mysql_query("UPDATE deploy_system_status SET percent_complete='Install FAILED!!!' WHERE id='$id'") or die(mysql_error());
}
?>
