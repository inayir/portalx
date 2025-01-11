<?php
/*
	User bilgilerini getirir. bg: bilgileri getir.
*/
include('../set_mng.php');
//
error_reporting(0);
include($docroot."/config/config.php");
include($docroot."/sess.php");
include("../sess.php");
if($_SESSION["user"]==""){
	header('Location: /login.php');
}//*/
$ps=$_POST['ps'];
$log="";
@$json='[{';
$keys=['pser_uid','pser_kodu','pser_tanim','pser_bolge','pser_plaka','pser_sofor','pser_sofor_tel','pser_sorumlu','pser_sorumlu_tel','pser_firma','pser_kapasite', 'aktif'];
/*$q="SELECT * FROM pservis WHERE aktif=1 AND pser_uid=".$ps; //echo $q;
$result = $baglan->query($q);
if($result){ 
	if(mysqli_num_rows($result)>0){
		$row=mysqli_fetch_array($result);
		for($i=0;$i<count($keys);$i++){
			if($i>0){ $json.=', '; }
			$json.='"'.$keys[$i].'" : "'.$row[$keys[$i]].'"';
		}
	}
} else { $json='[{Bulunamadı!}]'; }
//*/
@$collection=$db->personel_prop;

@$cursor = $collection->find(
    [
		'$or'=>[['aktif'=>'1'],['aktif'=>$aktif]]
    ],
    [
        'limit' => 0,
        'projection' => [
            'pser_kodu' => 1,
            'pser_tanim' => 1,
			'pser_bolge' => 1,
			'pser_sofor' => 1,
            'pser_sofor_tel' => 1,
			'pser_firma' => 1,
			'pser_plaka' => 1,
			'pser_kapasite' => 1,
			'pser_sorumlu' => 1,
            'pser_sorumlu_tel' => 1,
			'pser_kmz' => 1,
			'olusturan' => 1,
			'gtar' => 1,
			'son_deg_per' => 1,
			'son_deg_tar' => 1,
			'aktif' => 1,
        ],
    ],
	[
		'sort'=>['pser_kodu'=>1]
	]
);
//var_dump($cursor);//*/
$fsatir=Array();
foreach ($cursor as $formsatir) {
	$satir=[];
	$satir['pser_kodu']		=$formsatir->pser_kodu;
	$satir['pser_tanim']	=$formsatir->pser_tanim;
	$satir['pser_bolge']	=$formsatir->pser_bolge;
	$satir['pser_sofor']	=$formsatir->pser_sofor;
	$satir['pser_sofor_tel']=$formsatir->pser_sofor_tel;
	$satir['pser_firma']	=$formsatir->pser_firma;
	$satir['pser_plaka']	=$formsatir->pser_plaka;
	$satir['pser_kapasite']	=$formsatir->pser_kapasite;
	$satir['pser_sorumlu']	=$formsatir->pser_sorumlu;
	$satir['pser_sorumlu_tel']=$formsatir->pser_sorumlu_tel;
	$satir['pser_kmz']		=$formsatir->pser_kmz;
	$satir['olusturan']		=$formsatir->olusturan;
	$satir['gtar']			=$formsatir->gtar;//->toDateTime()->format($ini['date_local']);
	$satir['son_deg_per']	=$formsatir->son_deg_per;
	$satir['son_deg_tar']	=$formsatir->son_deg_tar;//->toDateTime()->format($ini['date_local']);
	$satir['aktif']			=$formsatir->aktif;//*/
	$fsatir[]=$satir;
	//var_dump($satir); echo "<br>";
}; 
$fisay=count($fsatir); //echo "fisay:".$fisay."<br>"; //for($ii=0; $ii<$fisay-1;$ii++){ }///exit;//*/
//var_dump($fsatir[0]); 
$fsatir=Array();
foreach ($cursor as $formsatir) {
	if(mysqli_num_rows($result)>0){
		$row=mysqli_fetch_array($result);
		for($i=0;$i<count($keys);$i++){
			if($i>0){ $json.=', '; }
			$json.='"'.$keys[$i].'" : "'.$row[$keys[$i]].'"';
		}
	}
	$fsatir[]=$formsatir; 
	//var_dump($formsatir); echo "<br>";
}
var_dump($fsatir[0]);
$json.='}]';
echo $json;  
/*$log.="\n".$json;
$dosya="user_infos.log"; 
	touch($dosya);
	$dosya = fopen($dosya, 'a');
	fwrite($dosya, $log);
	fclose($dosya); 
//*/
?>