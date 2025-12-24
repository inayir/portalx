<?php
include("../set_mng.php");
include($docroot."/sess.php");
require($docroot."/ldap.php");

require("ldap_functions.php");
$o_user_dn="CN=Ali Birinci Adam,OU=DestekHizmetleriveGuvenlikMd,OU=DHAD,OU=ASELSANKONYA,DC=aselsankonya,DC=com,DC=tr";

	$data['department']="DestekHizmetleriveGuvenlikMd";
echo "dn:".$o_user_dn."<br>";
//remove all mail groups
$s=RemoveFromGroups($o_user_dn);

echo "s:".$s." ".$msg;

?>