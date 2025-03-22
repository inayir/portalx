<?php
/*
	User save. LDAP and MongoDB
*/
function change_pass($distinguishedname,$newPassword, $usr, $usp){
	//Password change ************
	global $conn, $ini, $gtext;
	$msg="";
	try{
		$ADSI = new COM("LDAP:"); //echo "ADSI ok->";
	}catch(Exception $e){
		$rawErr = $e->getCode();
		$processedErr = $rawErr + 0x100000000;
		printf( 'Error code 0x%x', $processedErr );
	}
	try{
		$user = $ADSI->OpenDSObject("LDAP://".$ini['ldap_server']."/".$distinguishedname, $usr, $usp, 1);
	}catch(e){
		$rawErr = $e->getCode();
		$msg.=$gtext['u_error']."!"; //printf( 'Error code 0x%x', $rawErr );
	}
	if($user){ 
		//$user->Put("pwdLastSet",0);
		$user->SetPassword($newPassword); 
		try{
			$msg.=$user->SetInfo();
			echo $gtext['u_passchanged'];
			//$ldapbind1 = ldap_bind($conn, $distinguishedname, $oldpassword, $newPassword); //echo " Err:".ldap_error($conn);
			//if($ldapbind1){ $msg.="OK:".$gtext['u_passchanged']; }else{ $msg.="nOK!".$gtext['u_passnotchanged']; }//*/
			$msg.=$gtext['u_passchanged'];
		}catch(e){
			$rawErr = $e->getCode();
			$msg.=$gtext['u_error']."! - pwd 0x".$rawErr; //printf( 'Error code 0x%x', $rawErr );
		}
	}else{ $msg.=$gtext['connection'].":".$gtext['u_error']."!";}
	return $msg;
}
///---------------------------
include('../set_mng.php');
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
if($_SESSION["user"]==""){
	echo "login"; exit;
}
$log="Insert/Edit;";
include($docroot."/app/php_functions.php");
$logfile='personel';

$base_dn=$ini['base_dn'];
include($docroot.'/ldap.php');
global $ini, $bind, $gtext;
$sonuc=false; 
$username=$_POST['username']; 
$yol=$ini['fileserver_url']."//ex_mkdir.php?".$username;//if($username==""){ @$username=$_GET['username']; }
if($username==""){ echo "-1"; exit; }
//
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
//Bilgiler hazırlanıyor**********************************
$data=array(); //pushing to data from DB
$ksay=0;
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
	
}
//General
$o_user_dn=$_POST['distinguishedname'];
$baseou=substr($base_dn,3, strpos($base_dn,',')-3); //ASELSANKONYA
$data["givenname"]=$_POST['givenname']; 
	$sn=strtouppertr($_POST['sn'],1);
$data["sn"]=$sn; 
$displayname=$data['givenname']." ".$data["sn"];  
if($displayname!=""&&$displayname!=" "){ $data["displayname"]= $displayname; }
//Türkçe karakterler sorun çıkarmaması için değiştirilir.
$displayname=str_return($displayname,1);
//
$department=$_POST['department'];
if($department==''){ $department=$_POST['o_department']; }
$company=$_POST['company'];
if($company==''){ $company=$_POST['o_company']; }
//
if($_POST['o_username']==''){ //Insert
	$data["objectclass"][0]= "top";
	$data["objectclass"][1]= "person";
	$data["objectclass"][2]= "organizationalPerson";
	$data["objectclass"][3]= "user";
	$data["samaccountname"]=$username; //Account
	$data["userprincipalname"]= $username."@".$ini['domain']; //user logon name 
}
if($o_user_dn==""){ //Insert
	if($company!=$_POST['o_company']||$department!=$_POST['o_department']){ 
		if($department==$company){ 
			$data["department"]	= $_POST['company'];
			$data["company"]	= $baseou; 
		} else {  
			$data["department"]	= $department;
			$data["company"]	= $_POST['company']; 
		}	
	}
}
//
$distinguishedname="CN=".$displayname.",OU=";
if($department!=$company||$company!=$ini['domshort']){ 
	$distinguishedname.=$data['department'].",OU=";  
}
$distinguishedname.=$data['company'].",".$base_dn;
//Profile 
if($o_user_dn==""&&$ini['homedir']!=""){ //configden gelir.
	$data["homedrive"]= $ini['homedrive'];  //configden gelir
	$data["homedirectory"]= $ini['homedir'].$username; 
} 
if($_POST['description']!=$_POST['o_description']){ //sicilno insert
	$data["description"]=$_POST['description'];
} 
if($_POST['physicaldeliveryofficename']!=$_POST['o_physicaldeliveryofficename']){ //yeri
	$data["physicaldeliveryofficename"]= $_POST['physicaldeliveryofficename'];
}
if($_POST['mail']!=$_POST['o_mail']){
	$data["mail"]= $_POST['mail']; //user logon name 
}
//Organization 
if($_POST['title']!=$_POST['o_title']){ 
	$data["title"]= $_POST['title']; 
}
//manager bilgileri bulunmalı
if($_POST['manager']!=$_POST['o_manager']){
	$m=$_POST['manager'];
	$data['manager']=$m;
} 
//telephones //Atribute Editor
if($_POST['telephonenumber']!=$_POST['o_telephonenumber']){
	$data["telephonenumber"]= $_POST['telephonenumber'];
} 
if($_POST['mobile']!=$_POST['o_mobile']){
	$data["mobile"]= $_POST['mobile'];
	$vpntel=str_replace(" ", "", $_POST['mobile']);
	$vpntel=substr($vpntel,1);
	$data["msDS-cloudExtensionAttribute10"]= $vpntel; 
}//*/
//Enable the user
$data["useraccountcontrol"]="544";
if(strpos($displayname,$ini['disabledname'])>-1){ $data["useraccountcontrol"]= "514"; }
if($_POST['password']!=''){ //şifre değiştiriliyorsa...
	$newPassword=$_POST['password']; //şifre içeriği denetlenir...
}

if($_POST['streetaddress']!=''){
	$data['streetaddress']=$_POST['streetaddress'];
}
if($_POST['district']!=''){ //l : District
	$data['l']=$_POST['district'];
}
if($_POST['st']!=''){ //st : State
	$data['st']=$_POST['st'];
}
if($_POST['co']!=''){  //co Country -> seçimli hale gelince değiştirilsin.
	$data['co']=$_POST['co'];
}
if($_POST['resigndate']!=''){
	echo "expdate:".date("Y-m-d H:i:s", strtotime($_POST['resigndate']));
	$data['accountExpires']=date("d/m/Y H:i:s", strtotime($_POST['resigndate']));
}
$ins=false; 
echo $gtext['user'].": ".$username;
if(strpos($_POST['givenname'], $ini['disabledname'])>-1){ $dis=1; }
//işlem yapılıyor*********************************************************/
if($ini['usersource']=='LDAP'&&$data!=''){  //LDAP'a **********************************************************  
	echo "<br>LDAP->"; 
	$ldap_result=ldap_search($conn, $ini['dom_dn'], "(samaccountname=$username)");
	if(!$ldap_result){ $o_username=$_POST['o_username']; $ldap_result=ldap_search($conn, $ini['dom_dn'], "(samaccountname=$o_username)");}
	if($ldap_result){ 
		$info = ldap_get_entries($conn, $ldap_result); 
		if($info["count"]>0){ //MODIFY -- sadece bilgiler güncellenir, user taşınmaz
			$sonuc=ldap_mod_replace($conn, $o_user_dn, $data); //o_user_dn!
			if($sonuc){ //" Güncellendi.";
				echo $gtext['updated']." "; 
				$log.=$gtext['updated'].";".implode('; ',$data).";";
				//moving for distinguishedname -if givenname or sn is changed-
				if($_POST['givenname']!=$_POST['o_givenname']||$_POST['sn']!=$_POST['o_sn']){					
					$newdn='CN='.$displayname;
					$newparent='OU='.$department; 
					if($company!=$department){ $newparent.=',OU='.$company; $department=$company; }
					$newparent.=','.$ini['base_dn'];
					//echo " newparent:".$newparent;
					$sonuc=ldap_rename($conn, $o_userdn, $newdn, $newparent, true);
					if($sonuc){
						$log.="dn changed!;";
					}
				}
				//
				if($newPassword!=''){ //şifre değiştiriliyorsa...
					$s=change_pass($distinguishedname,$newPassword, $_SESSION['user'],$_SESSION['pass']);	
					if(strpos($s,'!')>=0){	$pwdset=1; }
					echo " Pass:".$s;
				}				
			}else{ 
				echo $gtext['notupdated']; 
				//echo " --->".$o_user_dn." (".ldap_error($conn).") ";
				$log.=$gtext['notupdated'].";dn:".$o_user_dn.";data:".implode(';',$data).";";
			}
		}else{ //no user -> insert------------------------------------------------------------
			$data["distinguishedname"]=$distinguishedname;
			$sonuc=ldap_add($conn, $distinguishedname, $data); 
			if(!$sonuc){  //"EkleneMEdi!"; 
				echo $gtext['notinserted']."! ".$sonuc; 
				//echo " --->".ldap_error($conn)." ";
				$log.=$gtext['notinserted']."; ".implode('; ',$data).";";
				//logger($logfile,$log);
				//exit;
			}else{ //$log.="user inserted;".$data.";\n";
				//açılıp açılmadığı da kontrol edilir.
				$ldap_result1 = ldap_search($conn, $ini['dom_dn'], "(samaccountname=$username)");
				if($ldap_result1){ //"Eklendi.";
					echo $gtext['inserted']; 
					$log.=$gtext['inserted']."; ".implode('; ',$data).";";
					//logger($logfile,$log);
				}else{ 
					echo $gtext['notinserted']."!"; 
					$log.=$gtext['notinserted']."; ".implode('; ',$data).";";
					logger($logfile,$log); 
					//exit; 
				}
				if($_POST['password']!=''){
					$s=change_pass($distinguishedname,$newPassword, $_SESSION['user'],$_SESSION['pass']);
					if(strpos($s,'!')>=0){	$pwdset=1; }
					echo $s;
					$log.=$s.";";
					//logger($logfile,$log); 
				}
				//Adding to groups-gruplara ekleme------------------------------
				require("./ldap_functions.php");
				addtogroupsfromini($distinguishedname);
				//klasör açılır, yetki verilir.//file serverdaki api çağrılır.
				echo "<br>".$gtext['directory'].": ".$ini['homedir'].$username; 
				$getdata = http_build_query(
					array('u' => $username)
				);
				//
				$opts = array('http' =>
				 array(
					'method'  => 'POST',
					'content' => $getdata
					)
				);
				//
				$context  = stream_context_create($opts);
				$apis=file_get_contents($yol, false, $context);
				//loga yazılmalı.
			}			
		}
	}else{ echo $gtext['notfound']; }
}
//DB e yazılır...
echo "\n<br>".$gtext['s_database']."-> ";
//Databasede kullanıcı var mı, bakılır...
unset($data['objectclass']);
unset($data['samaccountname']);
unset($data['userprincipalname']);
if($ini['usersource']!=1||$pwdset==1){
	$data["userPassword"]= $newPassword;
}
if($_POST['ptype']!=''){
	$data['ptype']=$_POST['ptype'];
}
if($_POST['note']!=''){
	$data['note']=$_POST['note'];
}
if($_POST['district']!=''){ //l : district
	unset($data['l']);
	$data['district']=$_POST['district'];
}
if($_POST['sdate']!=''){
	$data['sdate']=$_POST['sdate'];
}
if($_POST['resigndate']){
	$data['resigndate']=$_POST['resigndate'];
}
if($data["useraccountcontrol"]=='544'){  $data['aktif']=1; }else{ $data['aktif']=0;}
//
if($ksay>0){ //güncellenir
	$data["distinguishedname"]=$distinguishedname;
	@$acursor = $collection->updateOne(
		[
			'distinguishedname'=>$o_user_dn
		],
		[ '$set' => $data ]
	);
	if($acursor->getModifiedCount()>0){ echo $gtext['updated']; $prop_act=1; $act='update'; }
	else{ echo $gtext['notupdated']."->".$o_user_dn; $log.="{'update error':''};"; }
}else{
	$data["username"]= $username;
	@$acursor = $collection->insertOne(
		$data
	);
	if($acursor->getInsertedCount()>0){ //eklendi
		if($ini['usersource']!=1){ echo $gtext['inserted']; }
		$ins=true; $act='insert'; 
		//personel_prop dosyasına da kayıt eklenir.	
		@$pxcursor = $pcollection->findOne(
			[
				'username'=>$username
			],
			[
				'limit' => 1,
				'projection' => [
					'description' => 1
				],
			]
		);
		if(isset($pxcursor)){ $pxsay=count($pxcursor); }
		if($pxsay<1){			
			$pdata=[];
			$pdata['username']=$data["username"];
			$pdata['y_ayar01']=0;
			$pdata['y_addinfoduyuru']=0;
			$pdata['y_addinfohaber']=0;
			$pdata['y_addinfoser']=0;
			$pdata['y_addinfomenu']=0;
			$pdata['y_bq']=0;
			$pdata['y_bo']=0;
			$pdata['y_link01']=0;
			$pdata['y_admin']=0;
			@$pcursor = $pcollection->insertOne(
				$pdata
			);
			echo "<br>".$gtext['permission']."->";
			if($pcursor->getInsertedCount()>0){ echo $gtext['inserted']; $prop_act=1; }else{ echo $gtext['notinserted']."! ->"; $log.="{'insert error yetki':''}"; } 
		}
	}
}
if($act!=""){ //personel activity
	$act_collection = $db->personel_act;
	$data['act']=$act;
	$data["actdate"]=datem(date("Y-m-d H:i:s", strtotime("now")));
	$act_cursor = $act_collection->insertOne(
		$data
	);//*/
}
if($prop_act==1){ //props activity
	$pact_collection = $db->personel_prop_act;
	$pact_cursor = $pact_collection->insertOne(
		$pdata
	);
}
function msgyaz($y, $data){
	$s=substr_count($y, '{');
	for($i=0;$i<$s;$i++){
		$b=strpos($y,"{"); 
		if($b!=false||$b!=-1){
			$b2=strpos($y,"}");
			$par=substr($y, $b+1, ($b2-$b-1)); 
			$y=str_replace("{".$par."}", $data[$par], $y);
		}
	}
	return $y;
}
echo "<br><br>";
if($ins==true){
	echo $gtext['s_message']." 1:<br>";
	echo msgyaz($ini['uaddmsg1'], $data);	//user add message
	echo "<br><br>";	
	echo $gtext['s_message']." 2:<br>";
	echo msgyaz($ini['uaddmsg2'], $data);  	//mail add message
}
logger($logfile, $log);
?>