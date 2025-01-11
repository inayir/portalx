<?php
$inifile=$docroot."/config/config.ini";
if(file_exists($inifile)){
	$ini=parse_ini_file($inifile); 
	if(@$ini['logo']==''){
		$ini['logo']='/img/portalx_logo.png';
	}
}else{ //first time	
	$_SESSION['LAST_ACTIVITY'] = time()-100;
	$ini=[]; 
	$ini['sess_time']=1800;
	$ini['bg_set']='2d4357';
	$ini['logo']='/img/portalx_logo.png';
}
?>
