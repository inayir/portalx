<?php
/*
	FormData: Form verisinin kaydedilmesi... 
	Std document: FormData. $satir->table boşsa FormData
*/
function get_mongodata($document, $filter, $options){
	global $conn;	
	$sorgu = new MongoDB\Driver\Query($filter, $options);
	return $conn->executeQuery($document, $sorgu);
}
//error_reporting(0);
$ini = parse_ini_file("../config/config.ini.php");
include("../sess.php");
$mongoconn=$ini['MongoConnection'];
$conn = new MongoDB\Driver\Manager($mongoconn);	
//
$form=$_POST['form']; //"YFR-040";
if($form==''){ exit; }
//
$document=$ini['MongoDB'].'.Forms';
$filter = ["form"=>$form];     
$options = [];
$sonuc=get_mongodata($document, $filter, $options);
$filter = Array();
foreach ($sonuc as $satir){ } 
$tumfields=$satir->fields;
$tf = json_decode(json_encode ( $tumfields ) , true);
$tfsay=count($tf);
if($satir->form_secure=='1'){
	if($user==""){ 
		//header('Location: /login.php');
	}
}

$veri = new MongoDB\Driver\BulkWrite();
$key1=$_POST['key1']; //update için _id
//form verisi alınıp kayıt oluşturulur......
if($key1==""){  //Insert
	$filter = ["form"=>$form];    
	$formdat["form"]=$form;
	for($f=1;$f<=$tfsay;$f++){ 
	  $fs='field_'.$f; $tip=$satir->fields->$fs->type;
	  if($tip=='p'||$tip=='text'||$tip=='radio'||$tip=='select'||$tip=='date'){
		//gelen veriler kaydedilir.
		if(isset($_POST[$satir->fields->$fs->name])){
			$gelen=$_POST[$satir->fields->$fs->name];
			$formdat[$satir->fields->$fs->name]=$gelen;
		}else{			
			$formdat[$satir->fields->$fs->name]=null;  //field boş geldiyse yeri ayrılır.
		}
	  }
	  if($satir->fields->$fs->type=='file'){
		  //yüklenen dosya var ise 
	  }
	} 
	//----Kaydededilir--------------
	$veri->insert($formdat);
	$sonucinsert = $conn->executeBulkWrite($ini['MongoDB'].'.FormData', $veri); //.$satir->table iptal
	if($sonucinsert->getInsertedCount()>0){  echo ' '.$gtext['inserted'];  } //Eklendi. 
}else{  //update	---değişecek.... fields te yer alan alanlar alınarak _POST ve sırası ile alınacak...
	for($f=1;$f<=$tfsay;$f++){ 
	  $fs='field_'.$f; $tip=$satir->fields->$fs->type;
	  if($tip=='p'||$tip=='text'||$tip=='radio'||$tip=='select'||$tip=='date'){
		//gelen veriler kaydedilir.
		if(isset($_POST[$satir->fields->$fs->name])){
			$gelen=$_POST[$satir->fields->$fs->name];
			$formdat[$satir->fields->$fs->name]=$gelen;
		}
	  }
	  if($satir->fields->$fs->type=='file'){
		  //yüklenen dosya var ise 
	  }
	} 
	//güncellenir.....
	if($satir->key1=="_id"){ //?
		$filter['_id']=new MongoDB\BSON\ObjectID($key1);
	}else{ $filter[$satir->key1]=$key1; }  //form ve _id  
	$k=new MongoDB\BSON\ObjectID($key1);	
	$veri->updateMany(
		['_id'=>$k], 
		['$set'=>$formdat]
	);
	$sonucupdate = $conn->executeBulkWrite($ini['MongoDB'].'.FormData', $veri); //.$satir->table iptal
	if($sonucupdate->getModifiedCount()>0){ echo "Update OK!"; }
}
//log----------------------------
?>