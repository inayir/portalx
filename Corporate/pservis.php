<?php
/*
	Personel Shuttles
*/
include('../set_mng.php');
//
error_reporting(0);
include($docroot."/sess.php");
if($user==""){ //gerekliyse.
	header('Location: /login.php');
}
//
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
//
$ymexists = in_array('ymenu', $collectionNames);
if(!$ymexists){ 
	$db->createCollection('ymenu',[
	]);
}
@$collectionym=$db->ymenu;

if($_SESSION['y_addinfoser']==1){  $aktif=0; }
@$cursor = $collection->find(
    [
		'$or'=>[['aktif'=>1],['aktif'=>$aktif]]
    ],
    [
        'limit' => 0,
        'projection' => [
            '_id' => 1,
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
$fsatir=Array();
foreach ($cursor as $formsatir) {
	$satir=[];
	$satir['_id']			=$formsatir->_id;
	$satir['pser_kodu']		=$formsatir->pser_kodu;
	$satir['pser_tanim']	=$formsatir->pser_tanim;
	$satir['pser_bolge']	=$formsatir->pser_bolge;
	$satir['pser_sofor']	=$formsatir->pser_sofor;
	$satir['pser_sofor_tel']=$formsatir->pser_sofor_tel;
	$satir['pser_firma']	=$formsatir->pser_firma;
	$satir['pser_plaka']	=$formsatir->pser_plaka;
	$satir['pser_kapasite']	=$formsatir->pser_kapasite;
	$satir['pser_sorumlu']	=$formsatir->pser_sorumlu;
	$satir['pser_sorumlu_tel']=$formsatir->pser_sorumlu_tel;
	if($formsatir->pser_kmz!=null){ $satir['pser_kmz']		=$formsatir->pser_kmz; }
	$satir['olusturan']		=$formsatir->olusturan;
	if($formsatir->gtar!=null){ 
		$utcdt=	$formsatir->gtar->toDateTime()->format($ini['date_local']." H:i"); 
		$dt=$utcdt;
	}else{ $dt='';}
	$satir['gtar']			=$dt; 
	$satir['son_deg_per']	=$formsatir->son_deg_per;
	$fsatir[]=$satir; 
}; 
$fisay=count($fsatir); //echo "fisay:".$fisay."<br>"; //for($ii=0; $ii<$fisay-1;$ii++){ }///exit;//*/
//var_dump($fsatir); //exit;
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $ini['shuttles']; /*Personel Servisleri*/?> </title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
                        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-route"></i> Personel Servis Güzergahları <?php if($_SESSION['y_addinfoser']==1){ "Girişi"; } ?></h1>
						<?php if($_SESSION['y_addinfoser']==1){ ?><a id="eklebtn" href="#" data-toggle="modal" data-target="#servisekleModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
						class="fas fa-shuttle-van fa-sm text-white-50"></i> Servis Ekle </a><?php } ?>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                      <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="list" width="100%" cellspacing="0">
                                    <thead>
										<tr>
											<th class="text-center">Kodu</th>
											<th class="text-center">Tanımı</th>
											<th class="text-center" width="30%">Bölge</th>
											<th class="text-center">Plaka</th>
											<th class="text-center">Şoför</th>
											<th></th>
										</tr>
                                    </thead>
                                    <tfoot>
										<tr>
											<th class="text-center">Kodu</th>
											<th class="text-center">Tanımı</th>
											<th class="text-center" width="30%"><small>Bölge</small></th>
											<th class="text-center">Plaka</th>
											<th class="text-center">Şoför</th>
											<th></th>
										</tr>
									</tfoot>
									<tbody><?php
									for($i=0; $i<$fisay; $i++){ ?>
										<tr>
											<td><?php echo $fsatir[$i]['pser_kodu'];?></td>
											<td><?php echo $fsatir[$i]['pser_tanim'];?></td>
											<td><?php echo $fsatir[$i]['pser_bolge'];?></td>
											<td><?php echo $fsatir[$i]['pser_plaka'];?></td>
											<td><?php echo $fsatir[$i]['pser_sofor'];?></td>
											<td>
												<div class='btn-group btn-group-md'><?php if($_SESSION['y_addinfoser']==1){ ?>
													<button class="btn btn-primary" onClick="javascript:pseredit('<?php echo $fsatir[$i]['_id']; ?>');"><i class="fas fa-edit fa-sm text-white-50"></i> Düzenle</button>
													<a class="btn btn-secondary" href="javascript:yolcu('<?php echo $fsatir[$i]['_id']; ?>');"><i class="fas fa-user fa-sm text-white-50"></i> Yolcu</a><?php } ?>
												</div>
											</td>
										</tr><?php 
	}
 ?>
</tbody>
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

<!-- servisekle Modal-->
				<div class="modal fade" id="servisekleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<form id="form_servisekle" method="POST" action="set_pser.php">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Servis Ekleme</h5>
								<button class="close" type="button" data-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
							<table class="table table-striped">
							<tr>
								<td class="text-right" width="15%">Kodu </td>
								<td>
									<input class="form-control" type="text" name="pser_kodu" id="pser_kodu" value="" />
									<input type="hidden" name="id" id="id" value="" />
								</td>
							</tr>
							<tr>
								<td class="text-right">Tanım</td>
								<td><input class="form-control" type="text" name="pser_tanim" id="pser_tanim" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">Bölge</td>
								<td><textarea class="form-control" type="text" name="pser_bolge" id="pser_bolge"></textarea></td>
							</tr>
							<tr>
								<td class="text-right">Plaka</td>
								<td><input class="form-control" type="text" name="pser_plaka" id="pser_plaka" /></td>
							</tr>
							<tr>
								<td class="text-right">Kapasite</td>
								<td><input class="form-control" type="text" name="pser_kapasite" id="pser_kapasite" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">Şoför</td>
								<td><input class="form-control" type="text" name="pser_sofor" id="pser_sofor" id="pser_sofor" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">Şoför Tel</td>
								<td><input class="form-control" type="text" name="pser_sofor_tel" id="pser_sofor_tel" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">Sorumlu Personel</td>
								<td><input class="form-control" type="text" name="pser_sorumlu" id="pser_sorumlu" id="pser_sofor" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">Sorumlu Tel</td>
								<td><input class="form-control" type="text" name="pser_sorumlu_tel" id="pser_sorumlu_tel" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">Firma</td>
								<td><input class="form-control" type="text" name="pser_firma" id="pser_firma" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">Durum</td>
								<td>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="aktif" id="aktif_1" value="1" checked />
										<label class="form-check-label" for="aktif_1"> Aktif</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="aktif" id="aktif_0" value="0" />
										<label class="form-check-label" for="aktif_0"> Pasif</label>
									</div>
								</td>
							</tr>
							</table>
							<span>Oluşturan:<span id='olusturanp'></span>(<span id='gtarp'></span>), Son Değişiklik:<span id='son_deg_perp'></span>(<span id='son_deg_tarp'></span>)</span>
							<input type="hidden" name="olusturan" id="olusturan" value="" />
							<input type="hidden" name="gtar" id="gtar" value="" />
							<input type="hidden" name="son_deg_per" id="son_deg_per" value="" />
							<input type="hidden" name="son_deg_tar" id="son_deg_tar" value="" />
							</div>
							<div class="modal-footer">
								<button class="btn btn-secondary" type="reset" id="cancel" data-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
								<button class="btn btn-primary" id="servisekle" disabled type="submit"><?php echo $gtext['insert']; ?></button>
							</div>
							</form>
						</div>
					</div>
				</div>
			<!--servisekle modal sonu-->
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="/js/demo/datatables-demo.js"></script>
<script>
var isl='';
var dturl="<?php echo $_SESSION['lang'];?>"; 
const obj=JSON.parse('<?php echo json_encode($fsatir); ?>'); //console.log(obj);
$(document).ready(function(){
	var table=$('#list').DataTable( {
        "language": {
			url :"../vendor/datatables/"+dturl+".json",
		}
	});
	$('#servisekle').on("click", function(){ //ekle/değiştir ajaxform
		var opt={
			type	: 'POST',
			url 	: './set_pserm.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('Emin Misiniz?');
			},
			success: function(data){  //console.log('Önizleme :'+data);
				if(data!='!-'){ alert(data); location.reload(); }
				else { alert('Bir hata oluştu!'); }
			}
		}
		$('#form_servisekle').ajaxForm(opt); //*/
	});
	$('form').find(':input').change(function(){ $('#servisekle').prop("disabled", false ); });
	$('#cancel').on('click', function(){ $('#servisekle').prop("disabled", true ); });
});
	function yolcu(id){  //alert('Yolcu İşlemleri:'+id);
		window.open('adm_pser_yolcu.php?s='+id,'_blank');
	}
	function pseredit(ser){ //bilgiler getirilir...	console.log(' servis:'+ser);
		const result = obj.find(({ _id }) => _id.$oid === ser);	
		$('#id').val(result['_id'].$oid); 
		$('#pser_kodu').val(result['pser_kodu']); 
		$('#pser_tanim').val(result['pser_tanim']); 
		$('#pser_bolge').html(result['pser_bolge']); 
		$('#pser_plaka').val(result['pser_plaka']); 
		$('#pser_kapasite').val(result['pser_kapasite']); 
		$('#pser_sofor').val(result['pser_sofor']); 
		$('#pser_sofor_tel').val(result['pser_sofor_tel']);
		$('#pser_sorumlu').val(result['pser_sorumlu']); 
		$('#pser_sorumlu_tel').val(result['pser_sorumlu_tel']); 
		$('#pser_firma').val(result['pser_firma']); 
		$('#olusturanp').html(result['olusturan']); 
		$('#olusturan').val(result['olusturan']); 
		$('#gtarp').html(result['gtar']); 
		$('#gtar').val(result['gtar']); 
		$('#son_deg_perp').html(result['son_deg_per']); 
		$('#son_deg_per').val(result['son_deg_per']); 
		$('#son_deg_tarp').html(result['son_deg_tar']); 
		$('#son_deg_tar').val(result['son_deg_tar']); 
		$('#aktif_'+result['aktif']).attr('checked', true); 
		$('#servisekle').html('Değiştir');//*/
		isl='E';
		$('#eklebtn').click();
	}
	$('#eklebtn').on('click',function(){
		if(isl==''){ $('#cancel').click(); }
	});
	$('#servisekleModal').on('blur',function(){
		isl='';
		$('#pser_bolge').html('');
	});

</script>
</body>

</html>