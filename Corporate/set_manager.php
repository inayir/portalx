<?php
/*
	Assign manager to department
*/
include('../set_mng.php');
//error_reporting(0);
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
if($_SESSION['user']==""&&$_SESSION['y_admin']!=1){
	echo "login"; exit;
}
$base_dn=$ini['base_dn'];
if($ini['usersource']=='LDAP'){ require($docroot."/ldap.php"); }
$logfile='personel';
@$collection=$db->personel;
@$collectiondep=$db->departments;
  
$username=$_POST['username']; //if($username==""){ $username="*cclear"; }
$department=$_POST['department']; //if($department==""){ $department="DHAD"; }

echo $gtext['a_department'].":".$department."->".$gtext['manager']." "; 
$log=$department."->Manager:".$gtext['manager'].";";

if($username==""){ echo $gtext['beingemptied']."..."; /*Boşaltılıyor*/ } 
$managedby=Array();
$displayname="";
$data=[];
//kullanıcıyı db de bul, dn al.
if($username!=""){
	try{
		@$cursor = $collection->findOne(
			[
				'username'=>$username
			],
			[
				'limit' => 1,
				'projection' => [
					'displayname'=>1,
					'distinguishedname'=>1,
				],
			]
		);
		if(!isset($cursor)){ 
			echo $gtext['user']." ".$gtext['notfound']."!"; exit; 
		}else{ 
			echo "\n"; 
			$managedby="";
			$managedby=$cursor->distinguishedname;
			$displayname=$cursor->displayname;
		}
	}catch(Exception $e){
		echo $gtext['notupdated']."!!"; exit;
	}
}
//
if($ini['usersource']=='LDAP'){
	$data['managedby']=$managedby; //personel dn -> ou->managedby
	//dep_dn almak için ldap'a bakıyoruz.
	$ldap_result = ldap_search($conn, $base_dn, "(ou=$department)");
	if($ldap_result){ //MODIFY department
		$infodep = ldap_get_entries($conn, $ldap_result);
		$dep_dn=$infodep[0]['distinguishedname'][0]; //department dn
		$son=ldap_mod_replace($conn, $dep_dn, $data);  //update manager
		if($son){
			echo $gtext['updated']." by:".$displayname; //department updated.
			$log.=$gtext['updated'].":".$displayname.";"
			//finding al person... to update managers
			$ldap_resultp = ldap_search($conn, $dep_dn, "(samaccountname=*)");
			if($ldap_resultp){ 
				$info = ldap_get_entries($conn, $ldap_resultp); 
				$up=0;
				$datap['manager']=$managedby;
				for($x=0;$x<$info["count"];$x++){
					if($info[$x]['department'][0]==$department){ //sadece o department. 
						if($managedby!=$info[$x]['distinguishedname'][0]){
							$sonuc=ldap_mod_replace($conn, $info[$x]['distinguishedname'][0], $datap);
							if($sonuc){ $up++; }
						}
					}
				}
				echo "\n".$up." ".$gtext['s_personel']." ".$gtext['updated']; 
				$log.=$up." person(s) ".$gtext['updated'].";";
			}
		}else{ 
			echo $gtext['notupdated']." "; 
			$log.=$gtext['notupdated'].";";
		}
	}else{ 
		echo $gtext['updated']." "; 
		exit; 
	}  
}
//DB
$log.="DB;";
if($displayname==""){
	$displayname=substr($managedby, 3, strpos($managedby,'OU='));
}
$data['managedby']=$displayname; //personel displayname -> departments->managedby
$data['manager']=$username; //personel username
$data['son_deg_per']=$_SESSION['user'];
$data['son_deg_tar']=datem(date("Y-m-d", strtotime("now")).'T'.date("H:i:s", strtotime("now")).'.000+00:00');
@$cursorup = $collectiondep->updateOne(
	[
		'ou' => $department
	],
	[ '$set' => $data]
);
if($cursorup->getModifiedCount()>0){ //"Güncellendi."; 
	echo "/".$gtext['updated'];  
	//tüm bağlı personelin yöneticisi değiştirilir.
	@$cursordep = $collectiondep->find(
		[
			'$or'=>[['department'=>$department]]
		],
		[
			'$set'=>$data
		]
	);
	if($cursordep->getModifiedCount()>0){ 
		echo " Personel records".$gtext['updated']; 
		$log.=" records ".$gtext['updated'].";";
	}
}else{ //"GüncelleneMEdi."; 
	echo "/".$gtext['notupdated'];  
	$log.=$gtext['notupdated'].";";
}  

logger($logfile,$log); 
?>