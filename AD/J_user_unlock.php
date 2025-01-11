<?php
/*
	useraccountcontrol enable etmek ve kilidi açar
*/
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
include($docroot."/set_lang.php");
if($ini['usersource']=='LDAP'){ require($docroot."/ldap.php"); }
include($docroot."/app/php_functions.php");
@$username=$_POST['u'];
echo $username." ";
$log="Unlock;user:".$username.";";
$ldap_result = ldap_search($conn, $ini['base_dn'], "(samaccountname=$username)");
if($ldap_result){ 
	$info = ldap_get_entries($conn, $ldap_result); 
	if($info["count"]>0){	
	   $userinfo['useraccountcontrol']=544;
	   $dn=$info[0]['distinguishedname'][0];
	   $sx=ldap_modify($conn, $dn, $userinfo);
	   if($sx){ echo $gtext['a_unlocked'];/*"Açılmıştır."*/ $log.="unlocked;"; }
	   else{ echo $gtext['a_notunlocked'];/*"AçılaMAdı!"*/ $log.="not unlocked;";}
	   //second ldap/AD 
	   echo "<br>2.Server:".$ini['ldap_server2'];
	   if($ini['ldap_server2']!=''){ 
			$conn2=ldap_connect('ldap://'.$ini['ldap_server2']); 
			echo "\n* ";
			$sx2=ldap_modify($conn2, $dn, $userinfo);
		   if($sx2){ echo $gtext['a_unlocked'];  $log.="2 unlocked;";}
		   else{ echo $gtext['a_notunlocked']; $log.="2 not unlocked;"; }
	   }//*/
	}
}else{ echo $gtext['notfound'];/*"BulunaMAdı!"*/  $log.="not found;"; }

$logfile="personel";
logger($logfile, $log);
?>