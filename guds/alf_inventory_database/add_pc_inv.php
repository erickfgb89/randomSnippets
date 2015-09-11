<html>
<head>
<?php
$site_name = $_GET['sitename'];
$site_name_upper = strtoupper($site_name);
?>
<link rel="stylesheet" type="text/css" href="alf-tablesorter.css" />
</head>
<body>
<center>
<?php
?>
<h1><b><p> Add A Can To <?php echo $site_name_upper;?> Inventory </b></p></h1>

<?php
include('connect-db.php');
$result_pc = mysql_query("SELECT * FROM powercan_definition WHERE site_name='$site_name'") or die(mysql_error());

        echo "<table class=\"tablesorter\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
        echo "<th> </th>";
        echo "<th>Power Can Type</th>";
        echo "<th>Total Count</th>";
        echo "<th>Total Used In Lab</th>";
        echo "<th>Description</th>";
        echo "</tr>\n</thead>\n";
        $rowcount_pc = 0;
        while($row_pc = mysql_fetch_array( $result_pc )) {
        $rowcount_pc++;
        if ($rowcount_pc % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
                echo '<td><a href="del_pc_inv.php?&id=' . $row_pc['id'] .'" onClick="return confirm(\'Are you SURE you want to retire this record?\')">Delete</a></td>';
                echo '<td>' . $row_pc['powercan_type'] . '</td>';
                echo '<td>' . $row_pc['total_count'] . '</td>';
                echo '<td>' . $row_pc['total_used'] . '</td>';
                echo '<td>' . $row_pc['powercan_description'] . '</td>';
                echo "</tr>";
                        }
        echo "</table>";

?>

<table class="tablesorter" border='1' cellpadding='10'>
<tr> <th>Power Can Type</th> <th>Number Present</th> </th> </tr>
<form action="add_pc_inv_submit.php" method="post">
 <input type="hidden" name="site_name" value="<?php echo $site_name; ?>"/>
 <div>
 <tr bgcolor="#EAF2D3">
 <td> <input type="text" name="powercan_type" value="Power Can Type"/></td>
 <td> <input type="text" name="total_count" value="Total Count"/></td>
 </tr>
 </table>
 <input type="submit" name="submit" value="Submit">
 <input type="submit" name="submit" value="Cancel" onclick="this.form.action='view_pc_inv.php?sitename=<?php echo $site_name;?>';"/>
 </div>
 </form>
</center>
</body>
</html>

