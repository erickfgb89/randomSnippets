<html>
 <head>
 <center>

<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<title>Manage IP Address</title>
 </head>
 <body>
<?php
include('connect-db.php');
if(isset($_GET['return'])){
    $return=$_GET['return'];
        if ($return == 'workingset'){
                $site_name = $_GET['sitename'];
        } else {
                $searchname = $_GET['searchname'];
                }
}
$system_name = $_GET['system_name'];
$unit = $_GET['unit'];
$row_name = $_GET['row_name'];
$cab_name = $_GET['cab_name'];
$model = $_GET['model'];

$row1 = explode("_", $row_name);
$cab1 = explode("_", $cab_name);

$row = $row1[1];
$cab = $cab1[1];

if ($cab >= 1 && $cab <= 12) {
    $new_row = 'row_'.$row.'_1_12';
   } elseif ($cab >= 13 && $cab <= 24) {
    $new_row = 'row_'.$row.'_13_24';
   } elseif ($cab >= 25 && $cab <= 36) {
    $new_row = 'row_'.$row.'_25_36';
   } elseif ($cab >= 37 && $cab <= 48) {
    $new_row = 'row_'.$row.'_37_48';
   } elseif ($cab >= 49 && $cab <= 60) {
    $new_row = 'row_'.$row.'_49_60';
   }
?>
<h1><b><p> Add an IP For <?php echo $system_name; ?>  </b></p></h1>
<?php

$result = mysql_query("SELECT * FROM lab_ip_data.row_definition where ip_type = 'data' or ip_type = 'mgmt'");
$options = "";

     while ($row = mysql_fetch_array($result)) {
     $visible_row_name = $row["visible_row_name"];
     $RowName = $row["row_name"];
     if ($RowName == $new_row) {
     $options .= "<option selected = \"selected\" <option value=\"$RowName\">".$visible_row_name.'</option>'; } else {
     $options .= "<option value=\"$RowName\">".$visible_row_name.'</option>';} 
     }

?>
 <table class="tablesorter" border='1' cellpadding='10'>
 <form action="ip_add_submit.php" method="post">
 <tr> <th>Select Row </th> <th> </th> <th> </th> </tr>
 <td> <select name="RowName"> <?php echo $options ?> </select> </td>
 <td> <input type="checkbox" name="manual"/>Manual Select</td>
 <td> <input type="submit" name="addip" value="Add IP"> </td>
 </tr>
<?php
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
 </table>
 <table>
<?php
if ($return == 'workingset'){
?>
        <form method="post">
        <td> <input type="submit" name="cancel" value="Return" onclick="this.form.action='working_set.php?rowname=<?php echo $row_name ?>&cabname=<?php echo $cab_name ?>&sitename=<?php echo $site_name ?>'"/> </td>
        </form>
<?php
        } else {
?>
        <form method="post">
        <td> <input type="submit" name="return" value="Return" onclick="this.form.action='search.php?go&name=<?php echo $searchname ?>'"/> </td>
        </form>
<?php
        }
?>
 </table>
 </body>
 </center>
</html>

