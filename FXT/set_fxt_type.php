<?php
/*
	Set Fixture type
*/
include("../set_mng.php"); 
//error_reporting(0);
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
if($user==""){
	//echo "login"; exit;
}
$now=date("Y-m-d H:i:s", strtotime("now"));
@$log=$now.";";
//
$data=Array();
@$state=$_POST["state"]; 
switch($state){ 
	case 1 		: $state=1; break; 
	case "1" 	: $state=1; break; 
	case "on" 	: $state=1; break; 
	case 0 		: $state=0; break; 
	case "0" 	: $state=0; break; 
	case "off" 	: $state=0; break; 
	case "" 	: $state=0; break; 
} 
@$default=$_POST["default"]; 
switch($default){ 
	case 1 		: $default=1; break; 
	case "1" 	: $default=1; break; 
	case "on" 	: $default=1; break; 
	case 0 		: $default=0; break; 
	case "0" 	: $default=0; break; 
	case "off" 	: $default=0; break; 
	case "" 	: $default=0; break; 
} 
$o_code=$_POST["o_code"];
$code=$_POST["code"];
//
if($_POST["code"]!=$_POST["o_code"]){ $data["code"]		= $_POST["code"];  }
if($_POST["type"]!=$_POST["o_type"]){ $data["type"]		= $_POST["type"]; }
if($default!=$_POST["o_default"]){ 	  $data["default"]	= $default; }
if($state!=$_POST["o_state"]){ 	  	  $data["state"]	= $state; }
$data["chdate"]	= $now;
$data["chuser"]	= $user;
//
$ksay=0; $ch=false;
@$collection=$db->Fixture_types;
$id=$_POST['id']; 
if($id!=''){ 
	$id=new \MongoDB\BSON\ObjectId($id); 
	$cursor = $collection->findOne(
		[
			'_id'=>$id,
		]
	);
	if(isset($cursor)){ $ksay=count($cursor);}
}
if($ksay<1){	//insert	1
	$isl="insert;";
	$data["crdate"]= $now;
	$data["cruser"]= $user;
	//önce kod kontrol edilir, varsa exit;
	@$cursori = $collection->findOne(
		[ 
			'code'=>$code
		]
	);
	if($cursori){ $csay=count($cursori); }
	if($csay>0){ echo $gtext['u_codeused']."!!"; exit; }
	//
	@$cursori = $collection->insertOne(
		$data
	);
	if($cursori->getInsertedCount()>0){ 
		echo $gtext['inserted']; $log.="Fixt ".$gtext['inserted'].";"; //eklendi
		$id=getInsertedId();
		$ch=true;
	}else{ echo $gtext['notinserted']."!!"; $log.="{'insert error':'';"; }
}else{ //update				2
	@$cursori = $collection->updateOne(
		[
			'_id'=>$id
		],
		[ '$set' => $data]
	);
	if($cursori->getModifiedCount()>0){ 
		echo $gtext['updated']; //güncellendi. 
		$ch=true;
		//bu kodu kullanan fixture kayıtlarında da kodlar değişir.
		$cdata=[]; 
		$cdata['type']=$data['code'];
		$cdata["chdate"]	= $now;
		$cdata["chuser"]	= $user;
		$col1=$db->Fixtures;
		//type değişecek kayıtlardaki kod alınır...
		$cur1=$col1->find(
			[
				'type'=>$o_code,
			]
		);
		if($cur1){
			//kayıtlar bir  arraya alınır...
			$cdatas=[];
			foreach($cur1 as $forcur1){
				$cdatas[]=$forcur1->code; 
			}
		}
		//Fixture kayıtlarındaki type değiştirilir.
		if($_POST['o_code']!=$_POST['code']){
			$cur2=$col1->updateMany(
				[
					'type'=>$o_code,
				],
				[
					'$set'=>$cdata
				]
			);
			if($cur2->getModifiedCount()>0){
				echo "\n".$gtext['updated']." ".$gtext['fixtures'].":".$cur2->getModifiedCount()." ".$gtext['record'];
			}
		}
		//$cdatas arraydaki kayıtlardaki type değişikliği Fixture_act dosyasına hareket olarak yazılır...
		$changedata='type:'.$data["code"];
		for($i=0;$i<count($cdatas);$i++){
			$datact=[];
			$datact['action']	 	='update';
			$datact['changedata']	=$changedata;
			$datact['actdate']		=mdatetimetodate($now);  //tarih desenine göre değiştirilecek...
			$colact=$db->Fixture_act;
			@$cursoract = $colact->insertOne(
				$datact
			);
		}
	}else{ 
		echo $gtext['notupdated']."!!"; $log.="{'update error':''};"; 
	}
}
if($ch){
	//default değişince diğer kayıtlar 
	if($default!=$_POST['o_default']&&$default==1){ 
		$datach=[];
		$datach['default']=0;
		@$cursori = $collection->updateOne(
			[
				'_id'=>['$ne'=>$id]
			],
			[ '$set' => $datach]
		);
	}
}
logger("set_fixture",$log);
?>