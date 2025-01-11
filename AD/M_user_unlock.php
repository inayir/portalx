<?php
/*
	useraccountcontrol enable etmek ve kilidi açmak
*/
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
include($docroot."/sess.php");
$log=date("Y-m-d H:i:s", strtotime("now"));
if($_SESSION["user"]==""){
	echo "login"; exit;
}
require("../ldap.php");

@$username=$_POST['u'];
if($username==""){ $username=$_GET['u']; }
echo $username.":";  
$ldap_result = ldap_search($conn, $ini['base_dn'], "(samaccountname=$username)");
if($ldap_result){ 
	$log.=";username: ".$username;
	$info = ldap_get_entries($conn, $ldap_result); //$keys=Array('useraccountcontrol');
	if($info["count"]>0){	
	   $userinfo['useraccountcontrol']=544;
	   $dn=$info[0]['distinguishedname'][0];
	   $sx=ldap_modify($conn, $dn, $userinfo);
	   if($sx){ echo $gtext['a_unlocked'];/*"Açılmıştır."*/ $log.=$gtext['a_unlocked']; }
	   else{ echo $gtext['a_notunlocked'];/*"AçılaMAdı!"*/ $log.=$gtext['a_notunlocked'];}
	   //ikinci bir ldap varsa.
	   if($ini['ldap_server2']!=''){
			define('LDAP_SERVER2', $ini['ldap_server2']);  
			$conn2=ldap_connect('ldap://'.LDAP_SERVER2); 
			$sx2=ldap_modify($conn2, $dn, $userinfo);
			echo "- 2.Server ";
		   if($sx2){ echo $gtext['a_unlocked']; $log.=$gtext['a_unlocked']; }
		   else{ echo $gtext['a_notunlocked']; $log.=$gtext['a_notunlocked'];}
	   }//*/
	}
}else{ echo $gtext['notfound'];/*"BulunaMAdı!"*/  $log.=$gtext['notfound']; }
$log.=";";
//
$dosya=$docroot."/logs/personel.log"; 
touch($dosya);
$dosya = fopen($dosya, 'a');
fwrite($dosya, $log);
fclose($dosya); //*/
?>