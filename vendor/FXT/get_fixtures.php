<?php
/*get Fixture_list by personel*/
include("../set_mng.php"); 
error_reporting(0);
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
if($user==""){
	//echo "login"; exit;
}
@$username=$_SESSION['user']; 
$now=date("Y-m-d H:i:s", strtotime("now"));
@$log=$now.";"; $sea='';
$username=$_POST['user'];
$keys=$_POST['keys'];
//Fixture_types
$tcol=$db->Fixture_types;
$tcursor=$tcol->find(
	[
		'code'=>['$ne'=>null]
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort' => [
			'type'=>1,
		],
	],
);
if($tcursor){
	$ftsatir=[];
	foreach ($tcursor as $tformsatir) {
		$tsatir=[];
		$tsatir['code']	=$tformsatir->code;
		$tsatir['type']	=$tformsatir->type;
		$ftsatir[]=$tsatir;
	}
}
//place
$pcol=$db->Places;
$pcursor=$pcol->find(
	[
		'state'=>['$ne'=>null]
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort' => [
			'code'=>1,
			'description'=>1,
		],
	],
);
if($pcursor){
	$fpsatir=[];
	foreach ($pcursor as $pformsatir) {
		$psatir['code']			=$pformsatir->code;
		$psatir['description']	=$pformsatir->description;
		$fpsatir[]=$psatir;
	}
}
//
@$collection=$db->Fixtures;
$cursor = $collection->find(
	[
		'username'=>$username
	],	
	[
		'limit' => 0,
		'projection' => [
		],
	]
);

$json='['; $ilk=0;
foreach ($cursor as $formsatir) {
	if($ilk>0){ $json.=','; }
	$json.='{';
	for($k=0;$k<count($keys);$k++){
		if($k>0){ $json.=','; }
		$key=$keys[$k];
		$value=$formsatir->$key;
		if($key=='type'){
			$ti=array_search($value, array_column($ftsatir, 'code')); 
			if($ti!=false||$ti!=''){ $value=$ftsatir[$ti]['type']; }
		}
		if($key=='place'){
			$pi=array_search($value, array_column($fpsatir, 'code'));
			if($pi!=false||$pi!=''){ $value=$fpsatir[$pi]['description']; }
		}
		if($key=='debitdate'&&$value!=''){
			$value=mdatetodate($value);
			$value=date($ini['date_local'], strtotime($value));
		}
		//
		$json.='"'.$keys[$k].'":"'.$value.'"';
	}
	$json.='}';
	$ilk++;
}
$json.=']';
echo $json;
?>