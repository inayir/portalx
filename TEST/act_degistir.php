<?php
/*mongo db kaydından bir array gelince onun içindeki fieldları listeler */
include("../set_mng.php"); 
//error_reporting(0);
include($docroot."/sess.php");
$now=date("Y-m-d H:i:s", strtotime("now"));

function getkeys($gelenarr){
	return array_keys(json_decode(json_encode($gelenarr),true));
}

@$collection=$db->personel_act;	
$cursor=$collection->find(
);
if($cursor){ 
	$inc=1;
	foreach($cursor as $formsatir){
		$id=$formsatir->_id;
		$dt=""; $allkey=[];
		$allkey=getkeys($formsatir); 
		for($k=0;$k<count($allkey);$k++){
			if($allkey[$k]!='_id'&&$allkey[$k]!='act'&&$allkey[$k]!='displayname'&&$allkey[$k]!='distinguishedname'&&$allkey[$k]!='actdate'){
				$field=$allkey[$k];  //var_dump($field);
				$val=$formsatir->$field;
				$dt.=$field.":".$val.";";
			}
		}
		$satir['action']		=$formsatir->act; 
		$satir['username']		=$formsatir->username; 
		$satir['displayname']	=$formsatir->displayname; 
		$satir['changedata']	=$dt; 
		$satir['actdate']		=$formsatir->actdate; 
		//
		$deg=$collection->insertOne(
			[
				'$set'=>$satir
			]
		);//*/
		echo '<br>-> ';
		if($deg->getModifiedCount()>0){  
			echo 'update: ';
			$del=$collection->remove(
			[
				'_id'=>$id
			]);
		}else{ echo "not update "; }
		echo $id;
	}
	// 	
}
?>