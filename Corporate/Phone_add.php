<?php
//error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot.'/set_mng.php');
include($docroot."/sess.php");
if($ini['usersource']=='LDAP'){ require($docroot."/ldap.php"); }
if($_SESSION['user']==""&&$_SESSION['y_admin']!=1){
	header('Location: /login.php');
}
//
@$collection = $db->personel;
$fsatir=[];
try{
	$cursor = $collection->find(
		[
			'title' => ['$eq'=>'Phone']
		],
		[
			'limit' => 0,
			'projection' => [
				'displayname' => 1,
				'telephonenumber' => 1,
				'bgcolor' => 1,
				'color' => 1,
			],
		],
		[
			'sort'=>['order'=>1]
		]
	);
	if(isset($cursor)){	
		foreach ($cursor as $formsatir) {
			$satir=[];
			$satir['_id']=$formsatir->_id;  
			$satir['telephonenumber']=$formsatir->telephonenumber;
			$satir['displayname']=$formsatir->displayname;
			$satir['color']=$formsatir->color;
			$satir['bgcolor']=$formsatir->bgcolor;
			$fsatir[]=$satir;
		} //*/	
	
	}
}catch(Exception $e){
	
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

    <title<?php echo $gtext['pb_phone']." ".$gtext['ins_edit'];/*Phone number Insert/Edit*/?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="/vendor/jquery/jquery.min.js"></script>
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['pb_phone']." ".$gtext['insert']."/".$gtext['change'];/*Phone number Ekle/Değiştir*/?></h1>
                        <a id="eklebtn" href="#" data-toggle="modal" data-target="#ymModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> <?php echo $gtext['pb_phone']." ".$gtext['insert'];/*Birim Ekle*/?></a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<div class="col-xl-9 col-lg-9 mb-6">
							<!-- DataTables Example -->
							<div class="card shadow mb-4">
							  <div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary"><?php echo $gtext['speed_dials'];/*Hızlı Aramalar*/?></h6>
								<span id='ret'><small></small></span>
							  </div>
							  <div class="card-body">
								<div class="table-responsive">
									<table id="phonelist" class="table table-striped" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th><?php echo $gtext['number'];/*Numara*/?></th>
											<th><?php echo $gtext['ack'];/*Açıklama*/?></th>
											<th><?php echo $gtext['procs'];/*İşlemler*/?></th>
										</tr>
									</thead>
									<tbody><?php 
									for($b=0;$b<$fisay;$b++){ 
									?>									
									<tr class="bg-<?php echo $fsatir[$b]['bgcolor']." text-".$fsatir[$b]['color'];?>">
										<td><?php echo $fsatir[$b]['telephonenumber']; ?></td>
										<td><?php echo $fsatir[$b]['displayname']; ?></td>
										<td><div class="dropdown">
											  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $fsatir[$b]['telephonenumber']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $gtext['procs'];/*İşlemler*/?></button>
											  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
												<a class="dropdown-item" onClick="javascript:updatenumber('<?php echo $fsatir[$b]['telephonenumber']; ?>');"><?php echo $gtext['change'];/*Değiştir*/?></a>
												<a class="dropdown-item" onClick="javascript:deletenumber('<?php echo $fsatir[$b]['telephonenumber']; ?>');"><?php echo $gtext['delete'];/*Hesabı Kapat*/?></a>
											  </div>
											</div>
										</td>
									</tr><?php } ?>
									</tbody>
									</table>
								</div>
							  </div>
							</div>
						</div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                    </div>

                    <!-- Content Row -->
                    <div class="row">
					
                    </div>
					<div id="rt"></div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include($docroot."/footer.php"); ?>
            <!-- End of Footer -->
			<!-- Modal-->
				<div class="modal fade" id="ymModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
					aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form id="myForm" action="set_phone.php" method="POST">
							<input type="hidden" name="_id" id="_id" value=""/>
							<input type="hidden" name="del" id="del" value="0"/>
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel"><?php echo $gtext['speed_dials']." ".$gtext['ins_edit']; /*Ekleme/Değiştirme*/?></h5>
								<button class="close" type="button" data-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<table class="table table-bordered" style="width:100%;">
								<tr id="descsat">
									<td><?php echo $gtext['telephonenumber'];/*Numara*/?>: </td>
									<td>								
										<input class="form-controls tel" type="text" name="telephonenumber" id="telephonenumber" value="" size="10"/>
									</td>
								</tr>
								<tr>
									<td><?php echo $gtext['displayname'];/*Tanım*/?>: </td>
									<td>
										<input class="form-controls tel" type="text" name="displayname" id="displayname" value="" style="width:100%;"/>
									</td>
								</tr>
								<tr>
									<td><?php echo $gtext['color'];/*Renk*/?>:</td>
									<td>
										<select name="color" id="color" calss="text-dark bg-white">
											<option value="white" class="text-white bg-dark" ><b><?php echo $gtext['white'];/*Beyaz*/?></b></option>
											<option value="primary" class="text-primary"><b><?php echo $gtext['primary'];/*Mavi*/?></b></option>
											<option value="secondary" class="text-secondary"><b><?php echo $gtext['secondary'];/*Gri*/?></b></option>
											<option value="success" class="text-success"><b><?php echo $gtext['success'];/*Yeşil*/?></b></option>
											<option value="danger" class="text-danger"><b><?php echo $gtext['danger'];/*Kırmızı*/?></b></option>
											<option value="warning" class="text-warning"><b><?php echo $gtext['warning'];/*Sarı*/?></b></option>
											<option value="info" class="text-info"><b><?php echo $gtext['info'];/*Açık Mavi*/?></b></option>
											<option value="dark" class="text-dark bg-white"  selected><b><?php echo $gtext['dark'];/*Siyah*/?></b></option>
										</select>
									</td>
								</tr>
								<tr>
									<td><?php echo $gtext['bgcolor'];/*Zemin rengi*/?>:</td>
									<td>										
										<select name="bgcolor" id="bgcolor" class="bg-white text-dark">
											<option value="white" class="bg-white text-dark" selected ><?php echo $gtext['white'];/*Beyaz*/?></option>
											<option value="primary" class="bg-primary"><?php echo $gtext['primary'];/*Mavi*/?></option>
											<option value="secondary" class="bg-secondary"><?php echo $gtext['secondary'];/*Gri*/?></option>
											<option value="success" class="bg-success"><?php echo $gtext['success'];/*Yeşil*/?></option>
											<option value="danger" class="bg-danger"><?php echo $gtext['danger'];/*Kırmızı*/?></option>
											<option value="warning" class="bg-warning"><?php echo $gtext['warning'];/*Sarı*/?></option>
											<option value="info" class="bg-info"><?php echo $gtext['info'];/*Açık Mavi*/?></option>
											<option value="dark" class="bg-dark text-white" ><?php echo $gtext['dark'];/*Siyah*/?></option>
										</select>
									</td>
								</tr>
								<tr>
									<td><?php echo $gtext['order'];/*Order*/?>:</td>
									<td>								
										<label class="btn btn-outline-primary">
											<input class="form-control" type="text" name="order" id="order" value="99" size="2"/>
										</label>
									</td>
								</tr>
								<tr>
									<td><?php echo $gtext['active'];/*Aktif*/?>:</td>
									<td>								
										<label class="btn btn-outline-primary">
											<input class="form-control" type="checkbox" data-toggle="toggle" name="aktif" id="aktif" data-on="Aktif" data-off="Pasif" style="border-color: black;"/>
										</label>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="text-center fw-bolder" id="test"><h1><?php echo $gtext['test'];/*Test*/?></h1></div>
									</td>
								</tr>
								</table>
							</div>
							<div class="modal-footer">
								<button class="btn btn-primary" id="record"  type="submit"><?php echo $gtext['insert']; ?></button>
								<button class="btn btn-secondary" type="button" id="cancel" data-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
							</div>
							</form>
						</div>
					</div>
				</div>
			<!-- modal sonu-->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    
    <!-- Bootstrap core JavaScript-->
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
	<script src="/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
	<script src="/js/portal_functions.js"></script>
<script>
var dturl="<?php echo $_SESSION['lang'];?>"; 
var aktif="1";
const obj=JSON.parse('<?php echo json_encode($fsatir); ?>'); 
$(document).ready(function() {
	$('#phonelist').DataTable( {
        "language": {
			url :"../vendor/datatables/"+dturl+".json",
		}
	});
	$('#telephonenumber, #displayname').on('keyup', function(){ 
		$('#test').html($('#telephonenumber').val()+' '+$('#displayname').val());	
	});
	$('#color, #bgcolor').on('change', function(){ 
		$('#test').removeClass();
		$('#test').addClass('text-center text-'+$('#color').val()+' bg-'+$('#bgcolor').val());	
	});
	$('input').on('change', function(){
		$('#record').attr("disabled", false); 
	});
	$('#eklebtn').on('click', function(){		
		if(aktif==1){ $('#aktif').bootstrapToggle('on'); }else{ $('#aktif').bootstrapToggle('off'); } 
	}); 
	$('#record').on('click', function(){
		var options={
			type:	'POST',
			url : './set_phone.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				confirm('Emin misiniz?');
			},
			success: function(data){
				$('#record').attr("disabled", true); 
				alert(data);
				location.reload();
			}
		}
		$('#myForm').ajaxForm(options);
	});
});
	function bilgilerigetir(tel){ 
		var result=getObjects(obj, "telephonenumber", tel); 
		$('#_id').val(result[0]['_id']['$oid']);
		$('#telephonenumber').val(result[0]['telephonenumber']);
		$('#displayname').val(result[0]['displayname']);
		$('#color').val(result[0]['color']);
		$('#bgcolor').val(result[0]['bgcolor']);
		aktif=result[0]['aktif']; if(aktif===undefined){ aktif='1'; } 
	}
	function updatenumber(tel){ 
		bilgilerigetir(tel);
		$('#del').val('0');
		$('#record').html('<?php echo $gtext['change'];?>');
		$('#eklebtn').click();
	}
	function deletenumber(tel){ 
		bilgilerigetir(tel);
		$('#del').val('1');
		$('#record').click(); 
	}
</script>
</body>

</html>