<?php
/*
	Set Fix Acc Record
*/
include("../set_mng.php"); 
error_reporting(0);
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
if($user==""){
	//echo "login"; exit;
}
//------------
function act($doc, $isl, $id, $now){//when Fixture appends...
	global $db,  $_POST;
	//all action saving. changing datas writes in $changedata //değişen bulunur...	
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
			$changedata='{"'.$k.'":"'.$odataval['o_'.$k]."->".$ndataval[$k].'"}';
			$fx++;
		}
	}
	//action data//act_date, type, code, action, changedata, user
	$datact=[];
	$datact['action']	 =$isl;
	$datact['fid']		 =$id;
	$datact['changedata']=$changedata;
	$datact['actdate']	 =$now;
	$colact=$db->$doc;
	@$cursoract = $colact->insertOne(
		$datact
	);
	//if($cursoract->getInsertedCount()>0){ echo "\n ".$gtext['actionsaved']; }
	//OK
}
//------------------
$now=date("Y-m-d H:i:s", strtotime("now"));
@$log=$now.";"; $ksay=0;
@$far_col=$db->FixtAccRecords;
$id=$_POST['_id'];
if($id!=''){ 
	$id=new \MongoDB\BSON\ObjectId($id);
		$cursor = $far_col->findOne(
			[ '_id'=>$id ]
		);
}
if(isset($cursor)){ $ksay=count($cursor);}//else{ echo $farecord." ".$gtext['notfound']; exit;}
$farecord=$_POST["farecord"];
$o_farecord=$_POST["o_farecord"];
//
$data=Array();
$data["description"]	= $_POST["description"];
$data["farecord"]		= $_POST["farecord"];
$data["type"]			= $_POST["type"];
$data["boughtfrom"]		= $_POST["boughtfrom"];
$data["inv_no"]			= $_POST["inv_no"];
$data["invdate"]		= $_POST["invdate"];
$itemsarr=[];
if(isset($_POST["items"])){
	$input=$_POST["items"];
	$itemsarr = explode("\n", str_replace("\r", "", $input));
}
$data["items"]			= $itemsarr;
if($user==''){ $user='inayir'; }
$data["cruser"] 		= $user;
//
if($ksay<1){	//insert	1
	@$cursori = $far_col->insertOne(
		$data
	);//*/
	if($cursori->getInsertedCount()>0){ 
		echo $gtext['inserted']; $log.="FAR ".$gtext['inserted'].";"; //eklendi
		$id=$cursor->getInsertedId();
	}else{ echo $gtext['notinserted']."!!"; $log.="{'insert error':'';"; }
}else{ //update				2
	$data['moduser']=$user;
	@$cursori = $far_col->updateOne(
		[
			'_id'=>$id
		],
		[ '$set' => $data]
	);
	if($cursori->getModifiedCount()>0){ 
		echo $gtext['updated']; //eklendi 
		$updated=1;
	}else{ echo $gtext['notupdated']."!!"; $log.="{'update error':''};"; }
}
//Fixture saving....
//öncelikle fxtlerdeki o_farecord a kayıtlı olanlarda fxtaccrecord alanı sıfırlanacak 
$fxt_col=$db->Fixtures;
$odata=[];
$odata['fixtaccrecord']='';
$ocursor=$fxt_col->updateOne(
	[
		'fixtaccrecord'=>$o_farecord,
	],
	[
		'$set'=>$odata
	]
);
exit;
//get default place 
$defplace=""; $def=1;
$dp_col=$db->Places; 
$dpcursor=$dp_col->findOne(
	['default'=>$def],
	[
		'limit' => 1,
		'projection' => [
			'code'=>1,
		],
	]
);
if($dpcursor){
	$defplace=$dpcursor->code;
}
$upd=0; $ins=0;
$odata['fixtaccrecord']=$farecord;
$odata['chdate']=$now; $p=0;
//all fixture records will be conztrolled...
for($i=0;$i<count($itemsarr);$i++){
	$isl='insert'; 
	$ocursor=$fxt_col->findOne(
		[ 'serialnumber'=>$itemsarr[$i] ],
		[
			'limit' => 1,
			'projection' => [
			],
		]
	);
	if($ocursor){ 	//if available fixture FAR updates!//varsa FAR güncellenir.
		$id=new \MongoDB\BSON\ObjectId($ocursor->_id);
		$ocursor=$fxt_col->updateOne(
			[
				'_id'=>$id,
			],
			[
				'$set'=>$odata
			]
		);
		if($ocursor->getModifiedCount()>0){  $upd++; $isl='update';}
	}else{	//fxt yoksa oluşturulur.
		$ndata=[]; 
		$prefix=$_POST['i_codeprefix']; 
		$p++; $d="";
		$uz=strlen(floatval($p)); 
		while($uz<3){ $d.="0"; $uz++; }
		//for($puz=$uz;$puz<3; $puz++){ $d.="0"; } //rakamın önüne 0 ekleme
		$ndata['code']			=$prefix.$d.$p; //Yeni001 gibi
		$ndata['description']	=$_POST["description"];
		$ndata['type']			=$_POST["type"];
		$ndata['fixtaccrecord']	=$farecord;
		$ndata['serialnumber']	=$itemsarr[$i];
		$ndata['place']			=$defplace;
		$ndata['state']			='A';
		$ndata["setdate"]		= $now;
		$ndata["username"]		= "mainstock";
		$ndata["cruser"]		= $user; //oluşturan
		$ocursor=$fxt_col->insertOne(
			$ndata
		);
		if($ocursor->getInsertedCount()>0){ $ins++; $id=$cursor->getInsertedId(); }
	}
	act('Fixture_act',$isl, $id, $now);
}
if($upd>0){ echo "\n".$upd." ".$gtext['fixtures']." ".$gtext['updated']; }
if($ins>0){	echo "\n".$ins." ".$gtext['fixtures']." ".$gtext['inserted']; }
//if saved or changed write

logger("set_fixaccrecs",$log);
?>