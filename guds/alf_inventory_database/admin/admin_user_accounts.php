<html>
<body>
<center>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
<h1><b><p> Manage User Accounts  </b></p></h1>
<?php
include('../connect-db.php');
$result = mysql_query("SELECT * FROM users where level IS NOT NULL OR level != '' ORDER BY username ASC") or die(mysql_error());

        echo "<form name=\"form\" method=\"post\">";
        echo "<table id=\"tablesorter\" class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
        echo "<th></th>";
        echo "<th>User Name/E-mail Address</th>";
        echo "<th>Current Level</th>";
        echo "<th></th>";
        echo "</tr>\n</thead>\n";

        $rowcount = 0;
        while($row = mysql_fetch_array( $result )) {
	        $rowcount++;
        		if ($rowcount % 2) {
		                print '<tr bgcolor="#EAF2D3">';
		                           } else {
		                print '<tr bgcolor="white">';
		                           }
		echo '<td><a href="delete_user_account.php?id=' . $row['id'] . '" onClick="return confirm(\'Are you SURE you want to delete this record?\')">Delete</a></td>';
		echo '<td>' . $row['username'] . '</td>';
                echo '<td>' . $row['level'] . '</td>';
		echo '<td><a href="modify_user_account.php?id=' . $row['id'] . '">Modify</a></td>';
                }
                echo "</tr>";
        // close table
        echo "</table>";
?>
<input type="submit" name="add_name" value="Add A New Account" onclick="this.form.action='add_new_account.php'"/>
<input type="submit" name="cancel" value="Cancel" onclick="this.form.action='admin.php'">
</form>
</center>
</body>
</html>

