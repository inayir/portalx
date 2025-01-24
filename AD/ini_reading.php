<?php

$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
include('../sess.php');
$username="abidik1";
$department="BilgiTeknolojileriMd";
$company="DHAD";
$groups_point=$ini['groups_point'];  //as Custom
//echo "<br>Groups Point:".$groups_point;
$grsi=$ini['group']; //gruplar alındı.
$grs=explode(',', $grsi);
/*echo "<br>Grup sayısı:".count($grs);
for($i=0;$i<count($grs); $i++){ 
	echo "<br>".$i.":".$grs[$i];
}//*/
require("./ldap_functions.php");

//echo "<br>--------";
for($i=0;$i<count($grs); $i++){ 
	$bul=strpos($grs[$i],"{"); 
	if($bul!=""){ 
		$par=substr($grs[$i], strpos($grs[$i],"{")+1, strpos($grs[$i],"}")-1);
		//if(isset($_POST[$par])){
			$deg=$department; //$_POST[$par]; //birimin adı, string içinde değiştirilecek. department ise hangi department ise.
			$gr=str_replace("{".$par."}", $deg, $grs[$i]);
			//yer
			$yer=$groups_point;
			if($par=="department"){
				$yer.=",OU=".$department;
			}
			$yer.=",OU=".$company;
		//}
	} else {
		$gr=$grs[$i];
		$yer=$groups_point;
	}
	echo "<br>".$gr.": ".group_user_add_remove($yer,$gr,$username);					
} //*/
?>