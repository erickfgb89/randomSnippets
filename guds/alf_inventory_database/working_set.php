<html>
<head>
<?php
$site_name = $_GET['sitename'];
$site_name_upper = strtoupper($site_name);
?>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />

<link rel="stylesheet" href="jquery-ui-1.10.3.custom/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" />
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="jquery.tablesorter.min.js"></script>
<script src="jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="site.js"></script>
<script>setTooltipListener("td");</script>
<script type="text/javascript">
                $(document).ready(function() {
                                $("#tablesorter").tablesorter({
                                headers: { 0: {sorter: false }, 10: {sorter: false }
                               }
                });
                });
</script>
<style>
.ui-tooltip{ max-width: 100%; }
</style>
</head>
<body>
<center>
<?php
$row_name = $_GET['rowname'];
$cab_name = $_GET['cabname'];
?>
<h1><b><p> <?php echo $site_name_upper ?> Inventory Database </b></p></h1>

<?php

if ($row_name == 'powercan'){
header("Location: view_pc_inv.php?sitename=$site_name");
}
if ($row_name != '' && $row_name != '0' && $site_name != '' && $site_name != '0') {
	if ($row_name == 'Retired'){
	$location = 'Retired';
	$no_control = 1; } elseif 
			($cab_name == '' || $cab_name == '0'){
			$location = $row_name . "%";
			$no_control = 1; } else {
	$location = $row_name . "-" . $cab_name;
	$no_control = 0; }
include('connect-db.php');
if ($cab_name == '' || $cab_name == '0'){
$result = mysql_query("SELECT * FROM row_definition WHERE row_name = '$row_name'") or die(mysql_error());
$row = mysql_fetch_array($result);
$visible_row_name = $row['visible_row_name'];
$visible_cab_name = 'All';
} else {
$result = mysql_query("SELECT * FROM cab_definition,row_definition WHERE cab_definition.cab_name = '$cab_name' AND row_definition.row_name = '$row_name'") or die(mysql_error());
$row = mysql_fetch_array($result);
$visible_row_name = $row['visible_row_name'];
$visible_cab_name = $row['visible_cab_name'];}

if ($no_control != 1){
	// $result_pc = mysql_query("select *,inv.id as invid from powercan_inv_master_table as inv join powercan_definition as def on inv.powercan_type = def.powercan_type where inv.data_location_id like '$location' AND inv_site_name = '$site_name'") or die(mysql_error());

	$result_pc = mysql_query("SELECT * FROM powercan_inv_master_table WHERE data_location_id like '$location' AND inv_site_name = '$site_name'") or die(mysql_error());

	echo "<form name=\"form\" method=\"post\" onsubmit=\"return confirm('Are you sure you want to Edit/Add to this cabinet?')\">";
	echo "<table class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
	if ($level >= 5) {
	echo '<th></th>';
	}
        echo "<th>Power Can Type</th>";
        echo "<th>Number Present</th>";
	echo "</tr>\n</thead>\n";
        $rowcount_pc = 0;
        while($row_pc = mysql_fetch_array( $result_pc )) {
	$powercan_type = $row_pc['powercan_type'];
	$result_pc_desc = mysql_query("SELECT powercan_description FROM powercan_definition WHERE powercan_type = '$powercan_type' AND site_name = '$site_name'") or die(mysql_error());
	$row_pc_desc = mysql_fetch_array( $result_pc_desc );
	$powercan_description = $row_pc_desc['powercan_description'];
	$rowcount_pc++;
        if ($rowcount_pc % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
		if ($level >= 5) {
	        echo '<td><a href="delete_pc_record.php?id=' . $row_pc['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&sitename=' . $site_name . '" 
                onClick="return confirm(\'Are you SURE you want to delete this record?\')">Delete</a></td>';
		}
		echo '<td fname="' .$row_pc['powercan_type']. '" desc="' .$powercan_description. '">' . $row_pc['powercan_type'] . '</td>';
                echo '<td>' . $row_pc['count'] . '</td>';
		echo "</tr>";
		        }
	echo "</table>";
if ($level >= 5) {
?>
<input type="submit" name="add_pc" value="Add Power Can" onclick="this.form.action='add_pc_cab.php?rowname=<?php echo $row_name;?>&cabname=<?php echo $cab_name;?>&sitename=<?php echo $site_name;?>';" />
</form>
<?php
}
	echo "<p> </p>";
}
	echo '<b>Viewing: Site ' . $site_name . ' Row ' . $visible_row_name . ' Cab ' . $visible_cab_name . '</b>'; 

        // get results from AQN INV MAster database
        $result = mysql_query("SELECT * FROM aqn_inv_master_table WHERE data_location_id like '$location' AND site_name = '$site_name' ORDER BY unit_location ASC") 
	or die(mysql_error());

        // display data in table

        echo "<form name=\"form\" method=\"post\" onsubmit=\"return confirm('Are you sure you want to Edit/Add to this cabinet?')\">";
        echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
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
        $location_id = $row['data_location_id'];
        $data_location_id = explode("-", $location_id);
        $row_name = $data_location_id[0];
        $cab_name = $data_location_id[1];
	$result_location = mysql_query("SELECT * FROM cab_definition,row_definition WHERE cab_definition.cab_name = '$cab_name' AND row_definition.row_name = '$row_name'") or die(mysql_error());
	$system_name = $row['system_name'];
	$result_ip_address = mysql_query("SELECT address,isParentIP FROM lab_ip_data.aqn_master_table WHERE hostname = '$system_name'") or die(mysql_error());
	$get_ip_address = mysql_fetch_array($result_ip_address);
	$ip_address =  $get_ip_address['address'];
	$isParentIP = $get_ip_address['isParentIP'];
        if ($ip_address == '' || $ip_address == 'NULL') {
	// $ip_address = 'Add/Edit';
	$ip_address = 'No Address';
	$no_ip = 1;
        } else { $no_ip = 0; }
	if ($system_name == '' || $system_name == 'NULL') {	
	$no_ip = 1;
	$ip_address = '';
	}
	$row_location = mysql_fetch_array($result_location);
	$visible_row_name = $row_location['visible_row_name'];
	$visible_cab_name = $row_location['visible_cab_name'];
	$asset_location = $row_name . "-" . $cab_name;
        $rowcount++;
        if ($rowcount % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
                // echo out the contents of each row into a table
		if ($level >= 5) {
		if ($row['system_usage'] == 'Retired') {
		echo '<td><a href="delete_record.php?serial_number=' . $row['system_serial_number'] . '&id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&sitename=' . $site_name . '" 
		onClick="return confirm(\'Are you SURE you want to delete this record?\')">Delete</a></td>'; } else {
		if ($isParentIP == 1) {
		echo '<td>Retire</td>'; } else {
		echo '<td><a href="delete_record.php?serial_number=' . $row['system_serial_number'] . '&id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&sitename=' . $site_name . '" onClick="return confirm(\'Are you SURE you want to retire this record?\')">Retire</a></td>'; } }
		}
		if ($no_ip == 0 && $level >= 5) {
                echo '<td><a href="deploy_control.php?serial_number=' . $row['system_serial_number'] . '&row_name=' . $row_name . '&cab_name=' . $cab_name . '&sitename=' . $site_name . '&system_name=' . $system_name . '"> ' . $system_name . '<a href="search_hardware.php?name=' . $system_name .'&return=workingset&row_name=' . $row_name . '&cab_name=' . $cab_name . '&sitename=' . $site_name . '">  [H]</td>'; } else {
		echo '<td>' . $system_name . '</td>';} 		
		if ($level >= 5) {
		if ($no_ip == '1') {
		echo '<td><a href="ip_add.php?id=' . $row['id'] .'&system_name=' . $system_name . '&return=workingset&row_name=' . $row_name . '&cab_name=' . $cab_name . '&unit=' . $row['unit_location'] . '&model=' . $row['system_model'] . '&sitename=' . $site_name . '">' . $ip_address . '</td>'; } else {
		if ($isParentIP == 1) {
		echo '<td><a href="manage_ip.php?id=' . $row['id'] .'&orig_ip_address=' . $ip_address . '&system_name=' . $system_name . '&return=workingset&row_name=' . $row_name . '&cab_name=' . $cab_name . '&sitename=' . $site_name . '">' . $ip_address . '[*]</td>'; } else { 
		echo '<td><a href="manage_ip.php?id=' . $row['id'] .'&orig_ip_address=' . $ip_address . '&system_name=' . $system_name . '&return=workingset&row_name=' . $row_name . '&cab_name=' . $cab_name . '&sitename=' . $site_name . '">' . $ip_address . '</td>'; } }
		} else {
		echo '<td>' . $ip_address . '</td>'; }
                echo '<td>' . $row['system_usage'] . '</td>';
                echo '<td>' . $row['system_model'] . '</td>';
                echo '<td>' . $visible_row_name . "" . $visible_cab_name . " " . $row['unit_location'] . '</td>';
                echo '<td><a href="history.php?serial_number=' . $row['system_serial_number'] . '&row_name=' . $row_name . '&cab_name=' . $cab_name . '&sitename=' . $site_name . '">' . $row['system_serial_number'] . '</td>';
                echo '<td>' . $row['system_rfid_tag'] . '</td>';
		echo '<td>' . $row['system_asset_number'] . '</td>';
		echo '<td>' . $row['comments'] . '</td>';
		if ($level >= 5) {
		if ($isParentIP == 1) {
		echo '<td><a href="edit.php?isParentIP=1&id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&location=' . $asset_location . '&sitename=' . $site_name . '">Edit</a></td>'; } else {
		if ($no_ip == 1 ) {
		echo '<td><a href="edit.php?no_ip&id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&location=' . $asset_location . '&sitename=' . $site_name . '">Edit</a></td>'; } else {
		echo '<td><a href="edit.php?id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&location=' . $asset_location . '&sitename=' . $site_name . '">Edit</a></td>'; } 
		}}
		
	        echo "</tr>";
        }
        // close table
        echo "</table>";

if ($no_control != 1 && $level >= 5){
?>
<input type="submit" name="edit" value="Edit Cabinet" onclick="this.form.action='edit_cab.php?rowname=<?php echo $row_name;?>&cabname=<?php echo $cab_name;?>&sitename=<?php echo $site_name;?>';" />
<input type="submit" name="add_asset" value="Add An Asset" onclick="this.form.action='add_asset_cab.php?rowname=<?php echo $row_name;?>&cabname=<?php echo $cab_name;?>&sitename=<?php echo $site_name;?>';"/>
</form>
<form action="export_excel.php?location=<?php echo $location ?>&site=<?php echo $site_name ?>" target="working_set" method="post">
      <input type="submit" name="submit" value="Export to Excel">
</form>
<?php
 } else {
?>
</form>
<form action="export_excel.php?location=<?php echo $location ?>&site=<?php echo $site_name ?>" target="working_set" method="post">
      <input type="submit" name="submit" value="Export to Excel">
</form>
<?php 
} } else {
?>
<h1><b><p> Please select a Site, Row, and Cab to look at. </b></p></h1>
<?php
}
?>
</center>
</body>
</html>

