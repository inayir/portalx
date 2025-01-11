<?php
/*
	Set_settings ayarları-Mongodb collection oluşturma, portaladmin kullanıcısı açma
*/
//error_reporting(0);
include('../set_mng.php');
include('../sess.php');
include('../config/config.php');
$log="Settings2;";
include($docroot."/app/php_functions.php");
$logfile='Settings';
//MongoDB ----------------------------------------
$collections = $db->listCollections();
$collectionNames = [];
foreach ($collections as $collection) {
  $collectionNames[] = $collection->getName();
}
//------------------------------------------------
$col=$dbi.".personel";
$exists = in_array('personel', $collectionNames);
if(!$exists){ //echo " Collection Oluşturuluyor: ".$col;
	$db->createCollection('personel',[
	]);
}
$col=$dbi.".personel_prop";
$exists = in_array('personel_prop', $collectionNames);
if(!$exists){ //echo " Collection Oluşturuluyor: ".$col;
	$db->createCollection('personel_prop',[
	]);
}
$isl='!';
//admin user tanımlanır.
@$collection = $db->personel;
if($_POST['adm_user']!=""){
	//Girilen kullanıcı kaydedilir.................	
	$description='PA01';
	$data=[];
	$username=$_POST['adm_user'];
	$data['username']=$username;
	$data['description']=$description;
	$data['displayname']=$_POST['displayname'];
	$data['ptype']="LA"; //Local Admin
	if(isset($_POST['adm_pass'])){ $data['pass']=$_POST['adm_pass']; }
	else{ $data['pass']="12345678"; }
	$data['tarih'] 	= datem(date("Y-m-d H:i:s", strtotime("now")));	
	$log.=implode(';',$data).";";
	@$fcursor = $collection->findOne(
		[
			'username' => $username
		],
		[
			'limit' => 1,
			'projection' => [
				'username' => 1,
				'description' => 1,
				'displayname' => 1,
				'pass' => 1,
				'tarih' => 1,
			],
		]
	);
	if(isset($fcursor)){ $ksay=count($fcursor); }
	if($ksay<1){
		@$cursor = $collection->insertOne(
			$data
		);
		if($cursor->getInsertedCount()>0){ 
			$isl=$gtext['inserted']; 
			$log.=$gtext['inserted'].";";
			//personel_prop			
			$pisl=" Prop:";
			$propdata=[];
			$propdata['username']=$username;
			$propdata['y_ayar01']=1;
			$propdata['y_addinfoduyuru']=1;
			$propdata['y_addinfohaber']=1;
			$propdata['y_addinfoser']=1;
			$propdata['y_addinfomenu']=1;
			$propdata['y_bq']=1;
			$propdata['y_bo']=1;
			$propdata['y_link01']=1;
			$propdata['y_admin']=1;
			@$pcollection = $db->personel_prop;			
			$log.="prop->;".implode(';',$propdata);
			@$fpcursor = $pcollection->findOne(
				[
					'username' => $username
				],
				[
					'limit' => 1,
					'projection' => [
						'username' => 1,
						'description' => 1,
						'displayname' => 1,
						'pass' => 1,
						'tarih' => 1,
					],
				]
			);
			if(isset($fpcursor)){ $kpsay=count($fpcursor); }
			if($kpsay<1){
				@$pcursor = $pcollection->InsertOne(
					$propdata 
				);
				if($pcursor->getInsertedCount()>0){ 
					$pisl.=$gtext['inserted']; 
					$log.=$gtext['inserted'].";";
				}
			}else{
				@$pcursor = $pcollection->updateOne(
					[
						'description'=>$description
					],
					[ '$set' => $propdata ]
				);
				if($pcursor->getModifiedCount()>0){ 
					$pisl.=$gtext['updated'];/*'Güncellendi';*/ 
					$log.=$gtext['updated'].";";
				}else{ 
					$pisl.=$gtext['notupdated'];/*'GüncelleneMEdi '*/ 
					$log.=$gtext['notupdated'].";";
				}
			}
		}else{ 
			$isl=$gtext['notinserted'];/*'EkleneMEdi '*/ 
			$log.=$gtext['notinserted'].";";
		}
	}else{ //update
		@$cursor = $collection->updateOne(
			[
				'username'=>$username
			],
			[ '$set' => $data ]
		);
		if($cursor->getModifiedCount()>0){ 
			$isl=$gtext['updated'];/*'Güncellendi '*/ 
			$log.=$gtext['updated'].";";
		}else{ 
			$isl=$gtext['notupdated'];/*'GüncelleneMEdi '*/ 			
			$log.=$gtext['notupdated'].";";
		}
		//prop güncellenmez.
	} 
	if($isl!='!'){ //Prepairing Message...
		echo $gtext['auth_username']."->".$isl.": ".$data['username']." / ".$gtext['pass'].": ".$data['pass'];
		echo "\n".$gtext['u_firstpass']; /*Ayarlara ilk girişte bu şifre kullanılacaktır.*/	
		$ok=1;
	}else{ echo "!".$gtext['error'].":".$gtext['auth_username']." ".$gtext['notinserted']; /*Yetkili kullanıcı EkleneMEdi!*/}
	//Inserting PortalX Admin account--------------------------------------
	$datax=[];
	$datax['username']="admin-1";
	$datax['description']='Adm01';	
	$datax['displayname']="PortalX Admin-1";
	$datax['ptype']="LA"; 
	$datax['pass']="PortalX"; 
	$datax['tarih'] 	= datem(date("Y-m-d H:i:s", strtotime("now")));
	@$cursor = $collection->updateOne(
		[
			'username'=>$datax['username']
		],
		[ '$set' => $datax ],
		['$upsert'=>true]
	);
	if($cursor->getModifiedCount()>0||$cursor->getInsertedCount()>0){
		//personel_prop dosyasına da kayıt eklenir.
		$pisl='!';
		$propdata['username']=$data['username'];
		@$pcursor = $pcollection->insertOne(
			$propdata
		);
		if($pcursor->getInsertedCount()>0){ 
			$pisl=$gtext['inserted'];  //'Eklendi';
			$log.=$gtext['inserted'].";";
			//Inserting Adm01 account rights...
			$propdata['username']=$description_adm01;
			@$pcursor = $pcollection->updateOne(
				[
					'username'=>$datax['username']
				],
				[ '$set' => $propdata ]
			);
		}			
	}
	$log.=implode(';',$datax).";";
	logger($logfile,$log);
}else{
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['s_psettings'];/*Portal Ayarları*/?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!--link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"-->
	<link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">

    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.min.js"></script>
	<script src="/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
<?php include($docroot."/set_page.php"); ?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include($docroot."/sidebar.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include($docroot."/topbar.php"); ?>
                    
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <?php if($_SESSION['k']=='admin'){ ?><h6 class="m-0 font-weight-bold text-primary"><?php echo $gtext['s_psettingswelcome'];/*Portal Kurulumuna Hoşgeldiniz...*/ echo "<small>".$gtext['s_psettingssecond']."</small>";?></h6><?php }else{ ?><h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['s_psettings'];/*Portal Ayarları*/?></h1><?php } ?>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					  <!-- DataTables Example -->
                      <div class="card shadow mb-4 w-100">
                        <div class="card-header py-3" id="rt"></div>
                        <div class="card-body">
                            <div class="table-responsive" style="width:50%">
							<form name="form1" id="form1" method="POST" action="Settings2.php">
                                <table class="table table-bordered" id="b_list" width="100%" cellspacing="0">
                                    <thead>
									<tr>
										<th colspan="2"><b><i><?php echo $gtext['auth_username']."  ".$gtext['insert'];/*Yetkili Kullanıcı Ekle*/?></i></b></th>
									</tr>
                                    </thead>
                                    <tbody>
									<tr>
										<td><?php echo $gtext['name'];/*İsim*/?></td>
                                        <td>
											<input class="form-control" type="text" name="displayname" id="displayname" value=""/>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['username'];/*Kullanıcı*/?></td>
                                        <td>
											<input class="form-control" type="text" name="adm_user" id="adm_user" value=""/><br>
											<small>* <?php echo $gtext['example'];/*Örnek*/?>: portaladmin</small>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['pass'];/*Şifre*/?>*</td>
                                        <td>
											<div class="input-group">
												<input class="form-control" type="password" name="adm_pass" id="adm_pass" value="" />
												<button class="btn btn-outline-secondary sfrbtn" type="button" id="adm_passbtn" >G</button>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="4">
											<span class="d-flex justify-content-center">
												<button class="btn btn-primary" name="send" id="send"><?php echo $gtext['insert'];/*Ekle*/?></button>
											</span>
										</td>
									</tr>
                                    </tbody>
								</table>
								<small>(*)<?php echo $gtext['u_passformat']; /*Büyük harf, küçük harf, rakam içeren en az 8 karakter şeklinde girilmelidir.*/?></small>
							</div><?php /*if($ok==1){ ?>
							<div style="width: 50%;" class="border border-4 border-success text-center p-2"><a class="text-success" id="ileri" href="/index.php"><?php echo $gtext['u_startbrowsing'];/*Sayfanızı şimdi inceleyin->Ana Sayfa?></a></div><?php } //*/?>
						</div>
					  </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include($docroot."/footer.php"); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<script>
$('#send').on("click", function(){ //ekle/değiştir ajaxform
	var opt={
		target	: '#rt',
		type	: 'POST',
		url 	: './Settings2.php',
		contentType: 'application/x-www-form-urlencoded;charset=utf-8',
		beforeSubmit : function(){
			if(($('#adm_user').val()=='')||($('#adm_pass').val()=='')||($('#displayname').val()=='')){ 
				alert('<?php echo $gtext['u_fieldisnotblank'];/*Alanlar boş olamaz!*/?>');
				return false;
			}
			return confirm('<?php echo $gtext['q_rusure'];/*Emin Misiniz?*/?>');
		},
		success: function(data){ 
			if(data.indexOf('!')>-1){ alert('<?php echo $gtext['u_error'];/*Bir hata oluştu!*/?>\n'+data); }
			else { 
				alert(data);
				setTimeout(function(){
					alert('<?php echo $gtext['u_setup'];?>'); 
				},5000);
				location.href = '../install.php';
				 
			}
		}
	}
	$('#form1').ajaxForm(opt); //*/
});
$('#form1').find(':input').change(function(){ $('#gonder').attr("disabled", false ); });
$(".sfrbtn").on('mousedown', function(){ 
	var et=$(this).attr('id'); et=et.replace('btn',''); 
	if($('#'+et).attr('type')=='password'){ $('#'+et).attr('type', 'text'); }
	else{ $('#'+et).attr('type', 'password'); }
});
</script>
</body>

</html>
<?php } ?>
	