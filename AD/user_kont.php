<?php
/*
	User controlling
*/
include('../set_mng.php');
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/config/config.php");
include($docroot."/sess.php");
if($user==""){
	echo "!!"; //giriş yapılmalı
}
$dom_dn=$ini['dom_dn'];
require("./ldap.php");
$msg="Kullanıcı Adı: [";
$username=$_POST['u']; 
if($username==""){ echo "-"; }
else{ 
	if($ini['usersource']=='LDAP'){ 
		$ldap_result = ldap_search($conn, $dom_dn, "(samaccountname=$username)");
		if($ldap_result){ 
			$info = ldap_get_entries($conn, $ldap_result); 
			if($info["count"]>0){ echo "LU"; }  //Used
			else { echo "L+"; }
		}else{ echo "L-"; }
	}else{ //DB
		@$collection = $db->personel;
		try{
			@$cursor = $collection->findOne(
				[
					'username' => $username
				],
				[
					'limit' => 0,
					'projection' => [
						'username' => 1,
					]
				]
			);
			if(isset($cursor)){	$ksay=count($cursor); }
			if($ksay>1){ echo "U"; }  //Used
			else{ echo "+"; }
		}catch(Exception $e){
			
		}
	}
}
?>