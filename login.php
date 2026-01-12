<?php
/*
	LDAP/AD ile login 
*/
include('set_mng.php');
session_start();
include('get_ini.php');
include('set_lang.php');
//
@$dn=$ini['dom_dn']; 
if($dn==""){ $dn=$ini['base_dn']; }
$userok=0; 
@$referrer_page=$_POST['referrer_page'];
if($referrer_page==""){ @$referrer_page=$_SESSION['referrer_page']; } //$msg.="SESSION "; }
if($referrer_page==""){ @$referrer_page="/index.php"; $_SESSION['referrer_page']=$referrer_page; } //$msg="BOŞ "; 
   
@$_SESSION["user"]=="";
if(isset($_POST['ldap-username'])&&isset($_POST['pass'])){
	@$username=$_POST['ldap-username']; 
	@$password=$_POST['pass']; 
	if($ini['usersource']=='LDAP'){  //Kullanıcı doğrulaması LDAP/AD 
		require("app/ldap_login.php"); 
		@$_SESSION["description"]="";	
		if($username!=""&&$password!=""){ 
			$res=ldap_login($username, $password, $ini['domain'], $ini['ldap_server'], $dn);	
			if($res==1){ //OK
				$_SESSION["user"]=$username;
				$_SESSION["pass"]=$password;	
				include($docroot."/ldap.php"); 
				$liste=Array('samaccountname','displayname','description');
				$filter="(samaccountname=".$username.")";		
				$ldap_result = ldap_search($conn, $dn, $filter, $liste);
			echo "kontrol ldap_result sonrası";
				if($ldap_result){ 
					$info = ldap_get_entries($conn, $ldap_result); 
					if($info["count"]>0){	
					   $_SESSION["name"]=$info[0]['displayname'][0];
					   $description=$info[0]['description'][0]; 
					}else { $_SESSION["name"]="user0";}
				}else { $_SESSION["name"]="user";}
				$userok=1; 
			} else { 
				$usernotfound=1; 
			}	
		}
	}
	
	@$collection=$db->personel;
	@$cursor = $collection->findOne(
		[
			'username' =>$username
		],
		[
			'limit' => 1,
			'projection' => [
				'description' => 1,
				'displayname' => 1,
				'picture' => 1,
				'pass' => 1
			],   
		]
	); 
	if($ini['usersource']!='LDAP'||$usernotfound==1){ //Kullanıcı doğrulaması database'den yapılır.
		if($cursor->pass==$password){
			$description=$cursor->description;
			$_SESSION["user"]=$username;
			$_SESSION["pass"]=$password; 
			$_SESSION["name"]=$cursor->displayname;
			$avtr=$cursor->picture;
			$_SESSION["picture"]=$avtr;
			$userok=1;
		}else{ //nok  //Tekrar deneyiniz.//Tekrar deneyiniz. ?> 
			<script>
			alert('<?php echo $gtext['u_errlogin']; ?>'); 
			</script><?php 		
		}//*/
	}
	if($userok==1){ //Kullanıcı onaylanmışsa personel bilgileri ve yetkileri getirilir........................	
		@$collectionp=$db->personel_prop;
		@$cursorp = $collectionp->findOne(
			[
				'username' =>$username
			],
			[
				'limit' => 1,
				'projection' => [
					'description' => 1,
					'y_ayar01' => 1,
					'y_addinfoduyuru' => 1,
					'y_addinfohaber' => 1,
					'y_addinfoser' => 1,
					'y_addinfomenu' => 1,
					'y_fixtures' => 1,
					'y_bq' => 1,
					'y_bo' => 1,
					'y_link01' => 1,
					'y_admin' => 1
				],   
			]
		);
		if(isset($cursorp)){ $usay=count($cursorp); }
		if($usay>0){
			//foreach ($cursorp as $formsatirp) { 			
				$_SESSION["y_ayar01"]=$cursorp->y_ayar01; 
				$_SESSION["y_addinfoduyuru"]=$cursorp->y_addinfoduyuru;
				$_SESSION["y_addinfohaber"]=$cursorp->y_addinfohaber;
				$_SESSION["y_addinfoser"]=$cursorp->y_addinfoser;
				$_SESSION["y_addinfomenu"]=$cursorp->y_addinfomenu;
				$_SESSION['y_fixtures']=$cursorp->y_fixtures;
				$_SESSION["y_bq"]=$cursorp->y_bq;
				$_SESSION["y_bo"]=$cursorp->y_bo;   //*/
				$_SESSION["y_admin"]=$cursorp->y_admin;
			//}; 
		}else{
			if($ini['auth_username']==$username){//standart yetkiler
				$_SESSION["y_ayar01"]=1; 
				$_SESSION["y_addinfoduyuru"]=1;
				$_SESSION["y_addinfohaber"]=1;
				$_SESSION["y_addinfoser"]=1;
				$_SESSION["y_addinfomenu"]=1;
				$_SESSION['y_fixtures']=1;
				$_SESSION["y_bq"]=1;
				$_SESSION["y_bo"]=1;  
				$_SESSION["y_admin"]=1;
			}
		}
		if($_SESSION["user"]!=""){ 
				$_SESSION['LAST_ACTIVITY'] = time(); 
			if($_SESSION['referrer_page']!=""){
				header("Location: ".$_SESSION['referrer_page']); 
			}else{
				header("Location: /index.php"); 
			} 
		}
	}
}
if($userok==0){
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $ini['title']." ".$gtext['login'];/*Login*/?></title>

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

<script>
function forgot_pass(){ 
	var u=$('#ldap-username').val(); 
	if(u!=''){
		var yol='/forgot_pass_mail.php';
		window.open(yol+'?u='+u,'_tab');
	}else{
		alert('<?php echo $gtext['write_user']; ?>');
		$('#ldap-username').focus();
	}
}
$('#ldap-username').on('KeyPress', function(data){
	if(data=='@'){ alert('Sadece kullanıcı giriniz.');}
});
</script>
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
                                        <h5 class="h5 text-gray-900 mb-4"><?php echo $gtext['login']." ".$gtext['login'];/*Giriş*/?></h5>
                                    </div>
                                    <form class="user" method="POST" action="">
                                        <div class="form-group">
											<p><?php echo $gtext['write_user'].":";/*Kullanıcı Giriniz:*/?></p>
                                            <input type="text" class="form-control form-control-user"
                                                name="ldap-username" id="ldap-username"
                                                placeholder="<?php echo $gtext['write_user_placeholder'];/*Kullanıcı adınız...*/?>">
                                        </div>
                                        <div class="form-group">
											<p><?php echo $gtext['write_pass'].":";/*Şifre Giriniz:*/?></p>
                                            <input type="password" class="form-control form-control-user"
                                                name="pass" id="pass" placeholder="<?php echo $gtext['u_pass'];/*Şifreniz...*/?>...">
                                            <input type="hidden" name="referrer_page" id="referrer_page" value="<?php echo $referrer_page; ?>">
                                        </div>
                                        <!--div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Beni Hatırla</label>
                                            </div>
                                        </div-->
                                        <button type="Submit" class="btn btn-primary btn-user btn-block"><?php echo $gtext['p_login'];/*Giriniz*/?></button>
										<hr>
										<div class="text-center">
											<a class="small" href="" onClick="forgot_pass();"><?php echo $gtext['forgotten_pass'];/*Şifremi Unuttum*/?></a>
										</div>
                                    </form>
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
<?php } ?>