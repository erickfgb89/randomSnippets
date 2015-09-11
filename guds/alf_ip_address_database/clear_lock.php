<?php
 include('connect-db.php');
 if (isset($_GET['address'])) {
 $row_select = $_GET['RowName'];
 $address = $_GET['address']; 

 $result = mysql_query("UPDATE aqn_master_table SET locked='',lock_datetime='' WHERE address='$address'")  or die(mysql_error());
 
 header("Location: view.php?RowName=$row_select");
 }
 else
 {
 header("Location: view.php");
 }
?>


