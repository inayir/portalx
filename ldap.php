<?php
/* Ldap.php: AD den kullanýcý adýný arar, isim ve mail kaydýný getirir.*/
header('Content-Type: text/html; charset=utf-8');
define('LDAP_SERVER', $ini['ldap_server']);  
$conn=ldap_connect('ldap://'.LDAP_SERVER); 
if(!$conn){
	if($ini['ldap_server2']!=""){
		$ini['ldap_server']=$ini['ldap_server2'];
	}
	$conn=ldap_connect('ldap://'.LDAP_SERVER.':389'); 
}
if($_SESSION['user']!=""){
	if($conn){ // OK
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION,3);
		ldap_set_option($conn, LDAP_OPT_REFERRALS,0);
		if($ini['usersource']=='LDAP'){
			$domuser=$ini['domshort'].'\\'.$_SESSION['user']; //$log.=" domuser:".$domuser;		
			$p=$_SESSION['pass'];
		}else{
			$domuser=$ini['domshort'].'\\'.$ini['una']; //$log.=" domuser:".$domuser;
			$p=$ini['upw']; 
		}
		$bind=ldap_bind($conn, $domuser, $p);
		if(!$bind){ 
			//echo "User: ".$domuser." ".$p."------------No BIND!----------"; 
			$domuser=$ini['domshort'].'\\'.$ini['una']; //$log.=" domuser:".$domuser;
			$p=$ini['upw'];
			$bind=ldap_bind($conn, $domuser, $p);
			//if(!$bind){ echo "No LDAP Connection!"; }
		}//*/
	}//else{ echo "-99"; /*nok*/  }
}else{ header('Location: \login.php');  }
?>