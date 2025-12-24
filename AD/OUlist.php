<?php
/*
	LDAP OU ve SubOU larını json veri tipinde getirir. 
*/
include('../set_mng.php');
error_reporting(0);
header('Content-Type:text/html; charset=utf-8');
include($docroot."/sess.php");
if($user==""){
	header('Location: /login.php');
}
$log=date("Y-m-d H:i:s", strtotime("now")).";";
$base_dn=$ini['base_dn']; 
require($docroot."/ldap.php");
@$dp=$_POST["dp"]; if($dp==""||$dp=="C"){ $dp='company'; }else{ $dp='ou'; }
@$ou=$_POST["ou"]; //if($ou==""){ $ou=$_GET["ou"]; $dp=$ou; }
$log.="ou:".$ou.";";
//DB 
$ksay=0; 
@$collection = $db->departments;
@$pcol 		 = $db->personel;
$cursor = $collection->find(
	[
		$dp => $ou,
	],
	[
		'sort' => [
			'description' => 1,
		],
	]
);
$fsatir=[];
if(isset($cursor)){			
	foreach ($cursor as $formsatir) {
		$satir=[];
		$satir['ou']			=$formsatir->ou;
		$satir['company']		=$formsatir->company;
		$satir['description']	=$formsatir->description;
		$satir['managedby']		=$formsatir->managedby;
		$satir['manager']		=$formsatir->manager;
		$satir['state']		=$formsatir->state;
		$fsatir[]=$satir;
		$ksay++;
	} //*/	
}
//var_dump($fsatir);

$is=0; $json='[';
if($ksay>0){
	for ($i=0; $i < $ksay; $i++){
		$a=""; $d=""; $m=""; $dm="";
		$a=$fsatir[$i]["ou"];
		$d=$fsatir[$i]["description"];
		$m=$fsatir[$i]["managedby"];
		//----
		$dm="";
		$pcur=$pcol->findOne(
			[
				'username' => $fsatir[$i]["manager"],
			],
			[
				'limit' => 1,
				'projection' => [
					'displayname' => 1,
				],
			],
		);
		if($pcur){
			$dm=$pcur->displayname;
		}
		//----
		$st=$fsatir[$i]["state"];
		if($d!=""){ 
			if($is>0){ $json.=','; }
			$s='{"key": "'.$a.'", "value":"'.$d.'", "manager":"'.$m.'", "dmanager":"'.$dm.'", "state":"'.$st.'"}'; 
			$json.=$s;
			$is++;
		}
	}
} 
$json.=']'; 
echo $json;
?>