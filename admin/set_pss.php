<?php
/*
	Pano Mesajlarını kaydeder/siler.
*/
function querycontrol($p){
	global $gtext;
	$cont=array(" ","OR ","AND ","(SELECT");
	for($i=0;$i<count($cont);$i++){  
		if(strpos($p, $cont[$i])){ 
			echo $gtext['u_falsepass']." ".$gtext['u_passnotchanged']."-";
			//$log.="GPass: nok;"; 
			return false;
		}
	}
	return true;
}
function getkeys($gelenarr){
	return array_keys(json_decode(json_encode($gelenarr),true));
}
include("../set_mng.php");
error_reporting(0);
include($docroot."/sess.php");
$b=$_POST['b']; 
if($b!='pr'&&$_SESSION["user"]==""){ echo "login"; exit; }
//
if($ini['usersource']=='LDAP'){ include($docroot."/ldap.php"); }
header('Content-Type:text/html; charset=utf8');
//
$log="";
include($docroot."/app/php_functions.php");
$logfile='personel';
@$trace=0; 
//filtrelenir...
@$username=$_POST['u']; 
if($username==''){ @$username=$_POST['username']; } 
if($username==''){ @$username=$_POST['usernamen']; } 
if($username==''){ @$username=$_POST['ldap-username']; } 
echo $gtext['user']." "; 
@$gpass=$_POST['gpass'];
@$newPassword=$_POST['pass'];  
@$renewPassword=$_POST['repass']; 
//
$data=[]; @$ksay=0; 
//
if(querycontrol($newPassword)==false||querycontrol($renewPassword)==false){ echo $gtext['u_passnotacceptable']; logger($logfile,$log); exit; }
$log.="Pass;";
if($newPassword==""){  
	echo $gtext['u_passnotchanged']."(null)"; 
	$log.="null->nok;";
	logger($logfile,$log);
	exit;
}//*/
//old pass auth from DB...
@$collection = $db->personel;
@$cursor = $collection->findOne(
	[
		'username' => $username
	],
	[
		'projection' => [
		],   
	]
);
if(isset($cursor)){	 
	@$distinguishedname=$cursor->distinguishedname;
}else{ 
	echo $gtext['notfound']." (".$username.")"; 
	exit;
}
//old pass auth from DB...
if($gpass!=''){
	if(querycontrol($gpass)==false){ echo $gtext['u_passnotacceptable']; logger($logfile,$log); exit; }
	if($cursor->pass!=@$gpass){ //"Geçerli şifreniz yanlış! Şifre DeğiştirileMEdi!";
		echo $gtext['u_passnotchanged']."!";
		$log.="gpass: nok;";
		logger($logfile,$log);
		exit; 
	}
}
$log.="username:".$username.";";
//LDAP
if($ini['usersource']=='LDAP'){ 
	echo "LDAP:";
	$ADSI = new COM("LDAP:");
	$adsidn="LDAP://".$ini['ldap_server']."/".$distinguishedname; 
	if($trace==1){ echo "<br>".$adsidn."<br>"; }
	//
	$user = $ADSI->OpenDSObject($adsidn, $_SESSION['user'], $_SESSION['pass'], 1); 
	//if($trace==1){ echo "<br> user OK<br>"; }
	if($user){
		$user->SetPassword($newPassword);
		try{
			$user->SetInfo();
			echo $gtext['u_passchanged'];
			$log.="u_passchanged->ok;";
			//pwdLastSet -> 0, unlock .
			$ldap_result = ldap_search($conn, $ini['base_dn'], "(samaccountname=$username)");
			$info = ldap_get_entries($conn, $ldap_result); //$keys=Array('useraccountcontrol');
			if($info["count"]>0){	
			   if($_POST['rechpass']==="true"){ $userinfo['pwdLastSet']=0; }
			   $userinfo['useraccountcontrol']=544;
			   $dn=$info[0]['distinguishedname'][0];
			   $sx=ldap_modify($conn, $dn, $userinfo);
			   if($sx){ echo ".";}else{ echo "-";}
			}
		}catch(Exception $e){
			//$result["ERROR"]="1";
			echo $e->getMessage(); 
			echo $gtext['u_passnotchanged']."!";//"Şifre değiştirileMEdi.";
			$log.="passnotchanged->Setinfo nok;";
			logger($logfile,$log);
			exit;
		}
	}else{ //user bulunamadı.
		echo $gtext['u_passnotchanged']."!!!";//"Şifre değiştirileMEdi.";
		$log.="passnotchanged->ADSI nok;";
		logger($logfile,$log);
		exit;
	}	
	echo " - DB:";
}
//DB
$log.="DB:";
$data['pass']=tirnakayarla($newPassword); 
$data['lastpassdate']=datem(date("Y-m-d", strtotime("now")).'T'.date("H:i:s", strtotime("now")).'.000+00:00');
//*/
$data['aktif']=1;
@$cursor = $collection->UpdateOne(
	[
		'username' => $username
	],
	[ '$set' => $data ]
);
if($cursor->getModifiedCount()>0){ 
	echo $gtext['u_passchanged'];/*"Şifre değiştirildi.";*/ 
	$log.="u_passchanged->ok;"; 
	$act_collection = $db->personel_act;
	$datact=[];
	$datact['act']='set_pss';
	//değişen veriler alınır. $data 
	unset($data['lastpassdate']);
	$allkey=[];
	$allkey=getkeys($data); $dt="";
	for($k=0;$k<count($allkey);$k++){
		if($allkey[$k]!='_id'&&$allkey[$k]!='act'&&$allkey[$k]!='displayname'&&$allkey[$k]!='distinguishedname'&&$allkey[$k]!='actdate'){
			$field	=$allkey[$k];  
			$val	=$data->$field;
			$dt.=$field.":".$val.";";
		}
	}
	$datact['changedata']=$dt;  
	$datact['act_date']=datem(date("Y-m-d", strtotime("now").'T'.date("H:i:s", strtotime("now")).'.000+00:00'));
	$act_cursor = $act_collection->insertOne(
		$datact
	);//*/
	//if pass reset
	if($b=='pr'){
		$lstime=$_POST['lstime'];
		$datals=[];
		$datals['used']='Y';
		$colpr=$db->mail_links;
		$cursorpr=$colpr->UpdateOne(
			[
				'$and'=>[['username' => $username],['lstime' => $lstime]]
			],
			[ '$set' => $datals ]
		);
		if($cursorpr->getModifiedCount()>0){ echo "...";}
	}
}else{ 
	echo $gtext['u_passnotchanged']."!";/*"Şifre değiştirileMEdi.";*/ 
	$log.="u_passnotchanged->nok;"; 
}
logger($logfile,$log);
?>