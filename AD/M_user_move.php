<?php
/*
	Kullanıcıyı başka bir OU altına taşır.
*/
include("../set_mng.php");
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
if($_SESSION['user']==""){
	echo "login"; exit;
} 
require($docroot."/ldap.php");
require($docroot."/app/php_functions.php");
ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
$logfile='personel';
$username=$_POST['username']; //if($username==''){ @$username=$_GET['u']; }
$displayname=$_POST['displayname']; 
$o_userdn=$_POST['distinguishedname']; //echo "u:".$username." o_userdn:".$o_userdn;//exit;
//
echo $gtext['user'].": ".$username;
$log="\nMoving;username: ".$username.";dn: ".$o_userdn.";";
if($username!=''){
	if($ini['usersource']=='LDAP'){
		$log.="LDAP;";
		$data=array(); 
		$name=str_return($displayname,1); //tr karakter olmayan isim. Dn üretmede kullanılmalı.
		//newdn oluşturulur...
		$newdn='CN='.$name;
		$newparent='OU='.$_POST['department']; $department=$_POST['department'];
		if($_POST['company']!=$_POST['department']){ $newparent.=',OU='.$_POST['company']; $department=$_POST['company']; }
		$newparent.=','.$ini['base_dn'];	
		$log.="newdn:".$newdn.",".$newparent.";"; 
		$data['department']=$_POST['department'];
		$data['o_department']=$_POST['o_department'];
		$data['company']=$_POST['company']; 
		$data['o_company']=$_POST['o_company']; 
		$data['manager']=$_POST['managerdn'];
		//echo "dn:".$o_userdn."/ *newdn:".$newdn."/ *newparent:".$newparent.""; //exit;
		
		//ldap moving-------------------------
		$sonuc=ldap_rename($conn, $o_userdn, $newdn, $newparent, true);
		echo "\n*LDAP: "; 
		if($sonuc){
			echo " ".$gtext['moved'].", "; $log.="user moved.;";
			$dn=$newdn.','.$newparent;
			require("./ldap_functions.php");
			//groups->removing: remove user from security groups
			$msgremove="\n*".$gtext['ldap_groups'];
			$msgremove.=removefromgroups($dn); 
			echo $msgremove;  
			//groups->adding: add user to new department groups.
			$msg2=addtogroupsfromini($dn);
			if($msg2!=""){ echo $msg2; }
			//moving-data update to ldap
			unset($data['o_department']);
			unset($data['o_company']);
			$sonuc1=ldap_mod_replace($conn, $dn, $data); 
			echo "\n**LDAP data ";
			if($sonuc1==1){ echo $gtext['updated']; $log.=";\nLDAP data:".$gtext['updated'].";";}else{ echo $gtext['notupdated']; $log.=";\nLDAP data ".$gtext['notupdated'].";";}
			echo " ".$msg;
		}else{
			//not moved!
			echo " ".$gtext['notmoved']."! ";
			echo $msgremove;
			//re-adding to old groups...
			$data['department']=$_POST['o_department'];
			$data['company']=$_POST['o_company']; 
			$msg2=addtogroupsfromini($o_userdn);
			echo $msg2;
			$log.=$gtext['notmoved']."!".ldap_error($conn).";";
			logger($logfile,$log);
			exit;
		}
	}
	//Record to mongodb -----------------------------------------------
	echo "\n*DB:"; $log.="\n*DB;"; 
	$data['distinguishedname']=$dn;
	$data['manager']=$_POST['manager'];
	//
	@$collection = $db->personel;
	@$cursor = $collection->updateOne(
		[
			'distinguishedname'=>$o_userdn
		],
		[ '$set' => $data ]
	);
	if($cursor->getModifiedCount()>0){ 
		echo $gtext['updated']; 
		$log.=$gtext['updated'].";"; 
		//personel_act dosyasına yazılır...
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
		$datact['actdate']=datem(date("Y-m-d", strtotime("now").'T'.date("H:i:s", strtotime("now")).'.000+00:00'));
		$act_cursor = $act_collection->insertOne(
			$datact
		);
		if($act_cursor->getInsertedCount()>0){ echo " ".$gtext['updated']; $log.=$gtext['updated'].";";  }
		else{ echo $gtext['notupdated']."!!->"; $log.=$gtext['notupdated']."{'update error':''};";  }//*/
	}else{ 
		echo $gtext['notupdated']."!"; 
		$log.=$gtext['notupdated']."{'update error':''};"; 
	}
}else{ echo " !".$gtext['u_fieldmustnotblank']."!"; }  //Alanlar boş olamaz
//
logger($logfile,$log);
?>