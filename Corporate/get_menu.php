<?php /*
	Bir ayın Menüsünü getirir.
 */
for($g=0;$g<7;$g++){
	$gunler[]=$gtext['day'.$g];
}
$bugun=date($ini['date_local'], strtotime("now"));
$aysecim=$_POST['ay'];
$json="";
?>