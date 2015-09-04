<?php
include('../connect-db.php');
$id = $_GET['id'];

$result = mysql_query("SELECT * FROM users WHERE id='$id'") or die(mysql_error());
$row = mysql_fetch_array($result);

mysql_query("DELETE FROM users WHERE id='$id'") or die(mysql_error());
header("Location: admin_user_accounts.php");
