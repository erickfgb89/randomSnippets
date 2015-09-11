<?php
 include('connect-db.php');
 $row_name = $_GET['rowname'];
 $cab_name = $_GET['cabname'];
 $site_name = $_GET['sitename'];
 $location = $row_name . "-" . $cab_name;
?>

 <html>
 <head>
 <center>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
<script type="text/javascript">
function updateUsage()
{
  var $selected = $("#usageMasterEntry > option:selected").val();
  $(".usageSelector > [value=" + $selected + "]").attr("selected","selected");
}

</script>
 <title>Edit Cabinet</title>
 </head>
 <body>
 <h1><b><p> Edit Multiple Records </b></p></h1>
<?php
?>
 <table class="tablesorter" border='1' cellpadding='10'>
 <tr>
    <th>Site Name</th>
    <th>System Name</th> 
    <th>Usage
      <br />
<?php
$result_usage = mysql_query("SELECT * FROM usage_definition") or die(mysql_error());
$options_usage = "";
while ($row_usage = mysql_fetch_array($result_usage))
{
  $system_usage_def = $row_usage['system_usage_def'];
  $options_usage .= "<option value=\"$system_usage_def\">".$system_usage_def.'</option>';
}
?>    <select id="usageMasterEntry">
        <?php echo $options_usage?>
      </select>
      <button onclick="updateUsage()">Replicate</button>
    </th>
    <th>Model</th>
    <th>Location</th>
    <th>Serial Number</th>
    <th>RFID Tag</th>
    <th>Asset Number</th>
    <th>Comments</th>
 </tr>

<?php
	 $result_main = mysql_query("SELECT * FROM aqn_inv_master_table WHERE data_location_id='$location' AND site_name='$site_name' ORDER BY unit_location ASC FOR UPDATE") or die(mysql_error());
	 while($row_main = mysql_fetch_array( $result_main )) {
	 $id = $row_main['id'];
	 $system_site_name = $row_main['site_name'];
	 $system_name = $row_main['system_name'];
	 $system_usage = $row_main['system_usage'];
	 $system_model = $row_main['system_model'];
	 $unit_location = $row_main['unit_location'];
	 $system_serial_number = $row_main['system_serial_number'];
	 $system_rfid_tag = $row_main['system_rfid_tag'];
	 $system_asset_number = $row_main['system_asset_number'];
	 $comments = $row_main['comments'];

	$result_ip_address = mysql_query("select address,isParentIP from lab_ip_data.aqn_master_table where hostname = '$system_name'") or die(mysql_error());
	$row_ip_address = mysql_fetch_array($result_ip_address);
	$isParentIP = $row_ip_address['isParentIP'];
	$address = $row_ip_address['address'];
?>
<form action="multi_edit_cab_update.php" method="post" onReset="return confirm('Do you really want to reset the form?')">
 <input type="hidden" name="id[]" value="<?php echo $id; ?>"/>
 <input type="hidden" name="rowname" value="<?php echo $row_name; ?>"/>
 <input type="hidden" name="cabname" value="<?php echo $cab_name; ?>"/>
 <input type="hidden" name="sitename" value="<?php echo $site_name; ?>"/>
 <input type="hidden" name="orig_name[]" value="<?php echo $system_name; ?>"/>
 <div>
 <tr bgcolor="#EAF2D3">
 <td> <?php
        $result = mysql_query("SELECT * FROM site_definition") or die(mysql_error());
 	$edit_options_site = "";
        while ($row = mysql_fetch_array($result)) {
        $edit_site_name = $row["site_name"];
        if ($edit_site_name == $system_site_name){
        $edit_options_site .= "<option selected = \"selected\" value=\"$edit_site_name\">".$edit_site_name.'</option>'; } else {
        $edit_options_site .= "<option value=\"$edit_site_name\">".$edit_site_name.'</option>'; }
        }
        ?>
        <select name = "edit_sitename[]"> <?php echo $edit_options_site ?> </select>

<?php
if ($isParentIP == '1'){
?>
 <td> <?php echo $system_name; ?></td>
 <input type="hidden" name="system_name[]" value="<?php echo $system_name; ?>"/>
<?php
} else {
?>
 <td> <input type="text" name="system_name[]" value="<?php echo $system_name; ?>"/></td>
<?php
}
 $result_usage = mysql_query("SELECT * FROM usage_definition") or die(mysql_error());
 $options_usage = "";
 while ($row_usage = mysql_fetch_array($result_usage)) {
 $system_usage_def = $row_usage['system_usage_def'];
 if ($system_usage_def == $system_usage){
 $options_usage .= "<option selected = \"selected\" value=\"$system_usage_def\">".$system_usage_def.'</option>';
  } else {
 $options_usage .= "<option value=\"$system_usage_def\">".$system_usage_def.'</option>';
 }
 }
?>
 <td> <select class="usageSelector" name = "system_usage[]"> <?php echo $options_usage ?> </select> </td>
 <td> <input type="text" name="system_model[]" value="<?php echo $system_model; ?>"/></td>
 <td> <?php
if ($address == '' || $address == 'NULL' || $system_name == '' || $system_name == 'NULL'){
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
        } else {
?>
        <input type="hidden" name="edit_rowname[]" value="<?php echo $row_name; ?>"/>
        <input type="hidden" name="edit_cabname[]" value="<?php echo $cab_name; ?>"/>
<?php
        }
        $result_unit_def = mysql_query("SELECT * FROM unit_definition") or die(mysql_error());
	$edit_unit_location_row = "";
        while ($row_unit_def = mysql_fetch_array($result_unit_def)) {
        $edit_unit_location = $row_unit_def["unit_number"];
        if ($edit_unit_location == $unit_location) {
        $edit_unit_location_row .= "<option selected = \"selected\" value=\"$edit_unit_location\">".$edit_unit_location.'</option>'; }        else {
        $edit_unit_location_row .= "<option value=\"$edit_unit_location\">".$edit_unit_location.'</option>'; }
        }
        ?>
      <select name = "unit_location[]"> <?php echo $edit_unit_location_row ?> </select></td>
 <td> <input type="text" name="system_serial_number[]" value="<?php echo $system_serial_number; ?>"/></td>
 <td> <input type="text" name="system_rfid_tag[]" value="<?php echo $system_rfid_tag; ?>"/></td>
 <td> <input type="text" name="system_asset_number[]" value="<?php echo $system_asset_number; ?>"/></td>
 <td> <input type="text" name="comments[]" value="<?php echo $comments; ?>"/></td>
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
 </body>
 </center>
 </html>
		
