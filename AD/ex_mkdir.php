<?php
/*
	Web sunucudan gelen istekleri file serverda işlemek için web servisidir.
	http://afs1/ex_mkdir.php?hd=\\afs1\yuva\&&u=testuser&&d=akss.com.tr şeklinde çağrılmalıdır.
*/
error_reporting(0);
$ini = parse_ini_file("config/config.ini");
//güvenlik için isteğin geleceği sunucu belirlenir.
$ra=$_SERVER['REMOTE_ADDR'];

if($ra!=$ini['server']){
	echo $ini['server']." ? ".$ra." !!!"; exit;
}
$domain=$ini['domain']; //domain
$homedir=$ini['homedir']; //homedir
$username=$_POST['u']; //user
//$username=str_replace("?u=","", $username);
$s=false;
echo $domain." Alanında, ".$username." Kullanıcısı için, ";
if($username==""){ echo "---"; exit;}
else{
	$homedir.=$username;
	echo $homedir." klasörü";
	if(!file_exists($homedir)){
		$s=mkdir($homedir, 0777);  //windows yetkileri es geçer
		if($s){ echo " açıldı."; }else{ echo " açıla<b>MA</b>dı!"; }
	}else{ $s=true;}
	echo " Kullanıcı ";
	if($s){	//permission OI:Object Inherit CI:Container Inherit M:Modify with Delete F:Full access
		$e='icacls '.$homedir.' /t /grant '.$username."@".$domain.':'.$ini['drive_permission'].'(CI)(OI)';
		$s=exec($e);
		if(substr($s, 23, 1)=="1"){ echo "Yetkilendirildi.";}
		else{ echo 'Yetkilendirile<b>ME</b>di!!! '; }
	}
}
?>