<?php
/*
	Set Fix Debit
*/
include("../set_mng.php"); 
error_reporting(0);
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
if($user==""){
	//echo "login"; exit;
}
$now=date("Y-m-d H:i:s", strtotime("now"));
@$log=$now.";"; $ksay=0;
//Fixture finding...
@$fxt_col=$db->Fixtures;
$id=$_POST['_id']; //if($id==''){ $id="68f34f6df98d55588703b070"; }
$data=Array();
if($id!=''){ 
	$ids=new \MongoDB\BSON\ObjectId($id);
		$cursor = $fxt_col->findOne(
			[ '_id'=>$ids ]
		);
}
if(isset($cursor)){ 
	$ksay=count($cursor);	
	$data["code"]		=$cursor->code;
	$data["description"]=$cursor->description;
}else{ echo $gtext['fixture']." ".$gtext['notfound'].":".$id; exit; } //Fixture notfound
//2. username değişir...
$data["username"]	= $_POST["username"];
$data["debitdate"]	= $_POST["debitdate"];
if($user==''){ $user='adm_inayir'; }
$data['moduser']=$user;
@$cursori = $fxt_col->updateOne(
	[
		'_id'=>$ids
	],
	[ '$set' => $data]
);
if($cursori->getModifiedCount()>0){ 
	if($_POST['username']=='mainstock'){ echo $gtext['debit_released']; /*Zimmet bırakıldı*/}
	else{ echo $gtext['embezzled']; /*Zimmetlendi*/}
	$updated=1;
}else{ echo $gtext['notupdated']."!!"; $log.="{'update error':''};"; }
//Fixture_act action saving. changing datas writes in $changedata 
	//değişen bulunur...
	$odata=[]; $odataval=[]; $ndata=[]; $ndataval=[]; $fx=0;
	foreach ($_POST as $key => $value) {
		$keyy=strpos($key, 'o_'); 
		if($keyy<0||$keyy==''){ $ndata[]=$key; $ndataval[$key]=$value;  }
		else{ $odata[]=$key; $odataval[$key]=$value; }
	}
	for($f=0;$f<count($ndata);$f++){
		$k=$ndata[$f]; 
		if($k!='_id'){
			if($ndataval[$k]!=$odataval['o_'.$k]){
				if($fx>0){ $changedata.=',';}
				$changedata=$k.':'.$ndataval[$k];
				$fx++;
			}
		}
	}
	//action data//act_date, type, code, action, changedata, user
	$datact=[];
	$datact['action']	 	='debit'; //$isl;
	$datact['fid']		 	=$id;
	$datact['changedata']	=$changedata;
	$datact['code']			=$data["code"];
	$datact['description']	=$data["description"];
	$datact['actdate']		=mdatetimetodate($now);  //tarih desenine göre değiştirilecek...
	$colact=$db->Fixture_act;
	@$cursoract = $colact->insertOne(
		$datact
	);
	//if($cursoract->getInsertedCount()>0){ echo "\n ".$gtext['actionsaved']; }
	//OK

logger("set_fxt_debit",$log);
?>