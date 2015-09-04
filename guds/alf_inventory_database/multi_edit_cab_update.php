<?php
include('connect-db.php');
$num=count($_POST['id']);
$row_name = $_POST['rowname'];
$cab_name = $_POST['cabname'];
$site_name = $_POST['sitename'];
$location = $row_name . "-" . $cab_name;

 function doCancel() {
$row_name = $_POST['rowname'];
$cab_name = $_POST['cabname'];
$site_name = $_POST['sitename'];
        header("Location: working_set.php?cabname=$cab_name&rowname=$row_name&sitename=$site_name");
        }

 if (isset($_POST['cancel'])){
 doCancel(); }
 if (isset($_POST['submit']))
 {
$N = 0;
 while ($N < $num) {
 // get form data, making sure it is valid
 $id = $_POST['id'][$N];
 $edit_row_name = mysql_real_escape_string(htmlspecialchars($_POST['edit_rowname'][$N]));
 $edit_cab_name = mysql_real_escape_string(htmlspecialchars($_POST['edit_cabname'][$N]));
 $system_site_name = mysql_real_escape_string(htmlspecialchars($_POST['edit_sitename'][$N]));
 $system_name = mysql_real_escape_string(htmlspecialchars($_POST['system_name'][$N]));
 $orig_name = $_POST['orig_name'][$N];
 $system_usage = mysql_real_escape_string(htmlspecialchars($_POST['system_usage'][$N]));
 $system_model = mysql_real_escape_string(htmlspecialchars($_POST['system_model'][$N]));
 $unit_location = mysql_real_escape_string(htmlspecialchars($_POST['unit_location'][$N]));
 $system_serial_number = mysql_real_escape_string(htmlspecialchars($_POST['system_serial_number'][$N]));
 $system_rfid_tag = mysql_real_escape_string(htmlspecialchars($_POST['system_rfid_tag'][$N]));
 $system_asset_number = mysql_real_escape_string(htmlspecialchars($_POST['system_asset_number'][$N]));
 $comments = mysql_real_escape_string(htmlspecialchars($_POST['comments'][$N]));

 if ($edit_row_name == 'Retired'){
 $edit_location = Retired ; } else {
 $edit_location = $edit_row_name . "-" . $edit_cab_name;}

 if ($system_usage == "Retired") {
  mysql_query("UPDATE `aqn_inv_master_table` SET site_name='$system_site_name', system_name='$system_name', data_location_id='Retired', system_usage='$system_usage', system_model='$system_model', unit_location='Retired', system_serial_number='$system_serial_number', system_rfid_tag='$system_rfid_tag', system_asset_number='$system_asset_number', comments='$comments' WHERE id='$id'") or die(mysql_error());

 $remote_host = $_SERVER['REMOTE_HOST'];
 $changes_made = "Edited -- $system_name -- RETIRED";
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
 $changes_made = "Edited -- SystemName: $system_name -- Usage: $system_usage -- Model: $system_model -- Site: $system_site_name -- Location: $location -- RFID: $system_rfid_tag -- Asset Number: $system_asset_number";
 $current_date=date('Y-m-d H:i:s');

 mysql_query("INSERT INTO `changes_made_inv` (system_serial_number, remote_hostname, changes_made, date_time) VALUES ('$system_serial_number','$remote_host','$changes_made','$current_date')")
 or die(mysql_error());

 }

++$N;
 }
 header("Location: working_set.php?cabname=$cab_name&rowname=$row_name&sitename=$site_name");
 // once saved, redirect back to the view page

 }
?>


