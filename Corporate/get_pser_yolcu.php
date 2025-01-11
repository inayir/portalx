<?php
/*
	User bilgilerini getirir. bg: bilgileri getir.
*/
error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include("../set_mng.php");
include("../sess.php");
if($_SESSION["user"]==""){
	echo "login"; exit;
}//*/
@$ps=$_POST['ps']; 
$log="";
@$json='[{';
$keys=['description','displayname','department','mobile','mail','username'];
$collections = $db->listCollections();
$collectionNames = [];
foreach ($collections as $collect) {
  $collectionNames[] = $collect->getName();
}
$exists = in_array('personel', $collectionNames);
if(!$exists){ 
	$db->createCollection('personel',[
	]);
}
//tumpersonel
@$collection=$db->personel;
@$tpcur = $collection->find(
    [
		'pser_kodu'=>null
    ],
    [
        'limit' => 0,
        'projection' => [
            'description' => 1,
            'displayname' => 1,
			'department' => 1,
			'mobile' => 1,
            'mail' => 1,
			'username'=>1,
        ],
		'sort'=>['pser_kodu'=>1]
    ]
);
foreach ($tpcur as $tpformsatir) {
	$tpsatir=[];
	$tpsatir['description']	=$tpformsatir->description;
	$tpsatir['displayname']	=$tpformsatir->displayname;
	$tpsatir['department']	=$tpformsatir->department;
	$tpsatir['mobile']		=$tpformsatir->mobile;
	$tpsatir['mail']		=$tpformsatir->mail;
	$tpsatir['username']	=$tpformsatir->username;
	$ftpsatir[]=$tpsatir; 
}; 
/*/echo "pers say:".count($ftpsatir)."<br>";//var_dump($ftpsatir); 
$qp="SELECT * FROM personel WHERE pser_kodu='".$ps."' ORDER BY adisoyadi";
$resultp = $baglan->query($qp); 
if($resultp){ //*/
	//if(mysqli_num_rows($resultp)>0){
$say=count($ftpsatir); if($say>50){ $say=50; }
		for($k=0;$k<$say;$k++){
			if($k>0){ $json.='},{'; }
			for($i=0;$i<count($keys);$i++){
				if($i>0){ $json.=', '; }
				$json.='"'.$keys[$i].'":"'.$ftpsatir[$k][$keys[$i]].'"';
			}
		}
	//}
//} else { $json='[{Bulunamadı!}]'; }
$json.='}]';
echo $json;  
/*$log.="\n\n Sorgu:".$qp." : ".$json;
$dosya="get_pser_yolcu.log"; 
	touch($dosya);
	$dosya = fopen($dosya, 'a');
	fwrite($dosya, $log);
	fclose($dosya); 
//*/
?>