<?php
/*
	/dil dosyası yüklenir.
*/ 
$dil=$_SESSION['lang'];
if(isset($_POST['langs'])){ @$dil=$_POST['langs']; $_SESSION['lang']=$dil; }
if($dil==""){ //ilke seferde boş gelen dil ayarı için browser dili alınır...
	$lang=explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$dila=explode('-', $lang[0]); 
	$dil=$dila[1];
	$_SESSION['lang']=$dil;
}
$dildosyasi=$docroot.'/lang/TR.php';
include($dildosyasi);
$dildosyasi=$docroot.'/lang/'.$dil.'.php';
include($dildosyasi);
?>