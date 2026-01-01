<?php 
/* Fixture Account Record */
include("../set_mng.php");  //for Mongo DB connection, if needed. If not; write first line: include('/get_ini.php');
include($docroot."/sess.php");
if($user==""){ //if auth pages needed...
	//header('Location: /login.php');
}
@$collection=$db->FixtAccRecords;
$cursor = $collection->find(
	[
		'description'=>['$ne'=>null]
	],
	[
		'limit' => 0,
		'projection' => [
		],
	]    
);
$fsatir=[];
foreach ($cursor as $formsatir) {
	$satir=[]; 
	$satir['_id']		 =$formsatir->_id;  
	$satir['farecord']	 =$formsatir->farecord; 
	$satir['type']		 =$formsatir->type; 
	$satir['description']=$formsatir->description; 
	$fsatir[]=$satir;
}
//var_dump($fsatir); 
$json='['; 
$fsay=count($fsatir); //echo "fsay:".$fsay."<br>";
for($i=0;$i<$fsay;$i++){ $jsat='';
	if($i>0){ $jsat.=','; }
	$jsat.='{"farecord":"'.$fsatir[$i]['farecord'].'","type":"'.$fsatir[$i]['type'].'","description":"'.$fsatir[$i]['description'].'"}';
	$json.=$jsat;
}
echo $json.']';

?>