<?php
/*
	Personel Servislerini kaydeder.
*/
include('../set_mng.php');
//error_reporting(0);
include($docroot."/config/config.php");
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
if($_SESSION["user"]==""){
	echo 'login'; exit;
}
$log=$gtext['shuttles'].";";
include($docroot."/app/php_functions.php");
$logfile='pservis';
//$keys=['_id','pser_kodu','pser_tanim','pser_bolge','pser_plaka','pser_sofor','pser_sofor_tel','pser_sorumlu','pser_sorumlu_tel','pser_firma','pser_kapasite', 'aktif'];
@$collection=$client->$db->pservis;
$pserdata=[];  @$ksay=0;
$id=$_POST['id'];
if(isset($_POST['pser_kodu'])){ 	$pserdata['pser_kodu']=$_POST['pser_kodu']; }
if(isset($_POST['pser_tanim'])){ 	$pserdata['pser_tanim']=$_POST['pser_tanim']; }
if(isset($_POST['pser_bolge'])){ 	$pserdata['pser_bolge']=$_POST['pser_bolge']; }
if(isset($_POST['pser_plaka'])){ 	$pserdata['pser_plaka']=$_POST['pser_plaka']; }
if(isset($_POST['pser_sofor'])){ 	$pserdata['pser_sofor']=$_POST['pser_sofor']; }
if(isset($_POST['pser_sofor_tel'])){$pserdata['pser_sofor_tel']=$_POST['pser_sofor_tel']; }
if(isset($_POST['pser_sorumlu'])){ 	$pserdata['pser_sorumlu']=("" != $_POST['pser_sorumlu']) ? $_POST['pser_sorumlu'] : '-'; }
if(isset($_POST['pser_sorumlu_tel'])){ $pserdata['pser_sorumlu_tel']=("" != $_POST['pser_sorumlu_tel']) ? $_POST['pser_sorumlu_tel'] : '-'; }
if(isset($_POST['pser_firma'])){ 	$pserdata['pser_firma']=$_POST['pser_firma']; }
if(isset($_POST['pser_kapasite'])){ $pserdata['pser_kapasite']=$_POST['pser_kapasite']; }
if(isset($_POST['pser_kmz'])){ 		$pserdata['pser_kmz']=("" != $_POST['pser_kmz']) ? $_POST['pser_kmz'] : '-'; }else{ $pserdata['pser_kmz']='-'; }
if(isset($_POST['aktif'])){ 		$pserdata['aktif']=strval($_POST['aktif']); }
//kmz dosya eklemek şeklinde kaydedilmeli.
if($id!=""){
	try{//kayıt var mı, bakılır.*/
		@$bul = $collection->findOne(
			[
				'_id'=>new \MongoDB\BSON\ObjectId($id)	
			]
		);//*/
		if(isset($bul)){ @$ksay=count($bul); }
	}catch(Exception $e){
		
	}
}
if($ksay>0){ //UPDATE : kayıt varsa güncellenir.
	$pserdata['olusturan']=$user;
	$pserdata['gtar']=datem(date("Y-m-d H:i:s", strtotime($_POST['gtar'])).'T00:00:00.000+00:00');
	$pserdata['son_deg_per']=$user;
	$pserdata['son_deg_tar']=datem(date("Y-m-d", strtotime("now")).'T00:00:00.000+00:00');
	$pserdata['aktif']=1;
	@$cursor = $collection->updateOne(
		[
			'_id' => new \MongoDB\BSON\ObjectId($id)
		],
		[ '$set' => $pserdata]
	);
	if($cursor->getModifiedCount()>0){ 
		echo $gtext['updated'];/*"Güncellendi.";*/ 
	}else{ 
		echo $gtext['notupdated'];/*"GüncelleneMEdi.";*/ 
	}
}else{  //INSERT : Kayıt yoksa eklenir...
	$pserdata['olusturan']=$user;
	$pserdata['gtar']=datem(date("Y-m-d H:i:s", strtotime("now")).'T00:00:00.000+00:00');
	$pserdata['son_deg_per']='-';
	$pserdata['son_deg_tar']='-';
	$pserdata['aktif']=1;
	@$cursor = $collection->insertOne(
		$pserdata
	);
	if($cursor->getInsertedCount()>0){ 
		echo $gtext['inserted']; /*"Eklendi.";*/ 
	}else{ 
		echo $gtext['notinserted']; /*"EkleneMEdi.";*/ 
	}
}
$log.="Data:".implode(';', $pserdata).";";
logger($logfile,$log);
?>