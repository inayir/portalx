<?php
/*
	Pano_Client
*/
include("../set_mng.php");
//error_reporting(0);
include($docroot."/config/config.php");
include($docroot."/sess.php"); 
$log="\n";

@$collection=$db->personel_pano;
$cursor = $collection->aggregate([
   ['$lookup'=>
		[
		  'from'=>"personel",
		  'localField'=>"msg_sahibi",
		  'foreignField'=>"username",
		  'as'=>"persons"
		]
	],
	['$unwind'=>'$persons'],
	[
       '$addFields'=> [
           'adsoyad'=> '$persons.adisoyadi',
           'brm'=> '$persons.birim',
           'email'=> '$persons.email',
           'msg_sahibi'=> '$msg_sahibi',
           '_id'=> '$_id',
           'pano_konu'=> '$pano_konu',
           'pano_gond'=> '$pano_gond',
           'tarih'=> '$tarih',
           'pano_star'=> '$pano_star',
           'aktif'=> '$aktif',
       ],
    ],
	[
		'$sort'=>['tarih'=>-1]
	]
	
]);
$fsatir=[];
foreach ($cursor as $formsatir) {	//var_dump($formsatir);
	$satir=[]; 
	$satir['_id']=$formsatir->_id;  
	$satir['adsoyad']=$formsatir->adsoyad; 
	$satir['email']=$formsatir->email; 
	$satir['pano_konu']=$formsatir->pano_konu; 
	$g=preg_replace(array('/\r/', '/\n/')," ",$formsatir->pano_gond);
	$satir['pano_gond']=$g; 
	if($formsatir->tarih!=null){ 
		$satir['tarih']=$formsatir->tarih->toDateTime()->format("d.m.Y"); //echo $utcdt."<br>";
	}
	if($formsatir->pano_star!=null){ 
		$satir['pano_star']=$formsatir->pano_star->toDateTime()->format("d.m.Y"); //echo $utcdt."<br>"; 
	}
	$satir['msg_sahibi']=$formsatir->msg_sahibi;
	$satir['aktif']=$formsatir->aktif;
	$fsatir[]=$satir;
	$log.="\nP:".$satir['msg_sahibi']." AS:".$satir['adsoyad']." G:".$satir['pano_gond'];
}
$fisay=count($fsatir); //echo "fisay:".$fisay; 
//
$dosya="logs/pano.log"; 
touch($dosya);
$dosya = fopen($dosya, 'a');
fwrite($dosya, $log);
fclose($dosya); 
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

    <title>Pano</title>

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
        <?php include("sidebar.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include("topbar.php"); ?>                    
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Personel Haberleşme Panosu</h1>
                        <a id="eklebtn" href="#" data-bs-toggle="modal" data-bs-target="#pano_Modal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-pen-fancy fa-sm text-white-50"></i> Yeni Bilgi</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					  <div class="card shadow lg-11">
                        <div class="card-header py-2"><small>Dikkat edilecek <a href='#'>Pano Kuralları</a></small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <TABLE class="table table-striped" id="list" width="100%" cellspacing="0">								
								<THEAD>
									<TH>Gönderiler</TH>
								</THEAD>
								<TBODY>
<?php for($i=0; $i<$fisay; $i++){ ?>
								<TR>
								<TD>
									<table>
									<tr>
										<td>Gönderen: <?php echo $fsatir[$i]['adsoyad']; ?></td>
										<td class='text-right'>Tarih</td>
										<td><?php echo $fsatir[$i]['tarih']; ?></td>
									</tr>
									<tr>
										<td><?php echo $fsatir[$i]['pano_konu']; ?></td>
										<td class='text-right' style='width:10%'>Son Tarih</td>
										<td><?php echo date($ini['date_local'], strtotime($fsatir[$i]['pano_star'])); ?></td>
									</tr>
									<tr>
										<td style='border-style:solid; border-width:1px; border-radius:5px;' height='100%'><textarea disabled width='100%' cols='100'><?php echo $fsatir[$i]['pano_gond']; ?></textarea></td>
										<td class='text-right'></td>
										<td></td>
									</tr>
									<tr>
										<td colspan="4">
											<?php if($user==$fsatir[$i]['msg_sahibi']){ ?><button class="btn btn-primary" id="pano_edit" type="button" onClick="javascript:pedit('<?php echo $fsatir[$i]['_id']; ?>');">Değiştir</button><?php } ?>
											<button class="btn btn-primary" id="pano_cevp" type="button">Cevap Ver</button>
										</td>
									</tr>
									</table>
								</TD>
								</TR>
<?php } ?>
								</tbody>
								</TABLE>
							</div>
						</div>
                      </div>

					</div>
                <!-- /.container-fluid -->

				</div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("footer.php"); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
<!-- ekle Modal-->
				<div class="modal fade" id="pano_Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<form id="form1" method="POST" action="set_panomsg.php">
							<input type="hidden" name="id" id="id" value="0" />
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Org.Şema Ekleme</h5>
								<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
							<table class="table table-striped">
							<tr>
								<td class="text-right">Konu</td>
								<td colspan="3"><input class="form-control" type="text" name="pano_konu" id="pano_konu" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">İçerik</td>
								<td colspan="3"><textarea class="form-control" name="pano_gond" id="pano_gond" rows="6"></textarea></td>
							</tr>
							<tr>
								<td class="text-right">Geçerlilik son tarihi</td>
								<td colspan="3"><input class="form-control" type="date" name="pano_star" id="pano_star" value=""/></td>
							</tr>
							</table>
							<div id="altyazi" style="display:none;">Giriş Tarihi:<span id="tarihp"></span> - Son Değ.Tarihi:<span id="son_deg_tarp"></span></div>
							</div>
							<div class="modal-footer">
								<button class="btn btn-secondary" type="reset" id="cancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
								<button class="btn btn-primary" id="msg_ekle" disabled type="submit"><?php echo $gtext['send']; ?></button>
							</div>
							</form>
						</div>
					</div>
				</div>
			<!-- ekle modal sonu-->
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.js"></script>
    <!-- Page level plugins -->
    <script src="/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="/js/demo/datatables-demo.js"></script>
<script>
var isl='';
$('form').find(':input').change(function(){ $('#msg_ekle').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#msg_ekle').prop("disabled", true ); });
const obj=JSON.parse('<?php echo json_encode($fsatir); ?>'); //console.log(obj);
$(document).ready(function(){	
	$('#list').DataTable({
        "language": {
			url :'/vendor/datatables/Turkish.json',
		}
	});
	$('#msg_ekle').on("click", function(){ //ekle/değiştir ajaxform
		var opt={
			type	: 'POST',
			url 	: './set_panomsg.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('Kaydediliyor?');
			},
			success: function(data){ //				
				console.log('Önizleme :'+data);
				if(data!=''){ if(confirm(data)){ location.reload(); }}
				else { alert('Bir hata oluştu!'); }
			}
		}
		$('#form1').ajaxForm(opt); //*/
	});
});
	function pedit(msg){ //bilgiler getirilir...		console.log(' id:'+msg);
		const result = obj.find(({ _id }) => _id.$oid === msg);	//console.log(' res:'+result['orgs_dosya']);  
		$('#id').val(result['_id'].$oid); //.$oid
		$('#pano_konu').val(result['pano_konu']); 
		$('#pano_gond').html(result['pano_gond']); 
		$('#pano_star').val(result['pano_star']);
		if(result['tarih']!=''){ $('#altyazi').prop('display', 'inline'); }
		$('#tarihp').html(result['tarih']); 
		$('#son_deg_tarp').html(result['son_deg_tar']); 
		$('#msg_ekle').html('Değiştir');//*/
		isl='E';
		$('#eklebtn').click(); //*/
	}
	$('#eklebtn').on('click', function(){
		$('#pano_star').val('2024-03-14');
	});
</script>
</body>

</html>