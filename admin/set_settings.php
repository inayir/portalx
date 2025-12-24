<?php
/*
	Set_settings ayarları ini ye yazar.
*/
include("../set_mng.php");
include('../vendor/php-ini-class/INI.class.php'); 
$inifile="../config/config.ini";
include('../sess.php');
//
error_reporting(0);
if(@$_SESSION['k']!=''){ 
	$_SESSION['user']='admin'; 
	$_SESSION['y_admin']==1; //	echo "admin ".$_SESSION['user']; 
}
if(@$_SESSION['user']==''){
	echo $gtext['u_mustlogin']; exit;
}
$y=@$_SESSION['y_admin'];
if(!$y||$_SESSION['user']==''){ //echo "yetki gerekir...";
	echo $gtext['reqprerequsity']; exit;
}
//
$msg="!-"; $data=Array();
$inic = new INI($inifile);
if(file_exists($inifile)){ $inic->read($inifile); }
//***Settings section
if(isset($_POST['firm'])){	$inic->data['Settings']['firm']=$_POST['firm'];	$data['firm']=$_POST['firm'];	}
if(isset($_POST['title'])){	$inic->data['Settings']['title']=$_POST['title']; $data['title']=$_POST['title'];	}
if(isset($_POST['logo'])){	
	if($_POST['logo']==""){ $inic->data['Settings']['logo']='/img/portalx_logo.png'; 
	}else{ $inic->data['Settings']['logo']=$_POST['logo']; $data['logo']=$_POST['logo'];	}
}
if(isset($_POST['bg_set'])){ 		$inic->data['Settings']['bg_set']=$_POST['bg_set']; $data['bg_set']=$_POST['bg_set'];	}
if(isset($_POST['bg_login'])){ 		$inic->data['Settings']['bg_login']=$_POST['bg_login']; $data['bg_login']=$_POST['bg_login'];	}
if(isset($_POST['date_local'])){ 	$inic->data['Settings']['date_local']=$_POST['date_local']; $data['date_local']=$_POST['date_local']; }
if(isset($_POST['act_seperator'])){ $inic->data['Settings']['act_seperator']=$_POST['act_seperator']; $data['act_seperator']=$_POST['act_seperator']; }
if(isset($_POST['sess_time'])){ 	$inic->data['Settings']['sess_time']=$_POST['sess_time'];  $data['sess_time']=$_POST['sess_time']; 	}
if(isset($_POST['menu_gun0'])){ 	$inic->data['Settings']['menu_gun0']=$_POST['menu_gun0'];  $data['menu_gun0']=$_POST['menu_gun0']; 	}
if(isset($_POST['menu_gun6'])){ 	$inic->data['Settings']['menu_gun6']=$_POST['menu_gun6'];  $data['menu_gun6']=$_POST['menu_gun6']; 	}
if(isset($_FILES['logo'])){			$inic->data['Settings']['logo'] = '/img/'.basename($_FILES['logo']['name']); 
	//file upload
	if (move_uploaded_file($_FILES['logo']['tmp_name'], "/img/".basename($_FILES['logo']['name']))){
		//ok
	}
}
//***Personel section
//menuofday
	$s=0; 
	if(@$_POST['menuofday']=="on"){ $s=1; }
	$inic->data['Personel']['menuofday']=$s;
	$data['menuofday']=$s; 
//pservis_sofor_tel_gosterim	
	$s=0; 
	if(@$_POST['pservis_sofor_tel_gosterim']=="on"){ $s=1; }
	$inic->data['Personel']['pservis_sofor_tel_gosterim']=$s;
	$data['pservis_sofor_tel_gosterim']=$s; 
//personel_pano	
	$s=0; 
	if(@$_POST['personel_pano']=="on"){ $s=1; }
	$inic->data['Personel']['personel_pano']=$s;
	$data['personel_pano']=$s; 
//usercanedit
	$s=0; 
	if(@$_POST['usercanedit']=="on"){ $s=1; }
	$inic->data['Personel']['usercanedit']=$s;
	$data['usercanedit']=$s; 
//***DataBase_Settings_Mongo section
if(isset($_POST['MongoConnection'])){ 
	$inic->data['DataBase_Settings_Mongo']['MongoConnection']=$_POST['MongoConnection'];
	$data['MongoConnection']=$_POST['MongoConnection']; 
}
if(isset($_POST['MongoDB'])){ //name controlled before
	$inic->data['DataBase_Settings_Mongo']['MongoDB']=$_POST['MongoDB'];
	$data['MongoDB']=$_POST['MongoDB']; 
}
//***Apps section
//Fixtures
	$s=0; 
	if(@$_POST['Fixtures']=="on"){ $s=1; }	
	$inic->data['Apps']['Fixtures']=$s;	
	 $data['Fixtures']=$s;	
//
//***Documents section
//Org_Sema
	$s=0; 
	if(@$_POST['Org_Sema']=="on"){ $s=1; }	
	$inic->data['Documents']['Org_Sema']=$s;	
	 $data['Org_Sema']=$s; 
//
if(isset($_POST['Org_Sema_Dir'])){ 	$inic->data['Documents']['Org_Sema_Dir']=$_POST['Org_Sema_Dir'];	}
//Sertifikalar 
	$s=0; 
	if(@$_POST['Sertifikalar']=="on"){ $s=1; }	
	$inic->data['Documents']['Sertifikalar']=$s;
	$data['Org_Sema_Dir']=$s; 
//
if(isset($_POST['b_certs_url'])){ 	$inic->data['Documents']['b_certs_url']=$_POST['b_certs_url'];	}
//Kalifikasyonlar
	$s=0; 
	if(@$_POST['Kalifikasyonlar']=="on"){ $s=1; }
	$inic->data['Documents']['Kalifikasyonlar']=$s;
	$data['b_certs_url']=$s; 
//
if(isset($_POST['b_quals_url'])){ 	$inic->data['Documents']['b_quals_url']=$_POST['b_quals_url'];	}
//Formlar	
	$s=0; 
	if(@$_POST['Formlar']=="on"){ $s=1; }
	$inic->data['Documents']['Formlar']=$s;
	$data['Formlar']=$s; 
//
if(isset($_POST['b_forms_url'])){ 	
	$inic->data['Documents']['b_forms_url']=$_POST['b_forms_url'];	
	$data['b_forms_url']=$_POST['date_local']; 
}
//***Domain_Settings section
if(isset($_POST['usersource'])){	
	$inic->data['Domain_Settings']['usersource'] = $_POST['usersource']; 
	$data['usersource']=$_POST['usersource']; 
}
if(isset($_POST['domain'])){
	$inic->data['Domain_Settings']['domain'] = $_POST['domain']; $data['domain']=$_POST['domain']; 
	$data['domain']=$_POST['domain']; 
}
if(isset($_POST['ldap_server'])){	
	$inic->data['Domain_Settings']['ldap_server'] = $_POST['ldap_server']; 
	$data['ldap_server']=$_POST['ldap_server']; 
}
if(isset($_POST['ldap_server2'])){	
	$inic->data['Domain_Settings']['ldap_server2'] = $_POST['ldap_server2']; 
	$data['ldap_server2']=$_POST['ldap_server2']; 
}
if(isset($_POST['base_dn'])){		
	$inic->data['Domain_Settings']['base_dn'] = $_POST['base_dn']; 
	$data['base_dn']=$_POST['base_dn']; 
}
if(isset($_POST['domshort'])){		
	$inic->data['Domain_Settings']['domshort'] = $_POST['domshort']; 
	$data['domshort']=$_POST['domshort']; 
}
if(isset($_POST['disabledou'])){	
	$inic->data['Domain_Settings']['disabledOU'] = $_POST['disabledou']; 
	$data['disabledou']=$_POST['disabledou']; 
}
if(isset($_POST['auth_username'])){	
	$inic->data['Domain_Settings']['auth_username'] = $_POST['auth_username']; 
	$data['auth_username']=$_POST['auth_username']; 
}
//LDAP section
if(isset($_POST['asayrac'])){ 
	$inic->data['LDAP']['asayrac']=$_POST['asayrac'];
	$data['asayrac']=$_POST['asayrac']; 
}
if(isset($_POST['usernameflow'])){ 
	$inic->data['LDAP']['usernameflow']=$_POST['usernameflow'];
	$data['usernameflow']=$_POST['usernameflow']; 
}
if(isset($_POST['givenname_length'])){ 
	$inic->data['LDAP']['givenname_length']=$_POST['givenname_length'];
	$data['givenname_length']=$_POST['givenname_length']; 
}
if(isset($_POST['sn_length'])){ 
	$inic->data['LDAP']['sn_length']=$_POST['sn_length'];
	$data['sn_length']=$_POST['sn_length']; 
}
if(isset($_POST['passformat'])){ 
	$inic->data['LDAP']['passformat']=$_POST['passformat'];
	$data['passformat']=$_POST['passformat']; 
}
if(isset($_POST['stdpass'])){ 
	$inic->data['LDAP']['stdpass']=$_POST['stdpass'];
	$data['stdpass']=$_POST['stdpass']; 
}
if(isset($_POST['disabledname'])){ 
	$inic->data['LDAP']['disabledname']=$_POST['disabledname'];
	$data['disabledname']=$_POST['disabledname']; 
}
if(isset($_POST['disabledmailuser'])){ 
	$inic->data['LDAP']['disabledmailuser']=$_POST['disabledmailuser'];
	$data['disabledmailuser']=$_POST['disabledmailuser']; 
}
if(isset($_POST['homedir'])){		
	$inic->data['LDAP']['homedir'] = $_POST['homedir']; 
	$data['homedir']=$_POST['homedir']; 
}
if(isset($_POST['homedrive'])){		
	$inic->data['LDAP']['homedrive'] = $_POST['homedrive']; 
	$data['homedrive']=$_POST['homedrive']; 
}
if(isset($_POST['drive_permission'])){		
	$inic->data['LDAP']['drive_permission'] = $_POST['drive_permission']; 
	$data['drive_permission']=$_POST['drive_permission']; 
}
if(isset($_POST['drive_permission'])){		
	$inic->data['LDAP']['groups_point'] = $_POST['groups_point']; 
	$data['groups_point']=$_POST['groups_point']; 
}
if(isset($_POST['group'])){		
	$inic->data['LDAP']['group'] = $_POST['group']; 
	$data['group']=$_POST['group']; 
}
//***Messages section
if(isset($_POST['uaddmsg1'])){ 		
	$inic->data['Messages']['uaddmsg1']=$_POST['uaddmsg1'];	
	$data['uaddmsg1']=$_POST['uaddmsg1']; 
}
if(isset($_POST['uaddmsg2'])){ 		
	$inic->data['Messages']['uaddmsg2']=$_POST['uaddmsg2'];
	$data['uaddmsg2']=$_POST['uaddmsg2']; 
}
//
$sonuc=$inic->write($inifile);
if($sonuc){ 
	$msg=$gtext['saved'];//"Kaydedildi.";
	//
	if($_SESSION['k']!=''){
		@$client = new Client($data['MongoConnection']);
		$dbi=$data['MongoDB']; 
		@$db=$client->$dbi;
	}//*/
	$collections = $db->listCollections();
	$collectionNames = [];
	foreach ($collections as $collection) {
	  $collectionNames[] = $collection->getName();
	}
	$exists = in_array('settings_act', $collectionNames);
	if(!$exists){
		$db->createCollection('settings_act',[
		]);
	}
	//Changes are writing to settings_act 
	$data['ch_user']=$user;  //first: install
	$act_collection = $db->settings_act;
	$act_cursor = $act_collection->insertOne(
		$data
	);
}else{ $msg.=$gtext['notsaved'];/*"Ayarlar KaydedileMEdi!";*/ }
echo $msg;
?>