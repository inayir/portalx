<?php
/*
	Sertifikaları gösterir MongoDB
*/
include('../set_mng.php');
include($docroot."/app/php_functions.php"); //datem
//error_reporting(0);
include($docroot."/sess.php"); 
@$tip=$_GET['tip']; if($tip==""){ $tip='b_certs'; }

switch($tip){
	case 'b_certs': $title=$gtext['certs']; /*"Sertifikalar";*/ break;
	case 'b_quals': $title=$gtext['quals']; /*"Kalifikasyonlar";*/ break;
	default: $title=$gtext['certs'];
}
@$biryil=datem(date("Y.m.d 00:00:00", strtotime("+1 year")));
@$collection=$db->k_belgeler;
//Haber			
$cursor = $collection->aggregate([
	[
		'$match'=>[
			'$and'=>[['tip'=>$tip],['aktif'=>1]]
		]
	],
	[
		'$sort' => [
		  'kod' => -1, 
		],
	]
]);
$fsatir=[];
foreach ($cursor as $formsatir) {
	try{
		$satir=[]; 
		$satir['_id']=$formsatir->_id;  
		if(@$formsatir->tip!=null){ $satir['tip']=$formsatir->tip;	}	
		if(@$formsatir->snf!=null){ $satir['snf']=$formsatir->snf;	}	
		if(@$formsatir->kod!=null){ $satir['kod']=$formsatir->kod;	}	
		if(@$formsatir->tanim!=null){ $satir['tanim']=$formsatir->tanim;	}	
		if(@$formsatir->dosya!=null){ $satir['dosya']=$formsatir->dosya;	}	
		if($formsatir->b_tar!=null){ $satir['b_tar']=$formsatir->b_tar->toDateTime()->format($ini['date_local']); }
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

    <title><?php echo $title; ?></title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="../vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="../vendor/jquery/jquery.min.js"></script>
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
            <div id="content">

                <!-- Topbar -->
                <?php include($docroot."/topbar.php"); ?>
                    
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $title; /*alt title olmalı*/?></h1>
						<?php if($_SESSION['y_bq']==1){?>
                        <a id="belge_ekle" data-toggle="modal" data-target="#belgeekleModal" href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
						class="fas fa-download fa-sm text-white-50"></i> <?php echo $gtext['btn_adddoc'];/*Belge Ekle*/?></a><?php } ?>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<!-- DataTables Example -->
                      <div class="card shadow mb-4 w-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"><?php echo $gtext['valid']."  ".$title; ?></h6>
							<span id='ret'><small></small></span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="b_list" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th><?php echo $gtext['category'];/*Sınıfı*/?></th>
                                            <th><?php echo $gtext['code'];/*Kodu*/?></th>
                                            <th><?php echo $gtext['doc'];/*Belge*/?></th><?php if($_SESSION['y_bq']==1){?>
                                            <th><?php echo $gtext['file'];/*Dosya*/?></th>
											<th></th><?php } ?>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th><?php echo $gtext['category'];/*Sınıfı*/?></th>
                                            <th><?php echo $gtext['code'];/*Kodu*/?></th>
                                            <th><?php echo $gtext['doc'];/*Belge*/?></th><?php if($_SESSION['y_bq']==1){?>
                                            <th><?php echo $gtext['file'];/*Dosya*/?></th>
											<th></th><?php } ?>
                                        </tr>
                                    </tfoot>
                                    <tbody><?php 
									for($i=0; $i<$fisay; $i++){ 
										echo "<tr>";
										echo "<td>".$fsatir[$i]['snf']."</td>";
										echo "<td>".$fsatir[$i]['kod']."</td>";
										echo "<td><a target='_blank' href='b_gosterm.php?d=".$fsatir[$i]['_id']."'>".$fsatir[$i]['tanim']."</a></td>";
										if($_SESSION['y_bq']==1){ 
											echo "<td><a target='_blank' href='b_gosterm.php?d=".$fsatir[$i]['_id']."'>".$fsatir[$i]['dosya']."</a></td>";
											echo "<td>"; ?>
											<a class="btn btn-primary" href="javascript:get_belge('<?php echo $fsatir[$i]['_id'];?>');"><i class="fas fa-eye fa-sm text-white-50"></i> <?php echo $gtext['change'];/*Değiştir*/?></a><?php 
										}
										echo "</td></tr>";
									} ?>
									</tbody>
								</table>
							</div>	
						</div>	
					  </div>
                <!-- /.container-fluid -->

					</div>
				</div>
            <!-- End of Main Content -->
			</div>
            <!-- Footer -->
            <?php include($docroot."/footer.php"); ?>
            <!-- End of Footer -->

			</div>
        <!-- End of Content Wrapper -->

			</div>
		</div>
    </div>
    <!-- End of Page Wrapper -->
	<!-- dosya yükle Modal-->
	<div class="modal fade" id="belgeekleModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
		aria-hidden="true">
		<div class="modal-dialog w-75" role="document">
			<div class="modal-content">
				<form id="form1" method="POST" action="set_belge.php">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalLabel"><?php echo $gtext['a_ac_doc'];/*Belge Ekle/Değiştir*/?></h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
				<input type="hidden" name="_id" id="_id" value=""/>
				<input type="hidden" name="tip" id="tip" value="b_certs"/>
				<table class="table table-striped">
				<tr>
					<td><?php echo $gtext['doc']." ".$gtext['category'];/*Belge Sınıfı*/?></td>
					<td><input type="text" name="snf" id="snf" size="5" value=""/></td>
					<td><?php echo $gtext['doc']." ".$gtext['code'];/*Belge Kodu*/?></td>
					<td><input type="text" name="kod" id="kod" size="5" value=""/></td>
				</tr>
				<tr>
					<td><?php echo $gtext['doc']." ".$gtext['description'];/*Belge Tanımı*/?></td>
					<td colspan="3"><input type="text" name="tanim" id="tanim" size="50" value=""/></td>
				</tr>
				<tr>
					<td><?php echo $gtext['file'];/*Dosya*/?></td>
					<td colspan="3">
					<input type="text" name="belge_yolu" id="belge_yolu" size="50" value="<?php echo $ini['b_qualifications_yol']; ?>"/>
					<input type="text" name="dosya" id="dosya" size="70" value=""/>
					<input type="file" name="dyol" id="dyol"/>
					</td>
				</tr>
				<tr>
					<td><?php echo $gtext['a_doc_date'];/*Belge Tarihi*/?></td>
					<td><input type="text" name="b_tar" id="b_tar" size="15" value=""/></td>
					<td><?php echo $gtext['a_enter_date'];/*Giriş Tarihi*/?><br><?php echo $gtext['user'];/*Kullanıcı*/?></td>
					<td><p id="g_tar"></p><p id="user"></p></td>
				</tr>
				<tr>
					<td><?php echo $gtext['active'];/*Aktif*/?></td>
					<td><input type="checkbox" data-toggle="toggle" name="aktif" id="aktif" data-on="Aktif" data-off="Pasif"/></td>
				</tr>
				</table>
				<a id="belge_getir" data-toggle="modal" data-target="#belgeekleModal" href="#" style="display: none;"> Belge Getir</a>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" id="cancel" data-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
					<button class="btn btn-primary" id="eklebtn" disabled type="submit"><?php echo $gtext['insert']; ?></button>
				</div>
				</form>
			</div>
		</div>
	</div>
<!--dosya yükle modal sonu-->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/form-master/dist/jquery.form.min.js"></script>

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
var isl='';
const obj=JSON.parse('<?php echo json_encode($fsatir); ?>'); //console.log(obj);
var dturl="<?php echo $_SESSION['lang'];?>"; 
$(document).ready(function() {
	$('#b_list').DataTable( {
        "language": {
			url :"../vendor/datatables/"+dturl+".json",
		}
	});

	$('#eklebtn').on("click", function(){ 
		var opt={
			type	: 'POST',
			url 	: './set_belgem.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('<?php echo $gtext['q_rusure']; ?>');
			},
			success: function(data){ //console.log('Önizleme :'+data);
				if(data.indexOf('MEdi')>0){ alert('<?php echo $gtext['u_error']; ?>'); }
				else { alert(data); location.reload(); }
			}
		}
		$('#form1').ajaxForm(opt); //*/
	});
});
$('#dyol').on('change', function(){
	var f=$('#dyol').val();
	$('#dosya').val(f.substr(12));
});
$('#belge_ekle').on('click', function(){
	$('#cancel').click();
});
function get_belge(id){//bilgiler getirilir...	console.log('Deneme');
	const result = obj.find(({ _id }) => _id.$oid === id);	
	$('#id').val(result['_id'].$oid); 
	$('#snf').val(result['snf']); 
	$('#kod').val(result['kod']); 
	$('#tanim').val(result['tanim']); 
	$('#dosya').val(result['dosya']); 
	$('#b_tar').val(result['b_tar']); 
	$('#g_tar').html(result['g_tar']); 
	$('#user').html(result['user']); 
	if(result['aktif']==1){ $('#aktif').bootstrapToggle('on'); }else{ $('#aktif').bootstrapToggle('off'); }
	$('#eklebtn').html('Değiştir');//*/
	isl='D';
	$('#belge_ekle').click();
}

$('form').find(':input').change(function(){ $('#eklebtn').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#eklebtn').prop("disabled", true ); });
</script>
</body>

</html>