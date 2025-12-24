<?php
function group_add($group_ou,$groupname){
	/*Bir OU altına bir group ekleme*/
	header('Content-Type:text/html; charset=utf-8');
	$docroot=$_SERVER['DOCUMENT_ROOT'];
	include($docroot."/config/config.php");
	$base_dn=$ini['base_dn'];
	require($docroot."/sess.php");	
	require($docroot."/ldap.php");	
	//
	$data=Array();
	$data['objectClass'][0] = "top";
	$data['objectClass'][1] ="group";
	$data['cn'] 	=$groupname;
	$data['name'] 	=$groupname;
	$dn="CN=".$groupname.",".$group_ou;
	$data['distinguishedname']=$dn;
	ldap_add($conn,$dn,$data);
	return ldap_error($conn);
}
function group_user_add_remove($group_dn,$user_dn,$action, $debug){
	//Gruba üye ekleme-Add member to a group, $group_ou: grubun yeri(ou) $group: userin eklenecegi grup
	global $conn, $ini, $log; 
	header('Content-Type:text/html; charset=utf-8');
	$docroot=$_SERVER['DOCUMENT_ROOT']; 
	require($docroot."/sess.php");	
	require($docroot."/ldap.php");	
	//username ile kişi aranır, dn i bulunur
	if($user_dn==""){ $donus="*-".$gtext['u_fieldmustnotblank']."-"; return $donus; }
	$group_info=array(); //$group_dn = 'CN='.$group.',OU='.$group_ou.','.$ini['base_dn']; 	//$log.=" ***gr:".$group_dn." ";
	//dn ile işlem
	$group_info['member'] = $user_dn; // User's DN is added to group's 'member' array
	if($action=='D'){ //Removing
		$s=ldap_mod_del($conn,$group_dn,$group_info); 
		if($s!=''){ $donus.=$gtext['removed'];  $log.=":".$gtext['removed']; } //"Çıkarıldı";
		else { 
			$donus.=$gtext['notremoved']; //"ÇıkarılaMAdı!";
			if($debug==1){ 
				$donus.=" : ".$user_dn;  
				$log.="\n".$gtext['notremoved']."\n  grdn  :".$group_dn."\n  ->grinfo:".$user_dn;
			}
		}
	}else{ //Adding
		$s=ldap_mod_add($conn,$group_dn,$group_info); 
		if($s==1){ $donus.=$gtext['inserted'];  $log.=":".$gtext['inserted']; } //"Eklendi";
		else { 
			$donus.=$gtext['notinserted'];  //"EkleneMEdi!";
			if($debug==1){ 
				$donus.=" : ".$user_dn; 
				$log.="\n".$gtext['notinserted']."\n  ->grdn  :".$group_dn."\n  ->grinfo:".$user_dn;
			}
		}
	}
	return $donus; 
}
function addtogroupsfromini($user_dn){
	//adding user to groups from config
	global $ini, $data, $gtext, $log; 
	$groups_point=$ini['groups_point'];  
	$grsi=$ini['group']; //get groups
	$grs=explode(',', $grsi); 
	if(count($grs)>0){ 
		$lmsg="\n +++".$gtext['adding'].": ";
		for($i=0;$i<count($grs); $i++){ 
			$lmsg.="\n ".($i+1).": "; 
			$gr=$grs[$i]; 
			$group_ou="";
			if($groups_point!=""){ $group_ou="OU=".$groups_point; } 
			$bul=strpos($gr,"{"); 
			//if there is a parameter in group name. 		
			if($bul!=''||$bul>-1){
				$par=substr($gr, strpos($gr,"{")+1, strpos($gr,"}")-1);
				if(isset($data[$par])){//changing parameter to group name
					$deg=$data[$par];
					$gr=str_replace("{".$par."}", $deg, $gr);			
					if($par=="department"){	$group_ou.=",OU=".$data['department'];	}
				}
				//if company	
				if($par=="department"||$par=="company"){ $group_ou.=",OU=".$data['company']; }
			}  //else{ otherwise adding to the group in groups_point under base_ou }
			$lmsg.="    ".$gr."->"; $log.=";".$gr;
			$group_dn='CN='.$gr.','.$group_ou.','.$ini['base_dn'];
			$lmsg.=group_user_add_remove($group_dn,$user_dn,'A',0);				
		}
	}
	return $lmsg;
}//*/
function removefromgroups($user_dn){
	//removing groups in config.
	global $ini, $data, $gtext, $log; 
	$groups_point=$ini['groups_point']; 
	$grsi=$ini['group']; //groups
	$grs=explode(',', $grsi); 
	if(count($grs)>0){ 
		$lmsg="\n ---".$gtext['removing'].": ";  
		for($i=0;$i<count($grs); $i++){
			$lmsg.="\n ".($i+1).": "; 
			$gr=$grs[$i]; 
			$group_ou="";
			if($groups_point!=""){ $group_ou="OU=".$groups_point; } 
			$bul=strpos($gr,"{"); 
			//if there is a parameter in group name...		
			if($bul!=''){ 
				$par=substr($gr, strpos($gr,"{")+1, strpos($gr,"}")-1); 
				if(isset($data['o_'.$par])){ 
					$deg=$data['o_'.$par];	//changing parameter to group name 				
					$gr=str_replace("{".$par."}", $deg, $gr);
					if($par=='department'){	$group_ou.=',OU='.$deg;	}
				}else{
					$lmsg.="par:".$par." data:".$data['o_'.$par];
				}
				//if company
				if($par=="department"){ $group_ou.=',OU='.$data['o_'.'company']; }
			}  //else{ otherwise adding to the group in groups_point under base_ou }
			$lmsg.="    ".$gr."->"; $log.=";".$gr;
			$group_dn='CN='.$gr.','.$group_ou.','.$ini['base_dn'];
			$lmsg.=group_user_add_remove($group_dn,$user_dn,'D',0); //removing			
		}
	}
	return $lmsg;
}
function removefromallgroups($user_dn){
	global $ini, $data;
	$lmsg="\n* Removing user from groups...";  
	$groups_point=$ini['groups_point'];  //as Custom
	for($i=0;$i<count($grs); $i++){ 
		$gr=$grs[$i]; $lmsg.="\n ".($i+1).": ";
		$group_ou="";
		if($groups_point!=""){ $group_ou="OU=".$groups_point; } //as Custom
		$bul=strpos($gr,"{"); 
		//grup adında parametre geçiyorsa...		
		if($bul!=''||$bul>-1){ 
			$par=substr($gr, strpos($gr,"{")+1, strpos($gr,"}")-1); 
			if(isset($data[$par])){ 
				//birimin adı, grup adı içinde değiştirilecek. department ise hangi department ise.
				$deg=$data[$par]; 
				$gr=str_replace("{".$par."}", $deg, $gr);
				if($par=='department'){	$group_ou.=",OU=".$data['department'];	}
			}
			//group_ou gelen company ise company altındaki Custom daki gruba eklenir...	
			$group_ou.=",OU=".$data['company'];  
		}  //parametre gelmezse doğrudan \base_ou altındaki Custom altındaki gruba eklenir...
		$lmsg.=$gr." :";
		$group_dn='CN='.$gr.$group_ou.','.$ini['base_dn'];
		$lmsg.=group_user_add_remove($group_dn,$user_dn,'D',1); //siliniyor				
	}	
} 
?>