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
$logfile='sync_personel';
$newpass=$_POST['newpass'];  if($newpass==""){ $newpass=$ini['stdpass']; }
//
$dn=$ini['dom_dn'];
$collections = $db->listCollections();
$collectionNames = [];
foreach ($collections as $collection) {
  $collectionNames[] = $collection->getName();
}
$exists = in_array('personel', $collectionNames);
if(!$exists){
		$db->createCollection('personel',[
	]);
}
@$collection = $db->personel;
//
$exists = in_array('personel_prop', $collectionNames);
if(!$exists){
		$db->createCollection('personel_prop',[
	]);
}
@$pcollection = $db->personel_prop; 
//
$exists = in_array('personel_act', $collectionNames);
if(!$exists){
		$db->createCollection('personel_act',[
	]);
}
//
$options = []; $insert=[]; $update=[];
$liste=Array("displayname", "givenname", "sn", "sAMAccountName", "mail", "description", "physicalDeliveryOfficeName", "telephoneNumber", "mobile", "company", "department", "distinguishedname","manager", "title",'useraccountcontrol','msDS-cloudExtensionAttribute10');
$upd=0; $ins=0;
$filter = '(&(objectCategory=person)(samaccountname=*))'; 
$result = ldap_search($conn, $dn, $filter, $liste);  
if($result){
	$entries = ldap_get_entries($conn, $result); 
	$esay=$entries['count']; 
	echo "SYNC Users:".$esay." user.<br>";
	for ($ix=1; $ix<$esay; $ix++){ 
		$ksay=0; $updok=0; $propins=0; $yer=false; $username==''; $description="";  $msg="\n<br>";
		$syncok=0;
		$username=$entries[$ix]['samaccountname'][0]; 
		if ($username==""){ $username=$entries[$ix]['username'][0]; }
		$msg.=$username;
		if ($username!=""){ 
			$displayname=$entries[$ix]['displayname'][0]; 	
			$msg.=" ".$displayname;
			$log.="\n".$now.";".$username.";".$displayname;
			//Description (sicilno) dolu ise ve içinde # işareti yoksa güncellenir.			
			if(isset($entries[$ix]['description'][0])){ $description=$entries[$ix]['description'][0]; }
			$msg.="(".$description.")";
			if($description!=''){
				$syncok=1;  
				$description=ltrim($description, '0'); //soldaki sıfırlar atılır.
				$log.=$description.";";
				//description alanında # veya , varsa aktarılmaz.
				if(strpos($description,'#')!=""&&strpos($description,'#')>=0){ $syncok=0; $log.="#0";}
				if(strpos($description,',')!=""&&strpos($description,',')>=0){ $syncok=0; $log.=",0";}
				if(strpos($description,' ')!=""&&strpos($description,' ')>=0){ $syncok=0; $log.="s0";}
				if($syncok==0){ $msg.="->uygun değil!"; $log.="description uygun değil!;"; }
			}else{ $msg.="->description Boş!";$log.="->description boş!;"; $syncok=0; }
			
		}else{ $msg.="->username Boş!"; $log.="->username bulunamadı!;";}
		if($syncok==1){ 
			$data=[];
			$data['username'] = $username;
			$data['displayname']= $displayname; 
			$data['description']= $description;
			$m="Yok";
			$log.="manager:";
			if(isset($entries[$ix]['manager'][0])){
				$m=$entries[$ix]['manager'][0];
				if(strpos($m,'CN=')>-1){ $m=substr($m, 3, strpos($m,',')-3); }
				$log.=$m.";";
			}else{ $log.="-;"; }
			$data['manager'] 	= $m; 			
			if(isset($entries[$ix]['givenname'][0])){
				$data['givenname'] 	= $entries[$ix]['givenname'][0];
				$log.="givenname:".$entries[$ix]['givenname'][0].";";
			}else{ $log.=";givenname:-;"; }	
			if(isset($entries[$ix]['sn'][0])){
				$data['sn'] 	= $entries[$ix]['sn'][0];
				$log.="sn:".$entries[$ix]['sn'][0].";";
			}else{ $log.="sn:-;"; }
			if(isset($entries[$ix]['mail'][0])){
				$data['mail'] 	= $entries[$ix]['mail'][0];
				$log.="mail:".$entries[$ix]['mail'][0].";";
			}else{ $log.=";mail:-;"; } 
			if(isset($entries[$ix]['telephonenumber'][0])){
				$data['telephonenumber'] = $entries[$ix]['telephonenumber'][0];
				$log.="tel:".$entries[$ix]['telephonenumber'][0].";";
			}else{ $log.=";tel:-;"; }
			if(isset($entries[$ix]['mobile'][0])){
				$data['mobile'] = $entries[$ix]['mobile'][0];
				$log.="mobile:".$entries[$ix]['mobile'][0].";";
			}else{ $log.="mobile:-;"; }
			if(isset($entries[$ix]['company'][0])){
				$data['company'] = $entries[$ix]['company'][0];
				$log.="company:".$entries[$ix]['company'][0].";";
			}else{ $log.="company:-;"; }
			if(isset($entries[$ix]['department'][0])){
				$data['department'] = $entries[$ix]['department'][0]; 
				$log.="department:".$entries[$ix]['department'][0].";";
			}else{ $log.="department:-;";}				
			if(isset($entries[$ix]['title'][0])){
				$data['title'] 	= $entries[$ix]['title'][0]; 
				$log.="title:".$entries[$ix]['title'][0].";";
			}else{ $log.="title:-;";}
			if(isset($entries[$ix]['distinguishedname'][0])){
				$data['distinguishedname'] 	= $entries[$ix]['distinguishedname'][0];
				$log.="office:".$entries[$ix]['distinguishedname'][0].";";
			}else{ $log.="dn:-;";}
			if(isset($entries[$ix]['physicaldeliveryofficename'][0])){
				$data['physicaldeliveryofficename'] = $entries[$ix]['physicaldeliveryofficename'][0];
				$log.="office:".$entries[$ix]['physicaldeliveryofficename'][0].";";
			}else{ $log.="office:-;";}
			if(isset($entries[$ix]['useraccountcontrol'][0])){
				$data['useraccountcontrol'] = $entries[$ix]['useraccountcontrol'][0];
				$log.="enable:".$entries[$ix]['useraccountcontrol'][0].";";
			}else{ $log.="office:-;";}
			$data['tarih'] = datem(date("Y-m-d H:i:s", strtotime("now")));
			//*/
			try{
				@$cursor = $collection->findOne(
					[
						'username'=>$username
					],
					[
						'limit' => 1,
						'projection' => [
						],
					]
				);
				if(isset($cursor)){	$ksay=count($cursor); }
			}catch(Exception $e){
				echo ldap_error($conn);
			}
			if($ksay>0){ //kayıt varsa güncellenir.	
				$log.="ksay:".$ksay.";";  //print_r($data);
				try{
					@$acursor = $collection->updateOne(
						[
							'username' => $username
						],
						[ '$set' => $data ]
					);
				}catch(Exception $e){
					echo ldap_error($conn);
				}
				if($acursor->getModifiedCount()>0){ 
					$msg.="->".$gtext['updated']; $upd++; $log.="->".$gtext['updated'].";"; 
					$act=1;
				}else{ $msg.="->".$gtext['notupdated']; $log.=";->".$gtext['notupdated']." {'update error':''}"; }
			}else{  //Yeni kayıt
				$data['pass']=$newpass;
				@$acursor = $collection->insertOne(
					$data
				);
				if($acursor->getInsertedCount()>0){ 
					$msg.="->".$gtext['inserted']; 
					$log.="->".$gtext['inserted'].";"; 
					$ins++;  //personel eklendi
					$propins=1;
					$act=1;
				}else{ 
					$msg.="->".$gtext['notinserted']."->"; 
					$log.="->{'user insert error':''};"; 
				}
			}
			echo $msg;
			//personel_prop dosyasında yoksa eklenir...
			if($propins==1){
				$msg.=" || ".$gtext['permission']." ";
				@$pcursor = $pcollection->findOne(
					[
						'username'=>$username
					],
					[
						'limit' => 1,
						'projection' => [
							'username' => 1
						],
					]
				);
				$psay=0;
				if(isset($pcursor)){ $psay=count($pcursor); }
				$yetki=0;
				if($ini['auth_username']==$username){ $yetki=1; }
				$pdata=[];
				$pdata['username']=$username;
				$pdata['y_ayar01']=$yetki;
				$pdata['y_addinfoduyuru']=$yetki;
				$pdata['y_addinfohaber']=$yetki;
				$pdata['y_addinfoser']=$yetki;
				$pdata['y_addinfomenu']=$yetki;
				$pdata['y_bq']=$yetki;
				$pdata['y_bo']=$yetki;
				$pdata['y_link01']=$yetki;
				$pdata['y_admin']=$yetki;
				if($psay>0){ 
					@$pcursor = $pcollection->updateOne(
						[
							'username'=>$username
						],
						[ '$set' => $pdata ]
					);
					if($pcursor->getModifiedCount()>0){ 
						$msg.=$gtext['updated']; 
						$log.="->".$gtext['permission']." ".$gtext['updated'].";"; $ins++; 
						$prop_act=1;
					}else{ 
						$msg.=$gtext['notupdated']."->"; 
						$log.="->".$gtext['permission']." ".$gtext['notupdated']." {'insert error yetki':''};"; 
					} 
				}else{ 
					@$pcursor = $pcollection->insertOne(
						$pdata
					);
					if($pcursor->getInsertedCount()>0){ 
						$msg.="*".$gtext['inserted']; 
						$log.="->".$gtext['permission']." ".$gtext['inserted'].";"; $ins++; 
						$prop_act=1;
					}else{ 
						$msg.="*".$gtext['notinserted']."->"; 
						$log.="->".$gtext['permission']." ".$gtext['notinserted']." {'insert error yetki':''};"; 
					} 
				} 
			}
		}
		echo $msg;
		if($act==1){ //personel activity
			$act_collection = $db->personel_act;
			$act_cursor = $act_collection->insertOne(
				$data
			);
		}
		if($prop_act==1){ //props activity
			$act_cursor = $act_collection->insertOne(
				$pdata
			);
		}
	}
	echo "\n<br> RESULT: ".$gtext['records'].": ".$upd." ".$gtext['updated'].", ".$ins." ".$gtext['inserted']."<br>";
}else{ echo "Not Connected!"; }
//
logger($logfile,$log);
?>