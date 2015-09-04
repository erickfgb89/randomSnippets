<?php
include('connect-db-storedprocedure.php');

$result = mysqli_query("CALL findEmpties('row_j_37_48',8)");

while($row = mysqli_fetch_array( $result )) {
$ip_address =  $row['address'];

echo ' ' . $ip_address . ' ';
}
