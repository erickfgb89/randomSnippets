<html>
<head>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="jquery.tablesorter.min.js"></script>
<script type="text/javascript">
                $(document).ready(function() {
                                $("#tablesorter").tablesorter({
                                headers: {0: {sorter: false }, 10: {sorter: false }
                               }
                });
                });
</script>
</head>
<body>
<center>
<?php
include('connect-db.php');
if(isset($_POST['hardware'])){
          $name=$_POST['name'];
	  header("Location: search_hardware.php?name=$name");}

if(isset($_POST['ipsearch'])){
          $name=$_POST['name'];
          header("Location: search_address.php?name=$name");} 

  if(isset($_REQUEST['go'])){
	if(isset($_POST['name'])){
	    if(preg_match("/^[  0-9a-zA-Z]+/", $_POST['name'])){
        	  $name_=$_POST['name'];}}
	if(isset($_GET['name'])){
	    if(preg_match("/^[  0-9a-zA-Z]+/", $_GET['name'])){
        	  $name_=$_GET['name'];}}
$name=trim($name_);
?>
<h1><b><p> Inventory Search Results </b></p></h1>
<?php
 if ($name != '' && $name != '0'){
?>
<h2><b><p> Search Results For: <?php echo $name ?></b></p></h2>
<?php
  $result=mysql_query("SELECT * FROM aqn_inv_master_table WHERE site_name LIKE '%" . $name .  "%' or system_name LIKE '%" . $name .  "%' OR system_usage LIKE '%" . $name ."%' OR system_model LIKE '%" . $name ."%' OR unit_location LIKE '%" . $name ."%' OR system_serial_number LIKE '%" . $name .  "%' OR system_rfid_tag LIKE '%" . $name .  "%' OR system_asset_number LIKE '%" . $name .  "%' OR comments LIKE '%" . $name .  "%'");

        $count=mysql_num_rows($result);
	echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
        echo '<p align="left">' . $count . ' Records returned.</p>';
        echo "<thead>\n<tr>";
	if ($level >= 5) {
        echo "<th></th>";
	}
        echo "<th>System Name</th>";
	echo "<th>IP Address</th>";
        echo "<th>Usage</th>";
        echo "<th>Model</th>";
        echo "<th>Location</th>";
        echo "<th>Serial Number</th>";
        echo "<th>RFID Tag</th>";
        echo "<th>Asset Number</th>";
        echo "<th>Comments</th>";
	if ($level >= 5) {
        echo "<th></th>";
	}
        echo "</tr>\n</thead>\n";
        // loop through results of database query, displaying them in the table
        $rowcount = 0;
        while($row = mysql_fetch_array( $result )) {
	
	$rowcount++;
	$location_id = $row['data_location_id'];
	$location = $row['data_location_id'];
	$data_location_id = explode("-", $location_id);
	$row_name = $data_location_id[0];
	$cab_name = $data_location_id[1];
	$asset_location = $row_name . "-" . $cab_name;
	$location_result = mysql_query("SELECT * FROM cab_definition,row_definition WHERE cab_definition.cab_name = '$cab_name' AND row_definition.row_name = '$row_name'") or die(mysql_error());
	$location_row = mysql_fetch_array($location_result);
        $system_name = $row['system_name'];
        $result_ip_address = mysql_query("SELECT address,isParentIP FROM lab_ip_data.aqn_master_table WHERE hostname = '$system_name'") or die(mysql_error());
        $get_ip_address = mysql_fetch_array($result_ip_address);
        $ip_address =  $get_ip_address['address'];
	$isParentIP = $get_ip_address['isParentIP'];
	if ($ip_address == '' || $ip_address == 'NULL') {
	//$ip_address = 'Add/Edit';
	$ip_address = 'No Address';
	$no_ip = 1;
	} else { $no_ip = 0; }
        if ($system_name == '' || $system_name == 'NULL') {
	$no_ip = 1;
        $ip_address = '';
        }
	$visible_row_name = $location_row['visible_row_name'];
	$visible_cab_name = $location_row['visible_cab_name'];
        if ($rowcount % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
                // echo out the contents of each row into a table
		if ($level >= 5) {
		if ($row['system_usage'] == 'Retired') {
		echo '<td><a href="delete_record.php?serial_number=' . $row['system_serial_number'] . '&id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&sitename=' . $row['site_name'] . '" 
                onClick="return confirm(\'Are you SURE you want to delete this record?\')">Delete</a></td>'; } else {
		if ($isParentIP == 1) {
                echo '<td>Retire</td>'; } else {
                echo '<td><a href="delete_record.php?serial_number=' . $row['system_serial_number'] . '&id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&sitename=' . $row['site_name'] . '" 
                onClick="return confirm(\'Are you SURE you want to retire this record?\')">Retire</a></td>'; } }
		}
		if ($no_ip == 0 && $level >= 5) {
                echo '<td><a href="deploy_control.php?serial_number=' . $row['system_serial_number'] . '&row_name=' . $row_name . '&cab_name=' . $cab_name . '&sitename=' . $row['site_name'] . '&system_name=' . $system_name . '">' . $system_name . '<a href="search_hardware.php?name=' . $system_name .'&return=search&searchname=' . $name . '">  [H]</td>'; } else {
                echo '<td>' . $system_name . '</td>'; } 
		if ($level >= 5) {
                if ($no_ip == '1') {
                echo '<td><a href="ip_add.php?id=' . $row['id'] .'&system_name=' . $system_name . '&row_name=' . $row_name . '&cab_name=' . $cab_name . '&unit=' . $row['unit_location'] . '&model=' . $row['system_model'] . '&return=search&searchname=' . $name . '">' . $ip_address . '</td>'; } else {
			if ($isParentIP == 1) {
                echo '<td><a href="manage_ip.php?id=' . $row['id'] .'&orig_ip_address=' . $ip_address . '&system_name=' . $system_name . '&row_name=' . $row_name . '&cab_name=' . $cab_name . '&return=search&searchname=' . $name . '">' . $ip_address . '[*]</td>'; } else{
		echo '<td><a href="manage_ip.php?id=' . $row['id'] .'&orig_ip_address=' . $ip_address . '&system_name=' . $system_name . '&row_name=' . $row_name . '&cab_name=' . $cab_name . '&return=search&searchname=' . $name . '">' . $ip_address . '</td>'; } }
		                } else {
                echo '<td>' . $ip_address . '</td>'; }
                echo '<td>' . $row['system_usage'] . '</td>';
                echo '<td>' . $row['system_model'] . '</td>';
                echo '<td>' . $visible_row_name . "" . $visible_cab_name . " " . $row['unit_location'] . '</td>';
                echo '<td><a href="history.php?search=1&name=' . $name . '&serial_number=' . $row['system_serial_number'] . '&row_name=' . $row_name . '&cab_name=' . $cab_name . '&sitename=' . $row['site_name'] . '">' . $row['system_serial_number'] . '</td>';
                echo '<td>' . $row['system_rfid_tag'] . '</td>';
                echo '<td>' . $row['system_asset_number'] . '</td>';
                echo '<td>' . $row['comments'] . '</td>';
		if ($level >= 5) {
		if ($isParentIP == 1) {
                echo '<td><a href="edit.php?isParentIP=1&id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&location=' . $asset_location . '&sitename=' . $row['site_name'] . '">Edit</a></td>'; } else {
                if ($no_ip == 1 ) {
                echo '<td><a href="edit.php?no_ip&id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&location=' . $asset_location . '&sitename=' . $row['site_name'] . '">Edit</a></td>'; } else {
                echo '<td><a href="edit.php?id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&location=' . $asset_location . '&sitename=' . $row['site_name'] . '">Edit</a></td>'; }
                }}
                echo "</tr>";
        }
        // close table
        echo "</table>";
} else {
?>
<h1><b><p> Enter Something to Search </b></p></h1>
<?php
}
}
?>
</center>
</body>
</html>

