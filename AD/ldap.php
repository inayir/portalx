<?php
/* Ldap.php: AD den kullanýcý adýný arar, isim ve mail kaydýný getirir.*/
//error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
define('LDAP_SERVER', $ini['ldap_server']);  

$conn=ldap_connect('ldap://'.LDAP_SERVER); 
if($conn){ // OK
	$domuser=$ini['domshort'].'\\'.$_SESSION['user']; //echo "->".$domuser;
	ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($conn, LDAP_OPT_REFERRALS,0);
	$bind=ldap_bind($conn, $domuser, $_SESSION['pass']); 
	//if($bind){ echo "Binded";}else{ echo "------------No BIND!----------"; echo "User: ".$_SESSION['user']." ".$_SESSION['pass'];}
}else{ echo "-99"; /*nok*/  }
?>