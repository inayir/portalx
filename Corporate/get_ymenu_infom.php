<?php
/*
	Yemek menüsünü getirir. st: son tarihi getir.  ym: yemek menüsü getir.
*/
include('../set_mng.php');
//error_reporting(0);
header('Content-Type:text/html; charset=utf8');
include($docroot."/config/config.php");
include("../sess.php"); 
@$collection=$db->ymenu; 
//Son girilen tarihi getirir, bir sonrası tarih olarak verilir.
if($_POST['st']=='st'||$_GET['st']=='st'){ //son tarihten bir sonraki güne kayıt açmak...
	try{
		/*@$bul = $collection->findOne(
			[
				'ym_tarih'=>[$gte=>'2023-12-31T00:00:00.000+00:00']
			],
			[
				'limit'=>1
			]
		);//*/
		$bul = $collection->aggregate(
			[],
			[
				'ym_tarih'=>['$gte'=>'2023-12-31T00:00:00.000+00:00']
			],
			[
				'$sort'=>['ym_tarih'=>-1]
			]
		);//*/
		$fsatir=[];
		foreach ($bul as $sat) {
			$fsatir[]=$sat;
		}
		$key_values = array_column($fsatir, 'ym_tarih'); 
		array_multisort($key_values, SORT_DESC, $fsatir);
		//echo "say: ".count($fsatir);
		$st=$fsatir[0]['ym_tarih']->toDateTime()->format($ini['date_local']);
		$st=date($ini['date_local'], strtotime($st." +1 day")); 
		$g=date("w", strtotime($st));
		if($g==6&&$ini['menu_gun6']==0){ $st=date($ini['date_local'], strtotime($st.'+3 day')); }
		if($g==0){ $st=date($ini['date_local'], strtotime($st.'+2 day')); }		
		echo $st;
	}catch(Exception $e){
		
	}
	exit;
}
$ym_tarih=$_POST['ym']; //girilmiş bir yemek menüsü getirilir... 
if($ym_tarih==""){ $ym_tarih=$_GET['ym']; } 


$date1=date("Y-m-d H:i:s", strtotime($ym_tarih));
$date1 = new \MongoDB\BSON\UTCDateTime(strtotime($date1)*1000);

@$cursor = $collection->findOne(
    [
        'ym_tarih' => ['$gte'=>$date1]
    ],
    [
        'limit' => 1,
        'projection' => [
            'ym_tarih' => 1,
            'ym_gun' => 1,
            'k1' => 1,'k2' => 1,'k3' => 1,
            'o1' => 1,'o2' => 1,'o3' => 1,'o4' => 1,'o5' => 1,
            'a1' => 1,'a2' => 1,'a3' => 1,'a4' => 1,'a5' => 1,
			'aktif' => 1,'olusturan' => 1,'gtar' => 1,'son_deg_per' => 1,'son_deg_tar' => 1,
			'_id'
        ],
    ],
	[
		'sort'=>['ym_tarih'=>1]
	]
);//*/
$log="";
@$json='[';
$keys=['ym_tarih','ym_gun','k1','k2','k3','o1','o2','o3','o4','o5','a1','a2','a3','a4','a5','aktif','olusturan','gtar','son_deg_per','son_deg_tar','_id'];
if($cursor->_id!=""){
	for($i=0;$i<count($keys);$i++){
		if($i>0){ $json.=', '; }
		$k=$keys[$i]; 
		if($k=='son_deg_tar'&&$cursor->$k==NULL){ $v=''; }
		elseif($k=='ym_tarih'||$k=='gtar'){ 
			$v=$cursor->$k->toDateTime()->format($ini['date_local']); 
		}
		else{ $v=$cursor->$k; }
	$json.='{"'.$k.'":"'.$v.'"}';
	}
}else{ $json.='"durum":"Bulunamadı!"'; }
 
$json.=']';
echo $json;  
/*$log.="\n".$json;
$dosya="get_ymenu_info.log"; 
	touch($dosya);
	$dosya = fopen($dosya, 'a');
	fwrite($dosya, $log);
	fclose($dosya); 
//*/
?>