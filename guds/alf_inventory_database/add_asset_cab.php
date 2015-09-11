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
$row_name = $_GET['rowname'];
$cab_name = $_GET['cabname'];
$site_name_upper = strtoupper($site_name);
?>
<h1><b><p> Add Asset to <?php echo $site_name_upper;?> Inventory Database </b></p></h1>
<?php
if ($row_name == 'Retired'){
$location = Retired ; } else {
$location = $row_name . "-" . $cab_name;}
include('connect-db.php');
$result = mysql_query("SELECT * FROM cab_definition,row_definition WHERE cab_definition.cab_name = '$cab_name' AND row_definition.row_name = '$row_name'") or die(mysql_error());
$row = mysql_fetch_array($result);
$visible_row_name = $row['visible_row_name'];
$visible_cab_name = $row['visible_cab_name'];
        echo '<b>Viewing: Row ' . $row['visible_row_name'] . " Cab " . $row['visible_cab_name'] . '</b>';

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
 <table class="tablesorter" border='1' cellpadding='10'>
 <tr> <th>System Name </th> <th>Usage</th> <th>Model</th> <th>Location</th> <th>Serial Number</th> <th>RFID Tag</th>
 <th>Asset Number</th> <th>Comments</th> </tr>
 <form action="add_asset_cab_submit.php" method="post">
 <input type="hidden" name="row_name" value="<?php echo $row_name; ?>"/>
 <input type="hidden" name="cab_name" value="<?php echo $cab_name; ?>"/>
 <input type="hidden" name="site_name" value="<?php echo $site_name; ?>"/>
 <input type="hidden" name="location" value="<?php echo $location; ?>"/>
 <tr bgcolor="#EAF2D3">
 <td> <input type="text" name="system_name"/></td>
<?php
 $result = mysql_query("SELECT * FROM usage_definition") or die(mysql_error());
 while ($row = mysql_fetch_array($result)) {
 $system_usage_def = $row['system_usage_def'];
 $options_usage .= "<option value=\"$system_usage_def\">".$system_usage_def.'</option>';
 }
 
?>
 <td> <select name = "system_usage"> <?php echo $options_usage ?> </select> </td>
 <td> <input type="text" name="system_model"/></td>
 <td> <?php
        $result = mysql_query("SELECT * FROM cab_definition") or die(mysql_error());
        while ($row = mysql_fetch_array($result)) {
        $visible_cab_name = "Cab " . $row['visible_cab_name'];
	$edit_cab_name = $row['cab_name'];
	if ($edit_cab_name == $cab_name){
        $edit_options_cab .= "<option selected = \"selected\" value=\"$edit_cab_name\">".$visible_cab_name.'</option>'; } else {
        $edit_options_cab .= "<option value=\"$edit_cab_name\">".$visible_cab_name.'</option>'; }
        }
        $result = mysql_query("SELECT * FROM row_definition") or die(mysql_error());
        while ($row = mysql_fetch_array($result)) {
        $visible_row_name = "Row " . $row["visible_row_name"];
	$edit_row_name = $row['row_name'];
        if ($edit_row_name == $row_name){
        $edit_options_row .= "<option selected = \"selected\" value=\"$edit_row_name\">".$visible_row_name.'</option>'; } else {
        $edit_options_row .= "<option value=\"$edit_row_name\">".$visible_row_name.'</option>'; }
        }
        ?>
        <select name = "edit_rowname"> <?php echo $edit_options_row ?> </select>
        <select name = "edit_cabname"> <?php echo $edit_options_cab ?> </select>
        <?php
        $result = mysql_query("SELECT * FROM unit_definition") or die(mysql_error());
        while ($row = mysql_fetch_array($result)) {
        $edit_unit_location = $row["unit_number"];
        $edit_unit_location_row .= "<option value=\"$edit_unit_location\">".$edit_unit_location.'</option>'; }
        
        ?>
      <select name = "unit_location"> <?php echo $edit_unit_location_row ?> </select></td>
 <td> <input type="text" name="system_serial_number"/></td>
 <td> <input type="text" name="system_rfid_tag"/></td>
 <td> <input type="text" name="system_asset_number"/></td>
 <td> <input type="text" name="comments" value=""/></td>
 </tr>
 </table>
<input type="submit" name="submit" value="Save Cabinet" />
<input type="submit" name="submit" value="Cancel" onclick="this.form.action='working_set.php?rowname=<?php echo $row_name;?>&cabname=<?php echo $cab_name;?>&sitename=<?php echo $site_name;?>';"/>
</form>
</center>
</body>
</html>

