<html>
<head>
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
</head>
<body>
<center>
<h1><b><p> IP Database Search Results</b></p></h1>
<?php 
include('connect-db.php');
if(isset($_GET['RowName'])){
$row_select = $_GET['RowName'];} 
if(isset($_POST['RowName'])){
$row_select = $_POST['RowName'];} 
//if(isset($_POST['submit'])){ 
  if(isset($_GET['go'])){ 
	if (isset($_POST['name'])) {
	    if(preg_match("/^[  0-9a-zA-Z]+/", $_POST['name'])){ 
		  $name_=$_POST['name']; }} else {
	    if(preg_match("/^[  0-9a-zA-Z]+/", $_GET['name'])){
		  $name_=$_GET['name']; }}

	$name=trim($name_);
?>
<h2><b><p> Search Results For: <?php echo $name ?></b></p></h2>
<input type="button" name="Return" value="Return to row <?php echo $row_select; ?>" onclick="window.location.href='view.php?RowName=<?php echo $row_select?>'" />
<?php
	  //-query  the database table 
	  $order = 'id';
	  $direction = 'ASC';
	  $sql="SELECT * FROM aqn_master_table WHERE address LIKE '%" . $name .  "%' OR hostname LIKE '%" . $name ."%' OR cab LIKE '%" . $name ."%' OR comments LIKE '%" . $name ."%' ORDER BY $order $direction"; 
	  //-run  the query against the mysql query function 
	  $result=mysql_query($sql); 
	  $count=mysql_num_rows($result);
		echo "<form name=\"form\" method=\"post\" onsubmit=\"return confirm('Are you sure you want to Proceed?')\">";
                echo "<table border='1' cellpadding='10'>";
		echo '<p align="left">' . $count . ' Records returned.</p>';
                echo "<tr>";
		?>
		<th><input type="submit" name="ip_information" value="IP Info" onclick="this.form.action='ip_info.php?search=1&name=<?php echo $name;?>&RowName=<?php echo $row_select;?>'" /></th>
		<?php
		echo "<th>Location</th>";
                echo "<th>Address</th>";
                echo "<th>Hostname</th>";
                echo "<th>Model</th>";
		echo "<th><p align=\"center\">Cabinet</p></th>";
	        echo "<th><p align=\"center\">Unit</p></th>";
                echo "<th>Comments</th>";
		if ($level >= 5) {
		?>
                <th><input type="submit" name="edit_delete" value="Edit" onclick="this.form.action='multi_edit.php';" /></th>
                <th><input type="submit" name="edit_delete" value="Delete" onclick="this.form.action='multi_delete.php';" /></th>
		<?php
		}
                echo "</tr>";
	$rowcount = 0;
	while($row=mysql_fetch_array($result)){
        $rowcount++;
	        if ($rowcount % 2) {
        	        print '<tr bgcolor="#EAF2D3">';
                	           } else {
	                print '<tr bgcolor="white">';
        	                   }
		if ($row['locked'] == "Y") {
                print '<tr bgcolor="red">';
		echo '<td> </td>';
		echo '<td><a href="view.php?RowName=' . $row['row_name'] . '">' . $row['row_name'] . '</td>';
                echo '<td><a href="clear_lock.php?RowName=' . $row['row_name'] . '&address=' . $row['address'] . '" title="Check Time Before Unlocking to &#xA;ensure no one is actually using this record!!!">Locked At:' . $row['lock_datetime'] . '</td>'; } else {
		echo '<td><input type="checkbox" name="ip_info[]" value="' . $row['address'] . '"/></td>';
		echo '<td><a href="view.php?RowName=' . $row['row_name'] . '">' . $row['row_name'] . '</td>';
                echo '<td><a href="history.php?search=1&name=' . $name . '&RowName=' . $row['row_name'] . '&address=' . $row['address'] . '&hostname=' . $row['hostname'] . '">' . $row['address'] . '</td>';
                }
                echo '<td>' . $row['hostname'] . '</td>';
                echo '<td>' . $row['model'] . '</td>';
                echo '<td><p align="center">' . $row['cab'] . '</p></td>';
                echo '<td><p align="center">' . $row['unit'] . '</p></td>';
                echo '<td>' . $row['comments'] . '</td>';
		if ($level >= 5) {
		if ($row['isParentIP'] == "1") {
                echo '<td>Edit</td>';
                } else {
                echo '<td><a href="edit.php?address=' . $row['address'] .'&RowName=' . $row_select .'">Edit</a>
                <input type="checkbox" name="edit[]" value="' . $row['address'] . '"/></td>';
                }
		if (($row['isParentIP'] == "1") || ($row['parentIP'] != "" )) {
                echo '<td>Delete</td>';
                } else {
                echo '<td><a href="delete.php?address=' . $row['address'] .'&RowName=' . $row_select .'&hostname=' . $row['hostname'] .'"
                onClick="return confirm(\'Are you SURE you want to delete this record?\')">Delete</a>
                <input type="checkbox" name="delete[]" value="' . $row['address'] . '"/></td>';
                }
                echo "</tr>";
	  } }
	  	echo "</table>";
	  } 
	  else{ 
	  echo  "<p>Please enter a search query</p>"; 
	  } 
	 //} 
         //} 
	?> 

<input type="button" name="Return" value="Return to row <?php echo $row_select; ?>" onclick="window.location.href='view.php?RowName=<?php echo $row_select;?>'" />

</center>
</body>
</html>

