<?php
include("../set_mng.php"); 
$col=$db->test;
$data=[];
//$data['_id']=new MongoId();
$data['code']='Test';
$data['desc']='Test Description';
$data['date']=date("y-m-d H:i:s", strtotime("now"));

@$cursor = $col->insertOne(
	$data
);
if($cursor->getInsertedCount()>0){ 
	echo 'inserted, oid: '.$cursor->getInsertedId();
}
	
	
?>