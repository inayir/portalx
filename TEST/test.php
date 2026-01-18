<?php
@$y=$_GET['y'];
?>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Test</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="../vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
    <!-- Custom scripts for all pages-->
	<script src="/js/sb-admin-2.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
	<!--DataTables-->
	<link href="/vendor/datatables.net/datatables.min.css" rel="stylesheet"> 
	<script src="/vendor/datatables.net/datatables.min.js"></script>
	<script src="/vendor/datatables.net/pdfmake.min.js"></script>
	<script src="/vendor/datatables.net/vfs_fonts.js"></script>
	<!-- Bootstrap Toogle-->
	<link href="/vendor/bootstrap-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
	<script src="/vendor/bootstrap-toggle/js/bootstrap4-toggle.min.js"></script>
</head>
<body>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <a type="button" class="btn btn-success" id="renewpassbtn" href="#" data-bs-toggle="modal" data-bs-target="#upwModal">Deneme</a>
	</div>
<!-- upwModal-->
			<div class="modal fade" id="upwModal" tabindex="-1" role="dialog" aria-labelledby="upwModalLabel"
				aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="form_pass" name="form_pass" method="POST" action="./set_pss.php">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php echo "Şifre Yenile"; echo "<br>Kullanıcı";?> :<span id='usrn'></span><br></h5>
							<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo "Close";?>">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
						<input class="form-control" type="hidden" name="dn" id="dn" />
						<input class="form-control" type="hidden" name="usernamen" id="usernamen" />
						<table class="table table-striped">
						<tr>
							<td>Şifre</td>
							<td>
								<input type="text" name="pass" id="ppass" value=""/>
								<button type="button" id="stdpss" title="Standard Password">Std</button>
							</td>
						</tr>
						<tr id="prepasstr" style="dispay: inline;">
							<td>Şifre Yeniden</td>
							<td><input type="text" name="repass" id="prepass" value=""/></td>				
						</tr>
						</table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" id="canceln" data-bs-dismiss="modal">Cancel</button>
							<button class="btn btn-primary" id="userpbtn" disabled type="button">KaydetKaydet</button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<!--upwModal sonu-->
	<div>
		<div class="form-check form-switch">
			<label><input class="form-check-input yetki" type="checkbox" name="y_addinfomenu" id="y_addinfomenu" data-toggle="toggle" data-on="E" data-off="H" value="" /> Yetki</label>
		</div>
		<input type="button" onClick="mav();" value="Değiştir"/>
	</div>
<script>
var y="<?php echo $y;?>";
$(function() {
    $('#y_addinfomenu').bootstrapToggle("on");
	//console.log(y+' '+$('.yetki').val());
  })
//$('.yetki').bootstrapToggle(true);
function mav(){
	$('#y_addinfomenu').bootstrapToggle("toggle");
	console.log("toggled:"+$('#y_addinfomenu').prop("checked"));
}
</script>
</body>
</html>