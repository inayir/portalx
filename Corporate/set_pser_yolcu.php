<?php
/*
	Personel Servislerini kaydeder.
*/
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include("../set_mng.php");
include("../sess.php");
if($_SESSION["user"]==""){
	echo "login"; exit;
}
$sonuc=false;
$username=$_POST['u'];
$pser_kodu=$_POST['ps']; //pser_kodu
$data=[];

$isl=$_POST['isl'];
if($isl=='C'){ 
	//kişi önceki servisinden çıkarılır... C ise sadece bu işlem yapılır.
	$data['pser_kodu']=NULL;
}else{ $data['pser_kodu']=$pser_kodu; }

@$collection=$db->personel;
@$cursor = $collection->updateOne(
		[
			'username'=>$username
		],
		[ '$set' => $data ]
	);
if($cursor->getModifiedCount()>0){ 
	if($isl=='C'){ echo $gtext['removed'];/*'Çıkarıldı';*/ }else{ echo $gtext['inserted'];/*"Eklendi";*/ }
	$r="Positive";
}else{ 
	if($isl=='C'){ echo $gtext['notremoved'];/*'ÇıkarılaMAdı!';*/ }else{ echo $gtext['notinserted'];/*"EkleneMEdi!";*/ }
	$r="Negative";
}
$log="\n".date('Y-m-d H:i:s', strtotime("now")).";user:".$username.";pser_kodu:".$pser_kodu.";".$isl." ".$r.";"; 
$dosya="set_pser_yolcu.log"; 
	touch($dosya);
	$dosya = fopen($dosya, 'a');
	fwrite($dosya, $log);
	fclose($dosya); 
//*/
?>