<html>
<head>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
</head>
<body>
<center>
<?php
include('connect-db.php');
if(isset($_POST['return'])){
    $return=$_POST['return'];
        if ($return == 'workingset'){
                $row_name = $_POST['row_name'];
                $cab_name = $_POST['cab_name'];
                $site_name = $_POST['sitename'];
		$orig_ip_address = $_POST['orig_ip_address'];
        } else {
                $searchname = $_POST['searchname'];
                $row_name = $_POST['row_name'];
                $cab_name = $_POST['cab_name'];
		$orig_ip_address = $_POST['orig_ip_address'];
                }
}
if(isset($_POST['addchild'])){
$RowName = $_POST['RowName'];
$system_name = $_POST['system_name'];
$howmany = $_POST['howmany'];
$project = $_POST['project'];
$parent_ip = $_POST['parent_ip'];
$ident = $_POST['ident'];

if(isset($_POST['manual'])){
$result = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE row_name = '$RowName' AND (hostname = '' OR hostname IS NULL) AND (model = '' OR model IS NULL) AND (cab = '' OR cab IS NULL) AND (unit = '' OR unit IS NULL) AND (comments = '' OR comments IS NULL)") or die(mysql_error());

	$count=mysql_num_rows($result);
?>
        <h1><b><p> IP Options For <?php echo $system_name; ?>  </b></p></h1>
	<h2><b><p> Please select IP Addresses  </b></p></h2>
        <table class="tablesorter" border='1' cellpadding='10'>
        <form action="ip_man_change.php" method="post">
<?php
	
        echo '<p align="left">' . $count . ' Records returned.</p>';
?>
 <input type="submit" name="manIPadd" value="Add IPs">
<?php
	echo "<tr>";
        echo "<th>IP Address</th>";
	echo "<th></th>";
	$rowcount = 0;
        while($row = mysql_fetch_array( $result )) {
        $rowcount++;
	if ($rowcount % 2) {
        	print '<tr bgcolor="#EAF2D3">';
                } else {
                print '<tr bgcolor="white">';
                }
	echo '<td>' . $row['address'] . '</td>';
	echo '<td><input type="checkbox" name="ip_address[]" value="' . $row['address'] . '"/></td>';
	echo "</tr>";
        }
        // close table
        echo "</table>";
	if ($return == 'workingset'){
?>
 <input type="hidden" name="row_name" value="<?php echo $row_name; ?>"/>
 <input type="hidden" name="cab_name" value="<?php echo $cab_name; ?>"/>
 <input type="hidden" name="sitename" value="<?php echo $site_name; ?>"/>
 <input type="hidden" name="system_name" value="<?php echo $system_name; ?>"/>
 <input type="hidden" name="RowName" value="<?php echo $RowName; ?>"/>
 <input type="hidden" name="parent_ip" value="<?php echo $parent_ip; ?>"/>
 <input type="hidden" name="orig_ip_address" value="<?php echo $orig_ip_address; ?>"/>
 <input type="hidden" name="project" value="<?php echo $project; ?>"/>
 <input type="hidden" name="ident" value="<?php echo $ident; ?>"/>
 <input type="hidden" name="return" value="workingset"/>
<?php
} else {
?>
 <input type="hidden" name="row_name" value="<?php echo $row_name; ?>"/>
 <input type="hidden" name="cab_name" value="<?php echo $cab_name; ?>"/>
 <input type="hidden" name="searchname" value="<?php echo $searchname; ?>"/>
 <input type="hidden" name="RowName" value="<?php echo $RowName; ?>"/>
 <input type="hidden" name="parent_ip" value="<?php echo $parent_ip; ?>"/>
 <input type="hidden" name="orig_ip_address" value="<?php echo $orig_ip_address; ?>"/>
 <input type="hidden" name="project" value="<?php echo $project; ?>"/>
 <input type="hidden" name="ident" value="<?php echo $ident; ?>"/>
 <input type="hidden" name="system_name" value="<?php echo $system_name; ?>"/>
 <input type="hidden" name="return" value="search"/>
<?php
}
?>
 <input type="submit" name="manIPadd" value="Add IPs">
 </form>	
<?php	

} else { // END MANUAL
$result = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE row_name = '$RowName' AND (hostname = '' OR hostname IS NULL) AND (model = '' OR model IS NULL) AND (cab = '' OR cab IS NULL) AND (unit = '' OR unit IS NULL) AND (comments = '' OR comments IS NULL) LIMIT $howmany") or die(mysql_error());

$update_parent = mysql_query("UPDATE lab_ip_data.aqn_master_table SET isParentIP='1' WHERE address='$parent_ip'") or die(mysql_error());
$N = 0;
//$result_num_children = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE parentIP='$parent_ip'") or die(mysql_error());
$childnamecheck = $system_name.$ident;
$result_num_children = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE hostname LIKE '%$childnamecheck%'") or die(mysql_error());
$N = mysql_num_rows($result_num_children);
while($row = mysql_fetch_array( $result )) {
$ip_address =  $row['address'];
$N++;
$child_system_name = $system_name.$ident.($N);

$get_parent_info = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE address='$parent_ip'") or die(mysql_error());
$row_parent_info = mysql_fetch_array($get_parent_info);
$parent_model = $row_parent_info['model'];
$parent_cab = $row_parent_info['cab'];
$parent_unit = $row_parent_info['unit'];
$child_comments = 'Added for Parent Host ' . $system_name;

$update_child = mysql_query("UPDATE lab_ip_data.aqn_master_table SET hostname='$child_system_name', model = '$parent_model', cab = '$parent_cab', unit = '$parent_unit', parentIP='$parent_ip', project = '$project', comments = '$child_comments' WHERE address='$ip_address'") or die(mysql_error());

  } //END SQL LOOP
if ($return == 'workingset'){
header("Location: manage_ip.php?system_name=$system_name&orig_ip_address=$orig_ip_address&return=workingset&row_name=$row_name&cab_name=$cab_name&sitename=$site_name");
	} else {
header("Location: manage_ip.php?system_name=$system_name&orig_ip_address=$orig_ip_address&return=search&searchname=$searchname");
	} // END RETURN
    }
} elseif (isset($_POST['change'])) { // END ADD CHILD - START CHANGE IP
$id = $_POST['id'];
$RowName = $_POST['RowName'];
$orig_rowname = $_POST['rowname_ip'];
$orig_address = $_POST['address'];
$system_name = $_POST['system_name'];
$rowname_ip = $_POST['rowname_ip'];
$result_row = mysql_query("select * from lab_ip_data.row_definition where row_name = '$rowname_ip'") or die(mysql_error());
$rowname = mysql_fetch_array($result_row);
$visible_row_name = $rowname['visible_row_name'];

?>
<h1><b><p> Location Change For <?php echo $system_name; ?>  </b></p></h1>
<h2><b><p> Currently located in <?php echo $visible_row_name ?> </b></p></h2>

<table class="tablesorter" border='1' cellpadding='10'>
<form action="ip_change_loc.php" method="post">
<tr> <th> </th> <th>Select New Area </th> <th>Select New Row</th> <th>Slect New Cab</th> <th>Select New Unit</th> <th> </th> </tr>
<div>
 <tr bgcolor="#EAF2D3">
<?php
$result = mysql_query("SELECT * FROM lab_ip_data.row_definition where ip_type = 'data' or ip_type = 'mgmt'");
$options = "";

     while ($row = mysql_fetch_array($result)) {
     $visible_row_name = $row["visible_row_name"];
     $RowName = $row["row_name"];
     if ($RowName == $rowname_ip){
     $options .= "<option selected = \"selected\" <option value=\"$RowName\">".$visible_row_name.'</option>'; } else {
     $options .= "<option value=\"$RowName\">".$visible_row_name.'</option>'; }
     }
?>
 <td> <input type="submit" name="changeloc" value="Change">  </td>
<?php 
if ($return == 'workingset'){
?>
 <input type="hidden" name="row_name" value="<?php echo $row_name; ?>"/>
 <input type="hidden" name="cab_name" value="<?php echo $cab_name; ?>"/>
 <input type="hidden" name="sitename" value="<?php echo $site_name; ?>"/>
 <input type="hidden" name="id" value="<?php echo $id; ?>"/>
 <input type="hidden" name="system_name" value="<?php echo $system_name; ?>"/>
 <input type="hidden" name="orig_ip_address" value="<?php echo $orig_ip_address; ?>"/>
 <input type="hidden" name="orig_address" value="<?php echo $orig_address; ?>"/>
 <input type="hidden" name="orig_rowname" value="<?php echo $orig_rowname; ?>"/>
 <input type="hidden" name="return" value="workingset"/>
<?php
} else {
?>
 <input type="hidden" name="row_name" value="<?php echo $row_name; ?>"/>
 <input type="hidden" name="cab_name" value="<?php echo $cab_name; ?>"/>
 <input type="hidden" name="searchname" value="<?php echo $searchname; ?>"/>
 <input type="hidden" name="id" value="<?php echo $id; ?>"/>
 <input type="hidden" name="orig_address" value="<?php echo $orig_address; ?>"/>
 <input type="hidden" name="orig_rowname" value="<?php echo $orig_rowname; ?>"/>
 <input type="hidden" name="system_name" value="<?php echo $system_name; ?>"/>
 <input type="hidden" name="return" value="search"/>
<?php
}
?>
 <td> <select name="RowName"> <?php echo $options ?> </select> </td>
<?php
$result = mysql_query("SELECT * FROM aqn_inv_master_table WHERE id='$id' FOR UPDATE") or die(mysql_error());
$row = mysql_fetch_array($result);
$unit_location = $row['unit_location'];
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
        <td><select name = "edit_rowname"> <?php echo $edit_options_row ?> </select> </td>
        <td><select name = "edit_cabname"> <?php echo $edit_options_cab ?> </select> </td>
        <?php
        $result = mysql_query("SELECT * FROM unit_definition") or die(mysql_error());
        while ($row = mysql_fetch_array($result)) {
        $edit_unit_location = $row["unit_number"];
        if ($edit_unit_location == $unit_location) {
        $edit_unit_location_row .= "<option selected = \"selected\" value=\"$edit_unit_location\">".$edit_unit_location.'</option>'; }        else {
        $edit_unit_location_row .= "<option value=\"$edit_unit_location\">".$edit_unit_location.'</option>'; }
        }
        ?>
	 <td><select name = "unit_location"> <?php echo $edit_unit_location_row ?> </select></td>
<?php
if ($return == 'workingset'){
?>
        <form method="post">
         <td><input type="submit" name="cancel" value="Cancel" onclick="this.form.action='working_set.php?rowname=<?php echo $row_name ?>&cabname=<?php echo $cab_name ?>&sitename=<?php echo $site_name ?>'"/> </td>
        </form>
<?php
        } else {
?>
	<form method="post">
	<td><input type="submit" name="return" value="Cancel" onclick="this.form.action='search.php?go&name=<?php echo $searchname ?>'"/> </td>
	</form>
<?php
        }
?>
 </tr>
</table>
</form>
<?php
} elseif (isset($_POST['release'])) { // END CHANGE IP // RELEASE START

$parent_ip = $_POST['parent_ip'];

$release = mysql_query("UPDATE lab_ip_data.aqn_master_table SET isParentIP = '', parentIP = '', hostname = '', model = '', cab = '', unit = '', project='', comments = '' WHERE address = '$parent_ip'") or die(mysql_error());

if ($return == 'workingset'){
header("Location: working_set.php?rowname=$row_name&cabname=$cab_name&sitename=$site_name");
} else {
header("Location: search.php?go&name=$searchname");
}                                    

} //MAIN END

?>
</center>
</body>
</html>

