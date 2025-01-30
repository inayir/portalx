<?php
/*
	Compnay'ye bağlı Departmentları getirir.
*/
function percount($ou, $disname){
	global $db;
	$xsay=0;
	@$xcollection = $db->personel; 
	$xcursor = $xcollection->find(
		[
			'department'=>$ou
		],
		[
			'limit' => 0,
			'projection' => [
				'givenname'=>1,
			]
		]
	);
	if(isset($xcursor)){	 
		foreach ($xcursor as $xsatir) {
			$yer="";
			$yer=strpos($xsatir->givenname, $disname);
			if($yer==''||$yer<0){ $xsay++; };
		}
	}
	return $xsay;
}
error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/set_mng.php");
include($docroot."/sess.php");
if($_SESSION['user']==""){ echo gtext['u_mustlogin']."!"; exit; }
if($ini['usersource']=='LDAP'){ require($docroot."/ldap.php"); }
$base_dn=$ini['base_dn'];
$vd=0;
@$company=$_POST["company"];  //if($company==""){ @$company=$_GET["company"]; }
if($company==""){
	echo $gtext['u_companymustbechosen']; exit;
} 
$dsatir=Array(); 
//MongoDB'den getirilir...
@$collection = $db->departments; //
try{
	$dcursor = $collection->find(
		[
			'dp'=>'D', 'company'=>$company,'status'=>['$ne'=>'C']
		],
		[
			'limit' => 0,
			'projection' => [
				'dp' => 1,
				'ou' => 1,
				'description' => 1,
				'distinguishedname' => 1,
				'company' => 1,
				'managedby' => 1,
				'status' => 1,
			],
			'sort'=>['order'=>1, 'description'=>1],
		]
	);
	if(isset($dcursor)){
		$ksay=0; 
		foreach ($dcursor as $dformsatir) {
			$satir=[];
			$satir['ou']=$dformsatir->ou; //echo "<br>".$dformsatir->ou; 
			$satir['company']=$dformsatir->company;
			$percount=percount($dformsatir->ou, $ini['disabledname']);
			$satir['description']=$dformsatir->description." (".$percount.")";
			$satir['distinguishedname']=$dformsatir->distinguishedname;
			$satir['managedby']=$dformsatir->managedby;
			$satir['manager']=substr($dformsatir->managedby,3,strpos($dformsatir->managedby,',OU')-3);
			$satir['status']=$dformsatir->status;
			$dsatir[]=$satir;	
			$ksay++;
		} 
		$js1.="]";
	}else{
		echo gtext['u_error']; //"Hata oluştu!"
	}
}catch(Exception $e){
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}
echo json_encode($dsatir);
?>