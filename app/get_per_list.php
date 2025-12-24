<?php
include('../set_mng.php');
error_reporting(0);
include($docroot."/sess.php");
header('Content-Type:text/html; charset=utf8');
if($_SESSION['user']==""){ 
	//header('Location: /login.php');
}
if($_SESSION['y_admin']!=1){ echo "login"; exit; }
$dis=$ini['disabledname'];
$keys=$_POST['keys'];  //$keys=['username','displayname','title'];echo "keys:".$keys;
@$collection=$db->personel;
$cursor = $collection->find(
	[
		'$and'=>[['description'=>['$ne'=>'']],['description'=>['$ne'=>null]],['displayname'=>['$ne'=>null]]]
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort'=>['displayname'=>1],
	]
);
$json='['; $ilk=0;
foreach ($cursor as $formsatir) {
	if(strpos($formsatir->displayname, $dis)==""||strpos($formsatir->displayname, $dis)<0||strpos($formsatir->username, 'adm_')<0){
		if($ilk>0){ $json.=','; }
		$json.='{';
		for($k=0;$k<count($keys);$k++){
			if($k>0){ $json.=','; }
			$key=$keys[$k];
			$json.='"'.$keys[$k].'":"'.$formsatir->$key.'"';
		}
		$json.='}';
		$ilk++;
	}
}
$json.=']';
echo $json;
?>