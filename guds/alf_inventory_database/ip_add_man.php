<?php
include('connect-db.php');
if(isset($_POST['return'])){
    $return=$_POST['return'];
        if ($return == 'workingset'){
                $site_name = $_POST['sitename'];
        } else {
                $searchname = $_POST['searchname'];
                }
}
$system_name = $_POST['system_name'];
$row_name = $_POST['row_name'];
$cab_name = $_POST['cab_name'];
$unit = $_POST['unit'];
$model = $_POST['model'];

if(isset($_POST['ip_address'])){
$ip_address = $_POST['ip_address'];

$result_location = mysql_query("SELECT * FROM cab_definition,row_definition WHERE cab_definition.cab_name = '$cab_name' AND row_definition.row_name = '$row_name'") or die(mysql_error());
$row_location = mysql_fetch_array($result_location);
$visible_row_name = $row_location['visible_row_name'];
$visible_cab_name = $row_location['visible_cab_name'];
$cab = $visible_row_name.$visible_cab_name;

$add_ip = mysql_query("UPDATE lab_ip_data.aqn_master_table SET hostname='$system_name', model = '$model', cab = '$cab', unit = '$unit', comments = '' WHERE address='$ip_address'") or die(mysql_error());

if ($return == 'workingset'){
header("Location: manage_ip.php?system_name=$system_name&orig_ip_address=$ip_address&return=workingset&row_name=$row_name&cab_name=$cab_name&sitename=$site_name");

        } else {
header("Location: manage_ip.php?system_name=$system_name&orig_ip_address=$ip_address&return=search&searchname=$searchname");

        } 
} else { 
if ($return == 'workingset'){
header("Location: working_set.php?rowname=$row_name&cabname=$cab_name&sitename=$site_name");
        } else {
header("Location: search.php?go&name=$searchname");
        }
}
