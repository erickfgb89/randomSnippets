<?php
/* 
 CONNECT-DB.PHP
 Allows PHP to connect to your database
*/

// Database Variables (edit with your own server information)
 $server = 'localhost';
 $user = 'ip_address_user';
 $pass = 'Def4$P_W';
 $db = 'lab_ip_data';
 
 // Connect to Database
 $connection = mysql_connect($server, $user, $pass) 
 or die ("Could not connect to server ... \n" . mysql_error ());
 mysql_select_db($db) 
 or die ("Could not connect to database ... \n" . mysql_error ());

if(isset($_SERVER['REMOTE_USER'])){
$remote_user = $_SERVER['REMOTE_USER'];


$sql="SELECT username,level FROM lab_inventory.users users WHERE username='$remote_user'" or die(mysql_error ());
$result=mysql_query($sql);
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);

if($count==1){
$level = $row["level"];
}
else {
header('Location: bad_login.php');
}}
?>
