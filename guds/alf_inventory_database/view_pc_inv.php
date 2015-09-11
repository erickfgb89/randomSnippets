<html>
<head>
<?php
$site_name = $_GET['sitename'];
$site_name_upper = strtoupper($site_name);
?>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<link rel="stylesheet" href="jquery-ui-1.10.3.custom/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" />
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
<script src="jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="site.js"></script>
<style>
.ui-tooltip { max-width: 100%; }
</style>
<script>setTooltipListener("td");</script>
</head>
<body>
<center>
<?php
?>
<h1><b><p> <?php echo $site_name_upper;?> Power Can Inventory </b></p></h1>

<?php
include('connect-db.php');
$result_pc = mysql_query("SELECT * FROM powercan_definition where site_name='$site_name'") or die(mysql_error());

	echo "<form name=\"form\" method=\"post\" onsubmit=\"return confirm('Are you sure you want to Edit/Add to this can?')\">";
        echo "<table class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
	echo "<th> </th>";
        echo "<th>Power Can Type</th>";
        echo "<th>Total Available</th>";
	echo "<th>Total Used In Lab</th>";
	echo "<th>Total Count</th>";
	echo "<th>Description</th>";
        echo "</tr>\n</thead>\n";
        $rowcount_pc = 0;
        while($row_pc = mysql_fetch_array( $result_pc )) {
        $rowcount_pc++;
        if ($rowcount_pc % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
		echo '<td><a href="del_pc_inv.php?id=' . $row_pc['id'] .'&sitename=' . $site_name . '" onClick="return confirm(\'Are you SURE you want to delete this record?\')">Delete</a></td>'; 
                echo '<td fname="' .$row_pc['powercan_type']. '" desc="' .$row_pc['powercan_description']. '"><a href="pc_inv_location.php?sitename=' . $site_name . '&powercan_type=' . $row_pc['powercan_type'] . '">' . $row_pc['powercan_type'] . '</a></td>';
		$total_count = $row_pc['total_count'] + $row_pc['total_used'];
                echo '<td>' . $row_pc['total_count'] . '</td>';
		echo '<td>' . $row_pc['total_used'] . '</td>';
		echo '<td>' . $total_count . '</td>';
		echo '<td>' . $row_pc['powercan_description'] . '</td>';
                echo "</tr>";
                        }
        echo "</table>";

?>

<input type="submit" name="edit" value="Edit Inventory" onclick="this.form.action='edit_pc_inv.php?sitename=<?php echo $site_name;?>';"/>
<input type="submit" name="add_can" value="Add A Can" onclick="this.form.action='add_pc_inv.php?sitename=<?php echo $site_name;?>';"/>
</form>
</center>
</body>
</html>


