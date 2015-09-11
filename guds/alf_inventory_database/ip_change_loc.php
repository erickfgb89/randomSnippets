<?php
include('connect-db.php');
$system_name = $_POST['system_name'];
$id = $_POST['id'];
if(isset($_POST['return'])){
    $return=$_POST['return'];
        if ($return == 'workingset'){
                $row_name = $_POST['row_name'];
                $cab_name = $_POST['cab_name'];
                $site_name = $_POST['sitename'];
        } else {
                $searchname = $_POST['searchname'];
                }
}
$edit_row_name = $_POST['edit_rowname'];
$edit_cab_name = $_POST['edit_cabname'];
$unit_location = $_POST['unit_location'];
if ($edit_row_name == 'Retired'){
$edit_location = Retired ; } else {
$edit_location = $edit_row_name . "-" . $edit_cab_name;}

mysql_query("UPDATE `aqn_inv_master_table` SET data_location_id='$edit_location', unit_location='$unit_location' WHERE id='$id'") or die(mysql_error());

$RowName = $_POST['RowName'];
$orig_address = $_POST['orig_address'];
$orig_rowname = $_POST['orig_rowname'];


$result_orig = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE address='$orig_address'") or die(mysql_error());
$row = mysql_fetch_array($result_orig);
$model_orig = $row['model'];
$comments_orig = $row['comments'];

$result_location = mysql_query("SELECT * FROM cab_definition,row_definition WHERE cab_definition.cab_name = '$edit_cab_name' AND row_definition.row_name = '$edit_row_name'") or die(mysql_error());
$row_location = mysql_fetch_array($result_location);
$visible_row_name = $row_location['visible_row_name'];
$visible_cab_name = $row_location['visible_cab_name'];
$cab_new = $visible_row_name . "" . $visible_cab_name;

$update_new = mysql_query("UPDATE lab_ip_data.aqn_master_table SET cab = '$cab_new', unit = '$unit_location' WHERE address = '$orig_address'") or die(mysql_error());

if ($orig_rowname == $RowName){
	if ($return == 'workingset'){
header("Location: working_set.php?rowname=$edit_row_name&cabname=$edit_cab_name&sitename=$site_name");
        } else {
header("Location: search.php?go&name=$searchname");
				    }
} else {
// Change IP and Delete Old One

$result = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE row_name = '$RowName' AND (hostname = '' OR hostname IS NULL) AND (model = '' OR model IS NULL) AND (cab = '' OR cab IS NULL) AND (unit = '' OR unit IS NULL) AND (comments = '' OR comments IS NULL) LIMIT 1") or die(mysql_error());
$row = mysql_fetch_array($result);
$new_address = $row['address'];

$result_orig = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE address='$orig_address'") or die(mysql_error());
$row = mysql_fetch_array($result_orig);
$model_orig = $row['model'];
$comments_orig = $row['comments'];

$result_location = mysql_query("SELECT * FROM cab_definition,row_definition WHERE cab_definition.cab_name = '$edit_cab_name' AND row_definition.row_name = '$edit_row_name'") or die(mysql_error());
$row_location = mysql_fetch_array($result_location);
$visible_row_name = $row_location['visible_row_name'];
$visible_cab_name = $row_location['visible_cab_name'];
$cab_new = $visible_row_name . "" . $visible_cab_name;

$update_old = mysql_query("UPDATE lab_ip_data.aqn_master_table SET hostname = '', model = '', cab = '', unit = '', project='', comments = '' WHERE address = '$orig_address'") or die(mysql_error());

$update_new = mysql_query("UPDATE lab_ip_data.aqn_master_table SET hostname = '$system_name', model = '$model_orig', cab = '$cab_new', unit = '$unit_location', comments = '$comments_orig' WHERE address = '$new_address'") or die(mysql_error());

if ($return == 'workingset'){
header("Location: working_set.php?rowname=$edit_row_name&cabname=$edit_cab_name&sitename=$site_name");
        } else {
header("Location: search.php?go&name=$searchname");
	}
}
?>
