<?php
include('connect-db.php');
$id = $_GET['id'];
$result = mysql_query("SELECT * FROM aqn_inv_master_table WHERE id='$id'") or die(mysql_error());
$row = mysql_fetch_array($result); 
$data_location_id = $row['data_location_id'];
mysql_query("DELETE FROM aqn_inv_master_table WHERE id='$id'") or die(mysql_error());
header("Location: search_duplicate.php?go");
?>

