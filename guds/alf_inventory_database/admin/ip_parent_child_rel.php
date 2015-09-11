<html>
 <head>
 <center>

<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<title>IP Address Parent Child Relationships</title>
 </head>
 <body>
<h1><b><p> IP Address Parent Child Relationships </b></p></h1>
<?php
include('../connect-db.php');
$result = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table where isParentIP = '1'");
	while($row = mysql_fetch_array( $result )) {	
	$parent_address = $row['address'];
        $parent_hostname = $row['hostname'];

		echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
		echo '<tr><td>Parent Host Name: <a href=../search.php?go&name=' . $parent_hostname . '>' . $parent_hostname . '</td></tr></table>' ;

		echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
		echo "<thead>\n<tr>";
		echo "<th>Child Host Name</th>";
                echo "<th>Child IP Address</th>";
		echo "<th>Project/Feedback Number</th>";
		echo "</tr>\n</thead>\n";

        $rowcount = 0;
		$result_child = mysql_query("SELECT * FROM lab_ip_data.aqn_master_table where parentIP = '$parent_address'");
		while($row_child = mysql_fetch_array( $result_child )) {
		$child_address = $row_child['address'];
	        $child_hostname = $row_child['hostname'];
		$project = $row_child['project'];
        $rowcount++;
        if ($rowcount % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
                echo '<td>' . $child_hostname . '</td>';
                echo '<td>' . $child_address . '</td>';
		echo '<td>' . $project . '</td>';	
                echo "</tr>";
                       } 
	echo "</table>";
				}
?>
<form name="form" method="post">
<input type="submit" name="return" value="Return" onclick="this.form.action='admin.php'">
</form>
</center>
</body>
</html>

