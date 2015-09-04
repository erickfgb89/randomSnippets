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
<h1><b><p> Add Power Can to Cab </b></p></h1>

<?php
$row_name = $_GET['rowname'];
$cab_name = $_GET['cabname'];
if ($row_name == 'Retired'){
$location = Retired ; } else {
$location = $row_name . "-" . $cab_name;}
include('connect-db.php');
$result = mysql_query("SELECT * FROM cab_definition,row_definition WHERE cab_definition.cab_name = '$cab_name' AND row_definition.row_name = '$row_name'") or die(mysql_error());
$row = mysql_fetch_array($result);
$visible_row_name = $row['visible_row_name'];
$visible_cab_name = $row['visible_cab_name'];

       $result_pc = mysql_query("SELECT * FROM powercan_inv_master_table WHERE data_location_id like '$location' AND inv_site_name = '$site_name'")
        or die(mysql_error());

        echo "<table class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
        echo '<th></th>';
        echo "<th>Power Can Type</th>";
        echo "<th>Number Present</th>";
        echo "</tr>\n</thead>\n";
        $rowcount_pc = 0;
        while($row_pc = mysql_fetch_array( $result_pc )) {
        $rowcount_pc++;
        if ($rowcount_pc % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
                echo '<td><a href="delete_pc_record.php?id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&sitename=' . $site_name . '" 
                onClick="return confirm(\'Are you SURE you want to delete this record?\')">Delete</a></td>';
                echo '<td>' . $row_pc['powercan_type'] . '</td>';
                echo '<td>' . $row_pc['count'] . '</td>';
                echo "</tr>";
                        }
        echo "</table>";
	echo "<p> </p>";
?>

<table class="tablesorter" border='1' cellpadding='10'>
<tr> <th>Power Can Type</th> <th>Number Present</th> </th> </tr>
<form action="add_pc_cab_submit.php" method="post">
 <input type="hidden" name="rowname" value="<?php echo $row_name; ?>"/>
 <input type="hidden" name="cabname" value="<?php echo $cab_name; ?>"/>
 <input type="hidden" name="sitename" value="<?php echo $site_name; ?>"/>
 <input type="hidden" name="location" value="<?php echo $location; ?>"/>
 <div>
 <tr bgcolor="#EAF2D3">
<?php
 $options_pc_type = '';
 $result_pc_type = mysql_query("SELECT * FROM powercan_definition WHERE site_name='$site_name'") or die(mysql_error());
 while ($row_pc_type = mysql_fetch_array($result_pc_type)) {
 $pc_type = $row_pc_type['powercan_type'];
 $options_pc_type .= "<option value=\"$pc_type\">".$pc_type.'</option>';
 }
?>
 <td> <select name = "powercan_type"> <?php echo $options_pc_type; ?>

<?php
 $options_count = '';
 $result_count = mysql_query("SELECT * FROM count_table") or die(mysql_error());
 while ($row_count = mysql_fetch_array($result_count)) {
 $pc_count = $row_count['count'];
 $options_count .= "<option value=\"$pc_count\">".$pc_count.'</option>';
 }
?>

 <td> <select name = "count"> <?php echo $options_count; ?>

 <?php
 
 ?>
 </tr>
 </table>
 <input type="submit" name="submit" value="Submit">
 <input type="submit" name="submit" value="Cancel" onclick="this.form.action='working_set.php?rowname=<?php echo $row_name;?>&cabname=<?php echo $cab_name;?>&sitename=<?php echo $site_name;?>';"/>
 </div>
 </form>


<?php
	echo '<p> </p>';
	echo '<b>Viewing: Site ' . $site_name . ' Row ' . $row['visible_row_name'] . ' Cab ' . $row['visible_cab_name'] . '</b>';

        // get results from database
        $result = mysql_query("SELECT * FROM aqn_inv_master_table WHERE data_location_id = '$location' AND site_name = '$site_name'")
        or die(mysql_error());

        // display data in table

//        echo "<form name=\"form\" method=\"post\"\">";
        echo "<table class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<tr>";
        echo "<th>System Name</th>";
        echo "<th>Usage</th>";
        echo "<th>Model</th>";
        echo "<th>Location</th>";
        echo "<th>Serial Number</th>";
        echo "<th>RFID Tag</th>";
        echo "<th>Asset Number</th>";
        echo "<th>Comments</th>";
        echo "<th></th>";
        echo "</tr>";
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
                echo '<td>' . $row['system_name'] . '</td>';
                echo '<td>' . $row['system_usage'] . '</td>';
                echo '<td>' . $row['system_model'] . '</td>';
                echo '<td>' . $visible_row_name . "" . $visible_cab_name . " " . $row['unit_location'] . '</td>';
                echo '<td>' . $row['system_serial_number'] . '</td>';
                echo '<td>' . $row['system_rfid_tag'] . '</td>';
                echo '<td>' . $row['system_asset_number'] . '</td>';
                echo '<td>' . $row['comments'] . '</td>';
                echo '<td><a href="edit.php?id=' . $row['id'] .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&location=' . $location . '&sitename=' . $site_name . '">Edit</a></td>';
                echo "</tr>";
	}
        // close table
        echo "</table>";
?>
</center>
</body>
</html>

