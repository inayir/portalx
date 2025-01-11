<?php
/*
	Org Scheme
*/
include('../set_mng.php');
//
error_reporting(0);
include($docroot."/sess.php");

@$collection=$db->orgsema;
@$cursor = $collection->find(
    [
		'aktif'=>1
    ],
    [
        'limit' => 0,
        'projection' => [
            '_id' => 1,
            'orgs_tanim' => 1,
			'orgs_dosya' => 1,
			'orgs_tarih' => 1,
			'olusturan' => 1,
			'gtar' => 1,
			'son_deg_per' => 1,
			'son_deg_tar' => 1,
			'aktif' => 1,
        ],
		'sort'=>['orgs_tarih'=>-1]
    ]
);
$fsatir=[]; 
foreach ($cursor as $formsatir) {
	$satir=[]; 
	if($formsatir->orgs_tarih!=null){ 
		$utcdt=	$formsatir->orgs_tarih->toDateTime()->format($ini['date_local']); 
		$dt=$utcdt; 
	}else{ $dt='';}  
	$satir['orgs_tarih']=$dt; 
	$satir['orgs_tanim']=$formsatir->orgs_tanim; //"Ana Organizasyon Şeması";
	$satir['orgs_dosya']=$formsatir->orgs_dosya; //$satir['orgs_dosya']="org_sema_10072023_onayli.pdf";
	$satir['olusturan']=$formsatir->olusturan; //$satir['orgs_dosya']="org_sema_10072023_onayli.pdf";
	if($formsatir->gtar!=null){ 
		$utcdt=	$formsatir->gtar->toDateTime()->format($ini['date_local']); 
		$dt=$utcdt; 
	}else{ $dt='';} 
	$satir['gtar']=$dt; //"01.01.2024"; 
	$satir['son_deg_per']=$formsatir->son_deg_per;
	if($formsatir->son_deg_tar!=null){ 
		$utcdt=	$formsatir->son_deg_tar->toDateTime()->format($ini['date_local']); 
		$dt=$utcdt; 
	}else{ $dt='';}  
	$satir['son_deg_tar']=$dt; //"01.01.2024"; 
	$fsatir[]=$satir;
}
$fisay=count($fsatir); 
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['orgscheme']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
	    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
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
                <div class="container-fluid" style="min-height: 600px;">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-sitemap"></i> <?php echo $gtext['orgscheme']; ?></h1>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
						<?php if($fisay>0){ ?>
						<small class="p-1"><?php echo $gtext['description'].":".$fsatir[0]['orgs_tanim']." ".$gtext['pubdate'].":".$fsatir[0]['orgs_tarih'];?></small>
						<iframe src="<?php echo $ini['Org_Sema_Dir'].'/'.$fsatir[0]['orgs_dosya'];?>" width='100%' height='800'></iframe>
						<?php }else{
							echo $gtext['orgscheme']." ".$gtext['notfound']; //"Organizasyon Şeması bulunamadı!";
						}?>
					<div id="alanlar">
					</div>
                    
                    </div>

<?php 

?>
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

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.min.js"></script>

</body>

</html>