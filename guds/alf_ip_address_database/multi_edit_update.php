<?php
include('connect-db.php');
 function doCancel() {
	$num=count($_POST['address']);
	$row_select = $_POST['row_select'][0];
	$N = 0;
	while ($N < $num) {
	$address = mysql_real_escape_string(htmlspecialchars($_POST['address'][$N]));
        mysql_query("UPDATE aqn_master_table SET locked='',lock_datetime='' WHERE address='$address'")
        or die(mysql_error());
	++$N;
	}
        header("Location: view.php?RowName=$row_select");
        }
 // check if the form has been submitted. If it has, process the form and save it to the database
 if (isset($_POST['cancel'])){
 doCancel(); }
 if (isset($_POST['submit']))
 {
 $num=count($_POST['address']);
 $row_select = $_POST['row_select'][0];
 $N = 0;
 while ($N < $num) {
 // get form data, making sure it is valid
 $address = mysql_real_escape_string(htmlspecialchars($_POST['address'][$N]));
 $hostname = mysql_real_escape_string(htmlspecialchars($_POST['hostname'][$N]));
 $model = mysql_real_escape_string(htmlspecialchars($_POST['model'][$N]));
 $cab = mysql_real_escape_string(htmlspecialchars($_POST['cab'][$N]));
 $unit = mysql_real_escape_string(htmlspecialchars($_POST['unit'][$N]));
 $comments = mysql_real_escape_string(htmlspecialchars($_POST['comments'][$N]));

 // save the data to the database
 mysql_query("UPDATE aqn_master_table SET hostname='$hostname', model='$model', cab='$cab', unit='$unit', comments='$comments' WHERE address='$address'") or die(mysql_error());

 // Update changes_made table
 $remote_host = $_SERVER['REMOTE_HOST'];
 $changes_made = "Edited -- $hostname -- $model -- $cab -- $unit -- $comments --";
 $current_date=date('Y-m-d H:i:s');
 $result = mysql_query("INSERT INTO changes_made (address, remote_hostname, changes_made, date_time) VALUES ('$address','$remote_host','$changes_made','$current_date')")  or die(mysql_error());
 mysql_query("UPDATE `aqn_master_table` SET locked='',lock_datetime='' WHERE address='$address'")
 or die(mysql_error());
 ++$N;
 }

 header("Location: view.php?RowName=$row_select");
 // once saved, redirect back to the view page

 }
?>

