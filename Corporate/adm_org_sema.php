<?php
/*

*/
include('../set_mng.php'); 
error_reporting(0);
include($docroot."/sess.php");
if($user==""){ //gerekliyse.
	header('Location: /login.php');
}
@$collection=$db->orgsema;
if($_SESSION['y_bo']==1){  $aktif=0; }
@$cursor = $collection->find(
    [
		'$or'=>[['aktif'=>1],['aktif'=>$aktif]]
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
	$satir['_id']=$formsatir->_id; 
	$satir['orgs_tanim']=$formsatir->orgs_tanim;
	$satir['orgs_dosya']=$formsatir->orgs_dosya; 
	if($formsatir->orgs_tarih!=null){ 
		$utcdt=	$formsatir->orgs_tarih->toDateTime()->format($ini['date_local']); 
		$dt=$utcdt; 
	}else{ $dt='';} 
	$satir['orgs_tarih']=$dt; 
	$satir['olusturan']=$formsatir->olusturan; 
	if($formsatir->gtar!=null){ 
		$utcdt=	$formsatir->gtar->toDateTime()->format($ini['date_local']." H:i");
		$dt=$utcdt;
	}else{ $dt='';}
	$satir['gtar']	=$dt; 
	$satir['son_deg_per']=$formsatir->son_deg_per;
	if($formsatir->son_deg_tar!=null){ 
		$utcdt=	$formsatir->son_deg_tar->toDateTime()->format($ini['date_local']." H:i"); 
		$dt=$utcdt; 
	}else{ $dt='';}
	$satir['son_deg_tar']=$dt;
	$satir['aktif']=$formsatir->aktif;
	$fsatir[]=$satir;
}
$fisay=count($fsatir); //echo "fisay:".$fisay;
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['a_orgschememan'];/*Org.Şema Yönetimi*/?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
	
    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
	<link href="/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">

    <!-- Custom styles and scripts for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <!-- Page level plugins -->
	<link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>
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
                        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-sitemap"></i> <?php echo $gtext['a_orgschemes'];/*Organizasyon Şemaları*/?></h1>
                        <?php if($_SESSION['y_addinfoser']==1){ ?><a id="eklebtn" href="#" data-bs-toggle="modal" data-bs-target="#orgsekleModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> <?php echo $gtext['a_neworgscheme'];/*Yeni Şema Ekle*/?></a><?php } ?>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
					  <div class="card shadow mb-2 w-100">
                        <div class="card-header py-2 d-sm-flex align-items-center justify-content-between"><small><?php echo $gtext['a_neworgscheme'];/*uyarı.*/?></small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-responsive" id="list" cellspacing="0">
                                    <thead>
										<tr>
											<th class="text-center"><?php echo $gtext['pubdate'];/*Yayım Tarihi*/?></th>
											<th class="text-center"><?php echo $gtext['description'];/*Tanım*/?></th>
											<th class="text-center" width="30%"><?php echo $gtext['preview'];/*Önizleme*/?></th>
											<th></th>
										</tr>
                                    </thead>
                                    <tfoot>
										<tr>
											<th class="text-center"><?php echo $gtext['pubdate'];/*Yayım Tarihi*/?></th>
											<th class="text-center"><?php echo $gtext['description'];/*Tanım*/?></th>
											<th class="text-center" width="30%"><?php echo $gtext['preview'];/*Önizleme*/?></th>
											<th></th>
										</tr>
									</tfoot>
									<tbody><?php for($i=0; $i<$fisay; $i++){ ?>
										<tr>
											<td><?php echo $fsatir[$i]['orgs_tarih']; ?></td>
											<td><a href='org_sema.php' target='_blank'><?php echo $fsatir[$i]['orgs_tanim']; ?></a></td>
											<td><iframe src="<?php echo $ini['Org_Sema_Dir'].'/'.$fsatir[$i]['orgs_dosya']; ?>" width='100%'></iframe></td>
											<td><?php if($_SESSION['y_bo']==1){ ?><button class="btn btn-primary" onClick="javascript:orgsedit('<?php echo $fsatir[$i]['_id']; ?>');"><i class="fas fa-edit fa-sm text-white-50"></i> <?php echo $gtext['edit'];/*Düzenle*/?></button>
											<button class="btn btn-danger" onClick="javascript:orgssil('<?php echo $fsatir[$i]['_id']; ?>');"><i class="fas fa-delete fa-sm text-white-50"></i> <?php echo $gtext['delete'];/*Sil*/?></button>
											<?php } ?>
											</td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					
                    </div>
					<br>
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

<!-- sema ekle Modal-->
				<div class="modal fade" id="orgsekleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<form id="form_orgsekle" method="POST" action="set_orgs.php">
							<input type="hidden" name="_id" id="_id" value="" />
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel"><?php echo $gtext['a_neworgscheme'];/*Ekle*/?></h5>
								<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
							<table class="table table-striped">
							<tr>
								<td class="text-right"><?php echo $gtext['description'];/*Tanım*/?></td>
								<td colspan="3"><input class="form-control" type="text" name="orgs_tanim" id="orgs_tanim" value="" /></td>
							</tr>
							<tr>
								<td class="text-right"><?php echo $gtext['pubdate'];/*Yayım Tarihi*/?></td>
								<td><input class="form-control" type="text" name="orgs_tarih" id="orgs_tarih" value="" /></td>
								<td class="text-right"><?php echo $gtext['state'];/*Durum*/?></td>
								<td>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="aktif" id="aktif_1" value="1" checked />
										<label class="form-check-label" for="aktif_1"> <?php echo $gtext['active'];/*Aktif*/?></label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="aktif" id="aktif_0" value="0" />
										<label class="form-check-label" for="aktif_0"> <?php echo $gtext['passive'];/*Pasif*/?></label>
									</div>
								</td>
							</tr>
							<tr>
								<td class="text-right"><?php echo $gtext['file'];/*Dosya*/?></td>
								<td colspan="3">
									<input class="form-control" type="file" name="orgs_dosya" id="orgs_dosya" accept=".pdf,application/pdf" value=""/>
									<input class="form-control" type="hidden" name="orgs_dosya_adi" id="orgs_dosya_adi" value=""/>
								</td>
							</tr>
							<tr>
								<td colspan="4"><iframe id="onizleme" src="" width='100%'></iframe></td>
							</tr>
							</table>
							<span><?php echo $gtext['uploader'];/*Yükleyen*/?>:<span id='olusturanp'></span>(<span id='gtarp'></span>), <?php echo $gtext['last_change'];/*Son Değişiklik*/?>:<span id='son_deg_perp'></span>(<span id='son_deg_tarp'></span>)</span>
							<input type="hidden" name="olusturan" id="olusturan" value="" />
							<input type="hidden" name="gtar" id="gtar" value="" />
							<input type="hidden" name="son_deg_per" id="son_deg_per" value="" />
							<input type="hidden" name="son_deg_tar" id="son_deg_tar" value="" />
							</div>
							<div class="modal-footer">
								<button class="btn btn-secondary" type="reset" id="cancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
								<button class="btn btn-primary" id="orgsekle" disabled type="submit"><?php echo $gtext['insert']; ?></button>
							</div>
							</form>
						</div>
					</div>
				</div>
			<!--sema ekle modal sonu-->

    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/js/sb-admin-2.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>

<script>
var isl='';
var dturl="<?php echo $dil;?>"; 
const obj=JSON.parse('<?php echo json_encode($fsatir); ?>'); 
var Org_Sema_Dir="<?php echo $ini['Org_Sema_Dir']; ?>";
$(document).ready(function(){
	var table=$('#list').DataTable();
	if(dturl!='US'){
		table.language = {
			url :"../vendor/datatables.net/"+dturl+".json",
		}
	}
	$('#orgsekle').on("click", function(){ //ekle/değiştir
		var opt={
			type	: 'POST',
			url 	: './set_orgsm.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				return confirm('<?php echo $gtext['q_rusure']; /*Emin Misiniz?*/?>');
			},
			success: function(data){ 
				if(data!='!-'){ alert(data); location.reload(); }
				else { alert('Bir hata oluştu!'); }
			}
		}
		$('#form_orgsekle').ajaxForm(opt); //*/
	});
	$('form').find(':input').change(function(){ $('#orgsekle').prop("disabled", false ); });
	$('#cancel').on('click', function(){ $('#orgsekle').prop("disabled", true ); });
});
	function orgssil(s){
		$.ajax({
			type: 'POST',
			url: './set_orgsm.php',
			data: { 'del':'1', '_id': s },
			beforeSend : function(){
				return confirm('<?php echo $gtext['u_deleted']." ".$gtext['q_rusure']; /*Silinecek, Emin Misiniz?*/?>');
			},
			success: function (data){
				if(data.indexOf('eMEdi!')>-1){ alert('<?php echo $gtext['u_error'];/*Bir hata oluştu!*/?>\n'+data); }
				else { alert(data); location.reload();  }
			}
		});
	}
	function orgsedit(s){ 
		const result = obj.find(({ _id }) => _id.$oid === s);	
		$('#_id').val(result['_id'].$oid); //.$oid
		$('#orgs_tarih').val(result['orgs_tarih']); 
		$('#orgs_tanim').val(result['orgs_tanim']); 
		$('#orgs_dosya_adi').val(result['orgs_dosya']); 
		$('#olusturanp').html(result['olusturan']); 
		$('#olusturan').val(result['olusturan']); 
		$('#gtarp').html(result['gtar']); 
		$('#gtar').val(result['gtar']); 
		$('#son_deg_perp').html(result['son_deg_per']); 
		$('#son_deg_per').val(result['son_deg_per']); 
		$('#son_deg_tarp').html(result['son_deg_tar']); 
		$('#son_deg_tar').val(result['son_deg_tar']); 
		var yol=Org_Sema_Dir+'/'+result['orgs_dosya'];
		$('#onizleme').attr('src', yol); 
		$('#orgsekle').html('Değiştir');
		isl='E';
		$('#orgsekleModal').modal('show'); 
	}
	$('#orgs_dosya').change(function(){
		$('#orgs_dosya_adi').val($('#orgs_dosya').val());
	});
	$('#eklebtn').on('click',function(){
		if(isl==''){ $('#cancel').click(); }
	});
	$('#orgsekleModal').on('blur',function(){
		isl='';
		$('#onizleme').attr('src', '');
	});

</script>
</body>

</html>