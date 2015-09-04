<?php

if($_POST['windows'] == '0'){
        $os_name=$_POST['linux'];
	$os_type='linux';
        } 

if($_POST['linux'] == '0'){
	$os_name=$_POST['windows'];
	$os_type='windows';
	}

$row_name = $_POST['row_name'];
$cab_name = $_POST['cab_name'];
$site_name = $_POST['site_name'];
$serial_number = $_POST['serial_number'];
$system_name = $_POST['system_name'];
$ilo_name = 'ilo-' . $system_name;
$spp = $_POST['spp'];
$power_on = 0;

if ($spp == 'yes'){
$spp_version = $_POST['spp_select']; } else {
$spp_version = 'None'; }

if($os_name == '0' || $os_name == ''){
        header("Location: working_set.php?rowname=$row_name&cabname=$cab_name&sitename=$site_name");
	exit;
        }

function linuxRH($os_name,$ks,$ilo_name,$append,$spp)
{
$power_on = 0;
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
       exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/Set_Host_Power_On_NetBoot.xml", $command_output);
       $power_on = 1;
    }
  }

if ($power_on != 1) {
exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/PXE-Reset_Server.xml", $reboot_output);
for ($i = 0; $i < count($reboot_output); $i++) {
    $bios_check = preg_match("/Post in progress/i", $reboot_output[$i]);
    if ($bios_check == 1) {
       die("Cannot reboot...BIOS in edit mode. Please check and try again");
    }
  }  
}

exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/Get_Host_Data.xml |grep -o -E '([[:xdigit:]]{1,2}-){5}[[:xdigit:]]{1,2}' |tr '[:upper:]' '[:lower:]'", $output);

if ($output == NULL) {
die("Ilo Did Not Return Any Data... Please Check Manually and try again...") ;
}

foreach(array_slice($output,0,count($output)) as $mac) {

$file = '/var/lib/tftpboot/pxelinux.cfg/01-' . $mac;
$file_contents = "default 1\n";
$file_contents .= "label 1\n";
$file_contents .= "timeout 1\n";
$file_contents .= "kernel $os_name/vmlinuz\n";
$file_contents .= "IPAPPEND 2\n";
if ($spp == 'yes'){
$file_contents .= $append . " ks=$ks/ks.cfg\n"; } else {
$file_contents .= $append . " ks=$ks/ks-nospp.cfg\n"; }
file_put_contents($file, $file_contents);
	} 
}


function linuxSL($os_name,$ks,$ilo_name,$spp)
{
$power_on = 0;
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
       exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/Set_Host_Power_On_NetBoot.xml", $command_output);
       $power_on = 1;
    }
  }

if ($power_on != 1) {
exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/PXE-Reset_Server.xml", $reboot_output);
for ($i = 0; $i < count($reboot_output); $i++) {
    $bios_check = preg_match("/Post in progress/i", $reboot_output[$i]);
    if ($bios_check == 1) {
       die("Cannot reboot...BIOS in edit mode. Please check and try again");
    }
  }  
}

exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/Get_Host_Data.xml |grep -o -E '([[:xdigit:]]{1,2}-){5}[[:xdigit:]]{1,2}' |tr '[:upper:]' '[:lower:]'", $output);

if ($output == NULL) {
die("Ilo Did Not Return Any Data... Please Check Manually and try again...") ;
}

foreach(array_slice($output,0,count($output)) as $mac) {

$file = '/var/lib/tftpboot/pxelinux.cfg/01-' . $mac;
$file_contents = "default 1\n";
$file_contents .= "label 1\n";
$file_contents .= "timeout 1\n";
$file_contents .= "kernel $os_name/linux\n";
if ($spp == 'yes'){
$file_contents .= "append initrd=$os_name/initrd ramdisk_size=65536 $ks.xml\n"; } else {
$file_contents .= "append initrd=$os_name/initrd ramdisk_size=65536 $ks-nospp.xml\n"; }
file_put_contents($file, $file_contents);
     }
}

function windowsdeploy($os_name,$ilo_name,$serial_number,$spp)
{
$power_on = 0;
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
       exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/Set_Host_Power_On_NetBoot.xml", $command_output);
       $power_on = 1;
    }
  }

if ($power_on != 1) {
exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/PXE-Reset_Server.xml", $reboot_output);
for ($i = 0; $i < count($reboot_output); $i++) {
    $bios_check = preg_match("/Post in progress/i", $reboot_output[$i]);
    if ($bios_check == 1) {
       die("Cannot reboot...BIOS in edit mode. Please check and try again");
    }
  }  
}
exec("/data/http_data/www/html/iLo-scripts/locfg.pl -s $ilo_name -f /data/http_data/www/html/iLo-scripts/Get_Host_Data.xml |grep -o -E '([[:xdigit:]]{1,2}-){5}[[:xdigit:]]{1,2}' |tr '[:upper:]' '[:lower:]'", $output);

if ($output == NULL) {
die("Ilo Did Not Return Any Data... Please Check Manually and try again...") ;
}

foreach(array_slice($output,0,count($output)) as $mac) {

$file = '/var/lib/tftpboot/pxelinux.cfg/01-' . $mac;
$file_contents = "default 1\n";
$file_contents .= "label 1\n";
$file_contents .= "timeout 1\n";
$file_contents .= "LINUX memdisk\n";
$file_contents .= "INITRD ISO/windows_deploy.iso\n";
$file_contents .= "APPEND iso raw\n";
file_put_contents($file, $file_contents);
}

if ($spp == 'yes'){
$xml_file='Autounattend.xml'; } else {
$xml_file='Autounattend-nospp.xml'; }

$file = '/data/samba_data/deploy_scratch/' . $serial_number . '.cmd';
$file_contents = "@echo off\n";
$file_contents .= "net use z: \\\\10.252.248.70\pe\n";
$file_contents .= "diskpart /s z:\disk-clean.txt\n";

$file_contents .= "z:\\$os_name\setup.exe /unattend:\\\\10.252.248.70\pe\\$os_name\\$xml_file\n";

file_put_contents($file, $file_contents);
}

if($os_type == 'windows'){
	windowsdeploy($os_name,$ilo_name,$serial_number,$spp);
        }

if($os_type == 'linux'){

	if (preg_match("/rhel5.1/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel5/5.1'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel5.2/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel5/5.2'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel5.3/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel5/5.3'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel5.4/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel5/5.4'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel5.5/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel5/5.5'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel5.6/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel5/5.6'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel5.7/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel5/5.7'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel5.8/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel5/5.8'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel5.9/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel5/5.9'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel5.10/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel5/5.10'; 
						  $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel6.0/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel6/6.0'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel6.1/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel6/6.1'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel6.2/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel6/6.2'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel6.3/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel6/6.3'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel6.4/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel6/6.4'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
	if (preg_match("/rhel6.5/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel6/6.5'; 
						 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
        if (preg_match("/rhel6.6/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel6/6.6';
                                                 $append="append initrd=$os_name/initrd.img ksdevice=bootif";}
        if (preg_match("/rhel7.0/", $os_name)) { $ks='http://10.252.248.70/kickstart/rhel7/7.0';
                                                 $append="append initrd=$os_name/initrd.img ksdevice=bootif net.ifnames=0";}
	
	if (preg_match("/sles10sp1/", $os_name)) { $ks='install=http://10.252.248.70/pxe/sles10-sp1-x86_64 autoyast=http://10.252.248.70/kickstart/sles10/sles10.ancient-autoyast'; }
	if (preg_match("/sles10sp2/", $os_name)) { $ks='install=http://10.252.248.70/pxe/sles10-sp2-x86_64 autoyast=http://10.252.248.70/kickstart/sles10/sles10.ancient-autoyast'; }
	if (preg_match("/sles10sp3/", $os_name)) { $ks='install=http://10.252.248.70/pxe/sles10-sp3-x86_64 autoyast=http://10.252.248.70/kickstart/sles10/sles10-autoyast'; }
	if (preg_match("/sles10sp4/", $os_name)) { $ks='install=http://10.252.248.70/pxe/sles10-sp4-x86_64 autoyast=http://10.252.248.70/kickstart/sles10/sles10-autoyast'; }
	if (preg_match("/sles11/", $os_name)) 	 { $ks='install=http://10.252.248.70/pxe/sles11-x86_64 autoyast=http://10.252.248.70/kickstart/sles11/sles11-autoyast';}
	if (preg_match("/sles11sp1/", $os_name)) { $ks='install=http://10.252.248.70/pxe/sles11-sp1-x86_64 autoyast=http://10.252.248.70/kickstart/sles11/sles11-autoyast';}
	if (preg_match("/sles11sp2/", $os_name)) { $ks='install=http://10.252.248.70/pxe/sles11-sp2-x86_64 autoyast=http://10.252.248.70/kickstart/sles11/sles11-autoyast';}
	if (preg_match("/sles11sp3/", $os_name)) { $ks='install=http://10.252.248.70/pxe/sles11-sp3-x86_64 autoyast=http://10.252.248.70/kickstart/sles11/sles11.3-autoyast';}
	if (preg_match("/sles12/", $os_name))	 { $ks='install=http://10.252.248.70/pxe/sles12-x86_64 autoyast=http://10.252.248.70/kickstart/sles12/sles12-autoyast';}
	
	if (preg_match("/rhel/", $os_name)) { linuxRH($os_name,$ks,$ilo_name,$append,$spp); }
	if (preg_match("/sles/", $os_name)) { linuxSL($os_name,$ks,$ilo_name,$spp); }
        }

include('connect-db.php');
$current_date=date('Y-m-d H:i:s');
$result = mysql_query("SELECT * FROM deploy_system_status WHERE system_serial_number = '$serial_number'");
if (mysql_num_rows($result) < 1) {
mysql_query("INSERT INTO `deploy_system_status` (system_name, system_serial_number, deploy_start_time, percent_complete, script_location, spp_option, os_name) VALUES ('$system_name', '$serial_number', '$current_date', '10', 'Initial Reboot and OS install', '$spp_version', '$os_name')") or die(mysql_error());
} else {
 mysql_query("UPDATE `deploy_system_status` SET system_name='$system_name', system_serial_number='$serial_number', deploy_start_time='$current_date', percent_complete='10', script_location='Initial Reboot and OS install', spp_option='$spp_version', os_name='$os_name'  WHERE system_serial_number='$serial_number'") or die(mysql_error());
}

mysql_query("INSERT INTO `deploy_os_history` (system_name, system_serial_number, deploy_datetime, os_name, os_type, spp_option) VALUES ('$system_name', '$serial_number', '$current_date', '$os_name', '$os_type', '$spp_version')") or die(mysql_error());

header("Location: working_set.php?rowname=$row_name&cabname=$cab_name&sitename=$site_name");
?>

