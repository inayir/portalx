<?php
/*
Form listeler->cursor tipine geçecek.
*/
include("../set_mng.php"); 
//error_reporting(0);

include($docroot."/sess.php");
if($user==""){ //if auth pages needed...
	header('Location: /login.php');
}
$fisay=0;
$simdi=date($ini['date_local']." H:i", strtotime("now"));
$bugun=date($ini['date_local'], strtotime("now"));
//
@$snf=$_GET['s']; $fsatir=[];
//Form kaydı getirilir........................
$col=$db->Forms;
$cursor=$col->find(
	[
		'form'=>['$ne'=>null]
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort' => [
			'name'=>1,
		],
	],
);

if($cursor){
	foreach ($cursor as $formsatir){ 
		$satir=[];
		$satir['id']=$formsatir->_id;
		$satir['type']=$formsatir->type;
		$satir['category']=$formsatir->category;
		$satir['form']=$formsatir->form;
		$satir['description']=$formsatir->description;
		$fsatir[]=$satir;
	} 
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

    <title><?php echo $gtext['forms']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">    
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
	<!-- Bootstrap Toogle-->
	<link href="/vendor/bootstrap-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
	<script src="/vendor/bootstrap-toggle/js/bootstrap4-toggle.min.js"></script>
    <!--DataTables-->
	<link href="/vendor/datatables.net/datatables.min.css" rel="stylesheet"> 
	<script src="/vendor/datatables.net/datatables.min.js"></script>
	<script src="/vendor/datatables.net/pdfmake.min.js"></script>
	<script src="/vendor/datatables.net/vfs_fonts.js"></script>
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['forms']; ?></h1>
						<div class='text-right'>
						<a id="belge_ekle" href="#" data-bs-toggle="modal" data-bs-target="#uploadModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
						class="fas fa-download fa-sm text-white-50"></i> <?php echo $gtext['form']." ".$gtext['insert'];/*Belge Ekle*/?></a>
						<a class='btn btn-sm btn-secondary' href='des_form.php' target="_tab"><i class='fas fa-edit fa-sm text-white-50'></i> <?php echo $gtext['new']." ".$gtext['form']." ".$gtext['design'];?></a>
						</div>
                    </div>

                    <!-- Content Row -->
                    <!-- Content Row -->
                    <div class="row">
						<!-- DataTables Example -->
                      <div class="card shadow mb-2 w-100">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="b_list" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th><?php echo $gtext['category'];/*Sınıfı*/?></th>
                                            <th><?php echo $gtext['code'];/*Kodu*/?></th>
                                            <th><?php echo $gtext['description'];/*Tanım*/?></th>
                                            <th class='text-right'></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th><?php echo $gtext['category'];/*Sınıfı*/?></th>
                                            <th><?php echo $gtext['code'];/*Kodu*/?></th>
                                            <th><?php echo $gtext['description'];/*Tanım*/?></th>
                                            <th class='text-right'></th>
                                        </tr>
                                    </tfoot>
                                    <tbody><?php 
									for($i=0; $i<$fisay; $i++){ ?>
										<tr>
											<td><?php echo $fsatir[$i]['category']; ?></td>
											<td><a target="_blank" href="/forms/get_form.php?f=<?php echo $fsatir[$i]['form']; if($key2!="") { echo "&key2=".$key2; } ?>"><?php echo $fsatir[$i]['form'];?></a></td>
											<td><?php echo $fsatir[$i]['description'];?></td>
											<td class='text-center'>
												<div class="dropdown">
												<button class="btn btn-secondary dropdown-toggle" type="button" id="form_<?php echo $fsatir[$i]['id'];?>" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false"><?php echo $gtext['procs'];?></button>
													<div class="dropdown-menu" aria-labelledby="r_<?php echo $fsatir[$i]['id'];?>"><?php
										if($_SESSION['y_bo']==1){ 
											if($fsatir[$i]['type']=='desform'){ 
												echo "<li><a class='dropdown-item text-dark' href='des_form.php?f=".$fsatir[$i]['form']."' target='_tab'><i class='fas fa-edit fa-sm text-dark-50'></i> ".$gtext['change']."</a></li>"; 
												echo "<li><a class='dropdown-item text-white bg-dark' href='des_form.php?f=".$fsatir[$i]['form']."&c=C'><i class='fas fa-copy fa-sm text-white-50'></i> ".$gtext['clone']."</a></li>"; 
											}else{
												echo "<li><a class='dropdown-item text-dark' href='#' onClick='chform(".$fsatir[$i]['id'].")'><i class='fas fa-edit fa-sm text-dark-50'></i> ".$gtext['change']."</a></li>"; 
												echo "<li><a class='dropdown-item text-white bg-dark' href='des_form.php?f=".$fsatir[$i]['id']."&c=C'><i class='fas fa-copy fa-sm text-white-50'></i> ".$gtext['clone']."</a></li>"; 
											}
										}?>
													</div>
												</div>
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

                    <!-- Content Row -->
                    <div class="row">
					
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
			<!-- Upload Modal-->
			<div class="modal fade" id="uploadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-2" role="dialog" aria-labelledby="label101ModalLabel"
				aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="label101ModalLabel"><?php echo $gtext['form']." ".$gtext['insert'];/*Form Ekle*/?></h5>
							<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<form name="form1" id="form1" method="POST" action="set_fileform.php">
									<input type="hidden" name="id" id="id" value=""/>
						<div class="modal-body align-items-center">
							<table class="table table-striped">
							<tr>
								<td><?php echo $gtext['code'];?>:</td>
								<td>
									<input class="form-control isl" type="text" name="form" id="form" value="" placeholder="Code" max="10"/>
								</td>
								<td><?php echo $gtext['category'];/*Sınıfı/Kategori*/?>:</td>
								<td>
									<input class="form-control isl" type="text" name="category" id="category" value=""/>
								</td>
							</tr>
							<tr>
								<td><?php echo $gtext['description'];?>:</td>
								<td colspan="3">
									<input class="form-control isl" type="text" name="description" id="description" value="" placeholder="Description"/>
								</td>
							</tr>
							<tr>
								<td><?php echo $gtext['file'];/*Dosya*/?></td>
								<td colspan="3">
								<input class="form-control isl" type="text" name="belge_yolu" id="belge_yolu" size="50" value="<?php echo $ini['b_forms_url']; ?>"/>
								<input class="form-control isl" type="text" name="filename" id="filename" size="70" value=""/>
								<input class="form-control isl" type="file" name="file" id="file"/>
								</td>
							</tr>
							<tr>
								<td><?php echo $gtext['ack']; /*Açıklama*/?></td>
								<td><textarea class="form-control isl" name="ack" id="ack"></textarea></td>
							</tr>
							<tr>
								<td><?php echo $gtext['active'];/*Aktif*/?></td>
								<td>
									<div class="form-check form-switch">
										<input class="form-check-input isl" type="checkbox" name="state" id="state" data-toggle="toggle" data-on="<?php echo $gtext['active'];/*Aktif*/?>" data-off="<?php echo $gtext['passive'];/*Pasif*/?>"/>
									</div>
								</td>								
								<td><?php echo $gtext['a_doc_date'];/*Belge Tarihi*/?></td>
								<td>
									<input class="form-control datetime-local isl" type="text" name="formdate" id="formdate" size="15" value="<?php echo $bugun; ?>"/>
								</td>
							</tr>
							<tr>
								<td><?php echo $gtext['a_enter_date'];/*Giriş Tarihi*/?></td>
								<td>
									<p id="g_tar"></p>
								</td>								
								<td><?php echo $gtext['user'];/*Kullanıcı*/?></td>
								<td>
									<p id="user"></p>
								</td>
							</tr>
							</table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-success" id="chbtn" type="button" style="display:none;"><?php echo $gtext['change']; ?></button>
							<button class="btn btn-primary" id="record" type="submit" disabled ><?php echo $gtext['save']; ?></button>
							<button class="btn btn-secondary" type="button" id="lcancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<!--Upload modal sonu-->
        </div>
        <!-- End of Content Wrapper -->
		<!-- Footer -->
		<?php include($docroot."/footer.php"); ?>
		<!-- End of Footer -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

	<!-- Core plugin JavaScript-->
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script> 
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/js/sb-admin-2.js"></script>
<script>
var isl='';
var dturl="<?php echo $_SESSION['lang'];?>";
var obj=JSON.parse('<?php echo json_encode($fsatir);?>'); 
$(document).ready(function() {
	var table = $('#b_list').DataTable( {
        "language": {
			url :"../vendor/datatables.net/"+dturl+".json",
		}
	});
});
var opt={
	type	: 'POST',
	url 	: './set_fileform.php',
	contentType: 'application/x-www-form-urlencoded;charset=utf-8',
	beforeSubmit : function(){
		if($('#form').val()==''){
			alert('<?php echo $gtext['u_fieldmustnotblank']; ?>');
			return false;
		}
		return confirm('<?php echo $gtext['q_rusure']; /*Emin Misiniz?*/?>');
	},
	success: function(data){ console.log(data);
		//if(data.indexOf('!')>0){ alert('Bir hata oluştu! '+data); }
		//else { alert(data); location.reload(); }
	}
}
$('#form1').ajaxForm(opt);

$('#chbtn').on('click', function(){ 
	$('.isl').removeAttr("disabled");
	$(this).css('display','none');
});
function chform(id){
	$(this).css('display','inline');
	//bilgileri getir. obj
}
$('form').find(':input').change(function(){ $('#record').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#record').prop("disabled", true ); });
</script>
</body>

</html>