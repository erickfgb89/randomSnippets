<?php
include('connect-db.php');
$row_name = $_POST['row_name'];
$cab_name = $_POST['cab_name'];
$site_name = $_POST['site_name'];
if(isset($_POST['search'])){
	$search=$_POST['search'];
	$name=$_POST['name'];}
function doCancel() {
        if ($search == 1) {
        header("Location: search.php?go&name=$name"); } elseif
	($search == 2) {
	header("Location: search_duplicate.php?go"); } else {
        header("Location: working_set.php?rowname=$row_name&cabname=$cab_name&sitename=$site_name"); }
        }
 if (isset($_POST['cancel'])){
 doCancel(); }
 if (isset($_POST['submit']))
 {

// get form data, making sure it is valid
 $id = $_POST['id'];
 $system_name = mysql_real_escape_string(htmlspecialchars($_POST['system_name']));
 $orig_name = $_POST['orig_name'];
 $system_usage = mysql_real_escape_string(htmlspecialchars($_POST['system_usage']));
 $system_model = mysql_real_escape_string(htmlspecialchars($_POST['system_model']));
 $system_site_name = mysql_real_escape_string(htmlspecialchars($_POST['edit_sitename']));
 $unit_location = mysql_real_escape_string(htmlspecialchars($_POST['unit_location']));
 $system_serial_number = mysql_real_escape_string(htmlspecialchars($_POST['system_serial_number']));
 $system_rfid_tag = mysql_real_escape_string(htmlspecialchars($_POST['system_rfid_tag']));
 $system_asset_number = mysql_real_escape_string(htmlspecialchars($_POST['system_asset_number']));
 $comments = mysql_real_escape_string(htmlspecialchars($_POST['comments']));
 $row_name = $_POST['row_name'];
 $cab_name = $_POST['cab_name'];
 $edit_row_name = $_POST['edit_rowname'];
 $edit_cab_name = $_POST['edit_cabname'];
 if ($edit_row_name == 'Retired'){
 $edit_location = Retired ; } else {
 $edit_location = $edit_row_name . "-" . $edit_cab_name;}
 
 
 if ($system_usage == "Retired") {
  mysql_query("UPDATE `aqn_inv_master_table` SET site_name='$system_site_name', system_name='$system_name', data_location_id='Retired', system_usage='$system_usage', system_model='$system_model', unit_location='Retired', system_serial_number='$system_serial_number', system_rfid_tag='$system_rfid_tag', system_asset_number='$system_asset_number', comments='$comments' WHERE id='$id'") or die(mysql_error());

 $remote_host = $_SERVER['REMOTE_HOST'];
 $changes_made = "Edited -- $system_name -- Site: $system_site_name -- RETIRED";
 $current_date=date('Y-m-d H:i:s');

 mysql_query("INSERT INTO `changes_made_inv` (system_serial_number, remote_hostname, changes_made, date_time) VALUES ('$system_serial_number','$remote_host','$changes_made','$current_date')")
 or die(mysql_error());

 } else {

if ($orig_name != '') {
$result_orig = mysql_query("SELECT hostname FROM lab_ip_data.aqn_master_table WHERE hostname='$orig_name'") or die(mysql_error());
if (mysql_num_rows($result_orig)) {
	if ($system_name != $orig_name) {
	$update_new_name = mysql_query("UPDATE lab_ip_data.aqn_master_table SET hostname = '$system_name' WHERE hostname = '$orig_name'") or die(mysql_error());
				}
		}
}
mysql_query("UPDATE `aqn_inv_master_table` SET site_name='$system_site_name', system_name='$system_name', data_location_id='$edit_location', system_usage='$system_usage', system_model='$system_model', unit_location='$unit_location', system_serial_number='$system_serial_number', system_rfid_tag='$system_rfid_tag', system_asset_number='$system_asset_number', comments='$comments' WHERE id='$id'") or die(mysql_error());

 $remote_host = $_SERVER['REMOTE_HOST'];
 $changes_made = "Edited -- SystemName: $system_name -- Usage: $system_usage -- Model: $system_model -- Site: $system_site_name -- Location: $edit_location -- RFID: $system_rfid_tag -- Asset Number: $system_asset_number";
 $current_date=date('Y-m-d H:i:s');

 mysql_query("INSERT INTO `changes_made_inv` (system_serial_number, remote_hostname, changes_made, date_time) VALUES ('$system_serial_number','$remote_host','$changes_made','$current_date')")
 or die(mysql_error());

 }
 }
if ($search == 1) {
header("Location: search.php?go&name=$name"); } elseif
($search == 2) {
header("Location: search_duplicate.php?go"); } else {
header("Location: working_set.php?rowname=$row_name&cabname=$cab_name&sitename=$site_name"); }

?>
