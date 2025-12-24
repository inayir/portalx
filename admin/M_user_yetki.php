<?php
/*
	Ayrılan Kullanıcının hesabını kapatmak->Ayrılanlar klasörüne taşımak
*/
include("../set_mng.php");
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php"); 
if($user==""){ 
	echo "login"; exit;
} 
$username=$_POST['u']; 
if($username==''){ @$username=$_GET['u']; } 
$log=date("Y-m-d H:i:s", strtotime("now"));
if($username!=''){  
	@$collection = $db->personel_prop;
	@$cursor = $collection->findOne(
		[
			'username'=>$username,
		],
		[
			'limit' => 1,
			'projection' => [
			],
		]
	);
	/*	
				'username' => 1,
				'y_ayar01' => 1,
				'y_addinfoduyuru' => 1,
				'y_addinfohaber' => 1,
				'y_addinfoser' => 1,
				'y_addinfomenu' => 1,
				'y_fixtures' => 1,
				'y_bq' => 1,
				'y_bo' => 1,
				'y_rcall' => 1,
				'y_admin' => 1 //*/
	if(isset($cursor)){	$ksay=count($cursor);  }

	$json='{';
	$json.='"username":"'.$cursor->username.'",';
	$json.='"y_ayar01":"'.$cursor->y_ayar01.'",';
	$json.='"y_addinfoduyuru":"'.$cursor->y_addinfoduyuru.'",';
	$json.='"y_addinfohaber":"'.$cursor->y_addinfohaber.'",';
	$json.='"y_addinfoser":"'.$cursor->y_addinfoser.'",';
	$json.='"y_addinfomenu":"'.$cursor->y_addinfomenu.'",';
	$json.='"y_fixtures":"'.$cursor->y_fixtures.'",';
	$json.='"y_bq":"'.$cursor->y_bq.'",';
	$json.='"y_bo":"'.$cursor->y_bo.'",';
	//$json.='"y_rcall":"'.$cursor->y_rcall.'",';
	$json.='"y_admin":"'.$cursor->y_admin.'"';
	$log.=";username: ".$cursor->y_admin;//*/
	$json.='}';
	echo $json;
}
//
$log.=$json.";";
$dosya=$docroot."/logs/personel_prop.log"; 
touch($dosya);
$dosya = fopen($dosya, 'a');
fwrite($dosya, $log);
fclose($dosya); //*/
?>
