<?php
/*
Belge Göster MongoDB
*/
declare(strict_types=1);
namespace MongoDB\Examples\Bulk;
use MongoDB\Client;
use MongoDB\Driver\WriteConcern;
use function assert;
use function getenv;
use function is_object;
use function MongoDB\BSON\fromPHP;
use function MongoDB\BSON\toRelaxedExtendedJSON;
use function printf;
//error_reporting(0);
require __DIR__ . '/../vendor/autoload.php';
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
include($docroot."/sess.php"); 

$id=$_GET['d']; //$d=uid

@$client = new Client($ini['MongoConnection']);
$dbi=$ini['MongoDB'];
@$db=$client->$dbi;
@$collection=$db->k_belgeler;
//Belge			
	@$cursor = $collection->find(
		[
			'_id'=>new \MongoDB\BSON\ObjectId($id)
		],
		[
			'limit' => 1,
			'projection' => [
				'_id' => 1,	
				'tip' => 1,
				'dosya' => 1,
			],
		]
	);
$satir=[];
foreach ($cursor as $formsatir) {
	$satir['tip']=$formsatir->tip;
	$satir['dosya']=$formsatir->dosya;
}
$yol=$ini[$satir['tip'].'_yol']."/".$satir['dosya']; //echo  $yol; //exit;
switch($satir['tip']){
	case 'b_certs': $btipi="Sertifika"; break;
	case 'b_quals': $btipi="Kalifikasyon"; break;
	default: $btipi="Sertifikalar";
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

    <title><?php echo $gtext['doc_view']; /* Belge Göster*/?></title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.css" rel="stylesheet">
<?php include($docroot."/set_page.php"); ?>

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
                <div class="container-fluid" style="min-height: 600px;">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-sitemap"></i> Belgeler </h1>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
						<?php 
						 ?>
						<iframe src="<?php echo $yol; ?>" width="100%" height="800"></iframe>				
						<div id="alanlar"></div> 
						<small><?php echo "Belge Yolu: ".$yol." - Tipi:".$btipi; ?></small>
                    </div>

<?php 

?>
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
</body>

</html>