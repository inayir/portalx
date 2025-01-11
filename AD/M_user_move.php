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
ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION,3);
ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
$logfile='personel';
$username=$_POST['username']; //
//if($username==''){ @$username=$_GET['u']; }
$displayname=$_POST['displayname']; 
$o_userdn=$_POST['distinguishedname']; 
//echo "u:".$username." o_userdn:".$o_userdn;
//exit;
//
$msg=$gtext['user'].": ".$username;
$log="\nMoving;username: ".$username.";dn: ".$o_userdn.";";
if($username!=''){
	if($ini['usersource']=='LDAP'){
		$msg.="\n*LDAP: "; $log.="LDAP;";
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
		$data['manager']=$_POST['manager'];
		//echo "dn:".$o_userdn."/ *newdn:".$newdn."/ *newparent:".$newparent.""; //exit;
		//-------------------------
		$sonuc=ldap_rename($conn, $o_userdn, $newdn, $newparent, true);
		if($sonuc){
			$msg.=" ".$gtext['moved'].", "; 
			$log.="user moved!".";";
			$dn=$newdn.','.$newparent;
			//groups---------------------------------------------------------
			require("./ldap_functions.php");
			//remove user from security groups
			$msg1=removefromgroups($dn); 
			if($msg1!=""){ $msg.=$msg1; }else{ $msg.=" not removed group;"; }
			//add user to new department groups.
			$msg2=addtogroupsfromini($dn);
			if($msg2!=""){ $msg.=$msg2; }else{ $msg.=" not add group;"; }
			//end moving-data update
			unset($data['o_department']);
			unset($data['o_company']);
			$sonuc1=ldap_mod_replace($conn, $dn, $data); //o_userdn!
			if($sonuc1==1){ $log.="data ".$gtext['updated'].";";}else{ $log.="data ".$gtext['notupdated'].";";}
			echo $msg;
		}else{
			//not moved!
			$msg.=" ".$gtext['notmoved']."! ";
			$log.=$gtext['notmoved']."!".ldap_error($conn).";";
			logger($logfile,$log);
			echo $msg;
			exit;
		}
	}
	//Record to mongodb -----------------------------------------------
	$log.="*DB;"; $msg="\nDB:";
	$data['distinguishedname']=$dn;
	//
	@$collection = $db->personel;
	@$cursor = $collection->updateOne(
		[
			'distinguishedname'=>$o_userdn
		],
		[ '$set' => $data ]
	);
	if($cursor->getModifiedCount()>0){ 
		$msg.=$gtext['updated']; 
		$log.=$gtext['updated'].";"; 
		//personel_act dosyasına yazılır...
		$act_collection = $db->personel_act;
		$data['act']='move';
		$data['actdate']=datem(date("Y-m-d", strtotime("now").'T'.date("H:i:s", strtotime("now")).'.000+00:00'));
		$act_cursor = $act_collection->insertOne(
			$data
		);
		if($act_cursor->getInsertedCount()>0){ $msg.=" ".$gtext['updated']; $log.=$gtext['updated'].";";  }
		else{ $msg.=$gtext['notupdated']."!!->"; $log.=$gtext['notupdated']."{'update error':''};";  }
	}else{ 
		$msg.=$gtext['notupdated']."!"; 
		$log.=$gtext['notupdated']."{'update error':''};"; 
	}	
	echo $msg; 
}else{ echo " !".$gtext['u_fieldisnotblank']."!"; }  //Alanlar boş olamaz
//
logger($logfile,$log);
?>