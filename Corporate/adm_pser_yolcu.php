<?php
/*
	Personel Servislerine yolcu ekle, kayıtlı yolcuları gör/değiştir
*/
error_reporting(0);
include("../set_mng.php");
include($docroot."/sess.php");
if($_SESSION["user"]==""){
	echo "login"; exit;
}//*/
$id=$_GET['s'];
$collections = $db->listCollections();
$collectionNames = [];
foreach ($collections as $collection) {
  $collectionNames[] = $collection->getName();
}
$exists = in_array('pservis', $collectionNames);
if(!$exists){ 
	$db->createCollection('pservis',[
	]);
}
@$collection=$db->pservis;
$idd=new MongoDB\BSON\ObjectID($id);
@$cursor = $collection->find(
    [
		'_id'=>$idd
    ],
    [
        'limit' => 1,
        'projection' => [
            'pser_kodu' => 1,
            'pser_tanim' => 1,
			'pser_bolge' => 1,
			'pser_sofor' => 1,
            'pser_sofor_tel' => 1,
			'pser_firma' => 1,
			'pser_plaka' => 1,
			'pser_kapasite' => 1,
			'pser_sorumlu' => 1,
            'pser_sorumlu_tel' => 1,
			'pser_kmz' => 1,
			'olusturan' => 1,
			'gtar' => 1,
			'son_deg_per' => 1,
			'son_deg_tar' => 1,
			'aktif' => 1,
        ],
		'sort'=>['pser_kodu'=>1]
    ]
);
foreach ($cursor as $formsatir) {
	$satir=[];
	$satir['pser_kodu']		=$formsatir->pser_kodu;
	$satir['pser_tanim']	=$formsatir->pser_tanim;
	$satir['pser_plaka']	=$formsatir->pser_plaka;
	$satir['pser_sofor']	=$formsatir->pser_sofor;
	$satir['pser_bolge']	=$formsatir->pser_bolge;
	$satir['aktif']			=intval($formsatir->aktif);
	//$fsatir[]=$satir; 
}; 
//-------------Servis Personeli-------------
@$pcoll=$db->personel;
@$pcur = $pcoll->find(
    [
		'$and'=>[['pser_kodu'=>$satir['pser_kodu']],['state'=>'A']],
    ],
    [
        'limit' => 0,
        'projection' => [
            'description' => 1,
            'displayname' => 1,
			'department' => 1,
			'telephoneNumber' => 1,
            'email' => 1,
			'username'=>1,
        ],
		'sort'=>['adisoyadi'=>1]
    ]
);
foreach ($pcur as $pformsatir) {
	$yolcusatir=[];
	$yolcusatir['sicilno']		=$pformsatir->description;
	$yolcusatir['adisoyadi']	=$pformsatir->displayname;
	$yolcusatir['birim']		=$pformsatir->department;
	$yolcusatir['telefon']		=$pformsatir->telephoneNumber;
	$yolcusatir['email']		=$pformsatir->email;
	$yolcusatir['username']		=$pformsatir->username;
	$yolcusatirlari[]=$yolcusatir; 
}; 
//-------Servise personel eklerken getirilecek bir servise kayıtlı olmayan personel.
@$tpcoll=$db->personel;
@$tpcur = $tpcoll->find(
    [
		'$and'=>[['pser_kodu'=>null],['state'=>'A']]
    ],
    [
        'limit' => 0,
        'projection' => [
            'department' => 1,
            'displayname' => 1,
			'department' => 1,
			'telephoneNumber' => 1,
            'email' => 1,
			'username'=>1,
        ],
		'sort'=>['pser_kodu'=>1]
    ]
);
foreach ($tpcur as $tpformsatir) {
	$tpsatir=[];
	$tpsatir['sicilno']		=$tpformsatir->department;
	$tpsatir['adisoyadi']	=$tpformsatir->displayname;
	$tpsatir['birim']		=$tpformsatir->department;
	$tpsatir['telefon']		=$tpformsatir->telephoneNumber;
	$tpsatir['email']		=$tpformsatir->email;
	$tpsatir['username']	=$tpformsatir->username;
	$ftpsatir[]=$tpsatir; 
};
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['a_pserpassengers']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.js"></script>
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <script src="/js/sb-admin-2.js"></script>
	<!--DataTables-->
	<link href="/vendor/datatables.net/datatables.min.css" rel="stylesheet"> 
	<script src="/vendor/datatables.net/datatables.min.js"></script>
	<script src="/vendor/datatables.net/pdfmake.min.js"></script>
	<script src="/vendor/datatables.net/vfs_fonts.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>

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
                        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-route"></i> <?php echo $gtext['a_pserpassengers'];/*Personel Servis Yolcuları*/?></h1>
						<div class='btn-group btn-group-md'>
							<a type='button' class='d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm' data-bs-target="#yolcuekleModal" data-bs-toggle="modal"><i class='fas fa-user-plus fa-sm text-white-50'></i> <?php echo $gtext['passenger']." ".$gtext['insert'];/*Yolcu Ekle*/?></a>
							<button class="d-none d-sm-inline-block btn btn-sm btn-bordered border-success shadow-sm" id='yenile' onClick="location.reload();"><?php echo $gtext['refresh'];/*Yenile*/?></button>
						</div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<table class="table table-striped" style="border: 1px; border-style: solid;">
						<thead>
						<th class="text-center"><?php echo $gtext['code'];/*Kodu*/ ?></th>
						<th class="text-center"><?php echo $gtext['description'];/*Tanımı*/?></th>
						<th class="text-center"><?php echo $gtext['numberplate'];/*Plaka*/?></th>
						<th class="text-center"><?php echo $gtext['driver'];/*Şoför*/?></th>
						<th class="text-center w-25"><?php echo $gtext['sh_route']."/".$gtext['area'];/*Güzergah/Bölge*/?></th>
						<th class="text-center"><?php echo $gtext['active'];/*Aktif*/?></th>
						<th></th>
						</thead>
						<tbody>
						<tr>
							<td><?php echo $satir['pser_kodu'];?></td>
							<td><?php echo $satir['pser_tanim'];?></td>
							<td><?php echo $satir['pser_plaka'];?></td>
							<td><?php echo $satir['pser_sofor'];?></td>
							<td><small><?php$satir['pser_bolge']?></small></td><?php $r=($satir['aktif']==1)? 'Aktif' : 'Pasif'; ?>
							<td><?php echo $r;?></td>
						</tr>
						</tbody>
						</table>
					</div>
					<!-- Content Row -->
                    <div class="row">
					  <!--Serviste kayıtlı yolcular-->
                      <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"><?php echo $gtext['passengers'];/*Yolcular*/?></h6>
                        </div>
                        <div class="card-body"><?php
						if (count($yolcusatirlari)>0) { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="pser_ylist" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th><?php echo $gtext['pernumber'];/*Sicil*/?></th>
                                            <th><?php echo $gtext['name'];/*İsim*/?></th>
                                            <th><?php echo $gtext['area'];/*Bölüm*/?></th>
                                            <th><?php echo $gtext['telephonenumber'];/*Telefon*/?></th>
                                            <th><?php echo $gtext['a_mail'];/*Mail*/?></th>
                                            <th><?php echo $gtext['process'];/*İşlem*/?></th>
                                        </tr>
                                    </thead>
                                    <!--tfoot>
                                        <tr>
                                            <th>SNo</th>
                                            <th>İsim</th>
                                            <th>Bölüm</th>
                                            <th>Telefon</th>
                                            <th>Mail</th>
                                            <th>İşlem</th>
                                        </tr>
                                    </tfoot-->
                                    <tbody><?php 																		  
										for($i=0; $i<count($yolcusatirlari); $i++){ ?>
										<tr>
                                            <td><?php echo $yolcusatirlari[$i]['sicilno']; ?></td>
                                            <td><?php echo $yolcusatirlari[$i]['adisoyadi']; ?></td>
                                            <td><?php echo $yolcusatirlari[$i]['birim']; ?></td>
                                            <td><?php echo $yolcusatirlari[$i]['telefon']; ?></td>
                                            <td><?php echo $yolcusatirlari[$i]['email']; ?></td>
                                            <td><a class="btn btn-primary" onClick="javascript:pser_yolcu('<?php echo $yolcusatirlari[$i]['username']; ?>', 'C');"><i class='fas fa-user-minus fa-sm text-white-50'></i> <?php echo $gtext['remove'];/*Çıkar*/?></a></td>
                                        </tr>
										<?php } ?>
									</tbody>
                                </table>
						  <?php }else{ echo "Yolcu Bulunamadı!";} ?>
							</div>
						
						</div>
                      </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("../footer.php"); ?>
            <!-- End of Footer -->
			<!-- yolcuekle Modal   serviste kayıtlı olmayan kişiler..........................................................-->
	<div class="modal fade" id="yolcuekleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="yolcuekleModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog centered">
			<div class="modal-content">							
				<div class="modal-header">
					<h5 class="modal-title fs-5" id="yolcuekleModalLabel"><?php echo $gtext['a_pseraddpassengers'];/*Yolcu Ekleme*/?></h5>
					<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
					<table class="table table-bordered" id="tumlist" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th><?php echo $gtext['name'];/*İsim*/?></th>
							<th><?php echo $gtext['area'];/*Bölüm*/?></th>
							<th><?php echo $gtext['process'];/*İşlem*/?></th>
						</tr>
					</thead>
					<tbody><?php														  
						for($i=0; $i<count($ftpsatir); $i++){ ?>
						<tr>
							<td><?php echo $ftpsatir[$i]['adisoyadi']; ?></td>
							<td><?php echo $ftpsatir[$i]['birim']; ?></td>
							<td><a class="btn btn-primary" onClick="javascript:pser_yolcu('<?php echo $ftpsatir[$i]['username']; ?>', 'E');"><i class='fas fa-user-plus fa-sm text-white-50'></i> <?php echo "*".$gtext['insert'];/*Ekle*/ ?></a></td>
						</tr>
						<?php } ?>
					</tbody>
					</table>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" id="cancel" data-bs-dismiss="modal"><?php echo $gtext['cancel'];/*Vazgeç*/?></button>
					<button class="btn btn-primary" id="servisekle" disabled type="submit"><?php echo $gtext['insert'];/*Ekle*/ ?></button>
				</div>
			</div>
		</div>
	</div>
			<!--yolcuekle modal sonu-->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
	
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
<script>
var ps="<?php echo $satir['pser_kodu']; ?>";
var pservis="<?php echo $satir['pser_tanim']; ?>";
var instext="<?php echo $gtext['insert']; ?>";
var dturl="<?php echo $_SESSION['lang'];?>";
$(document).ready(function() { 
	$('#pser_ylist').DataTable({ "language": { url :"../vendor/datatables.net/"+dturl+".json", } });
	$('#tumlist').DataTable( { 	 "language": { url :"../vendor/datatables.net/"+dturl+".json", } });
});
function pser_yolcu(username, isl){ 
	$.ajax({
		type: 'POST',
		url: 'set_pser_yolcu.php',
		data: { 'u': username, 'ps': ps, 'isl': isl },
		success: function (data){ 
			alert(data); 
			$('#yenile').click();
		}
	});
}
</script>
</body>

</html>