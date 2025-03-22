<?php
/*
	Company'ye bağlı Departmentları getirir.
	Gets departments in a company
*/
error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/set_mng.php");
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
if($_SESSION['user']==""){ echo "login"; exit; }
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
	$dcursor = $collection->aggregate([
		[
			'$match'=>[
				'$and'=>[['dp'=>['$ne'=>'']],['status'=>['$ne'=>'C']],['company' => $company]]
			],
		],
		[
			'$sort'=>[
				'dp'=>1,
				'description'=>1,
			]
		]
	]);
	if(isset($dcursor)){
		$ksay=0; 
		foreach ($dcursor as $dformsatir) {
			$satir=[];
			$satir['ou']=$dformsatir->ou; //echo "<br>".$dformsatir->ou; 
			$satir['company']=$dformsatir->company;
			$percount=percount('D', $dformsatir->ou, $ini['disabledname']);
			$satir['description']=$dformsatir->description;
			$satir['percount']=$percount;
			$satir['distinguishedname']=$dformsatir->distinguishedname;
			$satir['managedby']=$dformsatir->managedby;
			$satir['manager']=substr($dformsatir->managedby,3,strpos($dformsatir->managedby,',OU')-3);
			$satir['status']=$dformsatir->status;
			$dsatir[]=$satir;	
			$ksay++;
		} 
	}else{
		echo gtext['u_error']; //"Hata oluştu!"
	}
}catch(Exception $e){
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}
echo json_encode($dsatir);
?>