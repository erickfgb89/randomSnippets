<?php
/* 
 DELETE.PHP
 Deletes a specific entry from the tabls
*/

 // connect to the database
 include('connect-db.php');
 
 // check if the 'address' variable is set in URL, and check that it is valid
 if (isset($_GET['address']))
 {
 // get address value
 $address = $_GET['address'];
 $row_select = $_GET['RowName'];
 $hostname = $_GET['hostname'];
 // Get previous data
  if(isset($_GET['search'])){
        $search=$_GET['search'];
        $name=$_GET['name'];}
 $result = mysql_query("SELECT * FROM `aqn_master_table` WHERE address='$address' FOR UPDATE")
 or die(mysql_error());
 $row = mysql_fetch_array($result);

 $hostname_orig = $row['hostname'];
 $model_orig = $row['model'];
 $cab_orig = $row['cab'];
 $unit_orig = $row['unit'];
 $comments_orig = $row['comments'];
 
 // delete the entry
 $result = mysql_query("UPDATE `aqn_master_table` SET hostname = '', model = '', cab = '', unit = '', project='', comments = '' WHERE address = '$address'")
 or die(mysql_error()); 

 // Update changes_made table
 $remote_host = $_SERVER['REMOTE_HOST'];
 $changes_made = "Deleted -- $hostname_orig -- $model_orig -- $cab_orig -- $unit_orig -- $comments_orig --";
 $current_date=date('Y-m-d H:i:s');
 $result = mysql_query("INSERT INTO `changes_made` (address, remote_hostname, changes_made, date_time) VALUES ('$address','$remote_host','$changes_made','$current_date')")
 or die(mysql_error());
 
 // redirect back to the view page
        if ($search == 1) {
        header("Location: search.php?go&RowName=$row_select_return&name=$name"); } else {
        header("Location: view.php?RowName=$row_select"); }
 }
 else
 // if id isn't set, or isn't valid, redirect back to view page
 {
 header("Location: view.php");
 }
 
?>
