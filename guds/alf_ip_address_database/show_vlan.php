<html>
<head>
<style type="text/css">

 table, td, th {
 border-collapse: collapse;
 border: 1px solid black;
 border-spacing: 0px;
 }
 th
 {
 font-size:1.1em;
 background-color: #98bf21;
 color: white;
 }
 td
{
text-align: left;
}

</style>

        <title>Show ALF VLan Information</title>
</head>
<body>
<center>
<h1><b><p> Show ALF VLan Information </b></p></h1>
<input type="button" name="Return" value="Return" onclick="window.location.href='view.php'" />
<?php
include('connect-db.php');
$result = mysql_query("SELECT * FROM row_definition WHERE gateway_address IS NOT NULL and vlan IS NOT NULL") or die(mysql_error());
echo "<table id=\"sortedtable\" border='1' cellpadding='10'>";
	echo "<tr>";
	echo "<th>Row Name</th>";
        echo "<th>Gateway</th>";
        echo "<th>VLan</th>";
	echo "</tr>";

	$rowcount = 0;
        while($row = mysql_fetch_array( $result )) {
        $rowcount++;
        if ($rowcount % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
	echo '<td>' . $row['visible_row_name'] . '</td>';
	echo '<td>' . $row['gateway_address'] . '</td>';
	echo '<td>' . $row['vlan'] . '</td>';
	echo "</tr>";
	}
        echo "</form>";
        echo "</table>";
?>
<input type="button" name="Return" value="Return" onclick="window.location.href='view.php'" />
</center>
</body>
</html>
