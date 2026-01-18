<?php
/**/
$docroot=$_SERVER['DOCUMENT_ROOT'];
require_once $docroot.'/vendor/autoload.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;
// instantiate and use the dompdf class
$dompdf = new Dompdf();
//$dompdf->loadHtml(file_get_contents($url));
$dompdf->loadHtml($html);
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');
// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
$dompdf->stream();

?>