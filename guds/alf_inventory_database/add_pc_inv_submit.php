<?php
include('connect-db.php');
if (isset($_POST['submit']))
 {
// get form data, making sure it is valid
 $site_name = mysql_real_escape_string(htmlspecialchars($_POST['site_name']));
 $powercan_type = mysql_real_escape_string(htmlspecialchars($_POST['powercan_type']));
 $total_count = mysql_real_escape_string(htmlspecialchars($_POST['total_count']));
 $total_used = '0';

mysql_query("INSERT INTO `powercan_definition` (site_name, powercan_type, total_count, total_used) VALUES ('$site_name', '$powercan_type', '$total_count', '$total_used')") or die(mysql_error());

 }

header("Location: view_pc_inv.php?sitename=$site_name");
?>

