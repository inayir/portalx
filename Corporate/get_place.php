<?php
/*
	Place insert/edit.
*/
include("../set_mng.php");
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php"); 
if($_SESSION["user"]==""){
	echo "login"; exit;
}
$keys=['_id','code','description','type','streetaddress','district','st','country','telephonenumber','opened_date','state'];
$code=$_POST['p'];
@$collection=$db->Places;
$cursor = $collection->findOne(
	[
		'code' => $code,
	],
	[
		'limit' => 1,
		'projection' => [
		],
	]
);
$json='['; 	 //tüm bilgiler getirilir var_dump($cursor);
if($cursor){
	if(count($cursor)>0){ //echo print_r($fsatir); //echo "key count:".count($keys);
		for($i=0;$i<count($keys);$i++){
			if($i>0){ $json.=','; } 
			$deger=$cursor[$keys[$i]]; //echo "; ".$deger;
			$json.='{"key":"'.$keys[$i].'", "value": "'.$deger.'"}';
		}
	}else{ echo "{'NOT Found!'}"; }
}
$json.=']';
echo $json;
?>