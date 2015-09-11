<html>
<head>
<?php
$site_name = $_GET['sitename'];
$powercan_type = $_GET['powercan_type'];
$site_name_upper = strtoupper($site_name);
?>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
</head>
<body>
<center>
<?php
?>
<h1><b><p> <?php echo $site_name_upper;?> Power Can Location Data </b></p></h1>
<h2><b><p> For <?php echo $powercan_type;?> </b></p></h2>

<?php
include('connect-db.php');
$result = mysql_query("select * from powercan_inv_master_table where powercan_type = '$powercan_type' AND inv_site_name = '$site_name' ORDER BY data_location_id ASC") or die(mysql_error());

        echo "<form name=\"form\" method=\"post\"\">";
?>
<input type="submit" name="Return" value="Return" onclick="this.form.action='view_pc_inv.php?sitename=<?php echo $site_name;?>';"/>	
<?php
        echo "<table class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
        echo "<th>Location</th>";
	echo "<th>Count</th>";
        echo "</tr>\n</thead>\n";
        $rowcount_pc = 0;
        while($row = mysql_fetch_array( $result )) {
        $rowcount_pc++;
        if ($rowcount_pc % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
                echo '<td>' . $row['data_location_id'] . '</td>';
		echo '<td>' . $row['count'] . '</td>';
                echo "</tr>";
                        }
        echo "</table>";

?>

<input type="submit" name="Return" value="Return" onclick="this.form.action='view_pc_inv.php?sitename=<?php echo $site_name;?>';"/>

</form>
</center>
</body>
</html>

