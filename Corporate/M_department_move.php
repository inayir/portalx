<?php
/*
	Kullanıcıyı başka bir OU altına taşır.
*/
include("../set_mng.php");
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
if($_SESSION['user']==""){
	//echo "login"; exit;
} 
require($docroot."/ldap.php");
require($docroot."/app/php_functions.php");
ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION,3);
ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
$logfile='department';
$o_dn=$_POST['o_dn']; 
$company=$_POST['company'];
if($o_dn!=''){ 
	if($ini['usersource']=='LDAP'){
		//newdn oluşturulur...
		$ou=substr($o_dn, strpos($o_dn, '=')+1, strpos($o_dn, ',')-3);
		$newdn='OU='.$ou;
		$newparent='OU='.$company.','.$ini['base_dn'];	
		$log.="newdn:".$newdn.",".$newparent.";"; 
		echo "-dn:".$o_dn."<br>-newdn:".$newdn."<br>-newparent:".$newparent.""; 
		$msg.="<br>\n*LDAP: "; $log.="LDAP;";		 
		//-------------------------
		$sonuc=ldap_rename($conn, $o_dn, $newdn, $newparent, true);
		if($sonuc){
			$msg.=" ".$gtext['moved'].", "; 
			$log.="user moved!".";";
			$dn=$newdn.','.$newparent;
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
	exit;
	//Record to mongodb -----------------------------------------------
	$log.="*DB;"; $msg="\nDB:";
	$data=array();
	$data['distinguishedname']=$dn;
	//
	$msg.="\n*Department:";
	@$collection = $db->department;
	@$cursor = $collection->updateOne(
		[
			'distinguishedname'=>$o_dn
		],
		[ '$set' => $data ]
	);
	if($cursor->getModifiedCount()>0){ 
		$msg.=$gtext['updated']; 
		$log.=$gtext['updated'].";"; 
		//personel dosyasına yazılır...
		$msg.="\n*Personel:";
		$p_collection = $db->personel;
		$pdata['company']=$company;
		$pdata['lastchdate']=datem(date("Y-m-d", strtotime("now").'T'.date("H:i:s", strtotime("now")).'.000+00:00'));
		$p_cursor = $p_collection->updateOne(
			$pdata
		);
		if($p_cursor->getInsertedCount()>0){ $msg.=" ".$gtext['updated']; $log.=$gtext['updated'].";";  }
		else{ $msg.=$gtext['notupdated']."!!->"; $log.=$gtext['notupdated']."{'update error':''};";  }
		//personel_act dosyasına yazılır...
		$msg.="\n*Activity:";
		$act_collection = $db->personel_act;
		$aadata['act']='move';
		$data['date']=datem(date("Y-m-d", strtotime("now").'T'.date("H:i:s", strtotime("now")).'.000+00:00'));
		$act_cursor = $act_collection->insertOne(
			$adata
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