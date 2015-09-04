<?php
include('connect-db.php');
 $id = $_GET['id'];
 $row_name = $_GET['rowname'];
 $cab_name = $_GET['cabname'];
 $site_name = $_GET['sitename'];
 $system_serial_number = $_GET['serial_number'];

 if(isset($_GET['search'])){
        $search=$_GET['search'];
        $name=$_GET['name'];}
$result = mysql_query("SELECT * FROM aqn_inv_master_table WHERE id='$id'") or die(mysql_error());
$row = mysql_fetch_array($result); 
$data_location_id = $row['data_location_id'];
$system_name = $row['system_name'];
$system_usage = $row['system_usage'];
 if ($data_location_id == 'Retired'){

 $remote_host = $_SERVER['REMOTE_HOST'];
 $changes_made = "Deleted -- $system_serial_number -- Asset Name: $system_name -- Site: $site_name -- Usage: $system_usage";
 $current_date=date('Y-m-d H:i:s');

 mysql_query("INSERT INTO `changes_made_inv` (system_serial_number, remote_hostname, changes_made, date_time) VALUES ('$system_serial_number','$remote_host','$changes_made','$current_date')")
 or die(mysql_error());

 mysql_query("DELETE FROM aqn_inv_master_table WHERE id='$id'") or die(mysql_error());

} else {

 $remote_host = $_SERVER['REMOTE_HOST'];
 $changes_made = "Retired -- $system_serial_number -- Asset Name: $system_name -- Site: $site_name -- Usage: $system_usage";
 $current_date=date('Y-m-d H:i:s');

 mysql_query("INSERT INTO `changes_made_inv` (system_serial_number, remote_hostname, changes_made, date_time) VALUES ('$system_serial_number','$remote_host','$changes_made','$current_date')")
 or die(mysql_error());

 mysql_query("UPDATE `aqn_inv_master_table` SET data_location_id='Retired', system_usage='Retired' WHERE id='$id'") or die(mysql_error());

}

if ($search == 1){
header("Location: search.php?go&name=$name");} else {
header("Location: working_set.php?rowname=$row_name&cabname=$cab_name&sitename=$site_name");}
?>

