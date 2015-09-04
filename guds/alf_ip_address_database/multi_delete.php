<?php
 include('connect-db.php');
 $multi_delete_address = $_POST['delete'];
 if(empty($multi_delete_address))
 {
 ?>
    <script type="text/javascript">
    window.history.go(-1);
    </script>
<?php
 }

 $N = count($multi_delete_address);
 for($i=0; $i < $N; $i++) {
        $result = mysql_query("SELECT * FROM aqn_master_table WHERE address = '$multi_delete_address[$i]' FOR UPDATE") or die(mysql_error());
        $row = mysql_fetch_array($result);
        // get data from db
	 $row_select = $row['row_name'];
	 $address = $row['address'];
         $hostname = $row['hostname'];
         $model = $row['model'];
         $cab = $row['cab'];
         $unit = $row['unit'];
         $comments = $row['comments'];

 $result = mysql_query("UPDATE aqn_master_table SET hostname = '', model = '', cab = '', unit = '', comments = '' WHERE address = '$address'") or die(mysql_error());

 // Update changes_made table
 $remote_host = $_SERVER['REMOTE_HOST'];
 $changes_made = "Deleted -- $hostname -- $model -- $cab -- $unit -- $comments --";
 $current_date=date('Y-m-d H:i:s');
 $result = mysql_query("INSERT INTO changes_made (address, remote_hostname, changes_made, date_time) VALUES ('$address','$remote_host','$changes_made','$current_date')")  or die(mysql_error());

 }

header("Location: view.php?RowName=$row_select");

?>
