<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="jquery.tablesorter.min.js"></script>
<script type="text/javascript">
                $(document).ready(function() {
                                $("#tablesorter").tablesorter({
                                headers: { 9: {sorter: false }
                               }
                });
                });
</script>
</head>
<body>
<center>
<h1><b><p> Hardware Search Results </b></p></h1>
<?php
include('connect-db.php');

if(isset($_GET['return'])){
    $return=$_GET['return'];
    	if ($return == 'workingset'){
		$row_name = $_GET['row_name'];
		$cab_name = $_GET['cab_name'];
		$site_name = $_GET['sitename'];
	} else {
		$searchname = $_GET['searchname'];
		}
}
if(preg_match("/^[  0-9a-zA-Z]+/", $_GET['name'])){
          $name_=$_GET['name'];}
        $name=trim($name_);

if ($name != '' && $name != '0'){
?>
<h2><b><p> Search Results For: <?php echo $name ?></b></p></h2>
<?php
  $result=mysql_query("SELECT * FROM aqn_inv_master_table WHERE cpu_desc LIKE '%" . $name .  "%' OR mem_info LIKE '%" . $name .  "%' OR scsi_controller LIKE '%" . $name .  "%' OR nic_info LIKE '%" . $name .  "%' OR system_serial_number LIKE '%" . $name .  "%' OR system_name LIKE '%" . $name .  "%' AND (cpu_desc IS NOT NULL and cpu_desc != '')");

        $count=mysql_num_rows($result);
        echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
        echo '<p align="left">' . $count . ' Records returned.</p>';
        echo "<thead>\n<tr>";
        echo "<th>System Name</th>";
        echo "<th>Model</th>";
        echo "<th>Serial Number</th>";
	echo "<th>CPU Description</th>";
	echo "<th>Total Physical CPUs</th>";
	echo "<th>Total Logical Cores</th>";
	echo "<th>Memory Info</th>";
	echo "<th>Disk Controller Info</th>";
	echo "<th>Network Controller Info</th>";
	echo "<th>Hardware Updated</th>";
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
		if ($level >= 5) {
                echo '<td><a href="deploy_control.php?serial_number=' . $row['system_serial_number'] . '&row_name=' . $row_name . '&cab_name=' . $cab_name . '&sitename=' . $row['site_name'] . '&system_name=' . $row['system_name'] . '">' . $row['system_name'] . '</td>'; } else {
		echo '<td>' . $row['system_name'] . '</td>';
		}
                echo '<td>' . $row['system_model'] . '</td>';
                echo '<td><a href="history.php?search=1&name=' . $name . '&serial_number=' . $row['system_serial_number'] . '&row_name=' . $row_name . '&cab_name=' . $cab_name . '&sitename=' . $row['site_name'] . '">' . $row['system_serial_number'] . '</td>';
		echo '<td>' . $row['cpu_desc'] . '</td>';
		echo '<td>' . $row['cpu_phy_num'] . ' w/ ' . $row['cpu_logical_num'] . ' Cores' . '</td>';
		echo '<td>' . $row['cpu_logical_num'] * $row['cpu_phy_num'] . '</td>';
		echo '<td><textarea cols="35" rows="5" style="background-color: transparent;" readonly>' . $row['mem_info'] . '</textarea></td>';
		echo '<td><textarea cols="50" rows="5" style="background-color: transparent;" readonly>' . $row['scsi_controller'] . '</textarea></td>';
		echo '<td><textarea cols="50" rows="5" style="background-color: transparent;" readonly>' . $row['nic_info'] . '</textarea></td>';
		echo '<td>' . $row['hardware_update_time'] . '</td>';
                echo "</tr>";
        }
        // close table
        echo "</table>";

if ($return == 'workingset'){
?>
<form method="post">
<input type="submit" name="return" value="Return" onclick="this.form.action='working_set.php?rowname=<?php echo $row_name ?>&cabname=<?php echo $cab_name ?>&sitename=<?php echo $site_name ?>'"/>
</form>
<?php
	}	
if ($return == 'search'){
?>
<form method="post">
<input type="submit" name="return" value="Return" onclick="this.form.action='search.php?go&name=<?php echo $searchname ?>'"/>
</form>
<?php
	}
} else {
?>
<h1><b><p> Enter Something to Search </b></p></h1>
<?php
}
?>
</center>
</body>
</html>

