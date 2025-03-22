<?php
/*
	LDAP OU ve SubOU larını json veri tipinde getirir. 
*/
include('../set_mng.php');
//error_reporting(0);
header('Content-Type:text/html; charset=utf-8');
include($docroot."/sess.php");
if($user==""){
	header('Location: /login.php');
}
$log=date("Y-m-d H:i:s", strtotime("now")).";";
$base_dn=$ini['base_dn']; 
require($docroot."/ldap.php");
$dp=$_POST["dp"]; if($dp==""){ $dp='company'; }
$ou=$_POST["ou"]; //if($ou==""){ $ou=$_GET["ou"]; $dp=$ou; }
$log.="ou:".$ou.";";
//DB
@$collection = $db->departments;
try{
	$cursor = $collection->aggregate([
		[
			'$match'=>[
				'$and'=>[['dp' => ['$ne'=>'']],[$dp => ['$eq'=>$ou]]]
			],
		],
		['$lookup'=>
			[
				'from'=>"personel",
				'localField'=>"manager",
				'foreignField'=>"username",
				'as'=>"pers"
			]
		],
		['$unwind'=>'$pers'],
		['$addFields'=> [
				'dmanager' => '$pers.displayname',
			],
		],
		['$sort' => [
			  'department' => 1, 
			],
		],
	]);
	if(isset($cursor)){	
		$ksay=0; 
		foreach ($cursor as $formsatir) {
			$satir=[];
			$satir['ou']=$formsatir->ou;
			$satir['company']=$formsatir->company;
			$satir['description']=$formsatir->description;
			$satir['managedby']=$formsatir->managedby;
			$satir['dmanager']=$formsatir->dmanager;
			$info[]=$satir;
			$ksay++;
		} //*/	
	}
}catch(Exception $e){		
}
$is=0; $json='['; 
for ($i=0; $i < $ksay; $i++){
	$m=""; $dm="";
	$a=$info[$i]["ou"];
	$d=$info[$i]["description"];
	$m=$info[$i]["managedby"];
	$dm=$info[$i]["dmanager"];
	if($d!=""){ 
		if($is>0){ $json.=','; }
		$s='{"key": "'.$a.'", "value":"'.$d.'", "manager":"'.$m.'", "dmanager":"'.$dm.'"}'; 
		$json.=$s;
		$is++;
	}
}
$json.=']'; 
echo $json;
?>