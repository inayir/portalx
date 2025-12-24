<?php
/*
	LDAP/AD Oturumlarını açar.
*/ 
//error_reporting(0);
session_start(); 
@$user=$_SESSION['user'];
@$name=$_SESSION['name']; 
@$sess_time=$ini['sess_time'];
if($sess_time==''){ $sess_time=1800; } 
if (isset($_SESSION['LAST_ACTIVITY']) && ((time() - $_SESSION['LAST_ACTIVITY']) > $sess_time)) {
    // last request was more than $ini['sess_time'] minutes ago -> std: 30 min
	session_unset();     // unset $_SESSION variable for the run-time 
	session_destroy();   // destroy session data in storage
	session_start();
}else{	
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
} 
$_SESSION['referrer_page']=$_SERVER['SCRIPT_FILENAME'];
//Dil dosyası yüklenir...
$dil=@$_SESSION['lang'];
if(isset($_POST['langs'])){ @$dil=$_POST['langs']; $_SESSION['lang']=$dil; }
if($dil==""){ //ilke seferde boş gelen dil ayarı için browser dili alınır...
	$lang=explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	if($lang!=''){
		$dila=explode('-', $lang[0]); 
		$dil=$dila[1];
		$_SESSION['lang']=$dil;
	}
}
if($dil==""||$dil=='US'){ $dil='EN'; }
$dildosyasi=$docroot.'/lang/TR.php';
include($dildosyasi);
$dildosyasi=$docroot.'/lang/'.$dil.'.php';
include($dildosyasi);
?>