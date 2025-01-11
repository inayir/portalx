<?php
/*
	Yemek Menülerini kaydeder.
*/
include("../set_mng.php");
//error_reporting(0);
header('Content-Type:text/html; charset=utf8');
$log=$gtext['menu'].";";
include($docroot."/sess.php");
$log=date("Y-m-d H:i", strtotime("now"));
include($docroot."/app/php_functions.php");
$logfile='ymenu';
//
for($g=0;$g<7;$g++){
	$gunler[]=$gtext['day'.$g];
}
if($_SESSION["user"]==""){
	echo "login"; exit;
}
@$collection=$client->$db->ymenu;

$ymdata=[]; @$ksay=0;
@$id=(int)$_POST['id'];
@$ym_tarih=date("Y-m-d", strtotime($_POST['ym_tarih'])).'T00:00:00.000+00:00'; 
$log.=$gtext['menu'].' date:'.$ym_tarih;
$date1=datem($ym_tarih); 
//
@$id=(int)$_POST['id'];
$ymdata['ym_tarih']=$date1;
if(isset($_POST['k1'])){ $ymdata['k1']=$_POST['k1']; }
if(isset($_POST['k2'])){ $ymdata['k2']=$_POST['k2']; }
if(isset($_POST['k3'])){ $ymdata['k3']=$_POST['k3']; }
if(isset($_POST['o1'])){ $ymdata['o1']=$_POST['o1']; }
if(isset($_POST['o2'])){ $ymdata['o2']=$_POST['o2']; }
if(isset($_POST['o3'])){ $ymdata['o3']=$_POST['o3']; }
if(isset($_POST['o4'])){ $ymdata['o4']=$_POST['o4']; }
if(isset($_POST['o5'])){ $ymdata['o5']=$_POST['o5']; }
if(isset($_POST['a1'])){ $ymdata['a1']=$_POST['a1']; }
if(isset($_POST['a2'])){ $ymdata['a2']=$_POST['a2']; }
if(isset($_POST['a3'])){ $ymdata['a3']=$_POST['a3']; }
if(isset($_POST['a4'])){ $ymdata['a4']=$_POST['a4']; }
if(isset($_POST['a5'])){ $ymdata['a5']=$_POST['a5']; }

try{//kayıt var mı, bakılır.*/
	@$bul = $collection->findOne(
		[
			'ym_tarih' => ['$gt'=>$date1]	
		]
	);
	if(isset($bul)){ @$ksay=count($bul); }
}catch(Exception $e){
	
}
if($ksay>0){ //UPDATE : kayıt varsa güncellenir.
	$ymdata['son_deg_per']=$user;
	$ymdata['son_deg_tar']=datem(date("Y-m-d", strtotime("now")).'T00:00:00.000+00:00');
	$ymdata['aktif']=1;
	@$cursor = $collection->updateOne(
		[
			'ym_tarih' => ['$gt'=>$date1]
		],
		[ '$set' => $ymdata]
	);
	if($cursor->getModifiedCount()>0){ 
		echo $gtext['updated'];/*"Güncellendi.";*/
		$log.=$gtext['updated'].";";
	}else{ 
		echo $gtext['notupdated'];/*"GüncelleneMEdi.";*/ 
		$log.=$gtext['notupdated'].";";
	}
}else{  //INSERT : Kayıt yoksa eklenir...
	$ymdata['olusturan']=$user;
	$ymdata['gtar']=datem(date("Y-m-d H:i:s", strtotime("now")));
	$ymdata['aktif']=1;
	@$cursor = $collection->insertOne(
		$ymdata
	);
	if($cursor->getInsertedCount()>0){ 
		echo $gtext['inserted']; /*"Eklendi.";*/ 
		$log.=$gtext['inserted'].";";
	}else{ 
		echo $gtext['notinserted']; /*"EkleneMEdi.";*/ 
		$log.=$gtext['notinserted'].";";
	}
}
$log.="Data:".implode(';',$ymdata).";";
logger($logfile,$log); 
//*/
?>