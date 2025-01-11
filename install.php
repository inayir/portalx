<?php
function searchtext($contents,$searchfor){
	$pattern = preg_quote($searchfor, '/'); 
	$pattern = "/^.*$pattern.*\$/m";
	$x=preg_match_all($pattern, $contents, $matches);
	if (!$x){ return $searchfor."\n"; }	
}
//error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
$lang=explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
$dila=explode('-', $lang[0]); 
$dil=$dila[1];
$_SESSION['lang']=$dil;
$dildosyasi=$docroot.'/lang/TR.php';
include($dildosyasi);
$dildosyasi=$docroot.'/lang/'.$dil.'.php';
include($dildosyasi);
session_start();
$msg="";
@$step=@$_POST['step'];  //boşsa başlama aşaması
//
@$referrer_page=$_SERVER['HTTP_REFERER'];
$f=strpos($referrer_page,'Settings.php');
if($f!=""&&$f>=0){ $step=3; }
$f=strpos($referrer_page,'Settings2.php');
if($f!=""&&$f>=0){ $step=4; }
$_SESSION['k']='admin'; //echo "k:".$_SESSION['k'];
//
if(@$_POST['go']!=""){
	if($step==1){ 
		//STEP 1: extension_dir php.ini den okunur.	dosya bulunan yola taşınır.
		$extension_dir=ini_get('extension_dir');
		//move php_mongodb.dll file to $extension_dir
		$file = $docroot.'\config\ext\php_mongodb.dll';
		$newfile = $extension_dir.'\php_mongodb.dll';
		$msg="<div>";
		if(!file_exists($newfile)){
			if (!copy($file, $newfile)) {
				$msg.="Failed to copy: ".$file.", Please copy file to ".$extension_dir."\n";
			}else{
				$msg.="Copied:".$file."  to ".$extension_dir."\n";
			}
		}
		$msg.="</div>
		<div>";
		//move php_mongodb.pdb file to $extension_dir
		$file = $docroot.'\config\ext\php_mongodb.pdb';
		$newfile = $extension_dir.'\php_mongodb.pdb';
		if(!file_exists($newfile)){
			if (!copy($file, $newfile)) {
				$msg.="Failed to copy: ".$file.", Please copy file to ".$extension_dir."\n";
			}else{
				$msg.="Copied:".$file."  to ".$extension_dir."\n";
			}
		}
		$msg.="</div>";
		//writing settings to php.ini ...
		$php_ini_file=php_ini_loaded_file();
		$contents = file_get_contents($php_ini_file);
		$ayar="";
		$s=searchtext($contents,'extension=ldap');
		if($s==''){ 
			$s=searchtext($contents,';extension=ldap'); 
			if($s!=''){ $s="extension=ldap\n"; }
		}
		$ayar.=$s;
		$ayar.=searchtext($contents,'extension=php_mongodb.dll'); 
		$ayar="\n".$ayar;
		//writing to php.ini:  extension=ldap extension=php_mongodb.dll
		if($ayar!=""){
			$msg.="<div>Written settings to php.ini</div>";
			touch($php_ini_file);
			$dosya=fopen($php_ini_file, 'a');
			fwrite($dosya, $ayar); 
			fclose($dosya); 
			//web srv must restart 
			$msg.="<div>Please restart web server, then go to next step...</div>";
			//$step=2;
		}
		//composer install
		$phpdir=substr($extension_dir, 0, strpos($extension_dir, '\ext'));
		$cmd="PATH";
		exec($cmd,$o, $r);
		if($r==0){ 
			$yer=strpos($o[0], $phpdir);		
			if($yer<0||$yer==""){
				//install.bat rewriting...
				$compdir=$docroot."\admin\Composer\bin";
				$bats="@echo Beginning Composer setup\nPATH=%PATH%;".$phpdir.";".$compdir.";\nphp composer.phar init --name=mongodb/mongodb --description=MongoDB --type=library --autoload=AUTOLOAD --quiet\ncomposer require mongodb/mongodb";
				$insbatfile="install.bat";
				touch($insbatfile);
				$batfile=fopen($insbatfile, 'w');
				fwrite($batfile, $bats); 
				fclose($batfile); 
				exec($insbatfile,$ox,$rx);
			}
		}
		exec("php composer.phar install");
		exec("php composer.phar require mongodb/mongodb");
	}
	if($step==2){ 
		$msg.="<div>Now, time to re-start your web server.</div>";
	}
	if($step==3){ 
		$msg.="<div>Redirecting to Settings...</div>";
		header('Location: /admin/Settings.php');
	}
	if($step==4){ 
		$msg.="<div>Redirecting to User Creation...</div>";
		header('Location: /admin/Settings2.php');
	}
	if($step>4){ 
		$msg.="<div>Redirecting to Site...</div>";
		header('Location: /index.php');
	}
}
$step++;
$msg.="<br>";
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Install PortalX</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
	<!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
	<script src="/js/sb-admin-2.js"></script>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
		<div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
				<!-- Begin Page Content -->
                <div class="container-fluid">                    
                    <!-- Content Row -->
					<div class="row">
						<h1 class="m-0 w-100 font-weight-bold text-primary text-center">Title</h1>						
					</div>
					<div>
						<h6 class="m-0 w-100 font-weight-bold text-default text-center">Ready to Install?</h6>
					</div>
					<div class="row clearfix">
						<div class="m-0 w-100 text-center"><br>	
						Instructions:<br>
						Step 1. Web Server Settings <span class="text-danger"><?php if($step>1){ echo "OK";}?></span><br>
						Step 2. Web Server Re-Start <span class="text-danger"><?php if($step>2){ echo "OK";}?></span><br>
						Step 3. Go to Settings <span class="text-danger"><?php if($step>3){ echo "OK";}?></span><br>
						Step 4. Go to Creating Admin User <span class="text-danger"><?php if($step>4){ echo "OK";}?></span><br>
						Step 5. End of Install<br>
						</div>
						<br><br>
						<div class="m-0 w-100 text-center">
							<form name="form1" method="POST" action="./install.php">
								<input type="hidden" name="step" id="step" value="<?php echo $step;?>">
								<div class="align-center text-center border border-success rounded m-5 p-1"><?php echo $msg; ?></div><br>
								<div><input class="btn btn-info align-center w-25 step" type="submit" name="go" value="<?php echo $gtext['next'];/*next*/?>"></div>
								<div class="align-center text-center border border-info rounded m-5 p-5">
								<a href="\Guides\User_Guide_EN.pdf">User Guide (EN)</a><br>
								<a href="\Guides\User_Guide_TR.pdf">User Guide (TR)</a><br>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- Footer -->
            <?php include($docroot."/footer.php"); ?>
            <!-- End of Footer -->
		</div>
	</div>
</body>
</html>