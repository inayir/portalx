<?php
/*
	Pano Mesajlarını kaydeder/siler.
*/
function pcont($p){
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
include("../set_mng.php");
//error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
include($docroot."/ldap.php");
if($_SESSION["user"]==""){ echo "login"; exit; }
//
$log="";
include($docroot."/app/php_functions.php");
$logfile='personel';
//
@$trace=$_POST['t']; if($trace==''){ $trace=1; } 
@$username=$_POST['u']; 
if($username==''){ @$username=$_POST['username']; } if($username==""){ $username="dsayiner"; }
@$gpass=$_POST['gpass'];
@$newPassword=$_POST['pass'];  if($newPassword==""){ $newPassword="Akss2025.."; }
@$renewPassword=$_POST['repass']; 
//@$distinguishedName=$_POST['dn'];  
//
$data=[]; @$ksay=0; 
//
if(pcont($newPassword)==false||pcont($renewPassword)==false){ echo $gtext['u_passnotacceptable']; logger($logfile,$log); exit; }
if($newPassword!=""){ 
	$data['pass']=tirnakayarla($newPassword); 
	$log.="Pass;"; 
}else{ 
	echo $gtext['u_passnotchanged']."(null)"; 
	$log.="pass null->nok;";
	logger($logfile,$log);
	exit;
}//*/
@$collection = $db->personel;
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
	$distinguishedname=$cursor->distinguishedname;
}else{ 
	echo $gtext['user']." ".$gtext['notfound']; 
	exit;
}
//old pass auth from DB...
if($gpass!=''){
	if(pcont($gpass)==false){ echo $gtext['u_passnotacceptable']; logger($logfile,$log); exit; }
	if($cursor->pass!=@$gpass){ //"Geçerli şifreniz yanlış! Şifre DeğiştirileMEdi!";
		echo $gtext['u_passnotchanged']."!";
		$log.="gpass: nok;";
		logger($logfile,$log);
		exit; 
	}
}
echo $gtext['user'].": ".$username."\n";
$log.="username:".$username.";";
//LDAP
if($ini['usersource']=='LDAP'){ 
	try{
		$ADSI = new COM("LDAP:");
		$adsidn="LDAP://".$ini['ldap_server']."/".$distinguishedname; if($trace==1){ echo "<br>".$adsidn."<br>"; }
		try{
			echo "LDAP:";
			$user = $ADSI->OpenDSObject($adsidn, $_SESSION['user'], $_SESSION['pass'], 1); if($trace==1){ echo "<br> user OK<br>"; }
			if($user){ 
				$user->SetPassword($newPassword);
				try{
					echo $user->SetInfo();
					echo $gtext['u_passchanged'];
					$log.="u_passchanged->ok;";
				}catch(Exception $e){ //bilgi güncellenemedi.
					echo $gtext['u_passnotchanged']."!";//"Şifre değiştirileMEdi.";
					$log.="passnotchanged->Setinfo nok;";
					logger($logfile,$log);
					exit;
				}
			}else{ //user bulunamadı.
				echo $gtext['u_passnotchanged']."!!";//"Şifre değiştirileMEdi.";
				$log.="passnotchanged->ADSI nok;";
				logger($logfile,$log);
				exit;
			}
		}catch(Exception $e){
			echo $gtext['u_passnotchanged']."!!!";//"Şifre değiştirileMEdi.";
			$log.="passnotchanged->OpenDSObject nok;";
			logger($logfile,$log);
			exit;
		}
		echo "/DB:";
	}catch(Exception $e){ //ADSI not connect.
		echo $gtext['u_passnotchanged']."!";/*"Şifre değiştirileMEdi.";*/ 
		$log.="passnotchanged->ADSI nok;";
		logger($logfile,$log);
		$rawErr = $e->getCode();
		$processedErr = $rawErr + 0x100000000;
		printf( 'Error code 0x%x', $processedErr );
		exit;
	}
	
}
//DB
$log.="DB:";
$data['lastpassdate']=datem(date("Y-m-d", strtotime("now")).'T'.date("H:i:s", strtotime("now")).'.000+00:00');
$data['aktif']=1;
@$cursor = $collection->UpdateOne(
	[
		'username' => $username
	],
	[ '$set' => $data]
);
if($cursor->getModifiedCount()>0){ 
	echo $gtext['u_passchanged'];/*"Şifre değiştirildi.";*/ 
	$log.="u_passchanged->ok;"; 
	$act_collection = $db->personel_act;
	$data['act_date']=datem(date("Y-m-d", strtotime("now").'T'.date("H:i:s", strtotime("now")).'.000+00:00'));
	$act_cursor = $act_collection->insertOne(
		$data
	);
}else{ 
	echo $gtext['u_passnotchanged']."!";/*"Şifre değiştirileMEdi.";*/ 
	$log.="u_passnotchanged->nok;"; 
}
logger($logfile,$log);
?>