<?php
/*
	Department delete. LDAP and MongoDB 
*/
function datem($dat){
	return new \MongoDB\BSON\UTCDateTime(strtotime($dat)*1000);
}

include('../set_mng.php');
//error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
if($_SESSION["user"]==""){ 	echo "login"; exit; }
//
$base_dn=$ini['base_dn'];
if($ini['usersource']=='LDAP'){ require($docroot."/ldap.php"); }
$ou=$_POST['ou']; //if($ou==""){ @$ou=$_GET['ou']; }
//
$ksay=0; $sonuc=false; 
@$collection = $db->departments;
try{
	$cursor = $collection->findOne(
		[
			'ou'=>$ou,
		],
		[
			'limit' => 1,
			'projection' => [
				'dp' => 1,
				'ou' => 1,
				'company' => 1,
				'description' => 1,
				'managedby' => 1,
				'distinguishedname' => 1,
			]
		]
	);
	if(isset($cursor)){	
		$ksay=count($cursor); 
		$dn=$cursor->distinguishedname;
		$description=$cursor->description;
	}
}catch(Exception $e){
	echo 'Caught exception: '; echo  $e->getMessage(); echo "\n";
}
echo $description." ";
if($ksay>0){ //ou bulunduysa...
	//General
	$baseou=substr($base_dn,3, strpos($base_dn,',')-3);
	//işlem yapılıyor*********************************************************/
	if($ini['usersource']=='LDAP'){  //LDAP işlemleri
		//$data["objectclass"][1]= "organizationalUnit";
		$ldap_result = ldap_search($conn, $base_dn, "(ou=$ou)");
		if($ldap_result){  
			echo "\n* LDAP: ";
			$sonuc=ldap_delete($conn, $dn);
			if($sonuc){ echo $gtext['deleted']; }//" Silindi.";
			else{ 
				echo $gtext['notdeleted']." -> ";
				$log.=$db_ou.":".$gtext['notdeleted']."->".ldap_error($conn);
				echo $msg; exit;  
			}
		}
		echo "\n* DB: ";
	} //LDAP işlemleri sonu-----------------------------------------
	//DB işlemleri...................................................
	$data=array();
	$data['status']='D'; //silindi
 
	@$cursor = $collection->updateOne(
		[
			'ou'=>$ou
		],
		[ '$set' => $data ]
	); 
	if($cursor->getModifiedCount()>0){ echo $gtext['deleted']; $upd=1; }else{ echo $gtext['notdeleted']; $log.="{'delete error':''}"; }
}else{  
	echo "Not Found!"; 
} 
logger("department",$log);
?>