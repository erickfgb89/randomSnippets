<html>
<head>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="jquery.tablesorter.min.js"></script>
<script type="text/javascript">
                $(document).ready(function() {
                                $("#tablesorter").tablesorter({
                                headers: { 0: {sorter: false }, 10: {sorter: false }
                               }
                });
                });
</script>
</head>
<body>
<center>
<h1><b><p> AQN Inventory Duplicate Search Results </b></p></h1>
<b><p>Please Wait... This will take a while...</b></p>
<?php
include('connect-db.php');
//$sql="SELECT * FROM aqn_inv_master_table WHERE system_serial_number IN ( SELECT system_serial_number FROM aqn_inv_master_table WHERE system_serial_number !='' GROUP BY system_serial_number HAVING count(system_serial_number) > 1 ) ORDER BY system_serial_number DESC";
$sql="select i.* from aqn_inv_master_table i right join (select system_serial_number from aqn_inv_master_table WHERE system_serial_number !='' group by system_serial_number having count(*)>1) s on s.system_serial_number = i.system_serial_number ORDER BY system_serial_number DESC";

          $result=mysql_query($sql);
          $count=mysql_num_rows($result);

	echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
        echo '<p align="left">' . $count . ' Records returned.</p>';
        echo "<thead>\n<tr>";
        echo "<th></th>";
        echo "<th>System Name</th>";
        echo "<th>Usage</th>";
        echo "<th>Model</th>";
        echo "<th>Location</th>";
        echo "<th>Serial Number</th>";
        echo "<th>RFID Tag</th>";
        echo "<th>Asset Number</th>";
        echo "<th>Comments</th>";
        echo "<th></th>";
        echo "</tr>\n</thead>\n";
        // loop through results of database query, displaying them in the table
        $rowcount = 0;
        while($row = mysql_fetch_array( $result )) {
	
	$rowcount++;
	$location_id = $row['data_location_id'];
	$data_location_id = explode("-", $location_id);
	$row_name = $data_location_id[0];
	$cab_name = $data_location_id[1];
	$location_result = mysql_query("SELECT * FROM cab_definition,row_definition WHERE cab_definition.cab_name = '$cab_name' AND row_definition.row_name = '$row_name'") or die(mysql_error());
	$location_row = mysql_fetch_array($location_result);
	$visible_row_name = $location_row['visible_row_name'];
	$visible_cab_name = $location_row['visible_cab_name'];
        if ($rowcount % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
                // echo out the contents of each row into a table
                echo '<td><a href="delete_dup_record.php?id=' . $row['id'] .'"
		onClick="return confirm(\'Are you SURE you want to delete this record?\')">Delete</a></td>'; 
                echo '<td>' . $row['system_name'] . '</td>';
                echo '<td>' . $row['system_usage'] . '</td>';
                echo '<td>' . $row['system_model'] . '</td>';
                echo '<td>' . $visible_row_name . "" . $visible_cab_name . " " . $row['unit_location'] . '</td>';
                echo '<td>' . $row['system_serial_number'] . '</td>';
                echo '<td>' . $row['system_rfid_tag'] . '</td>';
                echo '<td>' . $row['system_asset_number'] . '</td>';
                echo '<td>' . $row['comments'] . '</td>';
                echo '<td><a href="edit.php?search=2&id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&sitename=' . $row['site_name'] . '">Edit</a></td>';
                echo "</tr>";
        }
        // close table
        echo "</table>";
?>

