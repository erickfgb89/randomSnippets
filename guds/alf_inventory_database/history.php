<html>
<head>

<?php
$site_name = $_GET['sitename'];
$site_name_upper = strtoupper($site_name);
?>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
</head>
<body>
<center>

<?php
include('connect-db.php');
$row_name = $_GET['row_name'];
$cab_name = $_GET['cab_name'];
if(isset($_GET['search'])){
        $search=$_GET['search'];
        $name=$_GET['name'];}
$serial_number=$_GET['serial_number'];
?>
<h1><b><p> <?php echo $site_name;?> Inventory Database History For <?php echo $serial_number;?></b></p></h1>
<?php

$result = mysql_query("SELECT * FROM `changes_made_inv` WHERE system_serial_number = '$serial_number'")
	or die(mysql_error());

if ($search == 1){
?>
<input type="button" name="Return" value="Return" onclick="window.location.href='search.php?go&name=<?php echo $name?>'" />
<?php
} else {
?>
<input type="button" name="Return" value="Return" onclick="window.location.href='working_set.php?rowname=<?php echo $row_name?>&cabname=<?php echo $cab_name?>&sitename=<?php echo $site_name?>'" />

<?php
}

	echo '<p> </p>';
	echo "<table border='0' cellpadding='10'>";
	echo "<table class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
        echo "<tr>";
        echo "<th>Serial Number</th>";
        echo "<th>Remote Connection</th>";
        echo "<th>Changes Made</th>";
        echo "<th>Date/Time</th>";
        echo "</tr>\n</thead>\n";

	$rowcount = 0;
	while($row = mysql_fetch_array( $result )) {
        $rowcount++;
        if ($rowcount % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
	echo '<td>' . $row['system_serial_number'] . '</td>';
        echo '<td>' . $row['remote_hostname'] . '</td>';
        echo '<td>' . $row['changes_made'] . '</td>';
        echo '<td>' . $row['date_time'] . '</td>';
	echo "</tr>";
}
echo "</table>";
if ($search == 1){
?>
<input type="button" name="Return" value="Return" onclick="window.location.href='search.php?go&name=<?php echo $name?>'" />
<?php
} else {
?>
<input type="button" name="Return" value="Return" onclick="window.location.href='working_set.php?rowname=<?php echo $row_name?>&cabname=<?php echo $cab_name?>&sitename=<?php echo $site_name?>'" />
<?php
}
