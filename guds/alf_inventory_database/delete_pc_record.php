<?php
include('connect-db.php');
 $id = $_GET['id'];
 $row_name = $_GET['rowname'];
 $cab_name = $_GET['cabname'];
 $site_name = $_GET['sitename'];
 $location = $row_name . "-" . $cab_name;

$result = mysql_query("SELECT * FROM powercan_inv_master_table WHERE id='$id'") or die(mysql_error());
$row = mysql_fetch_array($result);
$data_location_id = $row['data_location_id'];
$powercan_type = $row['powercan_type'];
$db_count = $row['count'];

mysql_query("DELETE FROM powercan_inv_master_table WHERE id='$id'") or die(mysql_error());

$result_pc = mysql_query("SELECT * FROM powercan_definition WHERE powercan_type = '$powercan_type' AND site_name='$site_name'");
$row_pc_type = mysql_fetch_array($result_pc);
$pc_type = $row_pc_type['powercan_type'];
$db_total_count = $row_pc_type['total_count'];
$db_total_used = $row_pc_type['total_used'];

$pc_total_count = $db_total_count + $db_count;
$pc_total_used = $db_total_used - $db_count;

mysql_query("UPDATE `powercan_definition` SET total_count='$pc_total_count', total_used='$pc_total_used' WHERE powercan_type='$powercan_type' AND site_name='$site_name'") or die(mysql_error());

header("Location: working_set.php?rowname=$row_name&cabname=$cab_name&sitename=$site_name");

