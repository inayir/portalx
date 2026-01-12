<?php //mail testi
include('PHPMailer/PHPMailerAutoload.php');  
// recipients
if($to==''){ 		$to='inayir@gmail.com';  }
if($nameto==''){ 	$nameto	="Gönderilen Kişi"; }
if($subject==''){	$subject="Mail Konusu "; }	
if($gonderi==''){	$gonderi="Mail Denemesi... "; }	
$mail 	=new PHPMailer();
$mail->IsSMTP();
$mail->SMTPOptions = array (
    'ssl' => array(
        'verify_peer'  => false,
        'verify_depth' => 3,
        'allow_self_signed' => true,
        'peer_name' => 'mail.e-baski.com',
        'cafile' => '/ssl/certs/e_baski_com_a7621_77357_1541968869_1bc8d043c77131cb4ded4e1cfe596b6f.crt',
    )
); //*/
$mail->SMTPAuth   = true;
$mail->SMTPSecure = "tls";
//$mail->Host = 'mail.e-baski.com';
$mail->Host = 'localhost';
$mail->Port = 587;
$mail->SMTPDebug  =  2; 
$mail->Debugoutput = 'html';
$from='info@e-baski.com';  
$mail->Username = 'info@e-baski.com';
$mail->Password = 'newnew11';
$mail->CharSet = 'UTF-8';
$mail->SetFrom($from, "PortalX mail servisi", 0); 
$mail->AddAddress($to, $nameto);
$mail->Subject = $subject;
$mail->AddBCC('inayir@yandex.com.tr');

$mesaj="<html>
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
$mail->MsgHTML($mesaj);
$msg1="";
try {
    $success = $mail->send();
    if($success) {  
		echo "Mail Gönderildi.";
	} else {  //Gönderilemediyse.............
		echo "Mail GönderileMEdi.....Mailer Error: " . $mail->ErrorInfo;
		//$update_log.="\nGönderileMEdi:".$sorgu;
	}
} catch (Exception $ex) {    
    echo "Başarısız: ".$ex;      
}