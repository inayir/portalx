<?php
/*
	Creates a password for rules
*/
error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
header('Content-Type:text/html; charset=utf-8');
$log=date("Y-m-d H:i:s", strtotime("now")).";";
@$passformat=$ini['passformat']; // "aaaAAA99a!"
if($passformat==''){ $passformat="aaaAAA99a!";}
//$stdpass=$ini['stdpass']; //?
//if($passformat==''){ echo '[{"key": "pwd", "pss":"'.$stdpass.'"}]'; exit; }
@$pass="";
$a1="abcdefghijklmnoprstuvyz";
$a2="ABCDEFGHIJKLMNOPRSTUVYZ";
$n="1234567890";
$e="!?.-_=()%+";
//echo "crpass acoording to:".$passformat."<br>";
//passformat daki duruma göre işler.
for($pr=0;$pr<strlen($passformat);$pr++){
	switch($passformat[$pr]){
		case 'a': $par=$a1; break;
		case 'A': $par=$a2; break;
		case '9': $par=$n;  break;
		case '!': $par=$e;  break;
		default: $par=$a1; 
	}
	$r=rand(1,strlen($par)); 
	$pass.=$par[$r];
}
$json='[{"key": "pwd", "pss":"'.$pass.'"}]'; 
echo $json;
?>