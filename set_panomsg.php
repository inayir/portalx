<?php
/*
	Pano Mesajlarını kaydeder/siler.
*/
include("set_mng.php");
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include("sess.php");
$log=$gtext['pano_messages'].";";
include($docroot."/app/php_functions.php");
$logfile='pano';
//
for($g=0;$g<7;$g++){
	$gunler[]=$gtext['day'.$g];
}
if($_SESSION["user"]==""){ echo "login"; exit; }
@$isl=$_POST['isl'];
@$collection=$client->$db->personel_pano;
if($isl=='d'){ //Silinecek...
	$del=[];
	$del['state']='D'; //deleted...
	@$cursor = $collection->updateOne(
		[
			'_id' => new \MongoDB\BSON\ObjectId($_POST['id'])
		],
		[
			'$set'=>$del
		]
	);
	if($cursor->getModifiedCount()>0){ echo $gtext['deleted']; /*"Silindi.";*/ $r=true; }else{ echo $gtext['notdeleted']; /*"SilineMEdi!";*/ 
	$log.="{'delete error':''}"; }
}else{
	$pmdata=[]; @$ksay=0;
	@$date1=date("Y-m-d", strtotime($_POST['pano_star'])).'T'.date("H:i", strtotime($_POST['pano_star']));
	//00:00:00.000+00:00'; 
	@$simdi=datem($date1); 
	//
	if($_POST['id']=="0"){ $log.=";New;"; }
	else{ 
		@$id=new \MongoDB\BSON\ObjectId($_POST['id']); 
		$log.=";Upd";
	}
	$log.=";id:".$_POST['id'];
	$pano_gond=$_POST['pano_gond'];  
	if(strpos($pano_gond, '\'')>=0){
		$pano_gond=str_replace("\'","'",$pano_gond);
	}
	if(isset($_POST['pano_konu'])){ $pmdata['pano_konu']=tirnakayarla($_POST['pano_konu']); $log.=";Konu:".$pmdata['pano_konu']; }
	if(isset($_POST['pano_gond'])){ $pmdata['pano_gond']=tirnakayarla($pano_gond); $log.=";Msg:".$pmdata['pano_gond'];}
	if(isset($_POST['pano_star'])){ $pmdata['pano_star']=$simdi; $log.=";Star:".$pmdata['pano_star'];}

	try{//kayıt var mı, bakılır.*/
		@$bul = $collection->findOne(
			[
				'_id' => $id
			]
		);
		//var_dump($bul);
		if(isset($bul)){ @$ksay=count($bul); }
	}catch(Exception $e){
		
	}
	if($ksay>0){ //UPDATE : kayıt varsa güncellenir.
		$pmdata['son_deg_tar']=datem(date("Y-m-d", strtotime("now")).'T00:00:00.000+00:00');
		$pmdata['state']=1;
		@$cursor = $collection->UpdateOne(
			[
				'_id' => $id
			],
			[ '$set' => $pmdata]
		);
		if($cursor->getModifiedCount()>0){ 
			echo $gtext['updated']; //"Güncellendi."; 
			$log.=$gtext['updated'].";"; 
		}else{ 
			echo $gtext['notupdated']; 
			$log.=$gtext['notupdated'].";"; 
		}
	}else{  //INSERT : Kayıt yoksa eklenir...
		$pmdata['msg_sahibi']=$user;
		$pmdata['tarih']=datem(date("Y-m-dTH:i:s", strtotime("now")).'.000+00:00');
		$pmdata['state']=1;
		@$cursor = $collection->insertOne(
			$pmdata
		);
		if($cursor->getInsertedCount()>0){ 
			echo $gtext['inserted'];/*"Eklendi.";*/ 
			$log.=$gtext['inserted'].";"; 
		}else{ 
			echo $gtext['notinserted']; /*"EkleneMEdi.";*/ 
			$log.=$gtext['notinserted'].";"; 
		}
	}
}
$log.="Data:".implode(';',$pmdata).";";
logger($logfile,$log);
?>