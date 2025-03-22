<?php
/*
	Ayrıldıktan sonra geri gelen Kullanıcının hesabını açmak->Son birim klasörüne taşımak
*/
include("../set_mng.php");
//error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
if($user==""){
	header('Location: '.$docroot.'/login.php');
} 
require($docroot."/ldap.php");
//
$log="";
include($docroot."/app/php_functions.php");
$logfile='personel';
//
$username=$_POST['username']; 
if($username==''){ @$username=$_GET['username']; } 
if($username!=''){
	echo $gtext['a_reopening']."\n ".$gtext['user']." ".$username.":\n";
	if($ini['usersource']=='LDAP'){
		$ldap_result1 = ldap_search($conn, $ini['dom_dn'], "(samaccountname=$username)");	
		if($ldap_result1){
			$info = ldap_get_entries($conn, $ldap_result1); 	
			if($info["count"]>0){
				echo "* ".$gtext['a_userinfo']." ";
				$o_user_dn=$info[0]['distinguishedname'][0];
				//-------------------------------------------------------------------
				$data=array(); 
				$y=strpos($info[0]['givenname'][0], $ini['disabledname']); //echo $o_user_dn;
				if($y!=''&&$y>=0){
					$s=substr($info[0]['givenname'][0],($y+strlen($ini['disabledname'])));
					$data["givenname"] = $s; 
				}else{ 
					$data["givenname"]=$info[0]['givenname'][0]; 
				}
				$sn=strtouppertr($info[0]['sn'][0]);		
				$sn=str_return($info[0]['sn'][0],'');
				$data['sn']=$sn;
				$data["displayname"]	= $data["givenname"]." ".$sn;
				//mail
				$ym=strpos($info[0]['mail'][0], $ini['disabledmailuser']);
				if($ym!=''&&$ym>=0){
					$sm=substr($info[0]['mail'][0],($ym+strlen($ini['disabledmailuser'])));
					$data["mail"] = $sm; 
				}
				/*/Organization 
				if($info[0]['manager'][0]!=""){
					$data["manager"]=array(); //yönetici ile alakası kesilir...
					$log.="<br>manager:deleted;";
				}//*/
				//telephones //Atribute Editor 
				if($info[0]['pager'][0]!=""){
					$data["telephoneNumber"]=$info[0]['pager'][0]; 
					$log.="<br>telephoneNumber:deleted;";
				}
				if($info[0]['mobile'][0]!=""){
					$data["mobile"]=$info[0]['pager'][0]; //pager alanından geri alınır
					$data["pager"]=array(); //pager boşaltılır.
					$log.=" reopen;";
					if($data["mobile"]!=''){
						$data["msDS-cloudExtensionAttribute10"]=$data["mobile"]; //vpn iptal
					}
					$log.=" user vpn set;";
				}
				//Disable the user
				$data["useraccountcontrol"]="544";//*/
				$log.=" user reopen;"; 
				//-------------------------
				$log.="<br>".$gtext['user']." ";
				$sonuc=ldap_mod_replace($conn, $o_user_dn, $data);
				if($sonuc){
					echo $gtext['changed']; //değişti mesajı
					$log.=$gtext['changed'].",";
					//addto all mail groups from ini - to old departments.
					$data['department']=$info[0]['department'][0];
					$data['company']=$info[0]['company'][0];
					require("./ldap_functions.php");
					AddToGroupsFromIni($o_user_dn);	//gruplara eklenir.
				}else{
					echo $gtext['notchanged']; $log.=$gtext['notchanged'].";";
					$log.=" ldap_mod_replace->".ldap_error($conn)." ".ldap_errno($conn).";";
				}
				//moving to old department--------------------------------------------------
				$newdn="CN=".$data["displayname"];
				$newparent="OU=".$data['department'];
				if($data['department']!=$data['company']){ $newparent.=",OU=".$data['company']; }
				$newparent.=",".$ini['base_dn'];
				$log.="o_user_dn:".$o_user_dn.";newdn:".$newdn.";newparent:".$newparent.";";
				try{
					$sonucs=ldap_rename($conn,$o_user_dn,$newdn,$newparent,true); 
					if($sonucs){
						echo "\n* ".$gtext['moved']."->".$gtext['a_department'].":".$data['department']; //" taşındı."; 
						$log.=$gtext['moved']."; ";
					}else{ 
						echo "\n* ".$gtext['notmoved']."!"."->".$gtext['a_department'].":".$data['department']; 
						$log.=$gtext['notmoved']."! ";
						exit;
					}
				}catch(Exception $e){
					echo "\n ".$gtext['notmoved']."*"; 
					$log.="<br>ldap_rename->".ldap_error($conn)." ".ldap_errno($conn).";";
				}
			}else{ echo $gtext['notfound']." "; $log.=$gtext['notfound']." 1;";}
		}else{ echo $gtext['notfound']." "; $log.=$gtext['notfound']." 1;";}
	}else{
		//DBden gelecek bilgiler
		$ksay=0;		
		@$collection = $db->personel;
		@$cursor = $collection->findOne(
			[
				'samaccountname'=>$username
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
				$data["manager"]="";
				$data["pager"]=$formsatir->telephonenumber;
				$data["mobile"]="";
				$data["telephonenumber"]="";
				$data["useraccountcontrol"]="514";
				//ayrılış tarihi:				
				$data["resign"]=datem(date("Y-m-d", strtotime("now").'T'.date("H:i:s", strtotime("now")).'.000+00:00'));
			} 
		}	
	}
	$log.=" DB:";
	//Saving data to Mongo
	$data["distinguishedname"]=$newdn.",".$newparent;
	//
	@$collection = $db->personel;
	@$acursor = $collection->updateOne(
		[
			'distinguishedname'=>$o_user_dn
		],
		[ '$set' => $data ]
	);
	if($acursor->getModifiedCount()>0){ echo "\n* ".$gtext['updated']; $log.=$gtext['updated'].";"; $prop_act=1; }
	else{ echo "\n* ".$gtext['notupdated']."->"; $log.=$gtext['notupdated']."{'update error':''};"; }
	//personel_act dosyasına yazılır...
	$act_collection = $db->personel_act;
	$data['act_date']=datem(date("Y-m-d", strtotime("now").'T'.date("H:i:s", strtotime("now")).'.000+00:00'));
	$act_cursor = $act_collection->insertOne(
		$data
	);
	if($act_cursor->getModifiedCount()>0){ echo "\n* ".$gtext['updated']; $log.=$gtext['updated'].";"; $prop_act=1; }
	else{ echo "\n* ".$gtext['notupdated']."->"; $log.=$gtext['notupdated']."{'update error':''};";  }
	//*/
}else{ echo "Username Boş Olamaz!"; }

$log.="\n";
logger($logfile,$log);
?>