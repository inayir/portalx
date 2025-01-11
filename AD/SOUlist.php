<?php
error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
header('Content-Type:text/html; charset=utf8');
include("sess.php");
if($_SESSION["user"]==""){
	echo "login"; exit;
}
include($docroot."/config/config.php");
$base_dn=$ini['base_dn'];
require("ldap.php");

$liste=Array("ou", "description");

$filter="ou=*, ou=DHAD";
//$filter="objectClass=organizationalunit";
$sr=ldap_list($conn, $base_dn, $filter, $liste);
echo "<select id='subou'>";
$info=ldap_get_entries($conn, $sr);
for ($i=0; $i < $info["count"]; $i++){
	$a=$info[$i]["ou"][0];
	if($a[0]!="_"&&$info[$i]["description"][0]!=""){ 
		echo "<option value='".$a."'>";
		echo $info[$i]["description"][0]."</option>\n"; 
	}
}
echo "</select>";
//var_dump($info);
?>