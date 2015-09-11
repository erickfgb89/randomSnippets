<?php
include('connect-db.php');
if (isset($_POST['submit']))
 {

// get form data, making sure it is valid
 $system_name = mysql_real_escape_string(htmlspecialchars($_POST['system_name']));
 $system_usage = mysql_real_escape_string(htmlspecialchars($_POST['system_usage']));
 $system_model = mysql_real_escape_string(htmlspecialchars($_POST['system_model']));
 $unit_location = mysql_real_escape_string(htmlspecialchars($_POST['unit_location']));
 $system_serial_number = mysql_real_escape_string(htmlspecialchars($_POST['system_serial_number']));
 $system_rfid_tag = mysql_real_escape_string(htmlspecialchars($_POST['system_rfid_tag']));
 $system_asset_number = mysql_real_escape_string(htmlspecialchars($_POST['system_asset_number']));
 $comments = mysql_real_escape_string(htmlspecialchars($_POST['comments']));
 $row_name = $_POST['row_name'];
 $cab_name = $_POST['cab_name'];
 $site_name = $_POST['site_name'];
 $location = $row_name . "-" . $cab_name;

 $remote_host = $_SERVER['REMOTE_HOST'];
 $current_date=date('Y-m-d H:i:s');

 $result = mysql_query("SELECT * FROM aqn_inv_master_table WHERE system_serial_number = '$system_serial_number' AND site_name = '$site_name'");
 if (mysql_num_rows($result) < 1) {

 $changes_made = "Added -- SystemName: $system_name -- Usage: $system_usage -- Model: $system_model -- Site: $site_name -- Location: $location -- RFID: $system_rfid_tag -- Asset Number: $system_asset_number";
 mysql_query("INSERT INTO `changes_made_inv` (system_serial_number, remote_hostname, changes_made, date_time) VALUES ('$system_serial_number','$remote_host','$changes_made','$current_date')")
 or die(mysql_error());
} else {

 $changes_made = "Updated -- SystemName: $system_name -- Usage: $system_usage -- Model: $system_model -- Site: $site_name -- Location: $location -- RFID: $system_rfid_tag -- Asset Number: $system_asset_number";
 mysql_query("INSERT INTO `changes_made_inv` (system_serial_number, remote_hostname, changes_made, date_time) VALUES ('$system_serial_number','$remote_host','$changes_made','$current_date')")
 or die(mysql_error());
}

 mysql_query("INSERT INTO `aqn_inv_master_table` (site_name, system_name, data_location_id, system_usage, system_model, unit_location, system_serial_number, system_rfid_tag, system_asset_number, comments) VALUES ('$site_name', '$system_name', '$location', '$system_usage', '$system_model', '$unit_location', '$system_serial_number', '$system_rfid_tag', '$system_asset_number', '$comments')") or die(mysql_error());

 }
header("Location: working_set.php?rowname=$row_name&cabname=$cab_name&sitename=$site_name");

?>
