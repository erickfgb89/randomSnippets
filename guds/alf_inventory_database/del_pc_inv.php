<?php
include('connect-db.php');
 $id = $_GET['id'];
 $site_name = $_GET['sitename'];

$result = mysql_query("SELECT * FROM powercan_definition WHERE id='$id' AND site_name = '$site_name'") or die(mysql_error());
$row = mysql_fetch_array($result);
$powercan_type = $row['powercan_type'];

mysql_query("DELETE FROM powercan_inv_master_table WHERE powercan_type = '$powercan_type' AND inv_site_name = '$site_name'") or die(mysql_error());
mysql_query("DELETE FROM powercan_definition WHERE id = '$id' AND site_name = '$site_name'") or die(mysql_error());

header("Location: view_pc_inv.php?sitename=$site_name");

