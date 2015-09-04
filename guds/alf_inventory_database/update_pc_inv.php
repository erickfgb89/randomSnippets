<?php
include('connect-db.php');
$num=count($_POST['id']);

 if (isset($_POST['submit']))
 {
$N = 0;
 while ($N < $num) {
 // get form data, making sure it is valid
 $id = $_POST['id'][$N];
 $site_name = $_POST['sitename'][$N];
 $powercan_type = mysql_real_escape_string(htmlspecialchars($_POST['powercan_type'][$N]));
 $total_count = mysql_real_escape_string(htmlspecialchars($_POST['total_count'][$N]));
 $total_used = mysql_real_escape_string(htmlspecialchars($_POST['total_used'][$N]));
 $powercan_description = mysql_real_escape_string(htmlspecialchars($_POST['powercan_description'][$N]));

mysql_query("UPDATE `powercan_definition` SET site_name='$site_name', powercan_type='$powercan_type', total_count='$total_count', total_used='$total_used', powercan_description='$powercan_description' WHERE id='$id'") 
	or die(mysql_error());
++$N;
 }
header("Location: view_pc_inv.php?sitename=$site_name");
 }
?>

