<?php
/*
	Kullanıcıyı başka bir OU altına taşır.
*/
include('../set_mng.php');
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
if($_SESSION['user']==""){
	echo "login"; exit;
} 
require($docroot."/ldap.php");
require($docroot."/app/php_functions.php");
ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION,3);
ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
$logfile='department';
$o_dn=$_POST['o_dn']; 
$description=$_POST['description']; 
$dep=substr($o_dn, strpos($o_dn, '=')+1, strpos($o_dn, ',OU')-3);
echo $gtext['a_department'].": ".$description."\n";
$company=$_POST['company'];
//newdn oluşturulur...
$newdn='OU='.$dep;
$newparent='OU='.$company.','.$ini['base_dn'];	
$log.="newdn:".$newdn.",".$newparent.";"; 
$dn=$newdn.','.$newparent;
if($o_dn!=''){ 
	if($ini['usersource']=='LDAP'){
		echo "\n*LDAP: "; $log.="LDAP;";		
		//-------------------------
		$sonuc=ldap_rename($conn, $o_dn, $newdn, $newparent, true);
		if($sonuc){
			echo $gtext['moved']." "; 
			$log.="department moved!".";";
			//personel company info will change
			$upd=0;
			$data['company']=$company;
			$filter = '(&(objectCategory=person)(samaccountname=*))'; 
			$ldap_result=ldap_search($conn, $dn, $filter);
			if($ldap_result){ 
				$info = ldap_get_entries($conn, $ldap_result); 
				if($info["count"]>0){ 
					for($p=0;$p<$info["count"];$p++){
						$o_user_dn=$info[$p]['distinguishedname'][0];						
						$psonuc=ldap_mod_replace($conn, $o_user_dn, $data);
						if($psonuc){ $upd++; }
					}
				}
				echo ", ".$upd." ".$gtext['user']." ".$gtext['updated'].".";
			}
		}else{
			//not moved!
			echo $gtext['notmoved']."! Please uncheck Company's Object protection.";
			$log.=$gtext['notmoved']."!".ldap_error($conn).";";
			logger($logfile,$log);
			exit;
		}
		echo "\n*DB: ";
	}//*/
	//Record to mongodb -----------------------------------------------
	$log.="*DB;"; 
	$data=array();
	$data['distinguishedname']=$dn;
	$data['company']=$company; 
	@$collection = $db->departments; $ksay=0; 	//echo "collection:".$collection."\n";
	@$cursor = $collection->updateOne(
		[
			'ou'=>$dep
		],
		[ '$set' => $data ]
	);
	if($cursor->getModifiedCount()>0){ 
		echo $gtext['updated']; 
		$log.=$gtext['updated'].";"; 
		//personel dosyasına yazılır...
		echo "\n**Personel:";
		$p_collection = $db->personel;
		$pdata=array();
		$pdata['company']=$company;
		$pdata['lastchdate']=datem(date("Y-m-d", strtotime("now").'T'.date("H:i:s", strtotime("now")).'.000+00:00'));
		$p_cursor = $p_collection->updateOne(
			[
				'department'=>$dep,
			],
			[ '$set' => $pdata]
		);
		if($p_cursor->getInsertedCount()>0){ 
			echo " ".$gtext['updated']; $log.=$gtext['updated'].";"; 
		}else{ echo $gtext['notupdated']."!!->"; $log.=$gtext['notupdated']."{'update error':''};";  }
		//personel_act dosyasına yazılır...
		echo "\n**Activity:";
		$act_collection = $db->personel_act;
		$datact['act']='move';
		//değişen veriler alınır. $data 
		$allkey=[];
		$allkey=getkeys($data);
		for($k=0;$k<count($allkey);$k++){
			if($allkey[$k]!='_id'&&$allkey[$k]!='act'&&$allkey[$k]!='displayname'&&$allkey[$k]!='distinguishedname'&&$allkey[$k]!='actdate'){
				$field=$allkey[$k];  //var_dump($field);
				$val=$data->$field;
				$dt.=$field.":".$val.";";
			}
		}
		$datact['changedata']=$dt;  //*/
		$datact['date']=datem(date("Y-m-d", strtotime("now").'T'.date("H:i:s", strtotime("now")).'.000+00:00'));
		$act_cursor = $act_collection->insertOne(
			$datact
		);
		if($act_cursor->getInsertedCount()>0){ echo " ".$gtext['updated']; $log.=$gtext['updated'].";";  }
		else{ echo $gtext['notupdated']."!!->"; $log.=$gtext['notupdated']."{'update error':''};";  }
	}else{ 
		echo $gtext['notupdated']."!"; 
		$log.=$gtext['notupdated']."{'update error':''};"; 
	} 
}else{ echo " ! ".$gtext['u_fieldmustnotblank']."!"; }  //Alanlar boş olamaz
//
logger($logfile,$log);
?>