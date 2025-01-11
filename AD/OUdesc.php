<?php
/*
	LDAP OU ve SubOU larını json veri tipinde getirir. 
*/
include('../set_mng.php');
//error_reporting(0);
header('Content-Type:text/html; charset=utf-8');
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/sess.php");
/*if($user==""){
	header('Location: \login.php');
}//*/
$log=date("Y-m-d H:i:s", strtotime("now")).";";
$base_dn=$ini['base_dn']; 
require($docroot."/ldap.php");
$dp=$_POST["dp"]; 
if($dp==""){ $dp='company'; }else{ $dp='department'; }
$ou=$_POST["ou"]; //if($ou==""){ $ou=$_GET["ou"]; $dp=$ou; }
$log.="ou:".$ou.";";
//DB
@$collection = $db->departments;
try{
	@$cursor = $collection->find(
		[
			'ou' => ['$eq'=>$ou],
		],
		[
			'limit' => 0,
			'projection' => [
				'ou' => 1,
				'description' => 1,
				'managedby' => 1,
			],
			'sort'=>['dp'=>-1,'description'=>1],
		]
	);
	if(isset($cursor)){	
		$ksay=0; 
		foreach ($cursor as $formsatir) {
			$satir=[];
			$satir['ou']=$formsatir->ou;
			$satir['description']=$formsatir->description;
			$satir['managedby']=$formsatir->managedby;
			$info[]=$satir;
			$ksay++;
		} //*/	
	}
}catch(Exception $e){		
}

$is=0; $json='['; 
for ($i=0; $i < $ksay; $i++){
	$m=""; $dm="";
		$d=$info[$i]["description"];
		$m=$info[$i]["managedby"];
	if($d!=""){ 
		if($is>0){ $json.=','; }
		$s='{"value":"'.$d.'", "manager":"'.$m.'"}'; 
		$json.=$s;
		$is++;
	}
}
$json.=']'; 
echo $json;
?>