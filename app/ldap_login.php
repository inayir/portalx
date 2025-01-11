<?php
function ldap_login($username, $password, $dom, $ldapserver, $dn){
	//error_reporting(0); 
	$zaman=date("d-m-Y H:i:s", strtotime("now"));
	$log="\n $zaman : ".$username;

	//Basic Login verification
	if ($username==""){ echo "Hata: Kullanıcı adı boş olamaz."; exit; }
	$err="+"; 
	$user = strip_tags($username) .'@'. $dom;  //username
	$pass = stripslashes($password);
	$conn = ldap_connect("ldap://". $ldapserver); //LDAP_SERVER
	if ($conn){  //$log.=" conn OK. ";
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
		$bind = @ldap_bind($conn, $user, $pass);
		ldap_get_option($conn, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);
		if (!empty($extended_error)){
			$errno = explode(',', $extended_error);
			$errno = $errno[2];
			$errno = explode(' ', $errno);
			$errno = $errno[2];
			$errno = intval($errno);
			if ($errno == 532){ $err = 'Hata: Giriş yapılamadı: Kullanıcı süresi dolmuş.'; }
			else { $err=$errno." Hata: ".ldap_error($conn); }
		}
		elseif ($bind){
			//determine the LDAP Path from Active Directory details			
			$ldap_result = ldap_search(array($conn, $conn), array($dn,$dn), "(cn=$username*)") or die ("Arama Hatası!");			
			if (!count($ldap_result)) { //Kullanıcı bulunamadıysa
				$err = "Hata:".ldap_error($conn); 
			}
		}else{ $err="Hata: Bağlantı Kurulamadı!";  }
		ldap_close($conn);
	}else{ $err="Hata:".ldap_error($conn);  }
	if($err=="+"){ return true; } else { 
		return $err; //." ldap_login ou:".$ou; 
	}
	/*/
	$dosya="/logs/login.log"; 
	touch($dosya);
	$dosya = fopen($dosya, 'a');
	fwrite($dosya, $log);
	fclose($dosya); //*/
}
?>