<?php
/*
	Department save. LDAP and MongoDB /NOT MOVES!
*/
include('../set_mng.php');
//error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
if($_SESSION["user"]==""){ 	echo "login"; exit; }
//
$base_dn=$ini['base_dn'];
if($ini['usersource']=='LDAP'){ require($docroot."/ldap.php"); }
$o_dn=$_POST['o_dn']; //
$ou=$_POST['ou']; //if($ou==""){ @$ou=$_GET['ou']; }
$dp=$_POST['dp']; //$dp='D';
//
$ksay=0; $sonuc=false; 
$collections = $db->listCollections();
$collectionNames = [];
foreach ($collections as $collection) {
  $collectionNames[] = $collection->getName();
}
$exists = in_array('departments', $collectionNames);
if(!$exists){
	$db->createCollection('departments',[
	]);
}
@$collection = $db->departments;
	try{
		$cursor = $collection->findOne(
			[
				'ou'=>$ou,
			],
			[
				'limit' => 1,
				'projection' => [
					'dp' => 1,
					'ou' => 1,
					'company' => 1,
					'description' => 1,
					'managedby' => 1,
				]
			]
		);
		if(isset($cursor)){	
			$ksay=count($cursor); 
			$db_ou=$cursor->ou;
			$db_company=$cursor->company;
		}
	}catch(Exception $e){
		echo 'Caught exception: '; echo  $e->getMessage(); echo "\n";
	}
//Bilgiler hazırlanıyor**********************************
$data=array();
//General
$baseou=substr($base_dn,3, strpos($base_dn,',')-3);
if($o_dn==''){
	$data["objectclass"][0]= "top";
	$data["objectclass"][1]= "organizationalUnit";
}
if($o_dn==''){ 
	$data["ou"] = $ou; 
	$data["name"]	= $ou;
}
$data['description']= $_POST['description'];
$distinguishedname='OU='.$ou; //department veya company
if($dp=='D'){ $distinguishedname.=',OU='.$_POST['company']; } //department ise
$distinguishedname.=','.$base_dn;
$data['distinguishedname']=$distinguishedname;
//işlem yapılıyor*********************************************************/
if($ini['usersource']=='LDAP'){  //LDAP işlemleri
	$ldap_result = ldap_search($conn, $base_dn, "(ou=$ou)");
	if($ldap_result){ //echo " ldap_result+";
		$info = ldap_get_entries($conn, $ldap_result); 
		echo "\n* LDAP: ";
		if($info["count"]>0){ //var_dump($data);
			$sonuc=ldap_mod_replace($conn, $o_dn, $data);
			if($sonuc){ echo $gtext['updated']; }//" Güncellendi.";
			else{ 
				echo $gtext['notupdated']." -> ";
				$log.=$db_ou.":".$gtext['notupdated']."->".ldap_error($conn);
				echo $msg; exit;  
			} 
		}else{ //ou null-> save. echo " info-";
			$sonuc=ldap_add($conn, $distinguishedname, $data); 
			if($sonuc){ //"Eklendi.";	echo " add+";
				echo $gtext['inserted']; 
				require("../AD/ldap_functions.php");
				//groups_point ou eklenir.
				$group_ou=$distinguishedname; //group dn
				if($ini['groups_point']!=""){ //Parametre girilmemişse asıl ou'da gruplar yer alır.
					$datagp=Array();				
					$datagp["objectclass"][0]= "top";
					$datagp["objectclass"][1]= "organizationalUnit";
					$datagp['ou']=$ini['groups_point'];
					$datagp['name']=$ini['groups_point'];
					$group_ou="OU=".$ini['groups_point'].",".$distinguishedname;
					$datagp['distinguishedname']=$group_ou;
					$sonucgp=ldap_add($conn, $group_ou, $datagp);
					if(!$sonucgp){ echo $gtext['group']." ".$gtext['notinserted']; 
					}else{	echo "\n".$gtext['group']." ".$gtext['inserted']; }
				}
				//gruop lar eklenir... içinde department olanlar...
				$grs=explode(',', $ini['group']);
				if(count($grs)>0){ 
					echo "\n* Gruplar ekleniyor...";//.count($grs)." grup";  
					for($i=0;$i<count($grs); $i++){
						if(strpos($grs[$i], '{department}')>-1){
							//grubu ekle.  grup adı belirlenir.
							$groupname=str_replace('{department}', $ou, $grs[$i]);
							echo "\n ".$i." ".$groupname." -> ";
							$gr_res=group_add($group_ou,$groupname);
							if($gr_res=="Success"){ echo $gtext['group']." ".$gtext['inserted']; }
							else{ echo $gtext['group']." ".$gtext['notinserted']."->".$gr_res; }
						}						
						if(strpos($grs[$i], '{company}')>-1){
							//grubu ekle.  grup adı belirlenir.
							$groupname=str_replace('{company}', $ou, $grs[$i]);
							echo "\n ".$i." ".$groupname." -> ";
							$gr_res=group_add($group_ou,$groupname);
							if($gr_res=="Success"){ echo $gtext['group']." ".$gtext['inserted']; }
							else{ echo $gtext['group']." ".$gtext['notinserted']."->".$gr_res; }
						}						
					}
				}//*/
			}else{ //echo " add-";
				echo $gtext['notinserted']."->";
				echo ldap_error($conn);
				exit; //usersource=LDAP için 
			} 
		}
	}
	echo "\n* DB: ";
} //LDAP işlemleri sonu-----------------------------------------
//DB işlemleri..................................................
$data["dp"]		= $dp; 
$data["company"]= $_POST['company'];
$data["state"]  = $_POST['state'];
if($_POST['dp']=='C'||$_POST['company']==''){ //department=company ise root ou yazılır.
	$data["company"] = $baseou; //from config
}
if($ksay>0){  //update
	@$cursor = $collection->updateOne(
		[
			'ou'=>$ou
		],
		[ '$set' => $data ]
	); 
	if($cursor->getModifiedCount()>0){ echo $gtext['updated']; $upd=1; }else{ echo $gtext['notupdated']; $log.="{'update error':''}"; }
}else{  //INSERT
	@$cursor = $collection->insertOne(
		$data
	);
	if($cursor->getInsertedCount()>0){ 
		echo $gtext['inserted']; $ins++;  //eklendi
	}else{ echo $gtext['notinserted']; $log.="{'insert error':''}"; }
} //*/

if($upd==1){
	//değişen birimin ousu ise bu oudaki tüm personelin department veya company kayıtları değiştirilmelidir.
	@$pcollection = $db->personel;
	$data=array();
	$data['department']=$ou;
	$data['company']=$_POST['company'];
	if($ini['usersource']=='LDAP'){ 
		$ldap_result = ldap_search($conn, $ini['base_dn'], "(department=$ou)");
		$info = ldap_get_entries($conn, $ldap_result); 
		echo " - Personel: ";
		if($info["count"]>0){
			//var_dump($info);
			for($i==0; $i<$info["count"]; $i++){
				echo "\n".$i." ".$info[$i]['displayname'][0]." ";
				$sonuc=ldap_mod_replace($conn, $info[$i]['distinguishedname'][0], $data);
				if($sonuc){ //echo "OK"; 
					$pcursor = $pcollection->updateOne(
						[
							'displayname'=>$info[$i]['displayname'][0]
						],
						[ '$set' => $dataper ]
					); 
					if($pcursor->getModifiedCount()>0){ echo $gtext['updated']; $rec++; }
					else{ echo $gtext['notupdated']; $log.="{'update error':''}"; }			
				}
			}
			echo "\n".$gtext['updated']." #".$rec." records.";
		}else{ echo $gtext['notupdated']; $log.="{'update error':''}"; }
	}else{
		//sadece DB
	}
}
logger("department",$log);
?>