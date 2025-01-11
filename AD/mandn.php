<?php
include('../set_mng.php');
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/sess.php");
echo "Manager Dn getirilir.<br>";
$base_dn=$ini['base_dn'];
include($docroot.'/ldap.php');

$m="Murat Aygan"; 
$manresult = ldap_search($conn, $ini['dom_dn'], "displayname=$m", Array("username", "distinguishedname"));
$manentries = ldap_get_entries($conn, $manresult);
$dm=$manentries[0]['distinguishedname'][0];
echo "<br>	 Manager dn: ".$dm; //CN şekli bulunup getirilir... managerin distinguishedname

?>