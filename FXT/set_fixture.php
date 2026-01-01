<?php
/*
	Set Fixture
*/
include("../set_mng.php"); 
error_reporting(0);
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
if($user==""){
	//echo "login"; exit;
}
$now=date("Y-m-d H:i:s", strtotime("now"));
@$log=$now.";";
$id=$_POST['_id'];
if($id!=''){ 
	$ids=new \MongoDB\BSON\ObjectId($id);
}
@$collection=$db->Fixtures;
$cursor = $collection->findOne(
	[
		'_id'=>$ids
	]
);
if(isset($cursor)){ $ksay=count($cursor);}
$data=Array();
$state=setstate($_POST["state"]);
switch($state){ 
	case 1 		: $state="A"; break; 
	case "1" 	: $state="A"; break; 
	case "on" 	: $state="A"; break; 
	case 0 		: $state="P"; break; 
	case "0" 	: $state="P"; break; 
	case "off" 	: $state="P"; break; 
} 
$sno=$_POST["serialnumber"];
$osno=$_POST["o_serialnumber"];
$ofar=$_POST["o_fixtaccrecord"];  if($ofar==''){ $ofar=$_POST["fixtaccrecord"];}
//
$data["username"]		= $_POST["username"];
if($_POST["username"]==''){ $data["username"]='mainstock'; }
$data["code"]			= $_POST["code"];
$code=$_POST["code"];
$data["description"]	= $_POST["description"];
$data["type"]			= $_POST["type"];
$data["serialnumber"]	= $sno;
$data["fixtaccrecord"]	= $_POST["fixtaccrecord"];
$data["state"]			= $state;
$data["privcode1"]		= $_POST["privcode1"];
$data["privcode2"]		= $_POST["privcode2"];
$data["place"]			= $_POST["place"];
$data["setdate"]		= $now;
$data["hostname"]		= $_POST["hostname"];
if($ksay<1){	//insert	1
	//önce kod kontrol edilir, varsa exit;
	@$cursori = $collection->findOne(
		[ 
			'code'=>$code
		]
	);
	if($cursori){ $csay=count($cursori); }
	if($csay>0){ echo $gtext['u_codeused']."!!"; exit; }
	$isl="insert";
	@$cursori = $collection->insertOne(
		$data
	);
	if($cursori->getInsertedCount()>0){ 
		echo $gtext['inserted']; $log.="Fixt ".$gtext['inserted'].";"; //eklendi
	}else{ echo $gtext['notinserted']."!!"; $log.="{'insert error':'';"; }
}else{ //update				2
	@$cursori = $collection->updateOne(
		[
			'_id'=>$ids
		],
		[ '$set' => $data]
	);
	if($cursori->getModifiedCount()>0){ 
		$isl="update";
		echo $gtext['updated']; //güncellendi. 
		$updated=1;
		//yer değişikliği yapılmmışsa
		if($_POST['place']!=$_POST['o_place']){
			echo "\n".$gtext['place']." ".$gtext['changed']; $log.="place changed;".$_POST['place']."->".$_POST['o_place'].";";
			$isl="placechange";
		}
	}else{ 
		echo $gtext['notupdated']."!!"; $log.="{'update error':''};"; 
		if($_POST['place']!=$_POST['o_place']){ $log.="place NOTchanged;"; }
		$isl="placechange";
	}
}
//$log.="Fixture data:".explode(',',$data)."};";
//Fixture_act action saving. changing datas writes in $changedata //değişen bulunur...
	$odata=[]; $odataval=[]; $ndata=[]; $ndataval=[]; $fx=0;
	foreach ($_POST as $key => $value) {
		$y=strpos($key, 'o_'); 
		if($y<0||$y==''){ $ndata[]=$key; $ndataval[$key]=$value;  }
		else{ $odata[]=$key; $odataval[$key]=$value; }
	}
	for($f=0;$f<count($ndata);$f++){
		$k=$ndata[$f];
		if($k!='_id'&&$ndataval[$k]!=$odataval['o_'.$k]){
			if($fx>0){ $changedata.=',';}
			$changedata=$k.':'.$ndataval[$k];
			$fx++;
		}
	}
	//action data//act_date, type, code, action, changedata, user
	$datact=[];
	$datact['action']	 	=$isl;
	$datact['fid']		 	=$id;
	$datact['changedata']	=$changedata;
	$datact['code']			=$data["code"];
	$datact['description']	=$data["description"];
	$datact['actdate']		=$now;
	$colact=$db->Fixture_act;
	@$cursoract = $colact->insertOne(
		$datact
	);
	//if($cursoract->getInsertedCount()>0){ echo "\n ".$gtext['actionsaved']; }
	//OK
//fixtaccrecord finds in FAR (fixt acc records)...
$farecord=$_POST["fixtaccrecord"]; //geçerli
$colfar=$db->FixtAccRecords;
$curfar=$colfar->findOne(
	[
		'farecord'=>$farecord,
	],
	[
		'limit' => 1,
		'projection' => [
		],
	]
);
$fardata=[];
if($curfar){ 
	if($curfar['_id']!=null){ $ksay=1; }else{ $ksay=0; } //bu net sonuç vermiyor.itemsa hep ekleniyor...
}
$itemsarr=[]; 
if($ksay==0){  //far yoksa eklenir ve seri no da eklenir. 3
	$fardata['farecord']	= $farecord;
	$fardata["description"]	= $_POST["desc"];
	$fardata["type"]		= $_POST["type"];
	$itemsarr[]=$sno;
	$fardata["items"]		= $itemsarr;
	$curfar=$colfar->insertOne(
		$fardata
	);
	/*/Fazladan mesaj kapatıldı.
	echo "\n ".$gtext['fixtaccrecord']." ";
	if($curfar->getInsertedCount()>0){ 
		echo "\n".$farecord." ".$gtext['inserted']." ".$gtext['u_farcreate']; 
		$log.="FAR ".$gtext['inserted'].":".$farecord.";";//eklendi
	}else{ echo $gtext['notinserted']."!!"; $log.="{'FAR insert error':''};"; }
	//$log.="Far data".explode(',',$fardata).";";
	//OK*/
}else{ //far varsa... 4
	//far var, items var ve itemsda serino yoksa eklenir...
	if(isset($curfar->items)){
		$itemsarr = json_decode(json_encode ( $curfar->items ) , true);
		//search sno in array 
		$var = array_search($sno, $itemsarr);
		if(!$var){ 
			$itemsarr[]=$sno; 
			$faritemsdata=[];
			$faritemsdata["items"] = $itemsarr;
			$curfar=$colfar->updateOne(
				[
					'farecord'=>$farecord,
				],
				[
					'$set'=>$faritemsdata
				]
			);
		}
	}	
	/*Fazladan mesaj kapatıldı.
	echo "\n".$gtext['serialnumber'].":".$sno." ";
	//if($curfar->getModifiedCount()>0){ echo $gtext['inserted']; $log.="FARa sno ".$gtext['inserted'].":".$farecord."->".$sno.";"; } 
	//else { echo $gtext['notinserted'];  $log.="{'FAR uodate error':''};"; }
	//OK*/
}
//----- far değişmişse eski fardan sno çıkarılır...
$log.="osno:".$osno." ? ".$sno.", ofar:".$ofar." ? ".$farecord.";";
if(($ofar!=''&&$ofar!=$farecord)||($osno!=''&&$osno!=$sno)){
	echo "\n".$gtext['serialnumber']." ";
	$ocurfar=$colfar->findOne(
		[
			'farecord'=>$ofar,
		],
		[
			'limit' => 1,
			'projection' => [
			],
		]
	);
	$itemsarr=[];
	if(isset($ocurfar->items)){
		$itemsarr = json_decode(json_encode ( $ocurfar->items ) , true);
	}
	$ind=array_search($sno, $itemsarr);
	unset($ind, $itemsarr);
	$odata=[];
	$odata['items']=$itemsarr;
	$ocurfar=$colfar->updateOne(
		[
			'farecord'=>$ofar,
		],
		[
			'$unset'=>$odata
		]
	);
	$ocurfar=$colfar->updateOne(
		[
			'farecord'=>$ofar,
		],
		[
			'$set'=>$odata
		]
	);
	if($ocurfar->getModifiedCount()>0){ 
		echo $gtext['removed']; //çıkarıldı
		$log.=$gtext['removed']." from old FAR;";
	}else{ echo $gtext['notremoved']."!!"; $log.="{'remove error':"."};"; }
	echo "->".$gtext['old']." ".$gtext['fixtaccrecord'].":".$ofar;
}

logger("set_fixture",$log);
?>