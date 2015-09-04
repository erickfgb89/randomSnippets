<html>
<head>
<?php
include('../connect-db.php');
?>

<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
</head>
<body>
<center>

<h1><b><p> OS Inventory Database </b></p></h1>

<?php

$result = mysql_query("SELECT * FROM deploy_os_types ORDER BY os_name ASC") or die(mysql_error());

        echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
        echo "<th>OS Name</th>";
        echo "<th>OS Type</th>";
	echo "<th>Total Deployed</th>";
        echo "</tr>\n</thead>\n";

        $rowcount = 0;
        while($row = mysql_fetch_array( $result )) {
	$os_name = $row['os_name'];
	$result_os_count = mysql_query("SELECT count(os_name) from deploy_os_history where os_name='$os_name'") or die(mysql_error());
	$row_os_count = mysql_fetch_array( $result_os_count );
	$os_count = $row_os_count['count(os_name)'];
        $rowcount++;
        if ($rowcount % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
		echo '<td>' . $row['os_display_name'] . '</td>';
		echo '<td>' . $row['os_type'] . '</td>';
		echo '<td>' . $os_count . '</td>';
                echo "</tr>";
                        }
        echo "</table>";
?>
<form name="form" method="post">
<input type="submit" name="return" value="Return" onclick="this.form.action='admin.php'">
</form>
</center>
</body>
</html>

