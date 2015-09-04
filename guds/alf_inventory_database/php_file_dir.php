<?php
// open the current directory
$dhandle = opendir('/data/http_data/www/html/spp');
// define an array to hold the files
$files = array();

if ($dhandle) {
   while (false !== ($fname = readdir($dhandle))) {
	if (strpos($fname, "SPP-")===0){
          $files[] = $fname;
      }
   }
   // close the directory
   closedir($dhandle);
}

?>
<select name = "spp_select"> 
<?php
// Now loop through the files, echoing out a new select option for each one
foreach( array_reverse($files) as $fname )
{
   echo "<option>{$fname}</option>\n";
}
echo "</select>\n";
?>
