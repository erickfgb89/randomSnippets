<html>
<head>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
</head>
<body>
<center>
<?php
?>
<h1><b><p> G.U.D.S. Deployment Jobs Running </b></p></h1>

<?php
	include('connect-db.php');
        $result_status = mysql_query("SELECT * FROM deploy_system_status");
?>
        <meta http-equiv="refresh" content="20">
<?php
        echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
	echo "<th>Hostname</th>";
        echo "<th>Deploy Start</th>";
        echo "<th>Percent Completed</th>";
	echo "<th>Operating System</th>";
	echo "<th>SPP Selected</th>";
        echo "<th>What its doing</th>";
        echo "<th>Cancel The Operation</th>";
        echo "</tr>\n</thead>\n";
      
	$rowcount = 0;
        while($row_status = mysql_fetch_array( $result_status )) {
        $percent_complete = $row_status['percent_complete'];
        $script_location = $row_status['script_location'];
        $deploy_start_time = $row_status['deploy_start_time'];
        $system_name = $row_status['system_name'];      
	$os_name = $row_status['os_name'];
	$spp_option = $row_status['spp_option'];
        $serial_number = $row_status['system_serial_number'];
	$result_os_name = mysql_query("SELECT * FROM `deploy_os_types` WHERE os_name = '$os_name'") or die(mysql_error());
        $row_os_name = mysql_fetch_array($result_os_name);
        $visible_os_name = $row_os_name['os_display_name'];
        $rowcount++;
        if ($rowcount % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
	echo '<td><a href="search.php?go&name=' . $system_name . '">' . $system_name . '</td>';
        echo '<td>' . $deploy_start_time . '</td>';
	if (is_numeric($percent_complete)){
        echo '<td>' . $percent_complete . '%</td>'; } else {
	echo '<td>' . $percent_complete . '</td>'; }
	echo '<td>' . $visible_os_name . '</td>';
	echo '<td>' . $spp_option . '</td>';
        echo '<td>' . $script_location . '</td>';
        if ($percent_complete == '99') {
        echo '<td><a href="global_deploy_cancel_progress.php?system_name=' . $system_name . '&serial_number=' . $serial_number .'">Reset</a></td>';
        } else {
        echo '<td><a href="global_deploy_cancel_progress.php?system_name=' . $system_name . '&serial_number=' . $serial_number .'">Cancel</a></td>';
        }
        echo "</tr>";
	}
        echo "</table>";
?>
</center>
</body>
</html>


