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
<script type="text/javascript" src="jquery-1.3.1.min.js"></script>
        <script type="text/javascript" src="jquery.tablesorter.min.js"></script>
        <script type="text/javascript">
                        $(document).ready(function() {
                                $("#sortedtable").tablesorter({ sortlist: [0,0] });
                        });
                </script>
        <style type="text/css">
                        #sortedtable thead th {
                                color: #00f;
                                font-weight: bold;
                                cursor: pointer;
                                text-decoration: underline;
                        }
                </style>
</head>
<body>
<center>

<?php
include('connect-db.php');
$row_select = $_GET['RowName'];
$address=$_GET['address'];
$hostname=$_GET['hostname'];
if(isset($_GET['search'])){
$search=$_GET['search'];
$name=$_GET['name'];}
?>
<h1><b><p> ALF IP Database History For <?php echo $address;?></b></p></h1>
<?php
$sql = "SELECT * FROM row_definition WHERE row_name = '$row_select'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
        echo '<p> </p>';
        echo '<b>Viewing Row: ' . $row['visible_row_name'] . '</b>';

        if (preg_match ("/\b(10.226.200.|10.226.201.|10.226.202.|10.226.203.|10.226.204.|10.226.205.|10.226.206.|10.226.207.|10.226.216.|10.226.217.|10.226.218.|10.226.219.|10.226.220.|10.226.221.|10.226.222.|10.226.223.|10.226.232.|10.226.233.|10.226.234.|10.226.235.|10.226.236.|10.226.237.|10.226.238.|10.226.239.|10.226.248.|10.226.249.|10.226.250.|10.226.251.|10.226.252.|10.226.253.|10.226.254.|10.226.255.)\b/i", $address))
             $netmask_address = '255.255.248.0';
        elseif (preg_match ("/\b(10.226.240.|10.226.241.|10.226.242.|10.226.243.|10.226.244.|10.226.245.|10.226.246.|10.226.247.|10.226.224.|10.226.225.|10.226.225.|10.226.226.|10.226.227.|10.226.228.|10.226.229.|10.226.230.|10.226.231.)\b/i", $address))
             $netmask_address = '255.255.240.0';
        else
	     $netmask_address = $row['netmask_address'];
        echo '<p> </p>';


$result = mysql_query("SELECT * FROM changes_made WHERE address = '$address'")
	or die(mysql_error());

if ($search == 1){
?>
<input type="button" name="Return" value="Return" onclick="window.location.href='search.php?go&name=<?php echo $name?>'" />
<?php
} else {
?>
<input type="button" name="Return" value="Return" onclick="window.location.href='view.php?RowName=<?php echo $row_select?>'" />
<?php
}
	echo '<p> </p>';
	echo "<table border='0' cellpadding='10'>";
	echo "<tr bgcolor=\"#EAF2D3\">";
	echo '<td><b>Hostname: ' . $hostname . '.alf.gsc.mvlabs.corp.hp.com </b><br />';
	echo '<b>IP Address: ' . $address . ' </b><br />';
	echo '<b>Gateway Address: ' . $row['gateway_address'] . ' </b><br />';
	echo '<b>Netmask Address: ' . $netmask_address . ' </b><br />';
	if (preg_match ("/\b(10.226.|10.252.)\b/i", $address)) 
		echo '<b>DNS Servers: 10.252.248.11 and 10.252.248.12 </b><br />';
	elseif (preg_match ("/\b(10.50.)\b/i", $address))  
		echo '<b>DNS Servers: 10.50.240.2 </b><br />';  
	elseif (preg_match ("/\b(10.40.)\b/i", $address))  
	        echo '<b>DNS Servers: 10.40.0.30 and 10.40.0.31 </b><br />';

	echo '</tr>';
	echo "<table id=\"sortedtable\" border='1' cellpadding='10'>";
        echo "<thead>\n<tr>";
        echo "<tr>";
        echo "<th>Address</th>";
        echo "<th>Remote Connection</th>";
        echo "<th>Changes Made</th>";
        echo "<th>Date/Time</th>";
        echo "</tr>\n</thead>\n";

	$rowcount = 0;
	while($row = mysql_fetch_array( $result )) {
        $rowcount++;
        if ($rowcount % 2) {
                print '<tr bgcolor="#EAF2D3">';
                           } else {
                print '<tr bgcolor="white">';
                           }
	echo '<td>' . $row['address'] . '</td>';
        echo '<td>' . $row['remote_hostname'] . '</td>';
        echo '<td>' . $row['changes_made'] . '</td>';
        echo '<td>' . $row['date_time'] . '</td>';
	echo "</tr>";
}
echo "</table>";
if ($search == 1){
?>
<input type="button" name="Return" value="Return" onclick="window.location.href='search.php?go&name=<?php echo $name?>'" />
<?php
} else {
?>
<input type="button" name="Return" value="Return" onclick="window.location.href='view.php?RowName=<?php echo $row_select?>'" />
<?php
}
?>
