<?php
include('connect-db.php');
$row_select_return = $_GET['RowName'];
$ip_info = $_POST['ip_info'];
if(isset($_GET['search'])){
$search=$_GET['search'];
$name=$_GET['name'];}
$N = count($ip_info);
 for($i=0; $i < $N; $i++) {
$result = mysql_query("SELECT * FROM aqn_master_table WHERE address = '$ip_info[$i]'") or die(mysql_error());
$row = mysql_fetch_array($result);
$address = $row['address'];
$hostname = $row['hostname'];
$row_select = $row['row_name'];

        $sql = "SELECT * FROM row_definition WHERE row_name = '$row_select'";
        $result_2 = mysql_query($sql);
        $row_2 = mysql_fetch_array($result_2);

        if (preg_match ("/\b(10.226.200.|10.226.201.|10.226.202.|10.226.203.|10.226.204.|10.226.205.|10.226.206.|10.226.207.|10.226.216.|10.226.217.|10.226.218.|10.226.219.|10.226.220.|10.226.221.|10.226.222.|10.226.223.|10.226.232.|10.226.233.|10.226.234.|10.226.235.|10.226.236.|10.226.237.|10.226.238.|10.226.239.|10.226.248.|10.226.249.|10.226.250.|10.226.251.|10.226.252.|10.226.253.|10.226.254.|10.226.255.)\b/i", $address))
             $netmask_address = '255.255.248.0';
        elseif (preg_match ("/\b(10.226.240.|10.226.241.|10.226.242.|10.226.243.|10.226.244.|10.226.245.|10.226.246.|10.226.247.|10.226.224.|10.226.225.|10.226.225.|10.226.226.|10.226.227.|10.226.228.|10.226.229.|10.226.230.|10.226.231.)\b/i", $address))
             $netmask_address = '255.255.240.0';
        else
             $netmask_address = $row_2['netmask_address'];

        if (preg_match ("/\b(10.226.|10.252.)\b/i", $address)) 
		$dns = '10.252.248.11 10.252.248.12';
        elseif (preg_match ("/\b(10.50.)\b/i", $address))
		$dns = '10.50.240.2'; 
	elseif (preg_match ("/\b(10.40.)\b/i", $address))
                $dns = '10.40.0.30 10.40.0.31';
        echo '<td><b>Hostname: ' . $hostname . '.alf.gsc.mvlabs.corp.hp.com </b><br />';
        echo '<b>IP Address: ' . $address . ' </b><br />';
        echo '<b>Gateway Address: ' . $row_2['gateway_address'] . ' </b><br />';
        echo '<b>Netmask Address: ' . $netmask_address . ' </b><br />';
        echo '<b>DNS Servers: ' . $dns . ' </b><br />';
	echo '<br />';
	}
if ($search == 1){
?>
<input type="button" name="Return" value="Return" onclick="window.location.href='search.php?go&RowName=<?php echo $row_select_return?>&name=<?php echo $name?>'" />
<?php
} else {
?>
<input type="button" name="Return" value="Return" onclick="window.location.href='view.php?RowName=<?php echo $row_select_return?>'" />
<?php
}
?>
