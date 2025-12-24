<!DOCTYPE html>
<html lang="tr">
<?php
/*
	Prints a form
*/
function get_mongodata($t, $f, $o){
	global $mongoconn;
	$client = new MongoDB\Driver\Manager($mongoconn);	
	$sorgu = new MongoDB\Driver\Query($f, $o);
	return $client->executeQuery($t, $sorgu);
}
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
$ini = parse_ini_file("../config/config.ini.php");
include("../sess.php");
$mongoconn=$ini['MongoConnection'];
$simdi=date($ini['date_local']." H:i", strtotime("now"));
//$form_name=$_GET['f']; //girilmiş bir form getirilecektir. Örnek:: "YFR-040";
//if($form_name==''){ echo "Belirsiz Form!"; exit; }
@$key1=$_GET['key1']; //birinci key. genel olarak _id kullanılacaktır. 
//önce FormData kaydı getirilir
$ddocument=$ini['MongoDB'].'.FormData';
$fdatafilter['_id']=new MongoDB\BSON\ObjectID($key1);  
$doptions = [];
$fdatasonuc=get_mongodata($ddocument, $fdatafilter, $doptions);
foreach ($fdatasonuc as $fdatasatir){} 
$form_name=$fdatasatir->form; 

$fdatasatirlar = json_decode(json_encode ( $fdatasatir ) , true);
$fdatasatirsay=count($fdatasatirlar); 

	//Form kaydı getirilir........................
	$document=$ini['MongoDB'].'.Forms';
	$filter = ["form"=>$form_name];     
	$options = [];
	$formsonuc=get_mongodata($document, $filter, $options);
	foreach ($formsonuc as $formsatir){ } 
	if($formsatir->form_secure=='1'&&$user==""){ //header('Location: /login.php');
		}
	$formsatirlar = json_decode(json_encode ( $formsatir ) , true);
	$formsatirsay=count($formsatirlar);  //echo "formsatirsay:".$formsatirsay;

	$tumfields=$formsatir->fields;
	$tf = json_decode(json_encode ( $tumfields ) , true);
	$tfsay=count($tf); //echo "field sayısı:".$tfsay;

//Yazım başladı. ?>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Lilo">
	<title><?php echo $gtext['s_formprint']." ".$form; /*Form Yazdırma*/?></title>
	<!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.css" rel="stylesheet">
<style>
.cerceve{
	border-style: solid; 
	border-width: 1px;
}
</style>
</head>
<body>
	<table align="center">
	<tr>
		<td style="background-color: #000000;"><img src="<?php echo $ini['sidebar_logo']; ?>" width="200" alt="Logo"></td>
		<td colspan="3"><b><div style="text-align: center;"><?php echo $formsatir->form." ".$formsatir->tanimi; ?></div></b></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
	</tr>
<?php
for($fss=1; $fss<$fdatasatirsay;$fss++){
	$gelecekfield=""; $gelen_data="";
	$fs='field_'.$fss;
	$tip=$formsatir->fields->$fs->type;
	if(property_exists($formsatir->fields->$fs, 'name')){	$gelecekfield=$formsatir->fields->$fs->name; }
	if($scol==1){ echo "
	<tr>\n"; }
	try {
		$f1=$fss+1; $fs1='field_'.$f1; $scol=$formsatir->fields->$fs1->col;
		$gelen_data=$fdatasatir->$gelecekfield;
		if($tip=='date'){
			$gelen_data=date($ini['date_local'], strtotime($gelen_data));
		}
		if($tip=='datetime'){
			$gelen_data=date($ini['date_local']." H:i", strtotime($gelen_data));
		}
		if($tip=='radio'||$tip=='checkbox'||$tip=='select'){
			$ops=$formsatir->fields->$fs->options;
			$op = json_decode(json_encode ( $ops ) , true);
			$opsay=count($op)/2;
			for($o=1; $o<=$opsay; $o++){
				$so='s'.$o; $solabel='s'.$o.'label';
				if($gelen_data==$formsatir->fields->$fs->options->$so){
				$gelen_data=$formsatir->fields->$fs->options->$solabel; }
			}
		}
		echo "<td class='cerceve'>".$formsatir->fields->$fs->label."</td>";
		echo "<td class='cerceve'"; 
		if($scol==1){ echo " colspan='3'";} 
		echo ">";
		echo "<b>".$gelen_data."</b></td>\n";
	}catch(Exception $e){
			
	}
	if($scol==1){ echo "
	</tr>\n"; }
}

?>
	</table>
	<!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../vendor/form-master/dist/jquery.form.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="form_functions.js"></script>
</body>
</html>