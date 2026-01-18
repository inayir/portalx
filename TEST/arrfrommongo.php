<?php
/*mongo db kaydından bir array gelince onun içindeki fieldları listeler */
include("../set_mng.php"); 
//error_reporting(0);
include($docroot."/sess.php");
$now=date("Y-m-d H:i:s", strtotime("now"));

$id='690adbb030049e8d760363f2';
$id=new \MongoDB\BSON\ObjectId($id);
@$collection=$db->test1;
	
	
$cursor=$collection->findOne(
	[ 
		'_id'=>$id
	]
);

if($cursor){ //var_dump($cursor); 
	$cd=$cursor->changedata;
}
$ck=array_keys(json_decode(json_encode($cd),true));
//var_dump($ck);
for($i=0;$i<count($ck);$i++){
	echo $i.":".$ck[$i]."<br>";
}
?>