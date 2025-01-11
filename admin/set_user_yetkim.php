<?php
/*
	Kullanıcı yetkilerini kaydeder. Mongo
*/
include('../set_mng.php');
//error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
$username=$_POST['u'];
$log="\n username:".$username;
if($_SESSION["user"]==""){
	echo "login"; exit;
}
//$keys=['pser_uid','pser_kodu','pser_tanim','pser_bolge','pser_plaka','pser_sofor','pser_sofor_tel','pser_firma','pser_kapasite', 'aktif'];
$sonuc=false;
@$collection=$db->personel_prop;
$cursorp = $collection->findOne(
	[
        'username' => $username
    ],
    [
        'limit' => 1,
    ]
);
if(isset($cursorp)){ $ksay=count($cursorp); }
if($ksay<1){ $data["username"]=$username; }
if($_POST['y_admin']=='on'){ 		 $data["y_admin"]=1; 		}else { $data["y_admin"]=0; }
if($_POST['y_addinfoduyuru']=='on'){ $data["y_addinfoduyuru"]=1;}else { $data["y_addinfoduyuru"]=0; }
if($_POST['y_addinfohaber']=='on'){  $data["y_addinfohaber"]=1; }else { $data["y_addinfohaber"]=0; }
if($_POST['y_addinfoser']=='on'){ 	 $data["y_addinfoser"]=1; 	}else { $data["y_addinfoser"]=0; }
if($_POST['y_addinfomenu']=='on'){   $data["y_addinfomenu"]=1; 	}else { $data["y_addinfomenu"]=0; }
if($_POST['y_link01']=='on'){ 		 $data["y_link01"]=1; 		}else { $data["y_link01"]=0; }
if($_POST['y_bq']=='on'){ 			 $data["y_bq"]=1; 			}else { $data["y_bq"]=0; }
if($_POST['y_bo']=='on'){ 			 $data["y_bo"]=1; 			}else { $data["y_bo"]=0; }
if($_POST['y_rcall']=='on'){ 		 $data["y_rcall"]=1; 		}else { $data["y_rcall"]=0; }

if($ksay>0){ echo "(update) ";
	@$cursor = $collection->updateOne(
		[
			'username'=>$username
		],
		[ '$set' => $data ]
	);
	if($cursor->getModifiedCount()>0){ 
		echo $gtext['prereqsaved']; /*"Yetkiler Kaydedildi.";*/
		$log.=";prereqsaved->ok;"; 
		$act=1;
	}else{ 
		echo $gtext['notprereqsaved']."!"; /*"Yetkiler KaydedileMEdi!";*/
		$log.=";notprereqsaved->nok;"; 
	}
}else{ //Ekleme
	echo "(insert) ";
	$insert=array();
	$insert['username']=$username;
	
	@$cursor = $collection->insertOne(
		$data
	);
	if($cursor->getInsertedCount()>0){ 
		echo $gtext['prereqadd']; /*Yetkiler Eklendi*/
		$act=1;
	}else{ 
		echo $gtext['notprereqadded']."!";/*"Yetkiler EkleneMEdi!";*/
	}
}
if($act==1){
	$act_collection = $db->personel_act;
	$data['act_date']=datem(date("Y-m-d", strtotime("now").'T'.date("H:i:s", strtotime("now")).'.000+00:00'));
	$act_cursor = $act_collection->insertOne(
		$data
	);
}
$log.="\n".$s;
$dosya="set_user_yetkim.log"; 
	touch($dosya);
	$dosya = fopen($dosya, 'a');
	fwrite($dosya, $log);
	fclose($dosya); 
//*/
?>