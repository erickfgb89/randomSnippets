<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
//header('Refresh: 120');
$remote_user = $_SERVER['REMOTE_USER'];
?>

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

        <title>ALF IP Database</title>
</head>
<body>
<center>
<h1><b><p> ALF IP Database </b></p></h1>
<h2><b><p> Welcome <?php echo $remote_user; ?></b></p></h2>
<?php
include('connect-db.php');
$sql = "SELECT * FROM row_definition";
$result = mysql_query($sql);
$options = "";

     while ($row = mysql_fetch_array($result)) {
     $visible_row_name = $row["visible_row_name"];
     $RowName = $row["row_name"];
     $options .= "<option value=\"$RowName\">".$visible_row_name.'</option>';
}
?>
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
	<select name = RowName>
	<option value=0>choose a row <?php echo $options ?> </select>
	<input type="submit" name="submit" value="Submit" />
<?php
if ($level >= 5) {
?>
	<input type="submit" name="submit_blank_row" value="Show Empties" />
<?php
}
?>

</form>

<?php 

if (isset($_POST['submit'])){ 
$row_select=$_POST['RowName'];
$mysql_query_type="SELECT * FROM aqn_master_table WHERE row_name = '$row_select'"; }

elseif (isset($_POST['submit_blank_row'])) {
$row_select=$_POST['RowName'];
$mysql_query_type="SELECT * FROM aqn_master_table WHERE row_name = '$row_select' AND (hostname = '' OR hostname IS NULL) AND (model = '' OR model IS NULL) AND (cab = '' OR cab IS NULL) AND (unit = '' OR unit IS NULL) AND (comments = '' OR comments IS NULL)"; }

elseif (isset($_GET['RowName'])) {
$row_select = $_GET['RowName']; 
$mysql_query_type="SELECT * FROM aqn_master_table WHERE row_name = '$row_select'";
} else {
$row_select = ''; 
$mysql_query_type="SELECT * FROM aqn_master_table WHERE row_name = '$row_select'";
}
if ($row_select == 'vlan') {
echo "<meta http-equiv=\"refresh\" content=\"0;URL=show_vlan.php\">"; }

$sql = "SELECT * FROM row_definition WHERE row_name = '$row_select'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
        $mysql_query_type_count_active = "SELECT * FROM aqn_master_table WHERE row_name = '$row_select' AND hostname != ''";
        $mysql_query_type_count_inactive = "SELECT * FROM aqn_master_table WHERE row_name = '$row_select' AND (hostname = '' OR hostname IS NULL)";
	$mysql_query_type_count_dhcp = "SELECT * FROM aqn_master_table WHERE row_name = '$row_select' AND hostname = 'DHCP'";
        $count_result_active = mysql_query($mysql_query_type_count_active) or die(mysql_error());
        $count_result_inactive = mysql_query($mysql_query_type_count_inactive) or die(mysql_error());
	$count_result_dhcp = mysql_query($mysql_query_type_count_dhcp) or die(mysql_error());
        $count_active = mysql_num_rows($count_result_active);
        $count_inactive = mysql_num_rows($count_result_inactive);
	$count_dhcp = mysql_num_rows($count_result_dhcp);
	
        $total_num_ips = $count_active + $count_inactive;
        $total_num_used = $total_num_ips - $count_active;
        $total_num_free = $total_num_ips - $count_inactive;
	if ($total_num_free > 0 && $total_num_ips > 0) {
        $_free = $total_num_free / $total_num_ips; }
	if ($total_num_used > 0 && $total_num_ips > 0) {
        $_used = $total_num_used / $total_num_ips; }
        $percent_free = $_free * 100;
        $percent_used = $_used * 100;
        $format_free = number_format($percent_free, 0);
        $format_used = number_format($percent_used, 0);

	echo '<p> </p>';
	echo '<b>Viewing Row: ' . $row['visible_row_name'] . '</b>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Gateway Address: ' . $row['gateway_address'] . " (Vlan: " . $row['vlan'] . ")" . ' </b>';

	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Netmask Address: ' . $row['netmask_address'] . ' </b>';
	if (!isset($_POST['submit_blank_row'])){
	echo '<p><tr align="left"><b>' .$total_num_ips . ' Total Addresses (' . $count_dhcp . ' Used for DHCP) - Resource Pool stats: ' . $count_active . ' Active (' . $format_free . '% in use) and ' . $count_inactive . ' Empty (' . $format_used . '% avail) </tr></p></b>' ;}
?>


<form  method="post" action="search.php?go&RowName=<?php echo $row_select?>">
      <input  type="text" name="name">
      <input name="" type="t" value="" style="display:none">
      <input  type="submit" name="submit" value="Search Name/IP/Cabinet">
</form>
<?php
	if (isset($_GET['order'])) {
	$order = $_GET['order'];} else {
	$order = 'id'; }
	$direction = 'ASC';
	if (isset($_GET['dir'])) { 
		$dir = $_GET['dir'];
			if ($dir == 'up') {
			$direction = 'ASC';} elseif
			($dir == 'down') {
			$direction = 'DESC';} 
			} 
        // get results from database
	$mysql_query_type=$mysql_query_type.' ORDER BY '.$order.' '.$direction;
        $result = mysql_query($mysql_query_type) or die(mysql_error());
	
        // display data in table
	if ($row_select != '' && $row_select != "0") { 
	echo "<form name=\"form\" method=\"post\" onsubmit=\"return confirm('Are you sure you want to Proceed?')\">";
        echo "<table border='1' cellpadding='10'>";
	echo "<thead>\n<tr>";
	echo "<tr>";
	echo "<th>"; 
	?>
	<input type="submit" name="ip_information" value="IP Info" onclick="this.form.action='ip_info.php?RowName=<?php echo $row_select;?>';">
	<?php
	echo "</th>";
	if ($direction == 'ASC') {
	echo "<th><a href=view.php?RowName=$row_select&order=id&dir=down>Address</a></th>";
	echo "<th><a href=view.php?RowName=$row_select&order=hostname&dir=down>Hostname</a></th>";
	echo "<th><a href=view.php?RowName=$row_select&order=model&dir=down>Model</a></th>";
	echo "<th><a href=view.php?RowName=$row_select&order=cab&dir=down><p align=\"center\">Cabinet</p></a></th>";
	echo "<th><a href=view.php?RowName=$row_select&order=unit&dir=down><p align=\"center\">Unit</p></a></th>";
	} else {
	echo "<th><a href=view.php?RowName=$row_select&order=id&dir=up>Address</a></th>";
	echo "<th><a href=view.php?RowName=$row_select&order=hostname&dir=up>Hostname</a></th>";
	echo "<th><a href=view.php?RowName=$row_select&order=model&dir=up>Model</a></th>";
	echo "<th><a href=view.php?RowName=$row_select&order=cab&dir=up><p align=\"center\">Cabinet</p></a></th>";
	echo "<th><a href=view.php?RowName=$row_select&order=unit&dir=up><p align=\"center\">Unit</p></a></th>";}
	echo "<th>Comments</th>"; 
	if ($level >= 5) {
	echo "<th><input type=\"submit\" name=\"edit_delete\" value=\"Edit\" onclick=\"this.form.action='multi_edit.php';\" /></th>";
        echo "<th><input type=\"submit\" name=\"edit_delete\" value=\"Delete\" onclick=\"this.form.action='multi_delete.php';\" /></th>";
	}
	echo "</tr>\n</thead>\n";

        // loop through results of database query, displaying them in the table
	$rowcount = 0;
        while($row = mysql_fetch_array( $result )) {
	$rowcount++;               
	if ($rowcount % 2) {
		print '<tr bgcolor="#EAF2D3">';
			   } else {
		print '<tr bgcolor="white">';
			   }
                // echo out the contents of each row into a table
		if ($row['locked'] == "Y") {
		print '<tr bgcolor="red">';
		echo '<td> </td>';
		echo '<td><a href="clear_lock.php?RowName=' . $row['row_name'] . '&address=' . $row['address'] . '" title="Check Time Before Unlocking to &#xA;ensure no one is actually using this record!!!">Locked At:' . $row['lock_datetime'] . '</td>'; } else {
		echo '<td><input type="checkbox" name="ip_info[]" value="' . $row['address'] . '"/></td>';
                echo '<td><a href="history.php?RowName=' . $row['row_name'] . '&address=' . $row['address'] . '&hostname=' . $row['hostname'] . '">' . $row['address'] . '</td>';
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
		}}
                echo "</tr>"; 
        } 

        // close table/form
	echo "</form>";
        echo "</table>"; } else {
	echo '<h1><b><p> Please select a row or enter a search item </b></p></h1>';
	}

?>
</center>
</body>
</html> 
