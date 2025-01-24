<?php
/*
	Change Password
*/
header('Content-Type:text/html; charset=utf8');
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
include($docroot."/sess.php");
if($_SESSION["user"]==""){
	echo "login"; exit;
//}
@$distinguishedName=$_POST['dn']; 
if($distinguishedName==""){ $distinguishedName="CN=Ali Birinci,OU=BilgiTeknolojileriMd,OU=DHAD,OU=ASELSANKONYA,DC=aselsankonya,DC=com,DC=tr"; }
@$newPassword=$_POST['pass']; 	  if($newPassword==""){ $newPassword='Akss2025..'; }
@$renewPassword=$_POST['repass']; if($renewPassword==""){ $renewPassword=$newPassword; }
@$dn="LDAP://".$ini['ldap_server']."/".$distinguishedName;
try{
	$ADSI = new COM("LDAP:");
	echo "ADSI ok->";
}catch(Exception $e){
	$rawErr = $e->getCode();
	$processedErr = $rawErr + 0x100000000;
	printf( 'Error code 0x%x', $processedErr );
}
//
$user = $ADSI->OpenDSObject($dn, $_SESSION['user'], $_SESSION['pass'], 1);
if ($user){ 
	//$user->Put("pwdLastSet",0); 
	$user->SetPassword($newPassword);
	try{
		$user->SetInfo();
		echo "Password changed successfully";
	}catch(Exception $e){
		$rawErr = $e->getCode();
		$processedErr = $rawErr + 0x100000000;
		printf( 'Error code 0x%x', $processedErr );
	}
	//$ldapbind1 = ldap_bind($conn, $distinguishedName, $username, $newPassword); 
	//if($ldapbind1){ echo "Changed.";}else{ echo "NOT Changed!"; }
}else{ echo "noAuth<br>";}
	
?>