<?php
/*
	Ayrılan Kullanıcının hesabını kapatmak->Ayrılanlar klasörüne taşımak
*/
include("../set_mng.php");
//error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
if($user==""){
	echo 'login';
	exit;
} 
require($docroot."/ldap.php");
include($docroot."/app/php_functions.php");
$logfile='personel';
//if($bind){ echo "Binded";}else{ echo "User: ".$domuser." ".$p."------------No BIND!----------"; }
$username=$_POST['username']; //if($username==''){ @$username=$_GET['username']; } 
$log=$username." User Closing;";
if($username!=''){
	$data=array(); 
	echo $gtext['user']." ".$username." ".$gtext['a_closing']." ";
	if($ini['usersource']=='LDAP'){
		$ldap_result1 = ldap_search($conn, $ini['dom_dn'], "(samaccountname=$username)");	
		if($ldap_result1){
			$info = ldap_get_entries($conn, $ldap_result1); 	
			if($info["count"]>0){
				$o_userdn=$info[0]['distinguishedname'][0];
				$log.="dn:".$o_userdn.";";	
				$data["givenname"] = $ini['disabledname'].$info[0]['givenname'][0];
					$yer=strpos($info[0]['givenname'][0],$ini['disabledname']); //not add again.
					if($yer!=''&&$yer>=0){ $data["givenname"] = $info[0]['givenname'][0]; }
				$data["displayname"]=$data["givenname"]." ".$info[0]['sn'][0];
				$log.="displayname:".$data["displayname"].";";
				if($ini['disabledmailuser']!=''){
					$data["mail"] = $ini['disabledmailuser'].$info[0]['mail'][0]; 
					$log.="mail:".$data["mail"].";";
				}//*/
				//Organization 
				if($info[0]['manager'][0]!=""){
					$data["manager"]=array(); //yönetici ile alakası kesilir...
					$log.="manager:deleted;";
				}//*/
				//telephones //Atribute Editor 
				if($info[0]['telephonenumber'][0]!=""){
					$data["telephonenumber"]=array(); 
					$log.="telephonenumber:deleted;";
				}//*/
				if($info[0]['mobile'][0]!=""){
					$data["pager"]=$info[0]['mobile'][0]; //mobile telefon pager a kaydırılır.
					$data["mobile"]=array(); //rehberde çıkmaması için silinir->
					$data["msDS-cloudExtensionAttribute10"]="<not set>"; //vpn iptal
					$log.="mobile closed; user vpn closed;";
				}
				//Disable the user
				if($info[0]['useraccountcontrol'][0]!="514"){
					$data["useraccountcontrol"]="514";
					$log.="useraccountcontrol 514 disabled;";
				}
				//removing from mail groups-Önce gruplardan çıkarılır...
				$data["o_department"]=$info[0]['department'][0];
				$data["o_company"]=$info[0]['company'][0];
				require("./ldap_functions.php");
				echo removefromgroups($o_userdn);
				unset($data["o_department"]);
				unset($data["o_company"]);
				//changing infos------------------------------
				echo "\n* LDAP ".$gtext['a_userinfo']." ";
				$log.="LDAP ".$gtext['a_userinfo']." ";
				$sonuc=ldap_mod_replace($conn, $o_userdn, $data);
				if($sonuc){
					echo $gtext['changed']." "; 
					$log.=$gtext['changed'].";";
				}else{
					echo " **".$gtext['notchanged']."!!"; $log.=$gtext['notchanged'].";";
					$log.="ldap_mod_replace err:".ldap_error($conn)."->".ldap_errno($conn).";";
					echo $msg; logger($logfile,$log); exit;
				}
				//moving-------------------------------------------------------------
				$newdn="CN=".$ini['disabledname'].$info[0]['cn'][0]; 
					$yer1=strpos($info[0]['cn'][0],$ini['disabledname']); //not add again.
					if($yer1!=''&&$yer1>=0){ $newdn="CN=".$info[0]['cn'][0]; }
				$newparent=$ini['disabledOU'];  //$dn=$newdn.",".$newparent;
				$log.="newdn:".$newdn.";newparent:".$newparent.";";
				$data["distinguishedname"]=$newdn.",".$newparent;
				try{
					$res_rename=ldap_rename($conn,$o_userdn,$newdn,$newparent,true); 
					if($res_rename){
						echo "\n* ".$gtext['moved']." "; //" taşındı."; 
						$log.=$gtext['moved'].";";
					}else{ 
						echo "\n* ".$gtext['notmoved']."!!-> ".$ini['disabledOU']." Please move manually!"; 
						$log.=$gtext['notmoved']."!;";
						//logger($logfile,$log);
					}
				}catch(Exception $e){
					echo ldap_error($conn)." ".ldap_errno($conn);
					echo "* ".$gtext['notmoved']."!!"; 
					$log.="ldap_rename->".ldap_error($conn)." ".ldap_errno($conn).";";
				}
			}else{ echo $gtext['notfound']."!! "; $log.=$gtext['notfound']." 2;";}
		}else{ echo $gtext['notfound']."!! "; $log.=$gtext['notfound']." 1;";}
		echo "\n".$gtext['s_database']."-> ";
	}else{
		//DBden gelecek bilgiler
		$ksay=0;		
		@$collection = $db->personel;
		@$cursor = $collection->findOne(
			[
				'username'=>$username
			],
			[
				'limit' => 1,
				'projection' => [
					'displayname' => 1,
					'givenname' => 1,
					'telephonenumber' => 1
				],
			]
		);
		if(isset($cursor)){	
			$ksay=count($cursor); 
			foreach ($cursor as $formsatir){ 
				$data["displayname"]=$ini['disabledname'].$formsatir->displayname;
				$data["givenname"]=$ini['disabledname'].$formsatir->givenname;
				if($ini['disabledmailuser']!=''){  $data["mail"]=$ini['disabledmailuser'].$formsatir->mail; }
				$data["pager"]=$formsatir->telephonenumber;
				$data["mobile"]="";
				$data["useraccountcontrol"]="514";
			} 
			echo " Found in DB.";
		}
	}
	//Saving data to Mongo
	$log.="DB:"; 
	$data["manager"]="";
	$data["telephonenumber"]="";
	//ayrılış tarihi:				
	$data["resigndate"]=datem(date("Y-m-d H:i:s", strtotime("now")));
	$data["aktif"]=0;	
	//
	@$collection = $db->personel;
	@$acursor = $collection->updateOne(
		[
			'username'=>$username
		],
		[ '$set' => $data ]
	);
	if($acursor->getModifiedCount()>0){ 
		echo $gtext['updated']; 
		$log.=$gtext['updated'].";"; 
		//personel_act dosyasına yazılır...
		echo "\nActivity:";
		$data['act']='remove';
		$data["actdate"]=datem(date("Y-m-d H:i:s", strtotime("now")));
		$act_collection = $db->personel_act;		
		$act_cursor = $act_collection->insertOne(
			$data
		);
		if($act_cursor->getInsertedCount()>0){ echo " ".$gtext['inserted']; $log.=$gtext['inserted'].";";  }
		else{ echo $gtext['notinserted']."!!->"; $log.=$gtext['notinserted']."{'insert error':''};";  }
		//*/
	}else{ 
		echo $gtext['notupdated']."!!->"; 
		$log.=$gtext['notupdated']."{'update error':''}".";"; 
	}
	
}else{ echo "Username Boş Olamaz!"; }

logger($logfile,$log);
?>