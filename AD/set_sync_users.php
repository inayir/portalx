<?php
/*
	set_user_sync: LDAP ile MongoDB senkronizasyonu LDAP->MongoDB
*/
function in_multiarray($elem, $array,$field,$fieldget){ //search in multi array
    $top = sizeof($array) - 1;
    $bottom = 0;
    while($bottom <= $top)
    {
        if($array[$bottom][$field][0] == $elem){ 
			return $array[$bottom][$fieldget][0];
        }else{ 
            if(is_array($array[$bottom][$field][0])){
                if(in_multiarray($elem, ($array[$bottom][$field][0]))){
                    return $array[$bottom][$fieldget][0];
				}
			}
		}
        $bottom++;
    }        
    return false;
}
include("../set_mng.php");	
error_reporting(0);
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
$liste=Array("displayname", "givenname", "sn", "sAMAccountName", "mail", "description", "physicalDeliveryOfficeName", "telephoneNumber", "mobile", "company", "department", "distinguishedname","manager", "title","useraccountcontrol","msDS-cloudExtensionAttribute10","streetaddress","l","st","co");
$upd=0; $ins=0;
$filter = '(&(objectCategory=person)(samaccountname=*))'; 
$result = ldap_search($conn, $dn, $filter, $liste);  
$datact=[];
if($result){
	$entries = ldap_get_entries($conn, $result); 
	$esay=$entries['count']; 
	$msg=" ".$esay." user are matching...<br>";
	for ($ix=1; $ix<$esay; $ix++){ 
		$ksay=0; $updok=0; $propins=0; $yer=false; $username==''; $description="";  $msg.="\n<br>";
		$syncok=0;
		$username=$entries[$ix]['samaccountname'][0]; 
		if ($username==""){ $username=$entries[$ix]['username'][0]; }
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
				//if(strpos($description,'#')!=""&&strpos($description,'#')>=0){ $syncok=0; $log.="#0";}
				if(strpos($description,',')!=""&&strpos($description,',')>=0){ $syncok=0; $log.=",0";}
				if(strpos($description,' ')!=""&&strpos($description,' ')>=0){ $syncok=0; $log.="s0";}
				if($syncok==0){ $msg.="->uygun değil!"; $log.="description uygun değil!;"; }
			}else{ $msg.=$entries[$ix]['displayname'][0]."->description empty!";$log.="->description empty!;"; $syncok=0; }
			
		}else{ $msg.=$entries[$ix]['displayname'][0]."->username empty!"; $log.="->username NOT found!;";}
		//
		if($syncok==1){ 
			$data=[];
			$data['username'] = $username;
			$data['displayname']= $displayname; 
			$data['description']= $description;
			$m="-";
			$log.="manager:";
			if(isset($entries[$ix]['manager'][0])){
				$m=$entries[$ix]['manager'][0]; //manager's dn 
                //find manager's displayname. 
				$sman=in_multiarray($m, $entries, 'distinguishedname', 'displayname')." ";
                if($sman!=false){
                    $m=$sman;
                }else{
                    if(strpos($m,'CN=')>-1){ $m=substr($m, 3, strpos($m,',')-3); }
                }//*/
			}else{ $log.="-;"; } 
			$data['manager'] = $m; $log.=$m.";";
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
			//
			if(isset($entries[$ix]['streetaddress'][0])){
				$data['streetaddress'] = $entries[$ix]['streetaddress'][0];
				$log.="streetaddress:".$entries[$ix]['streetaddress'][0].";";
			}else{ $log.="streetaddress:-;";}
			if(isset($entries[$ix]['l'][0])){
				$data['l'] = $entries[$ix]['l'][0];
				$log.="district:".$entries[$ix]['l'][0].";";
			}else{ $log.="district:-;";}
			if(isset($entries[$ix]['st'][0])){
				$data['st'] = $entries[$ix]['st'][0];
				$log.="city:".$entries[$ix]['st'][0].";";
			}else{ $log.="city:-;";}
			if(isset($entries[$ix]['co'][0])){
				$data['co'] = $entries[$ix]['co'][0];
				$log.="country:".$entries[$ix]['co'][0].";";
			}else{ $log.="country:-;";}
			//
			if(isset($entries[$ix]['useraccountcontrol'][0])){
				$data['useraccountcontrol'] = $entries[$ix]['useraccountcontrol'][0];
				$log.="enable:".$entries[$ix]['useraccountcontrol'][0].";";
			}else{ $log.="office:-;";}
			$data['tarih'] = datem(date("Y-m-d H:i:s", strtotime("now"))); 
			//user search in db
			try{
				@$cursor = $collection->findOne(
					[
						'username'=>$username
					],
					[
						'limit' => 1,
						'projection' => [
							'state'=>1,
						],
					]
				);
				if(isset($cursor)){	
					$ksay=count($cursor); 						
					$msg.=" state:".$cursor->state;
					if($cursor->state<>'C'&&$cursor->state<>'A'){ $data['state']='A'; $msg.="-".$data['state'];}
					$msg.=",";
				}
			}catch(Exception $e){
				echo ldap_error($conn);
			}//*/
			if($ksay>0){ //there is a record, updates.				
				$log.="ksay:".$ksay.";";  
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
					$msg.=$gtext['updated']."->"; $upd++; $log.="->".$gtext['updated'].";"; 
					$datact['act']='sync-update';
				}else{ 
					$msg.=$gtext['notupdated']."->"; $log.=";->".$gtext['notupdated']." {'update error':''}";
				}
			}else{  //Yeni kayıt
				$data['pass']=$newpass;
				$data['state']='A';
				@$acursor = $collection->insertOne(
					$data
				);
				if($acursor->getInsertedCount()>0){ 
					$msg.=$gtext['inserted']."->"; 
					$log.=$gtext['inserted']."->".";"; 
					$ins++;  //personel eklendi
					$propins=1;
					$datact['act']='sync-insert';
				}else{ 
					$msg.=$gtext['notinserted']."->"; 
					$log.="->{'user insert error':''};"; 
				}
			}
			//adding personel_prop table
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
			//hep boş geldiği için hareeketler yazılamadı. ???
			if($data['act']!=''){ //personel activity
				$act_collection = $db->personel_act;
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
				$datact['changedata']=$dt;  
				$datact["actdate"]=datem(date("Y-m-d H:i:s", strtotime("now")));
				$act_cursor = $act_collection->insertOne(
					$datact
				);
			}//*/
			if($prop_act==1){ //props activity
				$act_cursor = $act_collection->insertOne(
					$pdata
				);
			}
		}else{
			$msg.=" Sync NOK!";
		}
	}
	$msg.=$username; 
	echo $msg;
	echo "\n<br> RESULT: ".$gtext['records'].": ".$upd." ".$gtext['updated'].", ".$ins." ".$gtext['inserted']."<br>";
}else{ echo "Not Connected!"; }
//
logger($logfile,$log);
?>