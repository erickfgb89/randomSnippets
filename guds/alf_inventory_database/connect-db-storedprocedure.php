<?php
/* 
 CONNECT-DB.PHP
 Allows PHP to connect to your database
*/

// Verify session

session_start();
if(!session_is_registered('invusername')){
header("Location: index.php");
}

 // Database Variables (edit with your own server information)
 $server = 'localhost';
 $user = 'inventory_user';
 $pass = 'Def4$P_W';
 $db = 'lab_inventory';
 
 // Connect to Database
 $sp_connection = mysqli_connect($server, $user, $pass, $db); 
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>
