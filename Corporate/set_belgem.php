<?php
/*
	Belge kaydeder.
*/
include('../set_mng.php');
$log="";
include($docroot."/app/php_functions.php");
$logfile='docs';
//error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/config/config.php");
include($docroot."/sess.php"); 
if($_SESSION["user"]==""){
	echo "login"; exit;
}//*/
@$collection=$db->k_belgeler;

$sonuc=false; @$sil=$_POST['del'];

if($sil==1){
	@$cursor = $collection->deleteOne(
		[
			'_id' => new \MongoDB\BSON\ObjectId($id)
		]
	);
	if($cursor->getDeletedCount()>0){ 
		echo $gtext['deleted']; $r=true; 
		$log.=$gtext['deleted'].";";
	}else{ 
		echo $gtext['notdeleted']."!"; 
		$log.=$gtext['notdeleted']."{'hata':''};"; 
	}
	//not: resimler silinmez.
}else{
	//bilgiler toplanır.
	$tanim=str_ayikla($_POST['tanim']);
	$aktif=0; if($_POST['aktif']=='on'){ $aktif=1; }
	$log.=$tanim.",".$aktif.",{File:".$_POST['dosya']."}";
	//
	$fields=["tip","snf","kod","tanim","b_tar","g_tar","user","aktif"];
	if(isset($_POST['tip'])){
		$data['tip']=$_POST['tip']; 
		$log.=",{'tip':".$data['tip']."}"; 
	}
	if(isset($_POST['snf'])){
		$data['snf']=$_POST['snf']; 
		$log.=",{'snf':".$data['snf']."}"; 
	}
	if(isset($_POST['kod'])){
		$data['kod']=$_POST['kod']; 
		$log.=",{'kod':".$data['kod']."}"; 
	}
	if(isset($_POST['tanim'])){
		$data['tanim']=$_POST['tanim']; 
		$log.=",{'tanim':".$data['tanim']."}"; 
	}
	if(isset($_POST['b_tar'])){
		$data['b_tar']=datem($_POST['b_tar']); 
		$log.=",{'b_tar':".$data['b_tar']."}"; 
	}
	if(isset($_POST['g_tar'])){
		$data['g_tar']=datem($_POST['g_tar']); 
		$log.=",{'g_tar':".$data['g_tar']."}"; 
	}
	if(isset($_POST['user'])){
		$data['user']=$_POST['user']; 
		$log.=",{'user':".$data['user']."}"; 
	}
	if(isset($_POST['aktif'])){
		$data['aktif']=$_POST['aktif']; 
		$log.=",{'aktif':".$data['aktif']."};"; 
	}
	
	if($_POST['_id']!=''){ //Update
		$log.=";{_id:".$_POST['uid']."};";
		$id=$_POST['uid'];
		$cursor = $collection->updateOne(
			[
				'_id' => new \MongoDB\BSON\ObjectId($id)
			],
			[ '$set' => $data]
		);
		if($cursor->getModifiedCount()>0){ 
			$msg=$gtext['updated']; $r=true; 
			$log.=$gtext['updated'].";";
		}else{ 
			$msg=$gtext['notupdated']."!"; 
			$log.=$gtext['notupdated'].",{'update hatasi':''};"; 
		}
	}else{ //Ekleme
		$msg="Eklen"; 
		$data['kullanici']=$user;
		@$cursor = $collection->insertOne(
			$data
		);
		if($cursor->getInsertedCount()>0){ 
			$msg=$gtext['inserted']; $r=true;
			$log.=$gtext['inserted'].";";			
		}else{ 
			$msg=$gtext['notinserted']."!"; 
			$log.=",{'insert hatasi':''};"; 
		}
	}
}
$log.=";Query: ".$data;
if($r){ 
	if($sil==""){
		$msg.=""; 
		$dizin = $_POST['belge_yolu']; 
		$yuklenecek_dosya = $dizin . "/". basename($_FILES['dyol']['name']);
		if (move_uploaded_file($_FILES['dyol']['tmp_name'], "..".$yuklenecek_dosya)){
			$msg.=" & [".$yuklenecek_dosya."] ".$gtext['uploaded']."\n";
			$d['dosya']=$yuklenecek_dosya; 
			@$cursord = $collection->updateOne(
				[
					'_id' => new \MongoDB\BSON\ObjectId($id)
				],
				[ '$set' => $d]
			);
			if($cursord->getModifiedCount()>0){ 
				echo "di."; $r=true; 
			}else{ 
				echo "eMEdi!"; 
			}
			$log.=";Uploaded File:".$yuklenecek_dosya;
		} else {
			$msg.=" & ".$gtext['err_upload']."!";
			$log.=$gtext['err_upload'].";";
		}
	}else{ $msg.=""; }
}else { 
	$msg.=$gtext['nothingdone']."!";
	$log.=$gtext['nothingdone']." !";
}
echo $msg;
//
logger($logfile,$log);
?>