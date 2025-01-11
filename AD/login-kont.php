<?php
/* Ldap.php: AD den kullanýcý adýný arar, isim ve mail kaydýný getirir.*/
error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
header('Content-Type: text/html; charset=utf-8');
include($docroot."/config/config.php");
require("./ldap.php");

$username=$_POST['u']; 
if($username==""){ $username=$_GET['u']; }//echo "user:".$username;
if($username==""){ echo "-"; exit; }

$ldap_result = ldap_search($conn, $ini['base_dn'], "(samaccountname=$username)");
if($ldap_result){ 
	$info = ldap_get_entries($conn, $ldap_result); 
	if($info["count"]>0){	
	   echo $info[0]['displayname'][0]."<br><br><br><br><br>";
       //var_dump($info);
	}
} 
?>