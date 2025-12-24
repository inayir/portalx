<?php
function ldap_login($username, $password, $dom, $ldapserver, $dn){
	//error_reporting(0); 
	$zaman=date("d-m-Y H:i:s", strtotime("now"));
	$log="\n $zaman : ".$username;
	//Basic Login verification
	if ($username==""){ echo "Hata: Kullanıcı adı boş olamaz."; exit; }
	$err="+"; 
	$connx = ldap_connect('ldap://'. $ldapserver.":389"); //LDAP_SERVER
	if ($connx){  //$log.=" conn OK. ";
		ldap_set_option($connx, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($connx, LDAP_OPT_REFERRALS, 0);
		$user = strip_tags($username) .'@'. $dom;  //username
		$pass = stripslashes($password);
		$bind = @ldap_bind($connx, $user, $pass);
		/*ldap_get_option($connx, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);
		if (!empty($extended_error)){
			$errno = explode(',', $extended_error);
			$errno = $errno[2];
			$errno = explode(' ', $errno);
			$errno = $errno[2];
			$errno = intval($errno);
			if ($errno == 532){ $err = 'Hata: Giriş yapılamadı: Kullanıcı süresi dolmuş.'; }
			else { $err=$errno." Hata: ".ldap_error($connx); }
		}
		else//*/
		if ($bind){
			//determine the LDAP Path from Active Directory details			
			$ldap_result = ldap_search(array($connx, $connx), array($dn,$dn), "(cn=$username*)") or die ("Arama Hatası!");			
			if (!count($ldap_result)) { //Kullanıcı bulunamadıysa
				$err = "Hata:".ldap_error($connx); 
			}
		}else{ $err="Hata: Bağlantı Kurulamadı!";  }
		ldap_close($connx);
	}else{ $err="Hata:".ldap_error($connx);  }
	if($err=="+"){ return true; } else { 
		return $err; //." ldap_login ou:".$ou; 
	}
}
?>