<html>
<body>
<center>
<?php
$row_name = $_GET['row_name'];
$cab_name = $_GET['cab_name'];
$site_name = $_GET['sitename'];
$site_name_upper = strtoupper($site_name);
$serial_number = trim($_GET['serial_number']);
$system_name = trim($_GET['system_name']);

$default_spp = 'SPP-2014.06.0-0';

include('connect-db.php');
?>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<?php

$result_ip_address = mysql_query("select address,row_name from lab_ip_data.aqn_master_table where hostname = (select system_name from lab_inventory.aqn_inv_master_table where system_serial_number = '$serial_number')") or die(mysql_error());
$row_ip_address = mysql_fetch_array($result_ip_address);
$address = trim($row_ip_address['address']);
$rowname_ip = $row_ip_address['row_name'];

$result_row = mysql_query("select visible_row_name from lab_ip_data.row_definition where row_name = '$rowname_ip'") or die(mysql_error());
$rowname = mysql_fetch_array($result_row);
$visible_row_name = $rowname['visible_row_name'];

?>

	<h1><b><p> Deployment Options For <?php echo $system_name; ?>  </b></p></h1>
	<h2><b><p> Located in <?php echo $visible_row_name ?> </b></p></h2>
	<h2><b><p> IP Address selected for this device is: <?php echo $address; ?></b></p></h2>
<?php
	echo '<br>';
	$result_status = mysql_query("SELECT * FROM deploy_system_status WHERE system_serial_number = '$serial_number'");
	if (mysql_num_rows($result_status) > 0) {
?>
	<meta http-equiv="refresh" content="20">
<?php
	$row_status = mysql_fetch_array($result_status);
	$percent_complete = $row_status['percent_complete'];
	$script_location = $row_status['script_location'];
	$deploy_start_time = $row_status['deploy_start_time'];
	
        echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
	echo "<th>Deploy Start</th>";
        echo "<th>Percent Completed</th>";
        echo "<th>What its doing</th>";
	echo "<th>Cancel The Operation</th>";
        echo "</tr>\n</thead>\n";

	echo '<tr bgcolor="#EAF2D3">';
	echo '<td>' . $deploy_start_time . '</td>';
        echo '<td>' . $percent_complete . '%</td>';
        echo '<td>' . $script_location . '</td>';
	if ($percent_complete == '99') {
	echo '<td><a href="deploy_cancel_progress.php?system_name=' . $system_name . '&serial_number=' . $serial_number .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&sitename=' . $site_name . '">Reset</a></td>'; 
	} else {
	echo '<td><a href="deploy_cancel_progress.php?system_name=' . $system_name . '&serial_number=' . $serial_number .'&rowname=' . $row_name .'&cabname=' . $cab_name . '&sitename=' . $site_name . '">Cancel</a></td>';
	}
        echo "</tr>";
        echo "</table>";
	

} else {
?>
	<form action="deploy.php" method="post" onsubmit="return confirm('Are you sure you want to Deploy to <?php echo $system_name; ?>?')"\>
<?php
	echo 'Please select the Options and then click deploy ...';
	echo '<br>';

?>
Select the OS you wish to install...

<?php
$options_windows_os = '';
$result_windows_os = mysql_query("SELECT * FROM deploy_os_types WHERE os_type='windows' ORDER BY os_name ASC") or die(mysql_error());
     while ($row = mysql_fetch_array($result_windows_os)) {
     $visible_os_name = $row["os_display_name"];
     $os_name = $row["os_name"];
     $options_windows_os .= "<option value=\"$os_name\">".$visible_os_name.'</option>';
}
?>
<select name = "windows"> <option value = 0 >Select Windows <?php echo $options_windows_os ?> </select>

<?php
$options_linux_os = '';
$result_linux_os = mysql_query("SELECT * FROM deploy_os_types WHERE os_type='linux' ORDER BY os_name ASC") or die(mysql_error());
     while ($row = mysql_fetch_array($result_linux_os)) {
     $visible_os_name = $row["os_display_name"];
     $os_name = $row["os_name"];
     $options_linux_os .= "<option value=\"$os_name\">".$visible_os_name.'</option>';
}
?>
<select name = "linux"> <option value = 0 >Select Linux <?php echo $options_linux_os ?> </select>

<br><br>
Do you want to install an SPP?
<input type="radio" name="spp" value="yes" checked> Yes
<input type="radio" name="spp" value="no"> No<br>
If Yes... Please select an SPP version:
<?php
$dhandle = opendir('/data/http_data/www/html/spp');
$files = array();

if ($dhandle) {
   while (false !== ($fname = readdir($dhandle))) {
      if (($fname != '.') && ($fname != '..') &&
          ($fname != basename($_SERVER['PHP_SELF'])) &&
          (strpos($fname, "SPP-")===0)){
          $files[] = $fname;
      }
   }
   closedir($dhandle);
}

?>
<select name = "spp_select"> 
<?php
sort($files);
foreach( array_reverse($files) as $fname )
{
   if ($fname == $default_spp){
   echo "<option selected=selected>{$fname}</option>\n"; } else {
   echo "<option>{$fname}</option>\n"; }

}
echo "</select>\n";
?>
<input type="hidden" name="row_name" value="<?php echo $row_name; ?>"/>
<input type="hidden" name="cab_name" value="<?php echo $cab_name; ?>"/>
<input type="hidden" name="site_name" value="<?php echo $site_name; ?>"/>
<input type="hidden" name="system_name" value="<?php echo $system_name; ?>"/>
<input type="hidden" name="serial_number" value="<?php echo $serial_number; ?>"/>
<br>
<?php
if ($address == '' || $address == NULL) {
?>

<h2><b><p> IP Address Not Found... Please check in IP address database! </b></p></h1>
<img align="middle" src="./images/error.gif" alt="IP Not Found">

<?php
} else {
?>
<h2><b><p> Pressing DEPLOY will REBOOT and deploy an OS on the selected machine....  </b></p></h1>
<br>
<td><input  type="submit" name="submit" value="DEPLOY!"></td>
<?php
}
?>
</form>
<form method="post">
<input type="submit" name="cancel" value="Cancel" onclick="this.form.action='working_set.php?rowname=<?php echo $row_name ?>&cabname=<?php echo $cab_name ?>&sitename=<?php echo $site_name ?>'"/>
</form>
<?php
} // CLOSE THE DEPLOY STATUS AREA
?>
<br>
<h2><b><p> Previous Deployment Selections (Last 5)</b></p></h2>
<?php
$result = mysql_query("SELECT * FROM `deploy_os_history` WHERE system_name = '$system_name' ORDER BY history_id DESC LIMIT 5") or die(mysql_error());

        echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
        echo "<th>Deploy Date and Time</th>";
        echo "<th>OS Name</th>";
        echo "<th>SPP Version</th>";
	//echo "<th>Redeploy?</th>";
        echo "</tr>\n</thead>\n";

        $rowcount = 0;
        while($row = mysql_fetch_array( $result )) {
	$os_name = $row['os_name'];
	$result_os_name = mysql_query("SELECT * FROM `deploy_os_types` WHERE os_name = '$os_name'") or die(mysql_error());
	$row_os_name = mysql_fetch_array($result_os_name);
	$visible_os_name = $row_os_name['os_display_name'];
	$rowcount++;
        if ($rowcount % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
        echo '<td>' . $row['deploy_datetime'] . '</td>';
        echo '<td>' . $visible_os_name . '</td>';
	echo '<td>' . $row['spp_option'] . '</td>';
	//echo '<td>' . '</td>';
	echo "</tr>";
        }
        // close table
        echo "</table>";
?>

</center>
</body>
</html>

