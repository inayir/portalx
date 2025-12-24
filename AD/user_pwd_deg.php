<?php
/*
	LDAP'taki Password değiştirme rutinidir.
*/
function ldapspecialchars($string) {
    $sanitized=array('\\' => '\5c',
                     '*' => '\2a',
                     '(' => '\28',
                     ')' => '\29',
                     "\x00" => '\00');

    return str_replace(array_keys($sanitized),array_values($sanitized),$string);
}
//error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
header('Content-Type: text/html; charset=utf-8');
include($docroot."/config/config.php");
@$log=""; 
$ldap_server=$ini['ldap_server'];
$ldap_server1='KONDUNYA01.aselsankonya.com.tr';
define('LDAP_SERVER', $ldap_server1);
$conn=ldap_connect('ldap://'.LDAP_SERVER); 
if($conn){ // OK
	$domuser=$ini['domshort'].'\\dadm_ipisirici'; //.$ini['una'];
	$dompass='Rbss2017..'; //$ini['upw']);
	ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($conn, LDAP_OPT_REFERRALS,0);
	$bind=ldap_bind($conn, $domuser, $dompass); 
	if($bind){ echo "Binded";}else{ echo "------------No BIND!----------"; echo "User: ".$domuser." ".$dompass;}
}else{ echo "-99"; /*nok*/  }
//*********önce ou lar getirilir...
$dn=$ini['dom_dn']; //echo $dn;
@$search=ldapspecialchars($_POST["u"]);  //
if($search==""){ @$search=$_GET["u"]; }  //echo "<br>Aranan: "
@$msg="Hesap Bulunamadı! ".$search;
@$newpass=$_POST["p"];  //
if($newpass==""){ @$newpass=$_GET["p"]; }
$opass=$_GET['o']; //önceki
echo "<br>u:".$search." o:".$opass; //." p:".$newpass;
//
$log=date("Y-m-d H:i:s", strtotime("now"));
//echo "<br>";
$domsearch=$ini['domshort'].'\\'.$search; 
$sonuc1=ldap_bind($conn, $domsearch, $opass); 
if(!$sonuc1){ echo $gtext['reqprerequsity']."...";/*"Yetki Gerektirir..."*/ exit;}
//$bind=ldap_bind($conn, $domuser, $dompass); 
//if($bind){ echo "Re-Binded";}else{ echo "------------No Re-BIND!----------"; }
				
$liste=Array('samaccountname','givenname','sn','mail','description','title','mobile','company','department','manager','telephonenumber','distinguishedname'); 
$filter = '(|(objectCategory=person)(objectCategory=contact))';
//echo "<br>Arama:";
$ldap_search_result = ldap_search($conn, $dn, $filter, $liste);  //
if ($ldap_search_result){ //echo " ldap_search Başarılı<br><br>";
	//echo $gtext['OK']; //"Tamam";
	$info = ldap_get_entries($conn, $ldap_search_result); 
	//echo "<br>info count:".$info['count']."<br>";
	
	if($info["count"]>0){ //user var-> değiştirilir.
		//echo ".";
	  for($i=0; $i<$info['count']; $i++){ 
		if($info[$i]['samaccountname'][0]==$search){
			//echo "->Aranan Kişi:".$search; //."?=".$info[$i]['samaccountname'][0];
			$udn=$info[$i]['distinguishedname'][0]; //echo "<br>Udn: ".$udn;
			//
			$domsearch=$ini['domshort'].'\\'.$search; 
			//echo "<br>Şifre yenileme öncesi eski şifre Kontrol: ".$domusr." / ".$opass; 
			//$osonuc=ldap_bind($conn, $domusr, $opass); //echo $osonuc;
			//if($osonuc){ echo "->OK"; }else{ echo "->False."; }
			//$bind=ldap_bind($conn, $domuser, $dompass); //yetkili kullanıcı
			//$newpass = mb_convert_encoding('"'.$newpass.'"', 'utf-16le'); 
			$unicodePwd = iconv("UTF-8", "UTF-16LE", $newpass);
			$hashpass = "{SHA}" . base64_encode( pack("H*", sha1( $unicodePwd)));
			$userinfo["userPassword"][0]= $hashpass; //hep boş.....
			$userinfo["unicodePwd"][0]= $hashpass; //
			$userinfo["pwdLastSet"][0]= 0; 
			$userinfo['useraccountcontrol'][0]=544;
			$sonuc=ldap_modify($conn, $udn, $userinfo);
			if($sonuc){ 
				//echo "<br>Şifre Yenilendi.<br>Yeni Şifre:".$unicodePwd;
				$msg=$gtext['u_passchanged']; $log.=$gtext['u_passchanged'];
				ldap_close($conn);
				/*$conn=ldap_connect('ldap://'.LDAP_SERVER);
				$domsearch=$ini['domshort'].'\\'.$search; 
				$psonuc=ldap_bind($conn, $domsearch, $opass);
				if($psonuc){ echo " -Yeni şifre onaylandı"; }
				else{ 
					$msg="<br>***Şifre DeğiştirileMEdi!*** : ";
					$error = ldap_error($conn);
					$errno = ldap_errno($conn);
					$msg.=$error." ".$errno;
				}//*/
			}else{ 
				$msg="<br>".$gtext['u_passnotchanged']; $log.=$gtext['u_passnotchanged'];
				$error = ldap_error($conn);
				$errno = ldap_errno($conn);
				$msg.=$error." ".$errno;
			}
		}	
	  }
	} else {  }
} else{
	$msg=$gtext['notfound']; /*"Olumsuz.";*/
	$log.=$gtext['notfound'];
}
echo $msg;
//
$dosya=$docroot."/logs/personel.log"; 
touch($dosya);
$dosya = fopen($dosya, 'a');
fwrite($dosya, $log."\n");
fclose($dosya); //*/
?>