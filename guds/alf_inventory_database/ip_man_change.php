<?php
include('connect-db.php');
if(isset($_POST['return'])){
    $return=$_POST['return'];
        if ($return == 'workingset'){
                $row_name = $_POST['row_name'];
                $cab_name = $_POST['cab_name'];
                $site_name = $_POST['sitename'];
		$orig_ip_address = $_POST['orig_ip_address'];
        } else {
                $searchname = $_POST['searchname'];
		$orig_ip_address = $_POST['orig_ip_address'];
                }
}
$RowName = $_POST['RowName'];
$system_name = $_POST['system_name'];
$parent_ip = $_POST['parent_ip'];
$project = $_POST['project'];
$ident = $_POST['ident'];

if(isset($_POST['ip_address'])){
$num_ip = count($_POST['ip_address']);
$update_parent = mysql_query("UPDATE lab_ip_data.aqn_master_table SET isParentIP='1' WHERE address='$parent_ip'") or die(mysql_error());
$IP_N = 0;
$N = 0;
while ($IP_N < $num_ip) {
$ip_address = $_POST['ip_address'][$IP_N];
//$result_num_children = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE parentIP='$parent_ip'") or die(mysql_error());
$childnamecheck = $system_name.$ident;
$result_num_children = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE hostname LIKE '%$childnamecheck%'") or die(mysql_error());
$N = mysql_num_rows($result_num_children);
$N++;
$child_system_name = $system_name.$ident.($N);

$get_parent_info = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE address='$parent_ip'") or die(mysql_error());
$row_parent_info = mysql_fetch_array($get_parent_info);
$parent_model = $row_parent_info['model'];
$parent_cab = $row_parent_info['cab'];
$parent_unit = $row_parent_info['unit'];
$child_comments = 'Added for Parent Host ' . $system_name;

$update_child = mysql_query("UPDATE lab_ip_data.aqn_master_table SET hostname='$child_system_name', model='$parent_model', cab='$parent_cab', unit='$parent_unit', parentIP='$parent_ip', project='$project', comments='$child_comments' WHERE address='$ip_address'") or die(mysql_error());

echo $ip_address . ' ';
++$IP_N;
	}
} // END if isset ip_address
if ($return == 'workingset'){
header("Location: manage_ip.php?system_name=$system_name&orig_ip_address=$orig_ip_address&return=workingset&row_name=$row_name&cab_name=$cab_name&sitename=$site_name");
        } else {
header("Location: manage_ip.php?system_name=$system_name&orig_ip_address=$orig_ip_address&return=search&searchname=$searchname");
        }
