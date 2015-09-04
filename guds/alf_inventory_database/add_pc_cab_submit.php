<?php
include('connect-db.php');
if (isset($_POST['submit']))
 {
// get form data, making sure it is valid
 $powercan_type = mysql_real_escape_string(htmlspecialchars($_POST['powercan_type']));
 $count = mysql_real_escape_string(htmlspecialchars($_POST['count']));
 $row_name = $_POST['rowname'];
 $cab_name = $_POST['cabname'];
 $site_name = $_POST['sitename'];
 $location = $_POST['location'];

mysql_query("INSERT INTO `powercan_inv_master_table` (powercan_type, inv_site_name, data_location_id, count) VALUES ('$powercan_type', '$site_name', '$location', '$count')") or die(mysql_error());

$result_pc = mysql_query("SELECT * FROM powercan_definition WHERE powercan_type = '$powercan_type' AND site_name='$site_name'");
$row_pc_type = mysql_fetch_array($result_pc);
$pc_type = $row_pc_type['powercan_type'];
$db_total_count = $row_pc_type['total_count'];
$db_total_used = $row_pc_type['total_used'];

$pc_total_count = $db_total_count - $count;
$pc_total_used = $db_total_used + $count;

mysql_query("UPDATE `powercan_definition` SET total_count='$pc_total_count', total_used='$pc_total_used' WHERE powercan_type='$powercan_type' AND site_name='$site_name'") or die(mysql_error());

 }

header("Location: working_set.php?rowname=$row_name&cabname=$cab_name&sitename=$site_name");
?>

