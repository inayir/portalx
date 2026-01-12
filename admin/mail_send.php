<?php //mail send
require($docroot."/app/php_functions.php");
$log=date("Y-m-d H:i:s", strtotime("now")).";";
//error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $docroot.'/PHPMailer/src/Exception.php';
require $docroot.'/PHPMailer/src/PHPMailer.php';
require $docroot.'/PHPMailer/src/SMTP.php'; 
		
// Mail sending...
$mail 	=new PHPMailer();


	$mail->IsSMTP();
	$mail->SMTPOptions = array (
		'ssl' => array(
			'verify_peer'  => false,
			'verify_depth' => 3,
			'allow_self_signed' => true,
			'peer_name' => 'e-baski.com',
			'cafile' => '/ssl/certs/e_baski_com_a92de_6199d_1794441599_96408af142e3cff1a36ea7877b8dfb46.crt',
		)
	); 
	$mail->Host = 'e-baski.com';
	//$mail->Host = 'localhost';
	//$mail->SMTPAuth   = true;
	$from='info@e-baski.com';  
	$mail->Username = 'info@e-baski.com';
	$mail->Password = 'newNEW11__';
	$mail->SMTPSecure = "ssl"; //"tls"; //PHPMailer::ENCRYPTION_SMTPS; //"tls";
	$mail->Port = 465; //587;
	$mail->SMTPDebug  =  2; 
	$mail->Debugoutput = 'html';
	$mail->CharSet = 'UTF-8';
	//Recipients
	$mail->SetFrom($from, "Mail servisi", 0); 
	$mail->AddAddress($to, $nameto);
	//$mail->AddBCC('inayir@yandex.com.tr');
	//Content
	$mail->Subject = $subject;
	$mail->isHTML(true);
	$message="<html>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf8'>
		<meta http-equiv='Content-Type' content='text/html; charset=windows-1254' />
	</head>
	<body>
		<br><br><br><br>
		<div>".$gonderi."</div>
		<div></div>
	</body>
	</html>
	";
	$mail->msgHTML($message);
    $mail->AltBody = $gonderi;
try {	
    if($mail->send()) {  
		//echo "Mail Gönderildi."; 
		$sent="OK";
		$log.="Sent to:".$to.";".$subject.";";
	} else {  //Gönderilemediyse.............
		//echo "Mail GönderileMEdi.....Mailer Error: " . $mail->ErrorInfo;
		$sent="nOK";
		//$update_log.="\nGönderileMEdi:".$sorgu;
		$log.="Can Not Sent to:".$to.";".$subject.";Mailer Error: " . $mail->ErrorInfo.";";
	}
} catch (Exception $ex) {    
    //echo "Başarısız: ".$ex;  
	$sent="nOK";
	$log.="Can Not Sent to:".$to.";".$subject.";Mailer Error: " . $mail->ErrorInfo.";";
}


logger('mail', $log);
/*if($to==''){ 		$to='inayir@gmail.com';  }
if($nameto==''){ 	$nameto	="Gönderilen Kişi"; }
if($subject==''){	$subject="Mail Konusu "; }	
if($gonderi==''){	$gonderi="Mail Denemesi... "; }	//*/
?>