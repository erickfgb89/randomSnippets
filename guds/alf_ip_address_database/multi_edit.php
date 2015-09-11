<?php
 include('connect-db.php');
 $multi_edit_address = $_POST['edit'];
 if(empty($multi_edit_address))
  {
 ?>
    <script type="text/javascript">
    window.history.go(-1);
    </script>
<?php
  }

?>
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
 <h1><b><p> Edit Multiple Database Records </b></p></h1>
 <table border='1' cellpadding='10'>
 <tr> <th>Address</th> <th>Hostname</th> <th>Model</th> <th>Cabinet</th> <th>Unit</th> <th>Comments</th> </tr>
 <form action="multi_edit_update.php" method="post" onReset="return confirm('Do you really want to reset the form?')">
<?php
 $N = count($multi_edit_address);
 for($i=0; $i < $N; $i++) {
	$result = mysql_query("SELECT * FROM aqn_master_table WHERE address = '$multi_edit_address[$i]' FOR UPDATE") or die(mysql_error());
	$row = mysql_fetch_array($result);
	$current_date=date('Y-m-d H:i:s');
	mysql_query("UPDATE aqn_master_table SET locked=\"Y\",lock_datetime='$current_date' WHERE address='$multi_edit_address[$i]'")
	 or die(mysql_error());

	// get data from db
	 $row_select = $row['row_name'];
	 $address = $row['address'];
	 $hostname_orig = $row['hostname'];
	 $model_orig = $row['model'];
	 $cab_orig = $row['cab'];
	 $unit_orig = $row['unit'];
	 $comments_orig = $row['comments'];
	if($i % 2) 
		{ 
		print '<tr bgcolor="#EAF2D3">';
		} 
	else 
		{ 
		print '<tr bgcolor="white">';
		}
?>
 <input type="hidden" name="address[]" value="<?php echo $address; ?>"/>
 <input type="hidden" name="row_select[]" value="<?php echo $row_select; ?>"/>
 <td> <?php echo $address; ?></td>
 <td> <input type="text" name="hostname[]" value="<?php echo $hostname_orig; ?>"/></td>
 <td> <input type="text" name="model[]" value="<?php echo $model_orig; ?>"/></td>
 <td> <input type="text" name="cab[]" value="<?php echo $cab_orig; ?>"/></td>
 <td> <input type="text" name="unit[]" value="<?php echo $unit_orig; ?>"/></td>
 <td> <input type="text" name="comments[]" value="<?php echo $comments_orig; ?>"/></td>
 </tr>
<?php
   }
?>
 </table>
 <input type="submit" name="submit" value="Submit">
 <input type="submit" name="cancel" value="Cancel">
 <input type="reset">
 </form>
 </body>
 </center>
 </html>
