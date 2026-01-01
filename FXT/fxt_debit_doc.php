<?php
/*get chosen fixtures to debit document for sign */
include("../set_mng.php");
//error_reporting(0);
include($docroot."/sess.php");
include($docroot."/app/php_functions.php");
if($user==""){
	//echo "login"; exit;
}
@$username=$_SESSION['user'];
$now=date("Y-m-d H:i:s", strtotime("now"));
@$log=$now.";";  $vars=[];
$vars['form']   =$_POST['form'];   //$vars['form']='YFR-101';fxt
$username		=$_POST['username'];  		//$username='inayir';
$doc_side		=$_POST['doc_side']; 	//$doc_side='to';
$fixcodes		=[];
foreach($_POST as $input => $value){ $y=-1;
	$y=strpos($input, 'fxt_');
	if($y!=''&&$y==0){
		$fixcodes[]=$value;
	}
}
$keys=['code','type','description','serialnumber','place','debitdate'];
//Personel
$percol=$db->personel;
$percursor=$percol->findOne(
	[
		'username' =>$username
	],
	[
		'limit' => 0,
		'projection' => [
			'displayname'=>1,
			'description'=>1,
			'title'=>1,
			'department'=>1,
		],
	],
);
if($percursor){
	$ftsatir=[];
	$displayname	=$percursor->displayname;
	$title			=$percursor->title;
	$description	=$percursor->description;
	$department		=$percursor->department;
}
//Fixture_types
$tcol=$db->Fixture_types;
$tcursor=$tcol->find(
	[
		'code'=>['$ne'=>null]
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort' => [
			'type'=>1,
		],
	],
);
if($tcursor){
	$ftsatir=[];
	foreach ($tcursor as $tformsatir) {
		$tsatir=[];
		$tsatir['code']	=$tformsatir->code;
		$tsatir['type']	=$tformsatir->type;
		$ftsatir[]=$tsatir;
	}
}
//place
$pcol=$db->Places;
$pcursor=$pcol->find(
	[
		'state'=>['$ne'=>null]
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort' => [
			'code'=>1,
			'description'=>1,
		],
	],
);
if($pcursor){
	$fpsatir=[];
	foreach ($pcursor as $pformsatir) {
		$psatir['code']			=$pformsatir->code;
		$psatir['description']	=$pformsatir->description;
		$fpsatir[]=$psatir;
	}
}
//
@$collection=$db->Fixtures;
$cursor = $collection->find(
	[
		'code'=>[ '$in'=>$fixcodes ]
	],
	[
		'limit' => 0,
		'projection' => [
		],
	]
);
//
$html='<html>
<head>
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div style="text-align:center;">'.$displayname.' '.$gtext['fixtures'].'</div><br>
<center>
<table>';
	$thead='<tr>';
for($k=0;$k<count($keys);$k++){
	$thead.='<td style="font-weight: Bold;text-align:center; border-width: 1px; border-style: solid">'.$gtext[$keys[$k]].'</td>';
}
	$thead.='</tr>';
	$html.=$thead;
foreach ($cursor as $formsatir) {
	$html.='<tr>';
	for($k=0;$k<count($keys);$k++){
		$key=$keys[$k];
		$value=$formsatir->$key;
		if($key=='type'){
			$ti=array_search($value, array_column($ftsatir, 'code'));
			if($ti!=false||$ti!=''){ $value=$ftsatir[$ti]['type']; }
		}
		if($key=='place'){
			$pi=array_search($value, array_column($fpsatir, 'code'));
			if($pi!=false||$pi!=''){ $value=$fpsatir[$pi]['description']; }
		}
		if($key=='debitdate'&&$value!=''){
			$value=mdatetodate($value);
			$value=date($ini['date_local'], strtotime($value));
		}
		//
		$html.='<td style="border-width: 1px; border-style: solid">'.$value.'</td>';
	}
	$html.='</tr>';
}
$html.=$thead;
$html.='</table>';
//
$html.='<br><br>
<div>';
$html.='<span style="width: 10%; height: 100px; float: left;">';
$html.='<p>Teslim Eden:</p>';
$html.='<p>';	
if($doc_side=='to'){ $html.=$displayname;} else{ $html.='........................................'; }
$html.='<br><br>'.$gtext['sign'].'........................................';
$html.='</p>';
$html.='</span>';
//
$html.='<span style="width: 10%; height: 100px;">';
$html.='<p>Teslim Alan:</p>';
$html.='<p>';
if($doc_side=='from'){ $html.=$displayname;} else{ $html.='........................................'; }
$html.='<br><br>'.$gtext['sign'].'........................................';
$html.='</p>';
$html.='</span>';
$html.='</div>
</center>';
$html.='</body></html>';
//echo $html; exit;
//
ob_end_clean();
$filename="Fxtdoc_".date("ymdHis", strtotime("now")).".pdf";
$dosya=$docroot."/Temp/".$filename;
require_once('../vendor/TCPDF/tcpdf.php');
class MYPDF extends TCPDF {	//Page header
	public function Header() {
		global $header1, $gtext, $ini, $vars;
		// Logo
		$image_file = '..'.$ini['logo'];
		//Image(string $file[, float|null $x = null ][, float|null $y = null ][, float $w = 0 ][, float $h = 0 ][, string $type = '' ][, mixed $link = '' ][, string $align = '' ][, mixed $resize = false ][, int $dpi = 300 ][, string $palign = '' ][, bool $ismask = false ][, mixed $imgmask = false ][, mixed $border = 0 ][, mixed $fitbox = false ][, bool $hidden = false ][, bool $fitonpage = false ][, bool $alt = false ][, array<string|int, mixed> $altimgs = array() ]) : mixed|false
		$this->Image($image_file, 12, 3, 40, 250,'', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->setFont('dejavusans', 'B', 18);
		// Title
		//Cell(float $w[, float $h = 0 ][, string $txt = '' ][, mixed $border = 0 ][, int $ln = 0 ][, string $align = 'C' ][, bool $fill = false ][, mixed $link = '' ][, int $stretch = 0 ][, bool $ignore_min_height = false ][, string $calign = 'T' ][, string $valign = 'M' ]) : mixed
		$this->setFont('dejavusans', '', 12, '', true);
		$this->Cell(0,14, $gtext['fixtassigndoc'], 1, false, 'C', 0, 'C', 0, false, 'T', 'M');
		$this->setFont('dejavusans', '', 8, '', true);
		$this->Cell(-10, 9, $gtext['form'].':'.$vars['form'].' ', 0, false, 'R', 0, 'C', 0, false, 'T', 'M');
		$this->Cell(-10,19, $gtext['date'].':'.date($ini['date_local'], strtotime("now")).' ', 0, false, 'R', 0, 'C', 0, false, 'T', 'M');
	}

	// Page footer
	public function Footer() {
		global $gtext, $ini;
		// Position at 15 mm from bottom
		$this->setY(-15);
		// Set font
		$this->setFont('dejavusans', '', 8, '', true);
		// Page number
		$this->Cell(0, 10, ' '.$ini['firm'].' ', 1, false, 'L', 0, 'C', 0, false, 'T', 'M');
		$this->Cell(0, 10, $gtext['page'].' '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 1, false, 'R', 0, 'C', 0, false, 'T', 'M');
	}
}
//
$pdf=new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle($gtext['fixtures']);
$pdf->SetSubject($gtext['fixtassigndoc']);
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setFontSubsetting(true);
$pdf->setFont('dejavusans', '', 9, '', true);
//
$pdf->AddPage();
$html=<<<EOD
<div>$html</div>
EOD;
$pdf->writeHTMLCell(0,0,'','', $html, 0,1,0, true, '', true);
$pdf->Output($dosya, 'I');
//echo $filename;
?>