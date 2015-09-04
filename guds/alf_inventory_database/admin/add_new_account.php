<html>
<head>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
</head>
<body>
<center>
<h1><b><p> Add A User </b></p></h1>
<?php
include('../connect-db.php');

?>

<table class="tablesorter" border='1' cellpadding='10'>
<tr> <th>Username/E-mail</th> <th>Access Level</th> </th> </tr>
<form action="add_new_account_submit.php" method="post">
 <div>
 <tr bgcolor="#EAF2D3">
 <td> <input type="text" name="username"/></td>
<?php
 $result_count = mysql_query("SELECT * FROM user_levels") or die(mysql_error());
 while ($row_count = mysql_fetch_array($result_count)) {
 $level = $row_count['level'];
 $options_level .= "<option value=\"$level\">".$level.'</option>';
 }
?>

 <td><select name = "accesslevel"> <?php echo $options_level; ?></td>
 </tr>
 </table>
 <input type="submit" name="submit" value="Submit">
 <input type="submit" name="submit" value="Cancel" onclick="this.form.action='admin_user_accounts.php';"/>
 </div>
 </form>
</center>
</body>
</html>

