<?php
include('connect-db.php');
if(isset($_POST['return'])){
    $return=$_POST['return'];
        if ($return == 'workingset'){
                $site_name = $_POST['sitename'];
        } else {
                $searchname = $_POST['searchname'];
                }
}
$RowName = $_POST['RowName'];
$system_name = $_POST['system_name'];
$row_name = $_POST['row_name'];
$cab_name = $_POST['cab_name'];
$unit = $_POST['unit'];
$model = $_POST['model'];

if(isset($_POST['manual'])){
?>
<html>
 <head>
 <center>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
 </head>
 <body>
<?php
$result = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE row_name = '$RowName' AND (hostname = '' OR hostname IS NULL) AND (model = '' OR model IS NULL) AND (cab = '' OR cab IS NULL) AND (unit = '' OR unit IS NULL) AND (comments = '' OR comments IS NULL)") or die(mysql_error());

        $count=mysql_num_rows($result);
?>
        <h1><b><p> IP Options For <?php echo $system_name; ?>  </b></p></h1>
        <h2><b><p> Please select IP Addresses  </b></p></h2>
        <table class="tablesorter" border='1' cellpadding='10'>
        <form action="ip_add_man.php" method="post">
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
        echo '<td><input type="radio" name="ip_address" value="' . $row['address'] . '"/></td>';
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
 <input type="hidden" name="model" value="<?php echo $model; ?>"/>
 <input type="hidden" name="unit" value="<?php echo $unit; ?>"/>
 <input type="hidden" name="return" value="workingset"/>
<?php
	} else {
?>
 <input type="hidden" name="row_name" value="<?php echo $row_name; ?>"/>
 <input type="hidden" name="cab_name" value="<?php echo $cab_name; ?>"/>
 <input type="hidden" name="searchname" value="<?php echo $searchname; ?>"/>
 <input type="hidden" name="system_name" value="<?php echo $system_name; ?>"/>
 <input type="hidden" name="model" value="<?php echo $model; ?>"/>
 <input type="hidden" name="unit" value="<?php echo $unit; ?>"/>
 <input type="hidden" name="return" value="search"/>
<?php
	}
?>
 </form>
<?php
} else {

$result_new_address = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table WHERE row_name = '$RowName' AND (hostname = '' OR hostname IS NULL) AND (model = '' OR model IS NULL) AND (cab = '' OR cab IS NULL) AND (unit = '' OR unit IS NULL) AND (comments = '' OR comments IS NULL)") or die(mysql_error());

$row = mysql_fetch_array($result_new_address);
$ip_address = $row['address'];

$result_location = mysql_query("SELECT * FROM cab_definition,row_definition WHERE cab_definition.cab_name = '$cab_name' AND row_definition.row_name = '$row_name'") or die(mysql_error());
$row_location = mysql_fetch_array($result_location);
$visible_row_name = $row_location['visible_row_name'];
$visible_cab_name = $row_location['visible_cab_name'];
$cab = $visible_row_name.$visible_cab_name;

$add_ip = mysql_query("UPDATE lab_ip_data.aqn_master_table SET hostname='$system_name', model = '$model', cab = '$cab', unit = '$unit', comments = '' WHERE address='$ip_address'") or die(mysql_error());

if ($return == 'workingset'){
header("Location: manage_ip.php?system_name=$system_name&orig_ip_address=$ip_address&return=workingset&row_name=$row_name&cab_name=$cab_name&sitename=$site_name");

        } else {
header("Location: manage_ip.php?system_name=$system_name&orig_ip_address=$ip_address&return=search&searchname=$searchname");

        } // END RETURN
}
