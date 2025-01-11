<?php
/*
	Opening Directory in File Server
	URL: http://fs/ex_mkdir.php?hd=\\fs\yuva\&&u=authuser&&d=portalx.com 
	fs : fileserver name, you can change.
*/
error_reporting(0);
$ini = parse_ini_file("config/config.ini");
//güvenlik için isteğin geleceği sunucu belirlenir.
$ra=$_SERVER['REMOTE_ADDR'];
if($ra!=$ini['server']){
	echo "No auth server!: ".$ra.""; exit;
}
$domain=$ini['domain']; //domain
$homedir=$ini['homedir']; //  homedirectory="\\\afs1\yuva\\";
$username=$_GET['u']; //user
$s=false;
if($username==""){ echo "---"; exit;}
else{
	$homedir.=$username;
	//echo "Homedir:".$homedir;
	if(!file_exists($homedir)){
		$s=mkdir($homedir, 0777, true);  //windows yetkileri es geçer.echo " açılma sonucu ".$s;
		if($s){ echo " açıldı."; }else{ " açıla<b>MA</b>dı!"; }
	}else{ $s=true;}
	if($s){	
		$e='icacls '.$homedir.' /t /grant '.$username."@".$domain.':F';
		$s=exec($e);
		if(substr($s, 23, 1)=="1"){ echo "Yetkilendirildi.";}
		else{ echo ' - YetkilendirileMEdi!!! '.$username."@".$domain." hd:".$homedir; }
	}
}
?>
