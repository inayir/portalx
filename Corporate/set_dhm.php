<?php
/*
	Duyuru-Haber kaydeder.
*/
include('../set_mng.php');
error_reporting(0);
include($docroot."/config/config.php");
include($docroot."/sess.php");
header('Content-Type:text/html; charset=utf8');
if($_SESSION["user"]==""){ 
	//echo "login"; exit;
}
$id=$_POST['_id'];
$log=$gtext['announce_and_news'].";";
include($docroot."/app/php_functions.php");
$logfile='dh';
//
$sonuc=false; 
@$sil=$_POST['del'];

@$collection=$client->$db->k_dhaber;
$data=[]; @$ksay=0;  
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
		$log.=$gtext['notdeleted']."{'delete error':''};"; 
	}
	//not: resimler silinmez.
}else{
	if(isset($_POST['dh'])){
		$data['dh']=$_POST['dh']; 
		$logarr.="{{'dh':".$data['dh']."}"; 
	}
	if(isset($_POST['dh_baslik'])){ 
		$data['dh_baslik']=addslashes($_POST['dh_baslik']); 	
		$logarr.=",{'dh_baslik':".$data['dh_baslik']."}"; 
	}
	if(isset($_POST['dh_capt_on'])){ 
		$data['dh_capt_on']=$_POST['dh_capt_on']; 
		$logarr.=",{'dh_capt_on':".$data['dh_capt_on']."}"; 
	}
	if(isset($_POST['dh_icerik'])){ 
		$data['dh_icerik']=addslashes($_POST['dh_icerik']); 	
		$logarr.=",{'dh_icerik':".$data['dh_icerik']."}"; 
	}
	if(isset($_POST['dh_dlink'])){ 	
		$data['dh_dlink']=addslashes($_POST['dh_dlink']); 
		$logarr.=",{'dh_dlink':".$data['dh_dlink']."}"; 
	}
	if(isset($_POST['dh_url'])){ 	
		$data['dh_url']=addslashes($_POST['dh_url']); 	
		$logarr.=",{'dh_url':".$data['dh_url']."}"; 
	}
	if(isset($_POST['dh_ytar'])){ 	
		$data['dh_ytar']=datem(date("Y-m-d H:i:s", strtotime($_POST['dh_ytar']))); 	
		$logarr.=",{'dh_ytar':".$data['dh_ytar']."}"; 
	}
	if(isset($_POST['dh_sgtar'])){	
		$data['dh_sgtar']=datem(date("Y-m-d H:i:s", strtotime($_POST['dh_sgtar']))); 	
		$logarr.=",{'dh_sgtar':".$data['dh_sgtar']."}"; 
	}
	if(isset($_POST['dh_sdtar'])){ 	
		$data['dh_sdtar']=datem(date("Y-m-d H:i:s", strtotime($_POST['dh_sdtar']))); 	
		$logarr.=",{'dh_sdtar':".$data['dh_sdtar']."}"; 
	}
	if(isset($_POST['lang'])){ 	
		$data['lang']=strval($_POST['lang']); 
		$logarr.=",{'lang':".$data['lang']."}"; 
	}
	if(isset($_POST['aktif'])){ 	
		$data['aktif']=strval($_POST['aktif']); 
		$logarr.=",{'aktif':".$data['aktif']."}"; 
	}
	//
	$r=false;
	if($id!=""){ //UPDATE : kayıt varsa güncellenir.
		if(isset($_FILES['dh_resim'])){ $r=true; } //echo "yuklenen resim var. ";}
		$data['dh_sdkullanici']=$user; $logarr.=",{'dh':".$data['dh_sdkullanici']."}"; 
		@$cursor = $collection->updateOne(
			[
				'_id' => new \MongoDB\BSON\ObjectId($id)
			],
			[ '$set' => $data]
		);
		if($cursor->getModifiedCount()>0){ 
			echo $gtext['updated']; $r=true; 
			$log.=$gtext['updated'];
		}else{ 
			echo $gtext['notupdated']."!"; 
			$log.=$gtext['notupdated']."{'update error':''};"; 
		}
	}else{ //INSERT : Kayıt yoksa eklenir...
		$data['kullanici']=$user;
		@$cursor = $collection->insertOne(
			$data
		);
		if($cursor->getInsertedCount()>0){ 
			echo $gtext['inserted']; $r=true; 
			$log.=$gtext['inserted'];
		}else{ 
			echo $gtext['notinserted']."!"; 
			$log.=$gtext['notinserted']."{'insert hatasi':''};"; 
		}
	}

	if($r){ 
		if($_POST['dh']!='K'&&$_FILES['dh_resim']['name']!=''){
			$dizin = '../dhimg';
			$yuklenecek_dosya = $dizin.'/'.basename($_FILES['dh_resim']['name']);
			$logarr.=",{'dh_resim':".$yuklenecek_dosya."}";
			$tmp_name=$_FILES['dh_resim']['tmp_name'];
			echo "\n[".$yuklenecek_dosya."] "; 
			$d=[];
			if (move_uploaded_file($tmp_name, $yuklenecek_dosya)){
				$logarr.=",{'uploaded picture':".$yuklenecek_dosya."}";
				$d['dh_resim']=$yuklenecek_dosya; 
				@$cursord = $collection->updateOne(
					[
						'_id' => new \MongoDB\BSON\ObjectId($id)
					],
					[ '$set' => $d]
				);
				if($cursord->getModifiedCount()>0){ echo $gtext['uploaded']; $r=true; }else{ echo $gtext['notuploaded']."!"; }
			} else {
				echo $gtext['notuploaded']."!";
				$log.=",{'".$gtext['notuploaded']."'},{'tempname':".$_FILES['dh_resim']['tmp_name']."};";
			}
		}
	}
	$log.="logarr:".$logarr.";";
}
//
logger($logfile,$log);
?>