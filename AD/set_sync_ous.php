<?php
/*
	set_user_sync: LDAP ile MongoDB senkronizasyonu LDAP->MongoDB
*/
error_reporting(0);
include("../set_mng.php");	
include($docroot."/sess.php");	
include($docroot."/ldap.php");
if(!$bind){ echo "LDAP Server Not Found!"; exit; }
//
$log="";
include($docroot."/app/php_functions.php");
$logfile='sync_ous';
//-----mongo------------------------------------
$collections = $db->listCollections();
$collectionNames = [];
foreach ($collections as $collection) {
  $collectionNames[] = $collection->getName();
}
$exists = in_array('departments', $collectionNames);
if(!$exists){
		$db->createCollection('departments',[
	]);
}
//
$base_dn=$ini['base_dn']; 
$liste=Array("ou", "company", "description", "managedby","distinguishedname");
$upd=0; $ins=0;
$filter="ou=*"; 
$result = ldap_search($conn, $base_dn, $filter, $liste);  //echo "result:".$result;
if($result){	
	$entries = ldap_get_entries($conn, $result); 
	$esay=$entries['count']; //var_dump($entries);
	for ($ix=0; $ix<$esay; $ix++){ 
	//for ($ix=0; $ix<5; $ix++){ 
		$ksay=0; 
		$ou=$entries[$ix]['ou'][0]; 
		if($ou!=$ini['groups_point']&&$entries[$ix]['description'][0]!=''){
			$data=[];			
			$company=substr($entries[$ix]['distinguishedname'][0],3);
			$uz=strlen($ou); 
			$company=substr($company,$uz+1);
			$dp="D";
			if($company!=$base_dn){
				$uz=strpos($company,',');
				$company=substr($company,3, $uz-3);
			}else{ $company=$ou; $dp="C";}
			//
			$data['dp'] 		= $dp;
			$data['ou'] 		= $entries[$ix]['ou'][0];
			$data['company']	= $company;
			$data['distinguishedname']  = $entries[$ix]['distinguishedname'][0];
			$data['description'] 		= $entries[$ix]['description'][0];
			$data['state'] 				= "A";
			
			$log.="managedby:";
			$managedby=$entries[$ix]['managedby'][0];
			if($managedby!=""){ //manager DN
				//Disabled_ ile başlayan isimler kesilir...
				$yer=strpos($managedby,$ini['disabledname']);
				if($yer!=''&&$yer>=0){
					$managedby='-'; $log.=":".$ini['disabledname']."-;";
				}else{
					//$managedby=substr($managedby,3);
					//$managedby=substr($managedby, 0, strpos($managedby,',')); $log.=$managedby.";";
					$manager="";
					@$pcollection = $db->personel; 
					@$pcursor = $pcollection->findOne(
						[
							'distinguishedname'=>$managedby
						],
						[
							'limit' => 1,
							'projection' => [
								'username' => 1,
							],
						],
					);
					if(isset($pcursor)){	$psay=1; }
					if($psay>0){
						$manager=$pcursor->username;
					}
				}
			}else{ $managedby='-'; $log.=":-;"; }//*/
			$data['managedby']	= $managedby;
			$data['manager']	= $manager;
			//
			@$collection = $db->departments; 
			//kayıt varsa güncellenir.----------------------
			@$cursor = $collection->findOne(
				[
					'ou'=>$ou
				],
				[
					'limit' => 1,
					'projection' => [
						'state' => 1,
					],
				],
			);
			if(isset($cursor)){	
				$ksay=1; 
				if($cursor->state<>'C'&&$cursor->state<>'D'&&$cursor->state<>'A'){ $data['state']='A'; echo "-".$data['state'];}
				$msg.=",";
			}
			if($ksay>0){ 
				@$cursor = $collection->updateOne(
					[
						'ou'=>$ou
					],
					[ '$set' => $data ]
				);//*/
				if($cursor->getModifiedCount()>0){ echo $gtext['updated']."->"; $upd++; }else{ echo $gtext['notupdated']."->"; $log.="{'update error':''}"; }
			}else{  //Yeni kayıt
				@$cursor = $collection->insertOne(
					$data
				);
				if($cursor->getInsertedCount()>0){ 
					echo $gtext['inserted']."->"; $ins++;  //eklendi
				}else{ echo $gtext['inserted']."=>"; $log.="{'insert error':''}"; }
			}//*/
			$log.="\n"; 
			echo $company."->".$ou."(".$data['description'].")<br>"; // managedby:".$managedby."
		}
	}
}
echo "\n<br> -> ".$gtext['records'].": ".$upd." ".$gtext['updated'].", ".$ins." ".$gtext['inserted']."<br>";
$log.=$upd." Kayıt Güncellendi, ".$ins." Kayıt Eklendi.\n";
logger($logfile,$log);
?>