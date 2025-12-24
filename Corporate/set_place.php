<?php
/*
	Place insert/edit.
*/
include("../set_mng.php");
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php"); 
if($_SESSION["user"]==""){
	echo "login"; exit;
}
$user=$_SESSION["user"];
$simdi=date("d.m.Y", strtotime("now"));
$log="Place;";
include($docroot."/app/php_functions.php");
$logfile='places';
//echo "OK"; exit;
@$collection=$db->Places;
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
	$data['code']=$_POST['code'];  
	$data['description']=$_POST['description'];  
	$data['type']=$_POST['type'];
	if(isset($_POST['streetaddress'])){
		$data['streetaddress']=$_POST['streetaddress'];  
	}
	if(isset($_POST['district'])){
		$data['district']=$_POST['district'];  
	}
	if(isset($_POST['st'])){
		$data['st']=$_POST['st'];  
	}
	if(isset($_POST['country'])){
		$data['country']=$_POST['country'];  
	}
	if(isset($_POST['telephonenumber'])){
		$data['telephonenumber']=$_POST['telephonenumber']; 
	}
	if(isset($_POST['opened_date'])){
		$data['opened_date']=$_POST['opened_date']; 
	}
	$aktif="P"; if($_POST['state']=='on'){ $aktif='A'; }
	$data['state']=$aktif; 
	$def=0; if($_POST['default']=='on'){ $def=1; }
	$data['default']=$def; 
	//	
	if($id!=''){ //Update
		$data['updateuser']=$user; //son değişiklik
		$cursor = $collection->updateOne(
			[
				'_id' => new \MongoDB\BSON\ObjectId($id)
			],
			[ '$set' => $data]
		);
		if($cursor->getModifiedCount()>0){ 
			echo $gtext['updated']."."; $isl="OK";
			$log.=$gtext['updated'].";";
		}else{ 
			echo $gtext['notupdated']."!"; 
			$log.=",{'update error':''};"; 
		}
	}else{ //Ekleme
		$data['saveuser']=$user; //ilk kayıt
		@$cursor = $collection->insertOne(
			$data
		);
		if($cursor->getInsertedCount()>0){ 
			echo $gtext['inserted']."."; $isl="OK";
			$log.=$gtext['inserted'].";";
		}else{ 
			echo $gtext['notinserted']."!"; 
			$log.=",{'insert error':''};"; 
		}
	}
	if($_POST['default']==1&&$isl=="OK"){
		//default to unique
		$dat=[];
		$dat['default']=0;
		$crs=$collection->updateMany(
			['$set'=>$dat]
		);
		$dat['default']=1;
		$crs=$collection->updateOne(
			['code'=>$_POST['code']], 
			['$set'=>$dat]
		);
	}
}
$log.="Data: ".implode(';',$data).";";

logger($logfile,$log);
?>