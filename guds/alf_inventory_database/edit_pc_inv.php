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
$site_name = $_GET['sitename'];
$site_name_upper = strtoupper($site_name);
?>

<h1><b><p> Edit <?php echo $site_name_upper;?> Power Can Inventory </b></p></h1>

<?php
 include('connect-db.php');
?>

 <table class="tablesorter" border='1' cellpadding='10'>
 <tr>
    <th>Power Can Type</th>
    <th>Total Count</th>
    <th>Total Used In Lab</th>
    <th>Description</th>
 </tr>

<?php
         $result_main = mysql_query("SELECT * FROM powercan_definition WHERE site_name='$site_name' FOR UPDATE") or die(mysql_error());
         while($row_main = mysql_fetch_array( $result_main )) {
         $id = $row_main['id'];
         $powercan_type = $row_main['powercan_type'];
         $total_count = $row_main['total_count'];
         $total_used = $row_main['total_used'];
         $powercan_description = $row_main['powercan_description'];
?>

<form action="update_pc_inv.php" method="post" onReset="return confirm('Do you really want to reset the form?')">
 <input type="hidden" name="id[]" value="<?php echo $id; ?>"/>
 <input type="hidden" name="sitename[]" value="<?php echo $site_name; ?>"/>
 <div>
 <tr bgcolor="#EAF2D3">
 <td> <input type="text" name="powercan_type[]" value="<?php echo $powercan_type; ?>"/></td>
 <td> <input type="text" name="total_count[]" value="<?php echo $total_count; ?>"/></td>
 <td> <?php echo $total_used; ?> </td>
 <input type="hidden" name="total_used[]" value="<?php echo $total_used; ?>"/>
 <td> <input type="text" name="powercan_description[]" value="<?php echo $powercan_description; ?>"/></td>
 <?php
 }
 ?>
 </tr>
 </table>
 <input type="submit" name="submit" value="Submit">
 <input type="submit" name="submit" value="Cancel" onclick="this.form.action='view_pc_inv.php?sitename=<?php echo $site_name;?>';"/>
 <input type="reset">
 </div>
 </form>
 </body>
 </center>
 </html>

