<?php
/*
Duyuruları listeler.
*/
include('../set_mng.php');
include($docroot."/app/php_functions.php");
//error_reporting(0);
include($docroot."/config/config.php");
include($docroot."/sess.php"); 
//
$dh=$_GET['dh'];
switch($dh){ //D Duyuru, K Kurumsal, H haber
	case "D" : $dha=" Duyurular"; $dhi="bullhorn"; break; 
	case "K" : $dha=" Kurumsal Duyurular"; $dhi="bullhorn"; break;
	case "H" : $dha=" Haberler"; $dhi="paper-plane"; break;
	default : $dh="D"; $dha=" Duyuru"; $dhi="bullhorn";
}
$biryil=datem(date("Y.m.d 00:00:00", strtotime("+1 year")));
@$collection=$db->k_dhaber;
//Haber			
$cursor = $collection->aggregate([
	[
		'$match'=>[
			'$and'=>[['dh'=>$dh],['dh_sgtar'=>['$gte'=>$biryil]]],
		],
	],
	[
		'$sort' => [
		  'dh_ytar' => -1, 
		],
	],
	[
		'$limit'=>10,
	],
]);
$fsatir=[];
foreach ($cursor as $formsatir) {
	try{
		$satir=[]; 
		$satir['_id']=$formsatir->_id;  
		if(@$formsatir->dh_baslik!=null){ $satir['dh_baslik']=$formsatir->dh_baslik;	}	
		if(@$formsatir->dh_icerik!=null){ 
			$g=$formsatir->dh_icerik;
			$satir['dh_icerik']=addslashes($g);			
		}	
		if(@$formsatir->dh_resim!=null){ $satir['dh_resim']=$formsatir->dh_resim;	}
		if($formsatir->dh_ytar!=null){ $satir['dh_ytar']=$formsatir->dh_ytar->toDateTime()->format($ini['date_local']); }
		if($formsatir->dh_sgtar!=null){ 
			$satir['dh_sgtar']=$formsatir->dh_sgtar->toDateTime()->format($ini['date_local']); 
		}
		$satir['aktif']=$formsatir->aktif;
		$fsatir[]=$satir;
	}catch(Exception $e){
		
	}
}
$fisay=count($fsatir); //echo "fisay:".$fisay; //var_dump($fsatir); exit;
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['all']." ".$dha; ?></title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="../vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
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
                <?php include("../topbar.php"); 
/*/pservis dosyasından liste getirilir.
$dhq="SELECT * FROM zduyuru_haber WHERE dh='".$dh."' AND aktif=1 ORDER BY dh_ytar DESC ";
$dhresult = $baglan->query($dhq);//*/
?>
                    
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><i class='fas fa-<?php echo $dhi."'></i> ".$dha; ?></h1>
						<!--a href="adm_dh_ekle.php?dh=D" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-bullhorn fa-sm text-white-50"></i> Duyuru Ekle </a-->
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                      <div class="card shadow mb-4 w-100">
					    <div class="card-body">
						  <div class="table-responsive">
							<table id="duyuru_list" class="table table-striped" width="100%" cellspacing="0">
							<thead>
								<th class="text-center"></th>
								<th class="text-center w-75">Haber</th>
								<th title='İşlemler'></th>
							</thead>
							<tfoot>
								<th class="text-center"></th>
								<th class="text-center">Haber</th>
								<th title='İşlemler'></th>
							</tfoot>
							<tbody>
					<?php
						if ($fisay>0) {
							for($i=0; $i<$fisay; $i++){ ?>
								<tr>
									<td><?php 
									if($fsatir[$i]['dh_resim']!="") { ?>
									<img class="img-fluid" style="width: 250px;"
									src="<?php echo $fsatir[$i]['dh_resim']; ?>" alt="..."><?php } ?></td>
									<td><?php echo $fsatir[$i]['dh_baslik']; 
									$hhi=$fsatir[$i]['dh_icerik']; echo "<br><small>".substr($hhi, 0, 150);if(strlen($hhi)>80){ echo "...";} ?></small></td>
									<td><a class='btn btn-primary' target='_blank' href='/Corporate/dh_card.php?u=<?php echo $fsatir[$i]['_id']; ?>'><i class='fas fa-eye fa-sm text-white-50'></i> Gör</a></td>
								</tr><?php 
							}
						}
					?>
							<tbody>
						</table>
					  </div>
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
	<!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>
	<script src="../vendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
<script>
var dturl="<?php echo $_SESSION['lang'];?>"; 
$(document).ready(function() {
	$('#duyuru_list').DataTable( {
        "language": {
			url :"../vendor/datatables/"+dturl+".json",
		}
	});
});
</script>
</body>

</html>