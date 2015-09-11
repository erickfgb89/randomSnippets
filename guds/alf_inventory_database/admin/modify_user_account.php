<html>
<head>
<center>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<title>Edit Username/E-mail</title>
</head>
<body>
<?php
include('../connect-db.php');
$id = $_GET['id'];
$result = mysql_query("SELECT * FROM users WHERE id='$id' FOR UPDATE") or die(mysql_error());
$row = mysql_fetch_array($result);
$username = $row['username'];
$current_level = $row['level'];
?>
<table class="tablesorter" border='1' cellpadding='10'>
<tr>  <th>User Name/E-mail</th> <th>Current Level</th> </tr>
<form action="modify_user_account_submit.php" method="post" onReset="return confirm('Do you really want to reset the form?')">
<input type="hidden" name="id" value="<?php echo $id; ?>"/>
<div>
<tr bgcolor="#EAF2D3">
<td> <input type="text" name="edit_username" value="<?php echo $username; ?>"/></td>
<?php
$result = mysql_query("SELECT * FROM user_levels") or die(mysql_error());
while ($row = mysql_fetch_array($result)) {
$level = $row["level"];
if ($level == $current_level){
$edit_level .= "<option selected = \"selected\" value=\"$level\">".$level.'</option>'; } else {
$edit_level .= "<option value=\"$level\">".$level.'</option>'; }
}
?>
<td><select name = "edit_level"> <?php echo $edit_level ?> </select></td>
</tr>
</table>
<input type="submit" name="submit" value="Submit">
<input type="submit" name="cancel" value="Cancel" onclick="this.form.action='admin_user_accounts.php'">
<input type="reset">
</div>
</form>
</body>
</center>
</html>

                                                              

