<html>
 <head>
 <center>
 <title>Add Multi Assets To Cabinet</title>
 </head>
<body>
 <h1><b><p> Add Multiple Records </b></p></h1>
 <table class="tablesorter" border='1' cellpadding='10'>
 <tr> <th>System Name </th> <th>Usage</th> <th>Model</th> <th>Location</th> <th>Serial Number</th> <th>RFID Tag</th>
 <th>Asset Number</th> <th>Comments</th></tr>

<?php
include('connect-db.php');
if(isset($_GET['howmany'])){
	$num = $_GET['howmany'];
	$row_name = $_GET['rowname'];
	$cab_name = $_GET['cabname'];
	$site_name = $_GET['sitename'];
 for($i=0; $i < $num; $i++) {	

?>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<form action="add_blank_asset_submit.php" method="post" onReset="return confirm('Do you really want to reset the form?')">
 <div>
 <tr bgcolor="#EAF2D3">
 <input type="hidden" name="howmany" value="<?php echo $num; ?>"/>
 <input type="hidden" name="sitename" value="<?php echo $site_name; ?>"/>
 <td> <input type="text" name="system_name[]" value=""/></td>
<?php
 $result_usage = mysql_query("SELECT * FROM usage_definition") or die(mysql_error());
 $options_usage = "";
 while ($row_usage = mysql_fetch_array($result_usage)) {
 $system_usage_def = $row_usage['system_usage_def'];
 $options_usage .= "<option value=\"$system_usage_def\">".$system_usage_def.'</option>';
 }
?>
 <td> <select name = "system_usage[]"> <?php echo $options_usage ?> </select> </td>
 <td> <input type="text" name="system_model[]" value=""/></td>
 <td> <?php
        $result_cab_def = mysql_query("SELECT * FROM cab_definition") or die(mysql_error());
        $edit_options_cab = "";
        while ($row_cab_def = mysql_fetch_array($result_cab_def)) {
        $visible_cab_name = "Cab " . $row_cab_def['visible_cab_name'];
        $edit_cab_name = $row_cab_def["cab_name"];
        if ($edit_cab_name == $cab_name){
        $edit_options_cab .= "<option selected = \"selected\" value=\"$edit_cab_name\">".$visible_cab_name.'</option>'; } else {
        $edit_options_cab .= "<option value=\"$edit_cab_name\">".$visible_cab_name.'</option>'; }
        }
        $result_row_def = mysql_query("SELECT * FROM row_definition") or die(mysql_error());
        $edit_options_row = "";
        while ($row_row_def = mysql_fetch_array($result_row_def)) {
        $visible_row_name = "Row " . $row_row_def["visible_row_name"];
        $edit_row_name = $row_row_def["row_name"];
        if ($edit_row_name == $row_name){
        $edit_options_row .= "<option selected = \"selected\" value=\"$edit_row_name\">".$visible_row_name.'</option>'; } else {
        $edit_options_row .= "<option value=\"$edit_row_name\">".$visible_row_name.'</option>'; }
        }
        ?>
        <select name = "edit_rowname[]"> <?php echo $edit_options_row ?> </select>
        <select name = "edit_cabname[]"> <?php echo $edit_options_cab ?> </select>
        <?php
        $result_unit_def = mysql_query("SELECT * FROM unit_definition") or die(mysql_error());
        $edit_unit_location_row = "";
        while ($row_unit_def = mysql_fetch_array($result_unit_def)) {
        $edit_unit_location = $row_unit_def["unit_number"];
        if ($edit_unit_location == '') {
        $edit_unit_location_row .= "<option selected = \"selected\" value=\"$edit_unit_location\">".$edit_unit_location.'</option>'; }        
	else {
        $edit_unit_location_row .= "<option value=\"$edit_unit_location\">".$edit_unit_location.'</option>'; }
        }
        ?>
      <select name = "unit_location[]"> <?php echo $edit_unit_location_row ?> </select></td>
 <td> <input type="text" name="system_serial_number[]" value=""/></td>
 <td> <input type="text" name="system_rfid_tag[]" value=""/></td>
 <td> <input type="text" name="system_asset_number[]" value=""/></td>
 <td> <input type="text" name="comments[]" value=""/></td>
 <?php
 }
 ?>
 </tr>
 </table>
 <input type="submit" name="submit" value="Submit">
 <input type="submit" name="cancel" value="Cancel">
 <input type="reset">
 </div>
 </form>
<?php
} else {
echo 'Please choose a number';
}
?>
</body>
</html>

