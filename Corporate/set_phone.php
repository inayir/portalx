<?php
/*
	Phone save.
*/
include("../set_mng.php");
//error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php"); 
if($_SESSION["user"]==""){
	echo "login"; exit;
}//*/
$simdi=date("d.m.Y", strtotime("now"));
$log="Phone;";
include($docroot."/app/php_functions.php");
$logfile='phone';
//
@$collection=$db->personel;
$sonuc=false; 
$id=$_POST['_id'];
//
if($_POST['del']==1){
	@$cursor = $collection->deleteOne(
		[
			'_id' => new \MongoDB\BSON\ObjectId($id)
		]
	);
	if($cursor->getDeletedCount()>0){ echo $gtext['deleted']; }
	else{ echo $gtext['notdeleted']."!"; $log.=";{'silme hatasi':''}"; }
}else{ //update/insert	//bilgiler toplanır.
	$data['title']="Phone";
	if(isset($_POST['telephonenumber'])){
		$data['telephonenumber']=$_POST['telephonenumber']; 
		$data['description']='#'.$_POST['telephonenumber']; 
	}
	if(isset($_POST['displayname'])){
		$data['displayname']=$_POST['displayname'];  
	}
	if(isset($_POST['color'])){
		$data['color']=$_POST['color']; 
	}
	if(isset($_POST['bgcolor'])){
		$data['bgcolor']=$_POST['bgcolor'];  
	}
	//
	$data['g_tar']=datem($simdi); 
	//
	//$data['user']=$_SESSION['user']; 
	//
	$aktif=0; if($_POST['aktif']=='on'){ $aktif=1; }
	$data['aktif']=$aktif; 
	//
	$order=$_POST['order'];
	$data['order']=$order; 
	//	
	if($_POST['_id']!=''){ //Update
		$cursor = $collection->updateOne(
			[
				'_id' => new \MongoDB\BSON\ObjectId($id)
			],
			[ '$set' => $data]
		);
		if($cursor->getModifiedCount()>0){ 
			echo $gtext['updated']."."; 
			$log.=$gtext['updated'].";";
		}else{ 
			echo $gtext['notupdated']."!"; 
			$log.=",{'update error':''};"; 
		}
	}else{ //Ekleme
		$data['saveuser']=$user;
		@$cursor = $collection->insertOne(
			$data
		);
		if($cursor->getInsertedCount()>0){ 
			echo $gtext['inserted']."."; 
			$log.=$gtext['inserted'].";";
		}else{ 
			echo $gtext['notinserted']."!"; 
			$log.=",{'insert error':''};"; 
		}
	}
}
$log.="Data: ".implode(';',$data).";";

logger($logfile,$log);
?>