<?php
include('../connect-db.php');
if (isset($_POST['submit']))
 {
$id = $_POST['id'];
$username = mysql_real_escape_string(htmlspecialchars($_POST['edit_username']));
$level = mysql_real_escape_string(htmlspecialchars($_POST['edit_level']));
mysql_query("UPDATE users SET username='$username', level='$level' WHERE id='$id'") or die(mysql_error());
 }
header("Location: admin_user_accounts.php");
