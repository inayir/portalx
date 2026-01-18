<?php
/*
	User bilgilerini getirir. bg: bilgileri getir.
*/
error_reporting(0);
include("../set_mng.php");
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
if($_SESSION["user"]==""){
	//echo "login"; exit;
}
@$state=$_POST['state'];  //P personel I intern-stajyer C consultant O other
if($state==""){ $state=$_GET['state']; }
// 
@$ptype=@$_POST['pt'];  //P personel I intern-stajyer C consultant O other
if($ptype==""){ $ptype=@$_GET['pt']; } 
//
@$keys=$_POST['keys']; 
if($keys==''){ 
	$keys=Array('displayname','username','givenname','sn','displayname','mail','description','title','mobile','company','department','distinguishedname','telephonenumber','physicaldeliveryofficename','manager','useraccountcontrol','ptype','note','address','sdate','resigndate');
}
//personel getirilir...
@$collection=$db->personel;
if($ptype!=''){
	$kosul=['$and'=>[['state'=>$state],['ptype'=>$ptype]]];
}else{ $kosul=['state'=>$state]; } //echo "kosul:".print_r($kosul)."<br>";

//department //not a lookup because errors...
$dcol=$db->departments;
$dcursor=$dcol->find(
	[
		'state'=>['$ne'=>null]
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort' => [
			'ou'=>1,
			'description'=>1,
		],
	],
);
if($dcursor){
	$fdsatir=[];
	foreach ($dcursor as $dformsatir) {
		$dsatir=[];
		$dsatir['ou']			=$dformsatir->ou;
		$dsatir['description']	=$dformsatir->description;
		$fdsatir[]=$dsatir;
	}
}
//personel
$cursor = $collection->aggregate([
	[
		'$match'=>$kosul,
	],
	[
		'$sort'=>[
			'displayname'=>1,
			'title'=>1
		],
	]
]);
//
$fsatir=Array(); 
$json='['; $ilk=0;
foreach ($cursor as $formsatir) {
	if(strpos($formsatir->description,'#')==""||strpos($formsatir->description,'#')<0){ 
		if($ilk>0){ $json.=','; }
		$json.='{';
		for($k=0;$k<count($keys);$k++){		
			if($k>0){ $json.=','; }
			$key=$keys[$k];
			$value=$formsatir->$key;		
			if($key=='department'&&$value!=''){
				$ti=array_search($value, array_column($fdsatir, 'ou')); 
				if($ti!=false||$ti!=''){ $value=$fdsatir[$ti]['description']; }
				//
			}
			if($key=='tarih'&&$value!=''){
				$value=mdatetodate($value);
				$value=date($ini['date_local'], strtotime($value));
			}
			//
			$json.='"'.$keys[$k].'":"'.$value.'"';
		}
		$json.='}';
		$ilk++;
	}
}
$json.=']';
echo $json;
?>