<?php
/*
	OU ve SubOUdaki managedby içeriğini getirir.
*/
//error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
header('Content-Type:text/html; charset=utf8');
include($docroot."/config/config.php");
$base_dn=$ini['base_dn'];
require("ldap.php");
$ou=$_GET["ou"];
$sou=$_GET["sou"];
if($sou!=""){
	$base_dn="OU=".$ou.",".$base_dn;
}
//echo $ou."->".$sou."<br>".$base_dn."<br>";
$liste=Array("ou","managedby");

$filter="ou=*";
//$filter="objectClass=organizationalunit";
$sr=ldap_list($conn, $base_dn, $filter, $liste);
//echo "[";
$info=ldap_get_entries($conn, $sr);
//for ($i=0; $i < $info["count"]; $i++){
	if($info[0]["ou"][0]==$sou){
		//echo '"'.$info[$i]["ou"][0].'"';
		if($info[0]["managedby"][0]!=""){ 
			$json='{"managedby":"'.$info[0]["managedby"][0].'"}';
			try {
				//echo "enc:".mb_detect_encoding($html)."<br><br><br><br><br><br>";
				echo iconv('UTF-8','Windows-1252',$json); //
			} catch (Exception $e){
				echo $e;
			}
		}//*/
	}
//}
//echo "]";
//var_dump($info);
?>