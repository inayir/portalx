<?php
/*
	Compnay'ye bağlı Departmentları getirir.
*/
//error_reporting(0);
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
//MongoDB'den getirilir...
	@$collection = $db->departments; //
	try{
		$dcursor = $collection->find(
			[
				'dp'=>'D', 'company'=>$company,
			],
			[
				'limit' => 0,
				'projection' => [
					'dp' => 1,
					'ou' => 1,
					'description' => 1,
					'distinguishedname' => 1,
					'managedby' => 1,
					'manager' => 1,
				],
				'sort'=>['order'=>1, 'description'=>1],
			]
		);
		if(isset($dcursor)){
			$ksay=0; $js1='[';
			foreach ($dcursor as $dformsatir) {
				if($ksay>0){ $js1.=','; }
				$js1.='{"ou":"'.$dformsatir->ou.'",';
				$js1.='"description":"'.$dformsatir->description.'",';
				$js1.='"distinguishedname":"'.$dformsatir->distinguishedname.'",';
				$js1.='"company":"'.$dformsatir->company.'",';
				$js1.='"managedby":"'.$dformsatir->managedby.'"';
				$js1.='}';
				$ksay++;
			} 
			$js1.="]";
		}else{
			echo "Hata oluştu!";
		}
	}catch(Exception $e){
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}	
	echo $js1;

//$logfile="get_depsbyc";
//logger($logfile, $log);
?>