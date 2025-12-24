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
	echo "login"; exit;
}
/*
function mdatetodate($d){
	if($d!=""){
		$t=strpos($d,'T'); 
		if($t==''){ return substr($d,0); }
		else{ return substr($d,0,$t); }
	}else{ return false; }
}//*/

@$username=$_POST['u'];  //
if($username==""){ $username=$_GET['u']; }//echo "u:".$username;
if($username!=""){ $s="(samaccountname=$username)"; } 
//
if($username==""){ echo "-"; exit; } 
@$keys=$_POST['keys']; //if($process==''){ $process=$_GET['keys'];} echo print_r($keys);
/*if($keys==''){ 
	$keys=Array('samaccountname','givenname','sn','displayname','mail','description','title','mobile','company','department','distinguishedname','telephonenumber','physicaldeliveryofficename','manager','useraccountcontrol','ptype','note','address','sdate','resigndate');
}/*else{	//kapatma-açmaya dair az bilgi getir.
	$keys=Array('samaccountname','displayname','description','company','department','useraccountcontrol');
}//*/
@$collection=$db->personel;
$cursor = $collection->aggregate([
	[
		'$match'=>['username'=>$username],
	]
]);
$fsatir=Array(); 

foreach ($cursor as $formsatir) {	
	$satir=[];
	$satir['description']=$formsatir->description;
	$satir['samaccountname']=$formsatir->username;
	$satir['displayname']=$formsatir->displayname;
	$satir['givenname']=$formsatir->givenname;
	$satir['sn']=$formsatir->sn;
	$satir['title']=$formsatir->title;
    if($formsatir->mobile!='') { $satir['mobile']=$formsatir->mobile; }
	$satir['otherMobile']=$formsatir->otherMobile;
	$satir['company']=$formsatir->company; 
	$satir['department']=$formsatir->department; 
	$satir['mail']=$formsatir->mail;
	$satir['distinguishedname']=$formsatir->distinguishedname;
	$satir['telephonenumber']=$formsatir->telephonenumber;
	$satir['otherTelephone']=$formsatir->otherTelephone;
	$satir['physicaldeliveryofficename']=$formsatir->physicaldeliveryofficename;
	$m=$formsatir->manager;
	$satir['manager']=$m; //substr($m, 3, strpos($m,',')-3);
	$satir['useraccountcontrol']=$formsatir->useraccountcontrol;
	if($satir['useraccountcontrol']==null){ $satir['useraccountcontrol']=0;}
	$satir['ptype']=$formsatir->ptype;
	$satir['note']=$formsatir->note;
	$satir['streetaddress']=$formsatir->streetaddress;
	$satir['district']=$formsatir->district;
	$satir['st']=$formsatir->st; if($formsatir->st==''){ $satir['st']=$formsatir->city;}
	$satir['co']=$formsatir->co; if($formsatir->co==''){ $satir['co']=$formsatir->country;}
	if($formsatir->sdate!=""){
		$satir['sdate']=mdatetodate($formsatir->sdate);
	}
	if($formsatir->resigndate!=""){
		$satir['resigndate']=mdatetodate($formsatir->resigndate);
	}
	$fsatir[]=$satir;
}
$json='['; 	 //tüm bilgiler getirilir 
if(count($fsatir)>0){ //echo print_r($fsatir); //echo "key count:".count($keys);
    for($i=0;$i<count($keys);$i++){
        if($i>0){ $json.=','; } 
        $deger=$fsatir[0][$keys[$i]]; //echo "; ".$deger;
        $json.='{"key":"'.$keys[$i].'", "value": "'.$deger.'"}';
    }
}else{ echo "{'NOT Found!'}"; }
$json.=']';
echo $json;
?>