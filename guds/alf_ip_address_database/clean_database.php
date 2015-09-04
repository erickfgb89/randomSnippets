<?php
include('connect-db.php');
 $result = mysql_query("SELECT * FROM `aqn_master_table`") or die(mysql_error());
 while($row = mysql_fetch_array( $result )) { 
 $address = $row['address'];
 if ($row['locked'] == "Y") {
 mysql_query("UPDATE aqn_master_table SET locked='',lock_datetime='' WHERE address='$address'")  or die(mysql_error());}
 }
?>
