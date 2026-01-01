<?php
/* Fixture Code Control... if used, result: nOK */
include("../set_mng.php");  //for Mongo DB connection, if needed. If not; write first line: include('/get_ini.php');
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
//error_reporting(0);
if($user==""){ //if auth pages needed...
	echo 'login'; exit;
}
@$code=$_POST['code'];  
$msg="OK"; $ksay=0;
if(@$_POST['f']=='FAR'){
	//FAR
	@$collection=$db->FixtAccRecords;
	$cursor = $collection->aggregate([
		[
			'$match'=>['farecord'=>$code],
		],
		[
			'$sort'=>[
				'description'=>1,
			]
		]
	]);
}else{
	//FXT
	$col=$db->FixtAccRecords; 
	$cursor=$col->findOne(
		[
			'code'=>$code
		],
		[
			'limit' => 1,
			'projection' => [
			],
		],
	);
}
if($cursor){
	foreach ($cursor as $formsatir) {	
		$satir=[];
		$satir['description']	=$formsatir->description;
		$ksay++;
	}
	if($ksay>0){ $msg="nOK"; }
}else{ $msg="nOK"; }
echo $msg;
?>