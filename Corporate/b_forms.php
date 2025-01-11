<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<?php
error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
include("../sess.php");
//
$bq="SELECT * FROM z_belgeler 
WHERE snf='K1-04' AND aktif=1 
ORDER BY kod";
$bresult = $baglan->query($bq);
?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['forms']; ?></title>

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
        <?php include("/sidebar.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include("/topbar.php"); ?>
                    
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Yönetsel Formlar</h1>
						<?php if($_SESSION['y_bo']==1){?>
                        <a id="belge_ekle" data-toggle="modal" data-target="#belgeekleModal" href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
						class="fas fa-download fa-sm text-white-50"></i> Belge Ekle</a><?php } ?>
                    </div>

                    <!-- Content Row -->
                    <!-- Content Row -->
                    <div class="row">
						<!-- DataTables Example -->
                      <div class="card shadow mb-4 w-100">
                        <div class="card-header py-3">
                            <!--h6 class="m-0 font-weight-bold text-primary">Yönetsel Formlar</h6-->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="b_list" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Sınıfı</th>
                                            <th>Kodu</th>
                                            <th>Tanım</th>
                                            <th>Dosya</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sınıfı</th>
                                            <th>Kodu</th>
                                            <th>Tanım</th>
                                            <th>Dosya</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody><?php 
									for($i=0; $dxrow = mysqli_fetch_assoc($bresult); $i++){ 
										echo "<tr>";
										echo "<td>".$dxrow['snf']."</td>";
										echo "<td>".$dxrow['kod']."</td>";
										echo "<td>".$dxrow['tanim']."</td>";
										echo "<td><a target='_blank' href='";
										
										if(strpos($dxrow['dosya'],'.pdf')>0){ echo "b_goster.php?d=".$dxrow['uid']; } 
										else { echo $ini['b_forms_yol']."/".$dxrow['dosya']; }
										echo "'>".$dxrow['tanim']."</a></td>";
										echo "<td>";
										if($_SESSION['y_bo']==1){ 
											echo "<a class='btn btn-primary' href='javascript:get_belge(".$dxrow['uid'].");'><i class='fas fa-eye fa-sm text-white-50'></i> Değiştir</a>"; }
										echo "</td></tr>";
									} ?>
									</tbody>
								</table>
							</div>	
						</div>	
					  </div>

                    <!-- Content Row -->

                    <div class="row">

                    </div>

                    <!-- Content Row -->
                    <div class="row">
					
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("/footer.php"); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
<!-- dosya yükle Modal-->
	<div class="modal fade" id="belgeekleModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
		aria-hidden="true">
		<div class="modal-dialog w-75" role="document">
			<div class="modal-content">
				<form id="form1" method="POST" action="set_belge.php">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalLabel">Belge Ekleme/Değiştirme</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
				<input type="hidden" name="uid" id="uid" value=""/>
				<input type="hidden" name="tip" id="tip" value="b_certs"/>
				<table class="table table-striped">
				<tr>
					<td>Belge Sınıfı</td>
					<td><input type="text" name="snf" id="snf" size="5" value=""/></td>
					<td>Belge Kodu</td>
					<td><input type="text" name="kod" id="kod" size="5" value=""/></td>
				</tr>
				<tr>
					<td>Belge Tanımı</td>
					<td colspan="3"><input type="text" name="tanim" id="tanim" size="70" value=""/></td>
				</tr>
				<tr>
					<td>Dosya</td>
					<td colspan="3">
						<p id="pbelge_yolu"><?php echo $ini['b_forms_yol']; ?></p>
						<p id="pdosya"></p>
						<input type="file" name="dyol" id="dyol"/>
						<input type="hidden" name="belge_yolu" id="belge_yolu" size="50" value="<?php echo $ini['b_forms_yol']; ?>"/>
						<input type="hidden" name="dosya" id="dosya" size="70" value=""/>
					</td>
				</tr>
				<tr>
					<td>Belge Tarihi</td>
					<td><input type="text" name="b_tar" id="b_tar" size="15" value=""/></td>
					<td>Giriş Tarihi<br>Kullanıcı</td>
					<td><p id="g_tar"></p><p id="user"></p></td>
				</tr>
				<tr>
					<td>Aktif</td>
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
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
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
var dturl="<?php echo $_SESSION['lang'];?>"; 
$(document).ready(function() {
	$('#b_list').DataTable( {
        "language": {
			url :"../vendor/datatables/"+dturl+".json",
		}
	});

	$('#eklebtn').on("click", function(){ //ekle/değiştir ajaxform
		var opt={
			type	: 'POST',
			url 	: './set_belge.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('Emin Misiniz?');
			},
			success: function(data){ //				console.log('Önizleme :'+data);
				if(data.indexOf('MEdi')>0){ alert('Bir hata oluştu!'); }
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
	$('#uid').val(''); 
	$('#snf').val(''); 
	$('#kod').val(''); 
	$('#tanim').val(''); 
	$('#dosya').val(''); 
});
function get_belge(id){//bilgiler getirilir...		console.log();
	$.ajax({
		type: 'POST',
		url: 'get_belge.php',
		data: { 'i': id },
		success: function (data){ //		console.log(data);
			if(data.trim()=='-1'){ isl='E'; }
			else{
				const obj=JSON.parse(data);		
				$('#uid').val(obj[0].uid); 
				$('#snf').val(obj[0].snf); 
				$('#kod').val(obj[0].kod); 
				$('#tanim').val(obj[0].tanim); 
				$('#pdosya').html(obj[0].dosya); 
				$('#dosya').val(obj[0].dosya); 
				$('#b_tar').val(obj[0].b_tar); 
				$('#g_tar').html(obj[0].g_tar); 
				$('#user').html(obj[0].user); 
				if(obj[0].aktif==1){ $('#aktif').bootstrapToggle('on'); }else{ $('#aktif').bootstrapToggle('off'); }
				$('#eklebtn').html('Değiştir');//*/
				isl='D';			
			}
			$('#belge_getir').click();
		}
	});	//*/
}

$('form').find(':input').change(function(){ $('#eklebtn').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#eklebtn').prop("disabled", true ); });
</script>
</body>

</html>