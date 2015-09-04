<?php
/* 
 EDIT.PHP
 Allows user to edit specific entry in database
*/

 // creates the edit record form
 // since this form is used multiple times in this file, I have made it a function that is easily reusable
 include('connect-db.php'); 

// query db
 $row_select = $_GET['RowName'];
 $address = $_GET['address'];
 if(isset($_GET['search'])){
 $search=$_GET['search'];
 $name=$_GET['name'];}
 $result = mysql_query("SELECT * FROM aqn_master_table WHERE address='$address' FOR UPDATE")
 or die(mysql_error());
 $row = mysql_fetch_array($result);
 $current_date=date('Y-m-d H:i:s');
 mysql_query("UPDATE aqn_master_table SET locked=\"Y\",lock_datetime='$current_date' WHERE address='$address'")
 or die(mysql_error());
 // get data from db
 $address = $row['address'];
 $hostname_orig = $row['hostname'];
 $model_orig = $row['model'];
 $cab_orig = $row['cab'];
 $unit_orig = $row['unit'];
 $comments_orig = $row['comments'];

echo "<center><p><strong>Current Row Selected: $row_select</p></center>";

function renderForm($address, $hostname_orig, $model_orig, $cab_orig, $unit_orig, $comments_orig, $error)
 {
 ?>
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
 <html>
 <head>
 <center>
	
 <style type="text/css">
 table, td, th {
 border-collapse: collapse;
 border: 1px solid black;
 border-spacing: 0px;
 }
 th
 {
 font-size:1.1em;
 background-color: #98bf21;
 color: white;
 }
 td
{
text-align: left;
}

</style>
 <title>Edit Record</title>
 </head>
 <body>
 <?php 
 // if there are any errors, display them
 if ($error != '')
 {
 echo '<div style="padding:4px; border:1px solid red; color:red;">'.$error.'</div>';
 }
 ?> 

 <table border='1' cellpadding='10'>
 <tr> <th>Address</th> <th>Hostname</th> <th>Model</th> <th>Cabinet</th> <th>Unit</th> <th>Comments</th> </tr> 
 <form action="" method="post" onReset="return confirm('Do you really want to reset the form?')">
 <input type="hidden" name="address" value="<?php echo $address; ?>"/>
 <div>

 <tr>
 <td> <?php echo $address; ?></td>
 <td> <input type="text" name="hostname" value="<?php echo $hostname_orig; ?>"/></td>
 <td> <input type="text" name="model" value="<?php echo $model_orig; ?>"/></td>
 <td> <input type="text" name="cab" value="<?php echo $cab_orig; ?>"/></td>
 <td> <input type="text" name="unit" value="<?php echo $unit_orig; ?>"/></td>
 <td> <input type="text" name="comments" value="<?php echo $comments_orig; ?>"/></td>
 </tr>
 </table>
 <input type="submit" name="submit" value="Submit">
 <input type="submit" name="cancel" value="Cancel">
 <input type="reset">
 </div>
 </form> 
 </body>
 </center>
 </html> 
 <?php
 }
 function doCancel() {
	$address = $_GET['address']; 
	$row_select = $_GET['RowName'];	
	if(isset($_GET['search'])){
	$search=$_GET['search'];
	$name=$_GET['name'];}
        mysql_query("UPDATE aqn_master_table SET locked='',lock_datetime='' WHERE address='$address'")
        or die(mysql_error());
	if ($search == 1) {	
        header("Location: search.php?go&name=$name"); } else {
	header("Location: view.php?RowName=$row_select"); }
        }
 // check if the form has been submitted. If it has, process the form and save it to the database
 if (isset($_POST['cancel'])){ 
 doCancel(); }
 if (isset($_POST['submit']))
 { 

// get form data, making sure it is valid
 $address = mysql_real_escape_string(htmlspecialchars($_POST['address']));
 $hostname = mysql_real_escape_string(htmlspecialchars($_POST['hostname']));
 $model = mysql_real_escape_string(htmlspecialchars($_POST['model']));
 $cab = mysql_real_escape_string(htmlspecialchars($_POST['cab']));
 $unit = mysql_real_escape_string(htmlspecialchars($_POST['unit']));
 $comments = mysql_real_escape_string(htmlspecialchars($_POST['comments'])); 

 // save the data to the database
 mysql_query("UPDATE aqn_master_table SET hostname='$hostname', model='$model', cab='$cab', unit='$unit', comments='$comments' WHERE address='$address'")
 or die(mysql_error()); 

 // Update changes_made table
 $remote_host = $_SERVER['REMOTE_HOST'];
 $changes_made = "Edited -- $hostname -- $model -- $cab -- $unit -- $comments --";
 $current_date=date('Y-m-d H:i:s');
 $result = mysql_query("INSERT INTO changes_made (address, remote_hostname, changes_made, date_time) VALUES ('$address','$remote_host','$changes_made','$current_date')")
 or die(mysql_error());
 mysql_query("UPDATE aqn_master_table SET locked='',lock_datetime='' WHERE address='$address'")
 or die(mysql_error());
 
 // once saved, redirect back to the view page
 if(isset($_GET['search'])){
        $search=$_GET['search'];
        $name=$_GET['name'];}
	if ($search == 1) {
        header("Location: search.php?go&name=$name"); } else {
        header("Location: view.php?RowName=$row_select"); }
        
 }
 
// } CLOSE OUT TEST to check that fields are filled in
 else
 // if the form hasn't been submitted, get the data from the db and display the form
 {
 
 // get the 'address' value from the URL (if it exists), making sure that it is valid 
 if (isset($_GET['address']))
 {
 // query db
 $address = $_GET['address'];
 $result = mysql_query("SELECT * FROM aqn_master_table WHERE address='$address' FOR UPDATE")
 or die(mysql_error()); 
 $row = mysql_fetch_array($result);
 
 // check that the 'address' matches up with a row in the databse
 if($row)
 {
 
 // get data from db
 $address = $row['address'];
 $hostname = $row['hostname'];
 $model = $row['model'];
 $cab = $row['cab'];
 $unit = $row['unit'];
 $comments = $row['comments'];
 
 // show form
 renderForm($address, $hostname, $model, $cab, $unit, $comments, '');
 }
 else
 // if no match, display result
 {
 echo "No results!";
 }
 }
 else
 // if the 'address' in the URL isn't valid, or if there is no 'address' value, display an error
 {
 echo 'Error!';
 }
 }
?>
