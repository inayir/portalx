<?php
/*
	User bilgilerini getirir. bg: bilgileri getir.
*/
//error_reporting(0);
header('Content-Type:text/html; charset=utf8');
//$ini = parse_ini_file("../config/config.ini.php");
include("../sess.php");
$ps=$_POST['ps'];
$log="";
@$json='[{';
$keys=['pser_uid','pser_kodu','pser_tanim','pser_bolge','pser_plaka','pser_sofor','pser_sofor_tel','pser_firma','pser_kapasite', 'aktif'];
$q="SELECT * FROM pservis WHERE aktif=1 AND pser_uid=".$ps; //echo $q;
$result = $baglan->query($q);
if($result){ 
	if(mysqli_num_rows($result)>0){
		$row=mysqli_fetch_array($result);
		for($i=0;$i<count($keys);$i++){
			if($i>0){ $json.=', '; }
			$json.='"'.$keys[$i].'" : "'.$row[$keys[$i]].'"';
		}
	}
} else { $json='[{Bulunamadı!}]'; }
$json.='}]';
echo $json;  
/*$log.="\n".$json;
$dosya="user_infos.log"; 
	touch($dosya);
	$dosya = fopen($dosya, 'a');
	fwrite($dosya, $log);
	fclose($dosya); 
//*/
?>