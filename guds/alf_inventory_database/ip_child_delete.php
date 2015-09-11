<?php
include('connect-db.php');
if(isset($_GET['return'])){
    $return=$_GET['return'];
        if ($return == 'workingset'){
                $row_name = $_GET['row_name'];
                $cab_name = $_GET['cab_name'];
                $site_name = $_GET['sitename'];
        } else {
                $searchname = $_GET['searchname'];
                }
}

$child_address = $_GET['childip'];
$parent_ip = $_GET['parentip'];
$system_name = $_GET['system_name'];
$orig_ip_address= $_GET['orig_ip_address'];

$update_child = mysql_query("UPDATE lab_ip_data.aqn_master_table SET hostname='', model='', cab='', unit='', project='', comments='', parentIP='' WHERE address='$child_address'") or die(mysql_error());

$result = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE parentIP='$parent_ip'") or die(mysql_error());
if (!mysql_num_rows($result) ) {
$update_parent = mysql_query("UPDATE lab_ip_data.aqn_master_table SET isParentIP='' WHERE address='$parent_ip'") or die(mysql_error());
}

if ($return == 'workingset'){
header("Location: manage_ip.php?system_name=$system_name&orig_ip_address=$orig_ip_address&return=workingset&row_name=$row_name&cab_name=$cab_name&sitename=$site_name");
        } else {
header("Location: manage_ip.php?system_name=$system_name&orig_ip_address=$orig_ip_address&return=search&searchname=$searchname");
        } // END RETURN

?>
