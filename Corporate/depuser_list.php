<?php
/*
	Assign manager to department
*/
include('../set_mng.php');
//error_reporting(0);
include($docroot."/sess.php");
@$collectiondep=$db->personel;
  
$department="BilgiTeknolojileriMd"; 
echo "Department:".$department."<br><br>";
//	
$or1=[['department'=>$department],['company'=>$department]];
@$cursordep = $collectiondep->find(
	[
		'$or'=>$or1
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort'=>['displayname'=>1]
	]
);
var_dump($cursordep);
foreach($cursordep as $fieldsat){
	echo "<br>Personel:".$fieldsat->username." ".$fieldsat->displayname." Department:".$fieldsat->department;
}

?>