<?php
include('../connect-db.php');
if (isset($_POST['submit']))
 {
// get form data, making sure it is valid
 $username = mysql_real_escape_string(htmlspecialchars($_POST['username']));
 $accesslevel = mysql_real_escape_string(htmlspecialchars($_POST['accesslevel']));

mysql_query("INSERT INTO users (username, level) VALUES ('$username', '$accesslevel')") or die(mysql_error());

 }

header("Location: admin_user_accounts.php");
?>
