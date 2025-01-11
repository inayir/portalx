<?php
/*
	LDAP'tan telefon kayıtlarını getirir.
*/
function array_orderby(){
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}
function searchSubArray(Array $array, $key, $value) {   
    foreach ($array as $subarray){  
        if (isset($subarray[$key]) && $subarray[$key] == $value)
          return $subarray;       
    } 
}
///***********************************************************
error_reporting(0); //$log="";
header('Content-Type: text/html; charset=utf-8');
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php"); 
//echo "PB Kaynağı:".$ini['pbsource']; exit;
include($docroot."/sess.php");
define('LDAP_SERVER', $ini['ldap_server']);  
$conn=ldap_connect('ldap://'.LDAP_SERVER); 
if($conn){ // OK
	$domuser=$ini['domshort'].'\\'.$ini['una'];
	ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($conn, LDAP_OPT_REFERRALS,0);
	$bind=ldap_bind($conn, $domuser, $ini['upw']); 
	//if($bind){ echo "Binded";}else{ echo "------------No BIND!----------"; echo "User: ".$_SESSION['user']." ".$_SESSION['pass'];}
}else{ echo "-99"; /*nok*/  }
//*********önce ou lar getirilir...
$base_dn=$ini['base_dn']; //echo $base_dn;
$ouliste=Array("ou", "description");
$oufilter="ou=*"; //$filter="objectClass=organizationalunit";
$ousr=ldap_list($conn, $base_dn, $oufilter, $ouliste);
$ouarr=array(); $souarr=Array();
$ou=ldap_get_entries($conn, $ousr); 
for ($i=0; $i < $ou["count"]; $i++){ $m="";
	$m=$ou[$i]["description"][0]; //Dir desc 	
	if($m!=""){ 
		$ouarr[]=array($ou[$i]["ou"][0],$m); 
		//*********sub oular aranır...
		$soufilter="ou=*".$ou[$i]["ou"][0]; //$filter="objectClass=organizationalunit";
		$sbase_dn="OU=".$ou[$i]["ou"][0].",".$base_dn; //echo "<br>".$sbase_dn;
		$sousr=ldap_list($conn, $sbase_dn, $oufilter, $ouliste);
		$sou=ldap_get_entries($conn, $sousr); 
		for ($si=0; $si < $sou["count"]; $si++){
			$sm=$sou[$si]["description"][0];  //Md desc
			if($sm!=""){ $ouarr[]=array($sou[$si]["ou"][0],$sm); } //echo "<br>".$si."  ->  ".$sm; }
		}
	}
}
for($vi=0;$vi<count($ouarr);$vi++){
	//$log.="\n ".$vi." ".$ouarr[$vi][0]."->".$ouarr[$vi][1];
}
//personel getirilir..............
$dn=$ini['dom_dn'];
$vd=0;
@$searched=$_POST["sea"];  //
if($searched==""){ @$searched=$_GET["sea"]; }
if($searched==""){
	exit;
} 
@$dp=$_POST["dp"]; if($dp==""){ $dp="P"; }
$searched=strtolower($searched); 
if($dp=="D"){
	$searched=str_replace(' ', '', $searched);
	$search  = array('Ç','ç','Ğ','ğ','ı','İ','Ö','ö','Ş','ş','Ü','ü',' ');
	$replace = array('c','c','g','g','i','i','o','o','s','s','u','u','-');
	$dsearch = str_replace($search,$replace,$searched);
}
$liste=Array('samaccountname','displayname','givenname','sn','mail','description','title','mobile','company','department','manager','telephonenumber'); //,'physicaldeliveryofficename');
$filter = '(|(objectCategory=person)(objectCategory=contact))';
$ldap_search_result = ldap_search($conn, $dn, $filter, $liste);  //
if ($ldap_search_result){
	$entries = ldap_get_entries($conn, $ldap_search_result); //var_dump($entries);
	//$sorted = array_orderby($entries, 'order', SORT_ASC, 'displayname', SORT_ASC);	
	$sorted = array_orderby($entries, 'displayname', SORT_ASC);	
	$json='[';
	$s=0;
	for($i=0; $i<$sorted['count']; $i++){	
		$js1="";	//(!empty($sorted[$i]['mobile'][0]))&&  telefonu olmayanları göstermiyordu
		if ((str_contains($sorted[$i]['givenname'][0],$ini['disabledname'])==false)&&((str_contains($sorted[$i]['title'][0],'Phone')==false)||(str_contains($sorted[$i]['title'][0],'')==false))){	
			//aranan searched içindeki metin name ile karşılaştırılacak. strtolowersız olmadı.
			$r=false;
			if($dp=="P"){ 
				$name=strtolower($sorted[$i]['givenname'][0]." ".$sorted[$i]['sn'][0]); 
				$r=str_contains($name,$searched); 
			} else { 
				$dep=strtolower(trim($sorted[$i]['department'][0]," ")); 
				$r=str_contains($dep,$dsearch); 
			}
			if($r!=false){ //bulunduysa eklenir.
				if($s>0){ $json.=","; }	
				$js1='{';
				for($ii=0; $ii<count($liste); $ii++){
					if($ii>0){ $js1.=','; }
					$tag=$liste[$ii];
					if($tag=='company'||$tag=='department'){ 
						$arr=searchSubArray($ouarr, 0, $sorted[$i][$tag][0]);
						//$log.="\nBulunan OU:".$arr[0]." ".$arr[1];
						$js1.='"'.$tag.'":"'.$arr[1].'"';
					}else{
						$js1.='"'.$tag.'":"'.$sorted[$i][$tag][0].'"';
					}
				}
				$js1.='}';
				$s++;
			}
		}
		if($js1!=""){ $json.=$js1; }
	}
	$json.=']';
}else{ $json='[{"givenname":"Giriş", "sn":"Bulunamadı"}]';}
echo $json;  
/*/$log.="\n".$json; 
$dosya="get_user_tel_list.log"; 
	touch($dosya);
	$dosya = fopen($dosya, 'a');
	fwrite($dosya, $log);
	fclose($dosya); //*/
?>