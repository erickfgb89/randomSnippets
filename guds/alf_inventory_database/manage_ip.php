<html>
<body>
<center>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<title>Manage IP Address</title>

<?php
include('connect-db.php');
$system_name = $_GET['system_name'];
if(isset($_GET['id'])){
$id = $_GET['id'];
}
if(isset($_GET['return'])){
    $return=$_GET['return'];
        if ($return == 'workingset'){
                $row_name = $_GET['row_name'];
                $cab_name = $_GET['cab_name'];
                $site_name = $_GET['sitename'];
        } else {
                $searchname = $_GET['searchname'];
		$row_name = $_GET['row_name'];
                $cab_name = $_GET['cab_name'];
                }
}
$orig_ip_address = $_GET['orig_ip_address'];
$result_ip_address = mysql_query("select address,row_name,isParentIP from lab_ip_data.aqn_master_table where hostname = '$system_name' AND address = '$orig_ip_address'") or die(mysql_error());
//$result_ip_address = mysql_query("select address,row_name,isParentIP from lab_ip_data.aqn_master_table where address = '$ip_address'") or die(mysql_error());
$row_ip_address = mysql_fetch_array($result_ip_address);
$address = trim($row_ip_address['address']);
$rowname_ip = $row_ip_address['row_name'];
$isParentIP = $row_ip_address['isParentIP'];

$result_row = mysql_query("select * from lab_ip_data.row_definition where row_name = '$rowname_ip'") or die(mysql_error());
$rowname = mysql_fetch_array($result_row);
$visible_row_name = $rowname['visible_row_name'];

        if (preg_match ("/\b(10.226.200.|10.226.201.|10.226.202.|10.226.203.|10.226.204.|10.226.205.|10.226.206.|10.226.207.|10.226.216.|10.226.217.|10.226.218.|10.226.219.|10.226.220.|10.226.221.|10.226.222.|10.226.223.|10.226.232.|10.226.233.|10.226.234.|10.226.235.|10.226.236.|10.226.237.|10.226.238.|10.226.239.|10.226.248.|10.226.249.|10.226.250.|10.226.251.|10.226.252.|10.226.253.|10.226.254.|10.226.255.)\b/i", $address))
             $netmask_address = '255.255.248.0';
        elseif (preg_match ("/\b(10.226.240.|10.226.241.|10.226.242.|10.226.243.|10.226.244.|10.226.245.|10.226.246.|10.226.247.|10.226.224.|10.226.225.|10.226.225.|10.226.226.|10.226.227.|10.226.228.|10.226.229.|10.226.230.|10.226.231.)\b/i", $address))
             $netmask_address = '255.255.240.0';
        else
             $netmask_address = $rowname['netmask_address'];
?>
        <h1><b><p> IP Options For <?php echo $system_name; ?>  </b></p></h1>
        <h2><b><p> Located in <?php echo $visible_row_name ?> </b></p></h2>
	<table class="tablesorter" border='1' cellpadding='10'>
	<form action="ip_change.php" method="post">

<?php
	if ($isParentIP != '1') {
?>
	<input type="submit" name="change" value="Change IP/Location" onClick="return confirm('Are you SURE you want to proceed?')">
        <input type="submit" name="release" value="Release IP" onClick="return confirm('Are you SURE you want to proceed?')"></td>
<?php
	if (isset($id)) {	
?>
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
<?php
			}
?>
	<input type="hidden" name="address" value="<?php echo $address; ?>"/>
<?php
	} else {
?>
	<h2><b><p> Asset has other IPs associated. You must delete those other IPs </b></p></h2>
	<h2><b><p> before moving or editing. </b></p></h2>
<?php	
	}
        echo "<table border='0' cellpadding='10'>";
        echo "<tr bgcolor=\"#EAF2D3\">";
        echo '<td><b>Hostname: ' . $system_name . '.alf.gsc.mvlabs.corp.hp.com </b><br />';
        echo '<b>IP Address: ' . $address . ' </b><br />';
        echo '<b>Gateway Address: ' . $rowname['gateway_address'] . ' </b><br />';
        echo '<b>Netmask Address: ' . $netmask_address . ' </b><br />';
        if (preg_match ("/\b(10.226.|10.252.)\b/i", $address))
                echo '<b>DNS Servers: 10.252.248.11 and 10.252.248.12 </b><br />';
        elseif (preg_match ("/\b(10.50.)\b/i", $address))
                echo '<b>DNS Servers: 10.50.240.2 </b><br />';
        elseif (preg_match ("/\b(10.40.)\b/i", $address))
                echo '<b>DNS Servers: 10.40.0.30 and 10.40.0.31 </b><br />';
        echo '</tr>';
	echo "</table>";

if ($return == 'workingset'){
?>
        <input type="submit" name="cancel" value="Return" onclick="this.form.action='working_set.php?rowname=<?php echo $row_name ?>&cabname=<?php echo $cab_name ?>&sitename=<?php echo $site_name ?>'"/>
<?php
        } else {
?>
        <input type="submit" name="return" value="Return" onclick="this.form.action='search.php?go&name=<?php echo $searchname ?>'"/>
<?php
        }
?>
 <table class="tablesorter" border='1' cellpadding='10'>
 <tr> <th> </th> <th>Identifier</th> <th>Manual Select</th> <th>Select Row</th> <th>How Many</th> <th>Project/Feedback Number</th> </tr>
<?php
if ($return == 'workingset'){
?>
 <input type="hidden" name="row_name" value="<?php echo $row_name; ?>"/>
 <input type="hidden" name="cab_name" value="<?php echo $cab_name; ?>"/>
 <input type="hidden" name="sitename" value="<?php echo $site_name; ?>"/>
 <input type="hidden" name="system_name" value="<?php echo $system_name; ?>"/> 
 <input type="hidden" name="rowname_ip" value="<?php echo $rowname_ip; ?>"/>
 <input type="hidden" name="parent_ip" value="<?php echo $address; ?>"/>
 <input type="hidden" name="orig_ip_address" value="<?php echo $orig_ip_address; ?>"/>
 <input type="hidden" name="return" value="workingset"/>
<?php
} else {
?>
 <input type="hidden" name="row_name" value="<?php echo $row_name; ?>"/>
 <input type="hidden" name="cab_name" value="<?php echo $cab_name; ?>"/>
 <input type="hidden" name="searchname" value="<?php echo $searchname; ?>"/>
 <input type="hidden" name="rowname_ip" value="<?php echo $rowname_ip; ?>"/>
 <input type="hidden" name="parent_ip" value="<?php echo $address; ?>"/>
 <input type="hidden" name="orig_ip_address" value="<?php echo $orig_ip_address; ?>"/>
 <input type="hidden" name="system_name" value="<?php echo $system_name; ?>"/>
 <input type="hidden" name="return" value="search"/>
<?php
}
$result = mysql_query("SELECT * FROM lab_ip_data.row_definition where ip_type = 'data' or ip_type = 'mgmt'");
$options = '';
     while ($row = mysql_fetch_array($result)) {
     $visible_row_name = $row["visible_row_name"];
     $RowName = $row["row_name"];
     if ($RowName == $rowname_ip){
     $options .= "<option selected = \"selected\" <option value=\"$RowName\">".$visible_row_name.'</option>'; } else {
     $options .= "<option value=\"$RowName\">".$visible_row_name.'</option>'; }
     }

$options_count = '';
$result_count = mysql_query("SELECT * FROM count_table") or die(mysql_error());
while ($row_count = mysql_fetch_array($result_count)) {
$pc_count = $row_count['count'];
$options_count .= "<option value=\"$pc_count\">".$pc_count.'</option>';
}
?>
 <td> <input type="submit" name="addchild" value="Add IPs"> </td>
 <td> <input type="text" name="ident" size="5" value="VM"/> </td>
 <td> <input type="checkbox" name="manual"/>Manual Select</td>
 <td> <select name="RowName"> <?php echo $options ?> </select> </td>
 <td> <select name="howmany"> <?php echo $options_count; ?> </td>
 <td> <input type="text" name="project" size="10" value="N/A"/> </td>
 </tr>
</form>
<?php
if ($isParentIP == '1') {
$result_child_address = mysql_query("select hostname,address,parentIP,project from lab_ip_data.aqn_master_table where parentIP = '$address' ORDER BY `hostname` ASC") or die(mysql_error());

	echo '<h2><b><p> Additional IP Addresses Associated With This Host. </b></p></h2>';
        echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
	echo "<thead>\n<tr>";
	echo "<th></th>";
        echo "<th>Host Name</th>";
        echo "<th>IP Address</th>";
	echo "<th>Project/Feedback Number</th>";
	echo "</tr>\n</thead>\n";
        // loop through results of database query, displaying them in the table
        $rowcount = 0;
        while($row = mysql_fetch_array( $result_child_address )) {
	$child_address = $row['address'];
	$child_hostname = $row['hostname'];
	$project = $row['project'];
	$parent_ip = $row['parentIP'];
	$rowcount++;
        if ($rowcount % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
	if ($return == 'workingset'){
	echo '<td><a href="ip_child_delete.php?return=workingset&orig_ip_address='. $orig_ip_address. '&childip='. $child_address .'&parentip='. $parent_ip .'&system_name='. $system_name .'&row_name='. $row_name .'&cab_name='. $cab_name .'&sitename='. $site_name .'" onClick="return confirm(\'Are you SURE you want to delete this record?\')">Delete</a></td>'; } else {
	echo '<td><a href="ip_child_delete.php?return=search&searchname=' . $searchname . '&orig_ip_address='. $orig_ip_address. '&childip='. $child_address .'&parentip='. $parent_ip .'&system_name='. $system_name .'&searchname=' . $searchname .'" onClick="return confirm(\'Are you SURE you want to delete this record?\')">Delete</a></td>'; }
	echo '<td>' . $child_hostname . '</td>';
	echo '<td>' . $child_address . '</td>';
	echo '<td>' . $project . '</td>';
	echo "</tr>";
	}
        // close table
        echo "</table>";
}
?>

 </body>
 </center>
</html>

