<?php
/*
	Form: Formun kaydedilmesi... 
*/
error_reporting(E_ALL);
include('../set_mng.php');
include($docroot."/config/config.php");
include($docroot."/sess.php");
//var_dump($_POST); //exit;
@$form=$_POST['form']; //key: form -> "YFR-040"; 
if($form==''){ echo "Form kodu boş olamaz!"; exit; }
$formdat=[];
$formdat['form']=$form; 
$formdat['type']="desform"; 
$formdat['tanimi']=$_POST['tanimi']; 
$formdat['category']=$_POST['category'];
$formdat['datakeydoc']=$_POST['datakeydoc'];  
$formdat['datakey']=$_POST['datakey']; 
$formdat['formsecure']=$_POST['formsecure']; 
$formdat['onay']=$_POST['onay']; 
$formdat['kontrol']=$_POST['kontrol'];
$formdat['orientation']=$_POST['orientation'];
$formdat['formdate']=$_POST['formdate'];
$formdat['state']=$_POST['state'];
$formdat['ack']=$_POST['ack'];
//$_POST ile gelen alan sayısı bulunur. sabitler çıkarılır, field sayısı bulunur.
$postcount=count($_POST)-13; //echo "psay: ".$psay." ";
//fieldlar düzgün sıra ile gelmezse bile okunur, filed_n düzeltilir.
$fsira=1;
$formfields=[];
for($f=1;$f<($postcount/7);$f++){ 
	$alan='field_'.$f; //
	echo "<br>Alan ".$f.". ".$alan.": "; echo $_POST[$alan.'_name'];
	if(isset($_POST[$alan.'_name'])){ 
		//field bilgileri... 
		$fs='field_'.$fsira; //sıra girilen sıraya göre yenilenmiş olur...$fs='field_'.$fsira; 
		$field_satir=[];
		$field_satir['name']			= $_POST[$alan.'_name'];
		$field_satir['label']			= $_POST[$alan.'_label'];
		$field_satir['type']			= $_POST[$alan.'_type'];
		$field_satir['ack']				= $_POST[$alan.'_ack'];
		$field_satir['mandatory']		= $_POST[$alan.'_mandatory'];
		$field_satir['default_value']	= $_POST[$alan.'_default_value'];
		if(isset($_POST[$alan.'_fromtable'])){ 
			$field_satir['fromtable']	= $_POST[$alan.'_fromtable']; 
		}
		if(isset($_POST[$alan.'_fromfield'])){ 
			$field_satir['fromfield']	= $_POST[$alan.'_fromfield']; 
		}
		$field_satir['col']			= $_POST[$alan.'_col'];
		//options type: field_x_type  
		$tip=$_POST[$alan.'_type']; 
		if($tip=='select'||$tip=='radio'){ //
			//options count?
			$field_satirops=[]; $oo=1;
			for($o=1; $o<100;$o++){ 
				if(isset($_POST[$alan.'_s'.$oo])){
					//echo $alan.'_s'.$oo.' : '.$_POST[$alan.'_s'.$oo];
					$field_satirops[$alan.'_s'.$oo.'label']=$_POST[$alan.'_s'.$oo.'label'];
					$field_satirops[$alan.'_s'.$oo]=$_POST[$alan.'_s'.$oo];
					if($alan.'_s'.$oo==$_POST['o_field_'.$f.'_s']){ //if checked ex:field_7_s1
						$field_satir['default_value']=$_POST[$alan.'_s'.$oo]; 
					}
					$oo++;
					//echo '<br>';
				}	
			}
			$field_satir['options']=$field_satirops;			
		}
		$formfields[$fs]=$field_satir;
		$fsira++;//*/
	}
}

$formdat['fields']=$formfields;
//echo "<br>"; var_dump($formdat); //exit;
//*/
$coll=$db->Forms; //$filter = ["form"=>$form];     $options = [];
//if there is an old record, getting...
$cursor = $coll->findOne(
	[
		'form'=>$form,
	],
	[
		'limit' => 1,
		'projection' => [
		]
	]
);
if(isset($cursor)){	//getting record
	$ksay=count($cursor); 
}
if($ksay>0){//update
	//fields önce silinir...	
	@$acursor = $coll->updateOne(
		[
			'form'=>$form
		],
		[ '$unset' => [ 'fields'=>"" ]]
	);	
	@$acursor = $coll->updateOne(
		[
			'form'=>$form
		],
		[ '$set' => $formdat ]
	);	//*/
	if($acursor->getModifiedCount()>0){ echo "Modified:".$form; }
}else{//insert
	//echo "insert"; exit;
	@$acursor = $coll->insertOne(
		$formdat
	);
	if($acursor->getInsertedCount()>0){ echo "Inserted:".$form; }
}
//file upload
//if (move_uploaded_file($_FILES['dyol']['tmp_name'], "..".$yuklenecek_dosya)){}
?>