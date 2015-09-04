<?php
include('connect-db.php');
function doCancel() {
        header("Location: working_set.php");
        }
 if (isset($_POST['cancel'])){
 doCancel(); }
 if (isset($_POST['submit'])){
	$num = $_POST['howmany'];
        $site_name = $_POST['sitename'];
$N = 0;
 while ($N < $num) {
 // get form data, making sure it is valid
 $edit_row_name = mysql_real_escape_string(htmlspecialchars($_POST['edit_rowname'][$N]));
 $edit_cab_name = mysql_real_escape_string(htmlspecialchars($_POST['edit_cabname'][$N]));
 $location = $edit_row_name . "-" . $edit_cab_name;
 $system_name = mysql_real_escape_string(htmlspecialchars($_POST['system_name'][$N]));
 $system_usage = mysql_real_escape_string(htmlspecialchars($_POST['system_usage'][$N]));
 $system_model = mysql_real_escape_string(htmlspecialchars($_POST['system_model'][$N]));
 $unit_location = mysql_real_escape_string(htmlspecialchars($_POST['unit_location'][$N]));
 $system_serial_number = mysql_real_escape_string(htmlspecialchars($_POST['system_serial_number'][$N]));
 $system_rfid_tag = mysql_real_escape_string(htmlspecialchars($_POST['system_rfid_tag'][$N]));
 $system_asset_number = mysql_real_escape_string(htmlspecialchars($_POST['system_asset_number'][$N]));
 $comments = mysql_real_escape_string(htmlspecialchars($_POST['comments'][$N]));

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

++$N;
 }
header("Location: working_set.php");
}
?>
