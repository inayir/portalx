<?php
/*
	"Closed" user will be deleted!->state='D'
*/
//error_reporting(0);
include("../set_mng.php");	
include($docroot."/sess.php");	
include($docroot."/ldap.php");
$log="Deleting;";
include($docroot."/app/php_functions.php");
$logfile='personel';

if(!$bind){ echo "LDAP Server Not Found!"; exit; }
if($_SESSION['user']==""){ echo "login"; exit; }
echo "User: ";
$username=$_POST['u'];
@$collection = $db->personel;
echo $username."->";
@$cursor = $collection->findOne(
	[
		'username'=>$username
	],
	[
		'limit' => 1,
		'projection' => [
			'username' => 1,
			'description' => 1,
			'displayname' => 1
		],
	]
);
if(isset($cursor)){	$ksay=count($cursor); }

$simdi=datem(date("Y-m-d H:i:s", strtotime("now")));
$data=[];
$data['username']=$username;
$data['state']='D';
$data['state_ch_date']=$simdi;
@$cursor = $collection->updateOne(
	[
		'username'=>$username
	],
	[ '$set' => $data ]
);
if($cursor->getModifiedCount()>0){ 
	echo $gtext['deleted']; $log.=";".$gtext['deleted'].";"; 
}else{ echo $gtext['notdeleted']."!"; $log.=$gtext['notdeleted']." {'state update error':''}"; }
//
logger($logfile,$log);
?>