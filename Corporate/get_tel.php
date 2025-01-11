<?php
/*
	MongoDB'den Telefon kayıtlarını getirir. ->personel
	Insensitive arar, küçük harfe çevirmek gerekmez.
	dp=D ise birim adlarında arar, değilse personel displayname içinde arar.
*/
//
error_reporting(0); //$log="";
header('Content-Type: text/html; charset=utf-8');
include("../set_mng.php");
include($docroot."/sess.php");
$liste=Array('displayname','description','mail','title','company','department','telephonenumber','mobile','manager'); 

@$searched=$_POST["sea"];  //
if($searched==""){ @$searched=$_GET["sea"]; }
if($searched==""){
	exit;
} 
@$dp=$_POST["dp"]; if($dp==""){ @$dp=$_GET["dp"]; } if($dp==""){ $dp="P"; }
$seafield='displayname';
if($dp=="D"){
	//$searched=str_replace(' ', '', $searched);
	//$search = array('Ç','ç','Ğ','ğ','ı','İ','Ö','ö','Ş','ş','Ü','ü',' ');
	//$replace = array('c','c','g','g','i','i','o','o','s','s','u','u','-');
	//$dsearch = str_replace($search,$replace,$searched);
	$seafield='department';
}
$sea=new \MongoDB\BSON\Regex(preg_quote($searched), 'i');
@$collection=$db->personel;
$cursor = $collection->aggregate([
	[
        '$match'=>['$and'=>[[$seafield => $sea],['status'=>['$ne'=>'0']]]]
    ],
	['$lookup'=>
		[
		  'from'=>"departments",
		  'localField'=>"department",
		  'foreignField'=>"ou",
		  'as'=>"deps"
		]
	],
	['$lookup'=>
		[
		  'from'=>"departments",
		  'localField'=>"company",
		  'foreignField'=>"ou",
		  'as'=>"comps"
		]
	],
	['$unwind'=>'$deps'],
	['$unwind'=>'$comps'],
	[
       '$addFields'=> [
           'department'=> '$deps.description',
           'company'=> '$comps.description',
           'username'=> '$username',
           'givenname'=> '$givenname',
           'sn'=> '$sn',
           'mail'=> '$mail',
           'description'=> '$description',
           'title'=> '$title',
           'telephonenumber'=> '$telephonenumber',
           'mobile'=> '$mobile',
           'manager'=> '$manager',
           'bgcolor'=> '$bgcolor',
           'color'=> '$color',
           'order'=> '$order',
       ]
    ],
	[
		'$sort'=>['order'=>1,'displayname'=>1]
	]
]);
//var_dump($cursor);
$fsatir=[];
foreach ($cursor as $formsatir) {
	$satir=[];
	//$satir['id']=$formsatir->_id;
	$satir['username']=$formsatir->username;
	$satir['displayname']=$formsatir->displayname;
	$satir['givenname']=$formsatir->givenname;
	$satir['sn']=$formsatir->sn;
	$satir['mail']=$formsatir->mail;
	$satir['title']=$formsatir->title;
	$satir['telephonenumber']=$formsatir->telephonenumber;
	$satir['description']=$formsatir->description;
	$satir['company']=$formsatir->company;
	$satir['department']=$formsatir->department;
	$satir['mobile']=$formsatir->mobile;
	$satir['bgcolor']=$formsatir->bgcolor;
	$satir['color']=$formsatir->color;
	$satir['dp']=$formsatir->dp;
	$satir['order']=$formsatir->order;
	$satir['manager']=$formsatir->manager;
	$fsatir[]=$satir;
}; 
$fisay=count($fsatir); 
if($fisay>0){
	$json='['; $s=0;
	//önce yöneticiler listelenmesi?
	for($yi=0; $yi<$fisay; $yi++){	
	}
	//tüm kullanıcılar
	for($i=0; $i<$fisay; $i++){	
		$js1="";	
		if ((!empty($fsatir[$i]['mobile']))&&(str_contains($fsatir[$i]['displayname'],$ini['disabledname']))==false){ 					
			if($s>0){ $json.=","; }	
			$js1='{';
			for($ii=0; $ii<count($liste); $ii++){
				if($ii>0){ $js1.=','; }
				$tag=$liste[$ii];
				$js1.='"'.$tag.'":"'.$fsatir[$i][$tag].'"';
			}
			$js1.='}';
			$s++;
		}
		if($js1!=""){ $json.=$js1; }//*/
	}
	$json.=']';
}else{ $json='!';}
//}else{ $json='[{"displayname":"'.$gtext['notfound'].'"}]';} /*Bulunamadı*/
echo $json;  
/*/$log.="\n".$json; 
$dosya="get_tel.log"; 
	touch($dosya);
	$dosya = fopen($dosya, 'a');
	fwrite($dosya, $log);
	fclose($dosya); //*/
?>