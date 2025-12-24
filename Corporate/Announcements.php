<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<?php
include("../set_mng.php");
include("../sess.php");
for($g=0;$g<7;$g++){
	$gunler[]=$gtext['day'.$g];
}
$bugun=date("Y.m.d 00:00:00", strtotime("now"));
$yarin=date("Y.m.d 00:00:00", strtotime("+1 day"));
?>
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
                        <h1 class="h3 mb-0 text-gray-800">Tüm Duyurular</h1>
                        <!--a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a-->
                    </div>
                    <!-- Content Row -->

                    <div class="row">

                        <!-- Duyurular -->
                        <div class="col-xl-9 col-lg-8">
                            <!-- 5 Duyuru gelsin, devamı _blank açılsın. --><?php
							$dq="SELECT dh_uid, dh_baslik, dh_icerik, dh_resim, dh_ytar, dh_sgtar FROM zduyuru_haber 
							WHERE dh='D' 
							ORDER BY dh_ytar"; //şimdilik hepsi gelsin. sayfalama gelince değişir.
							$dresult = $baglan->query($dq); //echo $dhq;  ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Duyurular</h6>
                                </div><?php
								for($ii=0; $drow = mysqli_fetch_assoc($dresult); $ii++){ 
								if($drow['dh_resim']!="") { $resimyolu=$drow['dh_resim']; }else { $resimyolu='img/undraw_posting_photo.svg'; }
								?>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                            src="<?php echo "../".$resimyolu; ?>" alt="...">
											<h5 class="text-center"><?php echo $drow['dh_baslik']; ?></h5>
                                    </div>
                                    <p><?php echo $drow['dh_icerik']; ?></p>
                                </div>
								<?php } ?>
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
    <script src="../vendor/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

</body>

</html>