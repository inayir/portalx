<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<?php
error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
include("sess.php");
$_SESSION['user']='';
$_SESSION['pass']='';
$_SESSION['name']='';

require($docroot."/app/ldap_login.php");

$username=$_POST['ldap-username']; //if($username==""){ $username=$_GET['ldap-username']; }//echo "user:".$username;
$password=$_POST['ldap-pass']; //if($password==""){ $password=$_GET['ldap-pass']; }
$referrer_page=$_POST['referrer_page'];
if($referrer_page==""){ $referrer_page=$_SERVER['HTTP_REFERER']; }
if($referrer_page==""){ $referrer_page="index.php"; }

$log=date("Y-m-d H:i:s", strtotime("now"));
if($username!=""&&$password!=""){
	$log.=";username: ".$username;
	$res=ldap_login($username, $password, $ini['domain'], $ini['ldap_server'], $ini['dom_dn']);	
	if($res==1){ //OK
		$_SESSION["user"]=$username;
		$_SESSION["pass"]=$password; 
		echo "Login oldu: ";		
		require("ldap.php"); 
		$ldap_result = ldap_search($conn, $ini['dom_dn'], "(samaccountname=$username)");
		if($ldap_result){ 
			$info = ldap_get_entries($conn, $ldap_result); 
			if($info["count"]>0){	
			   $_SESSION["name"]=$info[0]['displayname'][0];
			}else { $_SESSION["name"]="-----"; }
			$log.=";login;";
		}
		header("Location: ".$referrer_page);
	} else { //nok
		echo $gtext['p_notlogin'];
		$log.=";notlogin;";
		echo $res." -> ";
	}	
}
//
$dosya=$docroot."/logs/login.log"; 
touch($dosya);
$dosya = fopen($dosya, 'a');
fwrite($dosya, $log);
fclose($dosya); //*/
?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['login']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
 href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

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
                                        <h5 class="h5 text-gray-900 mb-4">Giriş Yapınız</h5>
                                    </div>
                                    <form class="user" method="POST" action="">
                                        <div class="form-group">
											<p>Kullanıcı Giriniz:</p>
                                            <input type="text" class="form-control form-control-user"
                                                name="ldap-username" id="ldap-username" aria-describedby="emailHelp"
                                                placeholder="Kullanıcı adınız...">
                                        </div>
                                        <div class="form-group">
											<p>Şifre Giriniz:</p>
                                            <input type="password" class="form-control form-control-user"
                                                name="ldap-pass" id="ldap-pass" placeholder="Şifreniz...">
                                            <input type="hidden" name="referrer_page" id="referrer_page" value="<?php echo $referrer_page; ?>">
                                        </div>
                                        <!--div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Beni Hatırla</label>
                                            </div>
                                        </div-->
                                        <button type="Submit" class="btn btn-primary btn-user btn-block">
                                            Giriş Yapınız
                                        </button>
                                        <!--hr>
                                        <a href="index.html" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a-->
                                    </form>
                                    <hr>
                                    <!--div class="text-center">
                                        <a class="small" href="forgot-password.html">Şifremi Unuttum</a>
                                    </div-->
                                    <!--div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div-->
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