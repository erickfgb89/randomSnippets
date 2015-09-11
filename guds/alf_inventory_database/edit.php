<html>
 <head>
 <center>

<?php
$site_name = $_GET['sitename'];
$site_name_upper = strtoupper($site_name);
?>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<title>Edit Record</title>
 </head>
 <body>

<?php
include('connect-db.php');
if(isset($_GET['isParentIP'])){
        $isParentIP=$_GET['isParentIP'];}
if(isset($_GET['no_ip'])){
        $no_ip=1;}
 $id = $_GET['id'];
 $row_name = $_GET['rowname'];
 $cab_name = $_GET['cabname'];
 $location = $_GET['location'];
 if(isset($_GET['search'])){
	$search=$_GET['search'];}
 if(isset($_GET['name'])){
        $name=$_GET['name'];}
 $result = mysql_query("SELECT * FROM aqn_inv_master_table WHERE id='$id' FOR UPDATE") or die(mysql_error());
 $row = mysql_fetch_array($result);
 $system_site_name = $row['site_name'];
 $system_name = $row['system_name'];
 $system_usage = $row['system_usage'];
 $system_model = $row['system_model'];
 $unit_location = $row['unit_location'];
 $system_serial_number = $row['system_serial_number'];
 $system_rfid_tag = $row['system_rfid_tag'];
 $system_asset_number = $row['system_asset_number'];
 $comments = $row['comments'];
?>

 <table class="tablesorter" border='1' cellpadding='10'>
<tr> <th>Site Name </th> <th>System Name </th> <th>Usage</th> <th>Model</th> <th>Location</th> <th>Serial Number</th> <th>RFID Tag</th> 
 <th>Asset Number</th> <th>Comments</th></tr>
 <form action="edit_submit.php" method="post" onReset="return confirm('Do you really want to reset the form?')">
 <input type="hidden" name="id" value="<?php echo $id; ?>"/>
 <input type="hidden" name="row_name" value="<?php echo $row_name; ?>"/>
 <input type="hidden" name="cab_name" value="<?php echo $cab_name; ?>"/>
 <input type="hidden" name="site_name" value="<?php echo $site_name; ?>"/>
 <input type="hidden" name="location" value="<?php echo $location; ?>"/>
 <input type="hidden" name="orig_name" value="<?php echo $system_name; ?>"/>
<?php
if ($search == 1){
?>
 <input type="hidden" name="search" value="<?php echo $search; ?>"/>
 <input type="hidden" name="name" value="<?php echo $name; ?>"/>
<?php
 } elseif ($search == 2){
?>
 <input type="hidden" name="search" value="<?php echo $search; ?>"/>
 <input type="hidden" name="name" value="<?php echo $name; ?>"/>
<?php 
}
?>
<div>
 <tr bgcolor="#EAF2D3">
 <td> <?php
 $result = mysql_query("SELECT * FROM site_definition") or die(mysql_error());
        while ($row = mysql_fetch_array($result)) {
        $edit_site_name = $row["site_name"];
        if ($edit_site_name == $system_site_name){
        $edit_options_site .= "<option selected = \"selected\" value=\"$edit_site_name\">".$edit_site_name.'</option>'; } else {
        $edit_options_site .= "<option value=\"$edit_site_name\">".$edit_site_name.'</option>'; }
        }
        ?>
        <select name = "edit_sitename"> <?php echo $edit_options_site ?> </select>
<?php
if ($isParentIP == '1') {
?>
 <td> <?php echo $system_name; ?> </td>
 <input type="hidden" name="system_name" value="<?php echo $system_name; ?>"/>
<?php
} else {
?>
 <td> <input type="text" name="system_name" value="<?php echo $system_name; ?>"/></td>
<?php
}
 $result = mysql_query("SELECT * FROM usage_definition") or die(mysql_error());
 while ($row = mysql_fetch_array($result)) {
 $system_usage_def = $row['system_usage_def'];
 if ($system_usage_def == $system_usage){
 $options_usage .= "<option selected = \"selected\" value=\"$system_usage_def\">".$system_usage_def.'</option>';
  } else {
 $options_usage .= "<option value=\"$system_usage_def\">".$system_usage_def.'</option>';
 }
 }
?>
 <td> <select name = "system_usage"> <?php echo $options_usage ?> </select> </td>
 <td> <input type="text" name="system_model" value="<?php echo $system_model; ?>"/></td>
 <td> <?php 
if ($no_ip == 1){
	$result = mysql_query("SELECT * FROM cab_definition") or die(mysql_error());
	while ($row = mysql_fetch_array($result)) {
	$visible_cab_name = "Cab " . $row['visible_cab_name'];
	$edit_cab_name = $row["cab_name"];
	if ($edit_cab_name == $cab_name){
	$edit_options_cab .= "<option selected = \"selected\" value=\"$edit_cab_name\">".$visible_cab_name.'</option>'; } else {
	$edit_options_cab .= "<option value=\"$edit_cab_name\">".$visible_cab_name.'</option>'; }
	}	
	$result = mysql_query("SELECT * FROM row_definition") or die(mysql_error());
	while ($row = mysql_fetch_array($result)) {
	$visible_row_name = "Row " . $row["visible_row_name"];
	$edit_row_name = $row["row_name"];
	if ($edit_row_name == $row_name){
	$edit_options_row .= "<option selected = \"selected\" value=\"$edit_row_name\">".$visible_row_name.'</option>'; } else {
	$edit_options_row .= "<option value=\"$edit_row_name\">".$visible_row_name.'</option>'; }
	}	
	?>		
        <select name = "edit_rowname"> <?php echo $edit_options_row ?> </select>
        <select name = "edit_cabname"> <?php echo $edit_options_cab ?> </select>
<?php
	} else {
?>
	<input type="hidden" name="edit_rowname" value="<?php echo $row_name; ?>"/>
	<input type="hidden" name="edit_cabname" value="<?php echo $cab_name; ?>"/>
<?php
	}
	$result = mysql_query("SELECT * FROM unit_definition") or die(mysql_error());
	while ($row = mysql_fetch_array($result)) {
	$edit_unit_location = $row["unit_number"];
	if ($edit_unit_location == $unit_location) {
	$edit_unit_location_row .= "<option selected = \"selected\" value=\"$edit_unit_location\">".$edit_unit_location.'</option>'; } 	      else {
        $edit_unit_location_row .= "<option value=\"$edit_unit_location\">".$edit_unit_location.'</option>'; }
	}
	?>
      <select name = "unit_location"> <?php echo $edit_unit_location_row ?> </select></td>
 <td> <input type="text" name="system_serial_number" value="<?php echo $system_serial_number; ?>"/></td>
 <td> <input type="text" name="system_rfid_tag" value="<?php echo $system_rfid_tag; ?>"/></td>
 <td> <input type="text" name="system_asset_number" value="<?php echo $system_asset_number; ?>"/></td>
 <td> <input type="text" name="comments" value="<?php echo $comments; ?>"/></td>
 </tr>
 </table>
 <input type="submit" name="submit" value="Submit">
 <input type="submit" name="cancel" value="Cancel">
 <input type="reset">
 </div>
 </form>
 </body>
 </center>
 </html>

