<?php
/*
	Org.Şema kaydeder.
*/
include('../set_mng.php');
error_reporting(0);
include($docroot."/config/config.php");
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
if($_SESSION["user"]==""){
	echo "login"; exit;
}
$log=$gtext['a_orgschemes'].";";
include($docroot."/app/php_functions.php");
$logfile='orgsm';
@$collection=$db->orgsema;
$q=[];  @$ksay=0;
@$sil=$_POST['del'];
$id=$_POST['_id'];

if($sil==1){
	@$cursor = $collection->deleteOne(
		[
			'_id' => new \MongoDB\BSON\ObjectId($id)
		]
	);
	if($cursor->getDeletedCount()>0){ echo $gtext['deleted'];/*Silindi."*/ $r=true; }else{ echo $gtext['notdeleted'];/*"SilineMEdi!";*/ $log.="{'silme hatasi':''}"; }
	//not: resimler silinmez.
}else{
	if(isset($_POST['orgs_tanim'])){ 	$q['orgs_tanim']=$_POST['orgs_tanim']; $log.=',"tanım":"'.$q['orgs_tanim'].'"';} 
	if(isset($_POST['orgs_tarih'])){ 	$q['orgs_tarih']=datem(date("Y-m-d", strtotime($_POST['orgs_tarih'])).'T00:00:00.000+00:00'); }
	if(isset($_FILES['orgs_dosya'])){ 	$q['orgs_dosya']=$_FILES['orgs_dosya']['name']; $log.=',"Dosya":"'.$q['orgs_dosya'].'"'; }
	if(isset($_POST['aktif'])){ 
		if($_POST['aktif']==1){ $q['aktif']=1; }else{ $q['aktif']=0; } 
	}
		
	$d=0;   //0:dosya yüklenmesin, 1 yüklensin
	if($id!=""){ //UPDATE : kayıt varsa güncellenir.
		$q['son_deg_per']=$user;
		$q['son_deg_tar']=datem(date("Y-m-d", strtotime("now")).'T00:00:00.000+00:00');
		@$cursor = $collection->updateOne(
			[
				'_id' => new \MongoDB\BSON\ObjectId($id)
			],
			[ '$set' => $q]
		);
		if($cursor->getModifiedCount()>0){ echo $gtext['updated'];/*"Güncellendi.";*/ $d=1; }
		else{ echo $gtext['notupdated'];/*"GüncelleneMEdi.";*/ $d=0; }
	}else{  //INSERT : Kayıt yoksa eklenir...
		$q['olusturan']=$user;
		$q['gtar']=datem(date("Y-m-d H:i:s", strtotime("now")));
		@$cursor = $collection->insertOne(
			$q
		);
		if($cursor->getInsertedCount()>0){ echo $gtext['inserted']; /*"Eklendi.";*/ $d=1; }
		else{ echo $gtext['notinserted']; /*"EkleneMEdi.";*/ $d=0; }
	}
	if($d==1){ 
		$log.=',"upload":"';
		if ($_FILES["orgs_dosya"]["error"] == 0){ //hata yoksa..
			$tmp_name = $_FILES["orgs_dosya"]["tmp_name"];//[$key];
			// basename() may prevent filesystem traversal attacks;
			// further validation/sanitation of the filename may be appropriate
			$uploads_dir = $ini['Org_Sema_Dir']; //'Org_Sema/';
			$name = basename($_FILES["orgs_dosya"]["name"]);//[$key]);
			$dyer=$uploads_dir.'/'.$name;
			$log.=$dyer.'"';
			$dyres=move_uploaded_file($tmp_name, $dyer);
			if($dyres==1){ echo " ".$gtext['uploaded'];/*Dosya Yüklendi.";*/ }
			else{ echo $gtext['err_upload'];/*"Yükleme hatası";*/ } 
			$log.=',"Sonuc":"'.$dyres.'"';
		}else{ echo $gtext['err_upload'];/*"Yükleme hatası'"; */ $log.=',"Error":"'.$_FILES["orgs_dosya"]["error"].'"'; }
			
	}
}
$log.='}';
logger($logfile,$log);
?>