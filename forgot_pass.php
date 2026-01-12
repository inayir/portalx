<?php
/*
	LDAP/AD ile login 
*/
include('set_mng.php');
session_start();
error_reporting(0);
include('get_ini.php');
include('set_lang.php');
//553167282452950502113143236504055374445201752161655472226
$initimezone=$ini['timezone']; if($initimezone==''){ $initimezone="Europe/Istanbul"; }
date_default_timezone_set($initimezone);
if(isset($_GET['u'])){
	$lstimepast="";
	@$username=$_GET['u']; //echo "get username:".$username; exit;
	@$l=$_GET['l']; 
	//@ varsa ayıklanır...$ini['dom_dn']
	@$collection=$db->mail_links;
	@$cursor = $collection->findOne(
		[
			'username' =>$username,
			'anchor' =>$l
		],
		[
			'limit' => 1,
			'projection' => [
			],   
		]
	); 
	
	if($cursor){
		$lstime=$cursor->lstime;
		$ondkoncesi=date("Y-m-d H:i:s", strtotime("-10 minutes"));
		if($lstime<$ondkoncesi){ $lstimepast="Y"; }
		if($cursor->used==''||$cursor->used=='Y'){ $linkused=$cursor->used; }
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

    <title><?php echo $ini['title'];/*Login*/?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
 href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
<?php include($docroot."/set_page.php"); ?>

</head>

<body class="bg-gradient-secondary">
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-6 col-lg-6 col-md-6">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4"><?php echo $ini['firm']; ?></h1>                                        
                                    </div>
										<div class="text-center">
										<h5 class="h5 text-gray-900 mb-3"><?php echo $gtext['user']." ".$gtext['chng_pass'];/*Şifre Değiştir*/?></h5>
									</div><?php if($lstimepast=='Y'||$linkused=='Y'){
											if($lstimepast=='Y'){ echo '<div class="text-center fw-bold">'.$gtext['u_lstimepast'].'</div>'; }
											if($linkused=='Y'){ echo '<div class="text-center fw-bold">'.$gtext['u_passresetlinkused'].'</div><div><button>'.$gtext[''].'</button></div>'; }
										}else{ ?>
                                    <form id="form1" method="POST" action="admin/set_pss.php">
                                        <div class="form-group">
											<p><?php echo $gtext['write_user'].":";/*Kullanıcı Giriniz:*/?></p>
                                            <input type="text" class="form-control form-control-user"
                                                name="ldap-username" id="ldap-username" value="<?php echo $username; ?>" aria-describedby="emailHelp"
                                                placeholder="<?php echo $gtext['write_user_placeholder'];/*Kullanıcı adınız...*/?>" readonly >
											<input type="hidden" name="b" id="b" value=""/>
											<input type="hidden" name="lstime" value="<?php echo $lstime; ?>"/>
                                        </div>
                                        <div class="form-group">
											<p><?php echo $gtext['write_pass'].":";/*Şifre Giriniz:*/?></p>
                                            <input type="password" class="form-control form-control-user" name="pass" id="pass" placeholder="<?php echo $gtext['write_pass']; ?>...">
                                        </div>
                                        <div class="form-group">
											<p><?php echo $gtext['repass'].":";/*Şifreyi Tekrar Giriniz:*/?></p>
                                            <input type="password" class="form-control form-control-user" name="repass" id="repass" placeholder="<?php echo $gtext['write_repass']; ?>...">
                                        </div>
                                        <button type="Submit" class="btn btn-primary btn-user btn-block"><?php echo $gtext['renew_pass'];/*Şifre Yenile*/?></button>
										<small class="text-center"><?php echo $gtext['u_passformat']; ?></small>
                                    </form><?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.js"></script>
<script>
function letcont(d, p){	
	var pa=Array.from(p);
	var mvc=false;
	for(var l=0; l<pa.length; l++){ if(d.indexOf(pa[l])>-1){ mvc=true; } }
	return mvc;
}
var opt={
	type	: 'POST',
	url 	: 'admin/set_pss.php',
	contentType: 'application/x-www-form-urlencoded;charset=utf-8',
	beforeSubmit : function(){
		$('#b').val('pr');
		var p=$('#pass').val(); 
		if(p==""||$('#repass').val()==''){
			alert('<?php echo $gtext['u_fieldmustnotblank']; ?>');
			$('#pass').focus();
			return false;
		}
		if(p!=$('#repass').val()){
			alert('<?php echo $gtext['u_passnotsame']; ?>');
			$('#pass').focus();
			return false;
		}
		var cl="ABCDEFGHIJKLMNOPQRSTUVYZ";
		var al="abcdefghijklmnopqrstuvyz";
		var nl="1234567890";
		var sl="!-.*";
		//
		var lcont=letcont(cl,p); 
		if(lcont!=false){ lcont=letcont(al,p); }
		if(lcont!=false){ lcont=letcont(nl,p); }
		if(lcont!=false){ lcont=letcont(sl,p); }
		//control
		if(p.length<8||lcont===false){
			alert('<?php echo $gtext['u_passformat']; ?>');
			$('#pass').focus();
			return false;			
		}
		return confirm('Emin Misiniz?');
	},
	success: function(data){ alert(data);
		if(data!='error'){ 
			window.location='/index.php';
		}
	}
}
$('#form1').ajaxForm(opt); 
</script>
</body>

</html>