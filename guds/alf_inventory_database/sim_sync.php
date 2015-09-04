<?php

error_reporting(0);
$host_name = trim($argv[1]);
$ilo_name = 'ilo-' . $host_name;

exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/Get_Host_Power.xml", $command_output);

if ($command_output == NULL) {
die("Ilo Did Not Return Any Data... Please Check Manually and try again!") ;
}

for ($i = 0; $i < count($command_output); $i++) {
    $error_check = preg_match("/^Error : Failed to establish TCP connection/", $command_output[$i]);
    if ($error_check == 1) {
       die("Cannot connect... Please check IP/ILO/SYSTEM and try again!");
    }
    $error_check = preg_match("/HOST_POWER=\"OFF\"/", $command_output[$i]);
    if ($error_check == 1) {
       die("Cannot connect... Host is Powered OFF!");
    }
}

$waitTimeoutInSeconds = 1; 
if($fp = fsockopen($host_name,22,$errCode,$errStr,$waitTimeoutInSeconds)){   
	exec("ssh -x -i /data/http_data/www/.id_rsa ALFAdmin@alfsim1 'mxnode -a " . $host_name ." --user ALFSIM1\/gudssync --pass Password@123'", $sim_output);
	for ($i = 0; $i < count($sim_output); $i++) {
		echo $sim_output[$i];
		}
} else {
if($fp = fsockopen($host_name,3389,$errCode,$errStr,$waitTimeoutInSeconds)){   
        exec("ssh -x -i /data/http_data/www/.id_rsa ALFAdmin@alfsim1 'mxnode -a " . $host_name ." --user ALFSIM1\/gudssync --pass Password@123'", $sim_output);
        for ($i = 0; $i < count($sim_output); $i++) {
                echo $sim_output[$i];
                }

} else {
   echo "System Not Reachable... Please ensure that its powered up!";
}} 
fclose($fp);

?>
