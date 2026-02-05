<?php
/*
	LDAP/AD Oturumlarını açar.
*/
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
$_SESSION['referrer_page']=$_SERVER['PHP_SELF']; 
include($docroot."/set_lang.php");
$initimezone=$ini['timezone']; if($initimezone==''){ $initimezone="Europe/Istanbul"; }
date_default_timezone_set($initimezone); 
?>