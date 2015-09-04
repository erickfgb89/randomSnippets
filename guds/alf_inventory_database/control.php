<?php

include('connect-db.php');
$remote_user = $_SERVER['REMOTE_USER'];
?>
<h2><b><p> Welcome <?php echo $remote_user . " \\"; ?> Access Level is <?php echo $level; ?></b></p></h2>
<?php
$result = mysql_query("SELECT * FROM cab_definition") or die(mysql_error());

$options = "";

     while ($row = mysql_fetch_array($result)) {
     $visible_cab_name = "Cab " . $row['visible_cab_name'];
     $cab_name = $row["cab_name"];
     $options_cab .= "<option value=\"$cab_name\">".$visible_cab_name.'</option>';
}

$result = mysql_query("SELECT * FROM row_definition") or die(mysql_error());

     while ($row = mysql_fetch_array($result)) {
     $visible_row_name = "Row " . $row["visible_row_name"];
     $row_name = $row["row_name"];
     $options_row .= "<option value=\"$row_name\">".$visible_row_name.'</option>';
}

$result = mysql_query("SELECT * FROM site_definition") or die(mysql_error());

     while ($row = mysql_fetch_array($result)) {
     $site_name = $row["site_name"];
     if ($site_name == 'alf') {
     $options_site .= "<option selected = \"selected\" value=\"$site_name\">".$site_name.'</option>'; } else {
     $options_site .= "<option value=\"$site_name\">".$site_name.'</option>'; }
}

?>
<html>
<body>
<table>
<tr>
<td>
<form action="" target="working_set">
	<select name = "sitename"><?php echo $options_site ?> </select>
        <select name = "rowname"> <option value = 0 >choose a row <?php echo $options_row ?> </select>
        <select name = "cabname"> <option value = 0 >view all cabs <?php echo $options_cab ?> </select>
        <input type="submit" name="submit" value="Submit" onclick="this.form.action='working_set.php?rowname=<?php $options_row ?>&cabname=<?php $options_cab ?>&sitename=<?php $options_site ?>'"/>
<?php
if ($level >= 5) {

 $result_count = mysql_query("SELECT * FROM count_table") or die(mysql_error());
 while ($row_count = mysql_fetch_array($result_count)) {
 $pc_count = $row_count['count'];
 $options_count .= "<option value=\"$pc_count\">".$pc_count.'</option>';
 }
?>

<select name = "howmany"> <?php echo $options_count; ?>
<input type="submit" name="add_milti" value="Add" onclick="this.form.action='add_blank_asset.php?rowname=<?php $options_row;?>&cabname=<?php $options_cab ?>&sitename=<?php $options_site ?>'"/>
</td>
<?php
}
?>
</form>
</tr>
</table>

<table>
<tr>
<td>
<form action="search.php?go" target="working_set" method="post">
      <input  type="text" name="name">
      <input  type="submit" name="submit" value="Search">
      <input  type="submit" name="hardware" value="Hardware Search"> 
      <input  type="submit" name="ipsearch" value="IP Address Search">
</td>
</form>
</tr>
</table>

<table>
<tr>
<td>
<form action="" target="working_set">
        <input type="submit" name="ipdatabase" value="IP Database" onclick="this.form.action='../alf_ip_address_database/index.php'"/>
</td>
</form>
<td>
<?php
if ($level >= 3) {
?>
<form action="" target="working_set">
	<input type="submit" name="deploy" value="View Deploy Jobs" onclick="this.form.action='deploy_view_jobs.php'"/>
</td>
</form>
<?php
}
if ($level >= 10) {
?>
<td>
<form action="admin/admin.php" target="working_set" method="post">
        <input type="submit" name="submit" value="Admin Functions">
</td>
</form>
<?php
}
?>
</tr>
</table>
</body>
</html>


