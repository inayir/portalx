<?php
/*
	set_fileform Form: Hazır Formun eklenmesi... 
*/
include('../set_mng.php');
//error_reporting(0);
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
@$form=$_POST['form']; //key: form -> "YFR-040"; 
if($form==''){ echo "Form kodu boş olamaz!"; exit; }
$isl='update';
$formdat=[];
$id=$_POST['id']; 
if($_POST['id']==''){ $isl='insert'; }
$formdat['form']=$form; 
$formdat['type']='fileform'; 
$formdat['description']=$_POST['description']; 
$formdat['category']=$_POST['category'];
$formdat['formdate']=$_POST['formdate'];
$formdat['state']=setstate($_POST['state']);
$formdat['ack']=$_POST['ack'];
$formdat['file']=$_FILES['file']['name']; 
if($_FILES['file']['name']!=''){ $file=1; /*file uploaded - Dosya yüklenmiştir.*/} 
$formdat['credate']=date("Y-m-d H:i:s", strtotime("now"));
echo $gtext['q_save'].": ".$form."->";
$fsira=1;
$formfields=[];

$coll=$db->Forms; //$filter = ["form"=>$form];     $options = [];
//if there is an old record, getting...
$cursor = $coll->findOne(
	[
		'form'=>$form,
	],
	[
		'limit' => 1,
		'projection' => [
		]
	]
);
$ksay=0;
if(isset($cursor)){	//getting record
	echo " Form ".$gtext['theris'].") "; 
}  
if($isl=='update'){ //update
	$acursor=$coll->updateOne(
		[
			'_id'=> new \MongoDB\BSON\ObjectId($id)
		],
		[
			'$set'=>$formdat
		]
	);
	if($acursor->getModifiedCount()>0){ echo $gtext['changed']; }
	else{ echo $gtext['notchanged']; }
}else{ //insert
	$acursor=$coll->insertOne(
		[
			$formdat
		]
	);
	if($acursor->getInsertedCount()>0){ echo $gtext['inserted']; }
	else{ echo $gtext['notinserted']; }
}
echo "\n";
//file upload  - yüklenen dosya varsa alınır...
if($file==1){
	echo $gtext['file'].": ".$_FILES['file']['name']." -> ";
	$dizin = $_POST['belge_yolu']; 
	$yuklenecek_dosya = $dizin . "/". basename($_FILES['file']['name']);
	if (move_uploaded_file($_FILES['file']['tmp_name'], "..".$yuklenecek_dosya)){
		echo $gtext['uploaded']; //yüklendi.
	}else{ echo $gtext['notuploaded']; /*yükleneMEdi.*/ }
}
?>