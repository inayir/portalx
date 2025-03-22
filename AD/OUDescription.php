<?php
/*
	LDAP OU/SubOU description  alanını getirir, arraya koyar. 
*/
error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
header('Content-Type: text/html; charset=utf-8');
include($docroot."/config/config.php");
include($docroot."/sess.php");
include($docroot."/ldap.php");
$base_dn=$ini['base_dn']; //echo $base_dn;
$sea=$_GET['o']; echo " -> Aranan:".$sea."  "." <br>";
$ouliste=Array("ou", "description");
$oufilter="ou=*"; //$filter="objectClass=organizationalunit";
$ousr=ldap_list($conn, $base_dn, $oufilter, $ouliste);

$ouarr=array();
$ou=ldap_get_entries($conn, $ousr);
for ($i=0; $i < $ou["count"]; $i++){
	$m=$ou[$i]["description"][0];		
	if($m!=""){ $ouarr[$i]=array($ou[$i]["ou"][0],$m); }
}
function searchSubArray(Array $array, $key, $value) {   
    foreach ($array as $subarray){  
        if (isset($subarray[$key]) && $subarray[$key] == $value)
          return $subarray;       
    } 
}

$arr=searchSubArray($ouarr, 0, $sea);
echo "<br><br><br>key:".$arr[0]."  desc:".$arr[1];

?>