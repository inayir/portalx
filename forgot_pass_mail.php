<?php
/*
	LDAP/AD ile login 
*/
include('set_mng.php');
error_reporting(0);
session_start();
include('get_ini.php');
include('set_lang.php');
//
@$_SESSION["user"]=="";
$to="";

if(isset($_GET['u'])){
	@$username=$_GET['u']; //filtrele.
	//@ varsa ayıklanır...$ini['dom_dn']
	//
	@$collection=$db->personel;
	@$cursor = $collection->findOne(
		[
			'username' =>$username
		],
		[
			'limit' => 1,
			'projection' => [
			],   
		]
	); 
	
	if($cursor){
		
		$to=$cursor->mail;
		$nameto=$cursor->displayname;
		$subject='Sayın '.$nameto.', Şifre Sıfırlama Maili';
		//
		$collections = $db->listCollections();
		$collectionNames = [];
		foreach ($collections as $collection) {
		  $collectionNames[] = $collection->getName();
		}
		$exists = in_array('mail_links', $collectionNames);
		if(!$exists){
				$db->createCollection('mail_links',[
			]);
		}//*/
		$colm=$db->mail_links;
		$data=[];
		$data['username']=$username;
		//randomize
		$anc="";
		$chars=["a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","j","J","k","K","l","L","m","M","n","N","o","O","p","P","r","R","s","S","t","T","u","U","v","V","y","Y","z","Z","1","2","3","4","5","6","7","8","9","0","-","_","(",")","!"];
		for($i=0;$i<30;$i++){
			$anc.=array_rand($chars, 1);
		}
		$data['anchor']=$anc;
		$data['lstime']=date("Y-m-d H.i:s", strtotime("now"));
		$data['use']="notused";
		$curm=$colm->insertOne(
			$data
		);
		if($curm){
			$w=$_SERVER['SERVER_NAME'];
			$link=$w."/forgot_pass.php?u=".$username."&l=".$data['anchor'];
			$gonderi="Şifre Sıfırlama Linkiniz:".$link;
			include($docroot."/admin/mail_send.php");
		}//*/
	}	
}
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['renew_pass'];/*Login*/?></title>
    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
 href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
<?php include("set_page.php"); ?>
</head>
<body class="bg-gradient-secondary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4"><?php echo $ini['firm']; ?></h1>    
											<h5 class="h5 text-gray-900 mb-3"><?php echo $gtext['renew_pass'];/**/?></h5>
									</div>
									<div class="text-center"><?php if($to!=""){ ?>
										<h5 class="h5 text-gray-900 mb-3"><p><?php echo "Sayın ".$nameto.", </p><p>"; 
										if($sent=="OK"){ echo $gtext['fp_link_mail_sent']; }
										else{	echo $gtext['fp_link_mail_no_sent']; } ?></p></h5>
									<?php }else{ ?>
										<h5 class="h5 text-gray-900 mb-3"><?php echo "Hesap bulunamadı!";?></h5>
									<?php } ?>
									</div>
								</div>
								<hr>
								<div class="p-5">
									<button type="button" class="btn btn-info btn-user btn-block text-uppercase fw-bold" onClick="window.open('/login.php');"><?php echo $gtext['login'];/*Giriniz*/?></button>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.js"></script>
	
</body>

</html>