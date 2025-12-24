<?php
/*
	to enable or unlock user.
*/
function unlock($username){
	global $conn,$base_dn,$ini,$domuser,$pass, $gtext;
	$mesg="-";
	$userinfo['useraccountcontrol']=544;
	$ldap_result = ldap_search($conn, $base_dn, "(samaccountname=$username)");
	if($ldap_result){ 
		$info = ldap_get_entries($conn, $ldap_result); //$keys=Array('useraccountcontrol');
		if($info["count"]>0){	
		    $dn=$info[0]['distinguishedname'][0]; 
		    $sx=ldap_modify($conn, $dn, $userinfo);
		    if($sx){ 
				$mesg="OK"; //" Opened."; 
			}else{	//ikinci bir ldap varsa.
				/*if($ldap_server2!=''){ 
					@$ldap_server2 = $ini['ldap_server2']; //"KONDUNYA01.aselsankonya.com.tr";
					$conn2=ldap_connect('ldap://'.$ldap_server2);
					if($conn2){				
						$bind2=ldap_bind($conn2, $domuser, $pass);
						$sx2=ldap_modify($conn2, $dn, $userinfo);
						if($sx2){ $mesg.=",2OK"; } //" Opened";  }
						else{ $mesg=" ".$gtext['a_notunlocked']; } //" AçılaMAdı"; }
					}else{ $mesg=" ".$gtext['a_notconnected']; } //"BağlanılaMAdı!"; 
				}else { $mesg=" ".$gtext['a_notunlocked']; } //" AçılaMAdı!"; }//*/
			}
		}
	}else{ $mesg=" nOK! "; }
	return $mesg;
}
error_reporting(0);
$usernames=['iaydin','ezaferoglu','etepe','msaydiner','oozcelik','fkasikci'];
$docroot=$_SERVER['DOCUMENT_ROOT']; if($docroot==''){ $docroot='C:\PORTAL'; }
$ini=parse_ini_file($docroot."/config/config.ini"); 
include($docroot."/app/php_functions.php"); 
//Dil dosyası yüklenir...
$dil="TR";
$dildosyasi=$docroot.'/lang/'.$dil.'.php';
include($dildosyasi);
//
$user='username';
$pass='pass';
$domuser=$ini['domshort']."\\".$user; //'dom_dn\\'.$user; 
@$base_dn=$ini['base_dn']; //base_dn: "OU=dom_dn,DC=example,DC=com"; 
@$ldap_server = $ini['ldap_server']; //"DC1.example.com";
define('LDAP_SERVER', $ldap_server); 
@$msg="\nUnlocking: ".date("d-m-Y H:i", strtotime("now"))."\n";
$log=date("d-m-Y H:i", strtotime("now"))." ".$gtext['a_unlocking']." IN"; 
$msg.=$ldap_server."->";
try{	 
	$conn=ldap_connect('ldap://'.$ldap_server); 
}catch(Exception $e){
	$msg.=ldap_error()." ".ldap_errno();
}
if($conn){	
	$bind=ldap_bind($conn, $domuser, $pass);
	if($bind){
		$msg.="Bağlandı.";
		for($c=0;$c<count($usernames);$c++){
			$m=unlock($usernames[$c]);
			$msg.="\nHesap:".$usernames[$c].":".$m;
			$log.=";".$usernames[$c].":".$m;
		}
	}else{ $msg.="Can Not Connected!!!"; }	
}else{
	$msg.="Can Not Connected!";	
}
echo $msg;
sleep(30);
$logfile='Jpersonel';
logger($logfile,$log);
?>