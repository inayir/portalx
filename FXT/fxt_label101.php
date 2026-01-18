<?php
/*fxt etiketi yazdırma l-101 */
include("../set_mng.php");
//error_reporting(0);
include($docroot."/sess.php");
if($user==""){
	//echo "login"; exit;
}
@$log=$now.";";  $vars=[];
$form   =$_POST['form'];   if($form==''){ $form='YFR-101'; }
$code	=$_POST['code'];  		//$username='inayir';
$pieces	=$_POST['pieces']; 	//$doc_side='to';

@$collection=$db->Fixtures;
$cursor = $collection->findOne(
	[
		'code'=>$code
	],
	[
		'limit' => 1,
		'projection' => [
		],
	]
);
//
ob_end_clean();
$filename=$form."_".date("ymdHis", strtotime("now")).".pdf";
$dosya=$docroot."/Temp/".$filename;
require_once('../vendor/TCPDF/tcpdf.php');
//
$width =35;
$height=25;
$pageLayout = array($width, $height); //  or array($height, $width) 
$pdf = new TCPDF('L', 'mm', $pageLayout, true, 'UTF-8', false); 
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle($gtext['fixture']." ".$gtext['label']);
//$pdf->SetSubject($gtext['label']);
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetMargins(0, 0, 0, false);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false); 
$pdf->setFontSubsetting(true); //*/
$pdf->setFont('dejavusans', '', 9, '', true);
$pdf->AddPage($pageLayout);
$pdf->SetPage(1,true);


$html='<html>
<head>
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div style="text-align:center;">'.$code.'</div><br>
<center>
<table>
<tr><td>'.$cursor->serialnumber.'</td></tr>';
//$html.='<tr><td><img src="/img/inim.png"></td></tr>';
$html.='</table>';
$html.='</body></html>';
$htm=<<<EOD
<div>$html</div>
EOD;
//writeHTMLCell(float $w, float $h, float|null $x, float|null $y[, string $html = '' ][, mixed $border = 0 ][, int $ln = 0 ][, bool $fill = false ][, bool $reseth = true ][, string $align = '' ][, bool $autopadding = true ]) : mixed
//$pdf->writeHTMLCell(1,5,1,1, $htm, 0,0,0, true, '', true);//*/
// new style
$style = array(
	'border' => 2,
	'padding' => 'auto',
	'fgcolor' => array(0,0,0),
	'bgcolor' => false //array(255,255,64)
);
//MultiCell(float $w, float $h, string $txt[, mixed $border = 0 ][, string $align = 'J' ][, bool $fill = false ][, int $ln = 1 ][, float|null $x = null ][, float|null $y = null ][, bool $reseth = true ][, int $stretch = 0 ][, bool $ishtml = false ][, bool $autopadding = true ][, float $maxh = 0 ][, string $valign = 'T' ][, bool $fitcell = false ]) : int
//                    w   h    txt   bor align fill  ln  $x  $y reseth strech ishtml  ap   mxh valign fitcell
$pdf->MultiCell('', '', $code, 1,   'C', false, 1, 1,  1, true,   0,    false, true, 0,  'C',   false);

$pdf->setFont('dejavusans', '', 6, '', true);
$pdf->Text('', 4, 'S/N:'.$cursor->serialnumber);
//TCPDF::write2DBarcode( $code,  $type,  $x = '',  $y = '',  $w = '',  $h = '',  $style = array(),  $align = '',  $distort = false )
$pdf->write2DBarcode($code, 'QRCODE,H', 2, 2, 50, 50, $style, 'N');
$sn='S/N:'.$cursor->serialnumber;
//
$pdf->Output($dosya, 'I');
?>