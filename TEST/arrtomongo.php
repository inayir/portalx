<?php
/*Personel actions list : */
include("../set_mng.php"); 
//error_reporting(0);
include($docroot."/sess.php");
$now=date("Y-m-d H:i:s", strtotime("now"));
@$collection=$db->test1;
		$ndata=[]; 
		$ndata['action']		="insert";
		$ndata['fid']			="68fa1a2930049e8d760363bd";
		$ndata["actdate"]		= $now;
		$arr=[];
		$arr['state']='1';
		$arr['place']='4200';
		$arr['cruser']='adm_inayir';//*/
		$ndata['changedata']=$arr;
		
		$cursor=$collection->insertOne(
			$ndata
		);

/*

//*/
if($cursor->getInsertedCount()>0){ 
	echo "Inserted!";
}else{ echo "Insert Olmadı!"; }

var_dump($ndata);
?>