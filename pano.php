<?php
/*
	İletişim Panosu
*/
include('set_mng.php');
//error_reporting(0);
if($ini['personel_pano']==0){ 
	echo $gtext['closed']; //"Pano kapalıdır.";
	sleep(2);
	header('Location: /index.php');
}//*/
include($docroot."/sess.php"); 
$log="\n";
$bastar=date("Y-m-d", strtotime("-1 year"));
$bugun=date("Y-m-d", strtotime("now"));
$ilktar=new \MongoDB\BSON\UTCDateTime(strtotime($bastar)*1000);
$sontar=new \MongoDB\BSON\UTCDateTime(strtotime($bugun)*1000);

@$collection=$db->personel_pano;
$cursor = $collection->aggregate([
	[
		'$match'=>[ 'pano_star'=>['$gte'=>$sontar]]
	],
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
			'displayname'=> '$persons.displayname',
			'brm'=> '$persons.birim',
			'email'=> '$persons.email',
		],
    ],
	[
		'$sort' => [
		  'tarih' => -1, 
		],
	],
]);
$fsatir=[];
foreach ($cursor as $formsatir) {
	$satir=[]; 
	$satir['_id']=$formsatir->_id;  
	$satir['displayname']=$formsatir->displayname; 
	$satir['email']=$formsatir->email; 
	$satir['pano_konu']=$formsatir->pano_konu; 
	$g=$formsatir->pano_gond;
	$satir['pano_gond']=$g; 
	if($formsatir->tarih!=null){ 
		$satir['tarih']=$formsatir->tarih->toDateTime()->format($ini['date_local']." H:i"); 
	}
	if($formsatir->pano_star!=null){ 
		$satir['pano_starG']=$formsatir->pano_star->toDateTime()->format($ini['date_local']." H:i"); 
		$satir['pano_star']=$formsatir->pano_star->toDateTime()->format("Y-m-d")." ".$formsatir->pano_star->toDateTime()->format("H:i"); 
	}
	$satir['msg_sahibi']=$formsatir->msg_sahibi;
	$satir['state']=$formsatir->state;
	$fsatir[]=$satir;
}
$fisay=count($fsatir); 

$json=addslashes(json_encode($fsatir)); 
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
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['pano'];/*İletişim Panosu*/?></h1>
                        <a id="eklebtn" href="#" data-bs-toggle="modal" data-bs-target="#pano_Modal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-pen-fancy fa-sm text-white-50"></i> <?php echo $gtext['new_data'];/*Yeni Bilgi*/?></a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					  <div class="card shadow lg-11">
                        <div class="card-header py-2"><small><?php echo $gtext['pano_rules'];/*Dikkat edilecek <b>Pano Kuralları:</b> Gayri ahlaki yazışmalar yapılmayacaktır. Bilgi güvenliğine dikkat edilmelidir. Başkalarını ilgilendiren yazışmalar yazışmalar yapılmayacaktır.*/?></small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <TABLE class="table table-striped" id="list" width="100%" cellspacing="0">								
								<THEAD>
									<TH><?php echo $gtext['pano_messages'];/*Gönderiler*/?></TH>
								</THEAD>
								<TBODY>
<?php for($i=0; $i<$fisay; $i++){ 
	if($fsatir[$i]['state']=="D"&&$user!=$fsatir[$i]['msg_sahibi']){ continue; }
		switch(($i%8)){
			case 1: $cevre="primary"; break;
			case 2: $cevre="secondary"; break;
			case 3: $cevre="success"; break;
			case 4: $cevre="info"; break;
			case 5: $cevre="warning"; break;
			case 6: $cevre="danger"; break;
			case 7: $cevre="light"; break;
			case 8: $cevre="dark"; break;
		} ?>
								<TR>
								<TD class="border border-<?php echo $cevre; ?> border-5 rounded">
									<table>
									<tr class="w-100">
										<td><?php echo $gtext['pano_sender'];/*Gönderen*/ echo ":".$fsatir[$i]['displayname']; ?></td>
										<td class='text-right'><?php echo $gtext['date'];/*Tarih*/?></td>
										<td><?php echo $fsatir[$i]['tarih']; ?></td>
									</tr>
									<tr>
										<td class="w-100"><div class="font-weight-bold"><?php echo $fsatir[$i]['pano_konu']; ?></div></td>
										<td class='text-right' style='width:10%'><?php echo $gtext['pano_lastdate'];/*Son Tarih*/?></td>
										<td><?php echo $fsatir[$i]['pano_starG']; ?></td>
									</tr>
									<tr>
										<td colspan="3" style='border-style:solid; border-width:1px; border-radius:5px;' height='100%'>
											<div style='overflow: auto; width: 100%;'><?php 
											$g=preg_replace(array('/\r/', '/\n/'),"<br>",$fsatir[$i]['pano_gond']);
											echo stripslashes($g); 
											?></div>									
										</td>
									</tr><?php if($user==$fsatir[$i]['msg_sahibi']){ ?>
									<tr>
										<td colspan="4">
											<button class="btn btn-primary m-1 border border-dark border-4" id="pano_edit" type="button" onClick="javascript:pedit('<?php echo $fsatir[$i]['_id']; ?>');"><?php echo $gtext['change'];/*Değiştir*/?></button>
											<button class="btn btn-danger m-1 border border-dark border-5" id="pano_sil" type="button" onClick="javascript:psil('<?php echo $fsatir[$i]['_id']; ?>');"><?php echo $gtext['remove'];/*Çıkar*/?></button><?php }else{ ?><button class="btn btn-info m-1 border border-dark border-5" id="pano_cevp" type="button"><?php echo $gtext['pano_reply'];/*Cevap Ver*/?></button>
										</td>
									</tr><?php } ?>
									</table>
								</TD>
								</TR>
<?php } ?>
								</TBODY>
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
							<h5 class="modal-title" id="exampleModalLabel"><?php echo $gtext['pano_msgchange'];/*Mesaj Değiştirme*/?></h5>
							<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
						<table class="table table-striped">
						<tr>
							<td class="text-right"><?php echo $gtext['pano_subject'];/*Konu*/?></td>
							<td colspan="3"><input class="form-control" type="text" name="pano_konu" id="pano_konu" value="" /></td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['pano_message'];/*İçerik*/?></td>
							<td colspan="3"><textarea class="form-control" name="pano_gond" id="pano_gond" rows="6"></textarea></td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['date'];/*Tarih*/?></td>
							<td><span id="tarihp"></span></td>
							<td class="text-right"><?php echo $gtext['pano_lastdate'];/*Son tarih*/?></td>
							<td><input class="form-control" type="datetime-local" name="pano_star" id="pano_star" value=""/></td>
						</tr>
						</table>
						<div id="altyazi" style="display:none;"></div>
						<div id="son_deg_tarp"></div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" id="msg_ekle" disabled type="submit"><?php echo $gtext['send']; ?></button>
							<button class="btn btn-secondary" type="reset" id="cancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<!-- ekle modal sonu-->

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
var dturl="<?php echo $_SESSION['lang'];?>"; 
var today='<?php echo $bugun;?>'; 
$(document).ready(function(){	
	$('#list').DataTable({
        "language": {
			url :"../vendor/datatables.net/"+dturl+".json",
		}
	});
	$('#msg_ekle').on("click", function(){ //ekle/değiştir ajaxform
		var opt={
			type	: 'POST',
			url 	: './set_panomsg.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('<?php echo $gtext['q_save'].'?';/*Kaydediliyor?*/?>');
			},
			success: function(data){ 
				if(data!=''){ if(confirm(data)){ location.reload(); }}
				else { alert('<?php echo $gtext['u_error'];/*Bir hata oluştu!*/?>'); }
			}
		}
		$('#form1').ajaxForm(opt); 
	});
});
function psil(msg){ //bilgiler silinir.
	$.ajax({
		type: 'POST',
		url: 'set_panomsg.php',
		data: { 'isl': 'd', 'id': msg },
		beforeSend : function(){
			return confirm('<?php echo $gtext['a_deleting'];/*Siliniyor?*/?>');
		},
		success: function (data){ 
			alert(data); 
			$('#yenile').click();
		}
	});	
}
function pedit(msg){ 
	const result = obj.find(({ _id }) => _id.$oid === msg);	 
	$('#id').val(result['_id'].$oid); 
	$('#pano_konu').val(result['pano_konu']); 
	$('#pano_gond').html(result['pano_gond']); 
	$('#pano_star').val(result['pano_star']); //console.log(result['pano_star']);
	if(result['tarih']!=''){ $('#altyazi').prop('display', 'inline'); }
	$('#tarihp').html(result['tarih']); 
	$('#son_deg_tarp').html(result['son_deg_tar']); 
	$('#msg_ekle').html('<?php echo $gtext['change'];/*Değiştir*/?>');
	isl='E';
	//$('#eklebtn').click(); 
	$('#pano_Modal').modal('show');
}
$('#eklebtn').on('click', function(){	
	$('#pano_star').val(today); //*/
});
$('#pano_star').on('blur', function(){
	var tar=$('#tarih').val();
	if($('#pano_star').val()<=tar){
		alert('<?php echo $gtext['u_ldatefromfdate'];?>');
	}//*/
});
$('form').find(':input').change(function(){ $('#msg_ekle').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#msg_ekle').prop("disabled", true ); });
const obj=JSON.parse('<?php echo $json; ?>'); 
//*/
</script>
</body>

</html>