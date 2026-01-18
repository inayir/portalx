<?php
/* Ldap.php: AD den kullanıcı adını arar, isim ve mail kaydını getirir.*/
//error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
$docroot=$_SERVER['DOCUMENT_ROOT'];
try{
	include($docroot."/config/config.php");
}catch(Except $e){
	$inifile=$docroot."/config/config.ini";
}
define('LDAP_SERVER', $ini['ldap_server']); 
echo "LDAP bağlantı testi<br>"; $ex=0;
echo "LDAP Sunucusu:".$ini['ldap_server'];
$conn=ldap_connect('ldap://'.LDAP_SERVER); 
if($conn){ 
	echo "->Bağlanıldı.";
}else{
	echo "->BağlanılaMAdı!<br>"; $ex=1;
	if($ini['ldap_server2']!=""){ 
		echo "*2.LDAP Sunucusu:".$ini['ldap_server2'];
		$ini['ldap_server']=$ini['ldap_server2']; 
		$conn=ldap_connect('ldap://'.LDAP_SERVER); 
		if($conn){ echo "->Bağlanıldı."; $ex=0; }
		else{ echo "->BağlanılaMAdı!<br>"; }
	}
	if($ex==1){ exit; } 
}
echo "<br>";//-------------------------------------------------------
if(@$_SESSION['user']!=""){
	echo "Login User:".@$_SESSION['user'];
	ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($conn, LDAP_OPT_REFERRALS,0);
	$domuser=$ini['domshort'].'\\'.$_SESSION['user']; 	
	$p=$_SESSION['pass'];
}else{
	echo "ini user:".$domuser;
	$domuser=$ini['domshort'].'\\'.$ini['una']; 
	echo "ini user:".$domuser;
	$p=$ini['upw']; 
}
$bind=ldap_bind($conn, $domuser, $p);
if($bind){ echo "Binded..."; }
else{ 
	echo "------------No BIND!----------<br>";
}
?>