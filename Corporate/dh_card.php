<?php
/*
	Pano sayfasını getirir.
*/
include("../set_mng.php");
//error_reporting(0); 
include($docroot."/sess.php"); 
for($g=0;$g<7;$g++){
	$gunler[]=$gtext['day'.$g];
}
$bugun=date("Y.m.d 00:00:00", strtotime("now"));
$yarin=date("Y.m.d 00:00:00", strtotime("+1 day"));
@$id=$_GET['u']; 

@$collection=$db->k_dhaber;
@$cursor = $collection->find(
	[
		'_id'=>new \MongoDB\BSON\ObjectId($id)
	],
	[
		'limit' => 0,
		'projection' => [
			'_id' => 1,
			'dh_ytar' => 1,
			'dh_capt_on' => 1,
			'dh_baslik' => 1,
			'dh_icerik' => 1,
			'dh_resim' => 1,
			'dh_url' => 1,
			'dh' => 1,
			'aktif' => 1,
		],
		'sort'=>['dh_ytar'=>-1]
	]
);
foreach ($cursor as $satir) {
	$satir=[]; 
	$satir['_id']=$satir->_id;
	if($satir->dh_ytar!=null){ 
		$dt=$satir->dh_ytar->toDateTime()->format($ini['date_local']." H:i"); 
	}else{ $dt='';} 
	$satir['dh_ytar']=$dt; 
	$satir['dh_baslik']=$satir->dh_baslik;  
	$satir['dh_icerik']=$satir->dh_icerik; 
	$satir['dh_resim']=$satir->dh_resim; 
	if($satir->dh_url!=''){ $satir['dh_url']=$satir->dh_url; }
	$satir['dh_capt_on']=$satir->dh_capt_on;
	$dh=$satir->dh;
	$satir['aktif']=$satir->aktif;
}

switch($dh){ //D Duyuru, K Kurumsal, H haber
	case "D" : $dha=" Duyuru"; $dhi="bullhorn"; break; 
	case "K" : $dha=" Kurumsal Duyuru"; $dhi="bullhorn"; break;
	case "H" : $dha=" Haber"; $dhi="paper-plane"; break;
	default : $dh="D"; $dha=" Duyuru"; $dhi="bullhorn";
}
//*/
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $ini['title']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.css" rel="stylesheet">
	<?php include("../set_page.php"); ?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("../sidebar.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include("../topbar.php"); ?>
                    
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h5 mb-0 text-gray-800"><?php echo $satir->dh_baslik; ?></h1>
						<div><small><?php echo "Yayım Tarihi:".$satir['dh_ytar']; ?></small></div>
                    </div>
                    <!-- Content Row -->

                    <div class="row">

                        <!-- Duyurular -->
                        <div class="col-xl-9 col-lg-8">
                            <!-- 5 Duyuru gelsin, devamı _blank açılsın. -->
                            <div class="card shadow mb-4"><?php								
								if($satir->dh_resim!="") { $resimyolu=$satir->dh_resim; }else { $resimyolu='/img/undraw_posting_photo.svg'; }
								?>
                                <div class="card-body">
									<!--h5 class="text-center"><?php echo $satir['dh_baslik']; ?></h5-->
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style=""
                                            src="<?php echo $resimyolu; ?>" alt="...">
                                    </div>
                                    <p><?php echo $satir->dh_icerik; ?></p>
                                    <?php 
									if($satir->dh_dlink!=""){ ?> 
									<p><a href="javascript:open('<?php echo "/Docs/".$satir->dh_dlink; ?>');">Dosya<?php echo ":".$satir->dh_dlink;?></a></p> <?php } 
									if($satir->dh_url!=""){ ?>
									<p class="text-center"><a href="<?php echo $satir->dh_url; ?>" target="_blank">Haberin Linki</a></p><?php 
									} ?>
                                </div>
                            </div>
                        </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("../footer.php"); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>