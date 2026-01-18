<?php
//include('../set_mng.php');
echo "y_admin:".$_POST['y_admin'];
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Check Toogle denemesi</title>

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
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
	<!-- Bootstrap Toogle-->
	<link href="/vendor/bootstrap-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
	<script src="/vendor/bootstrap-toggle/js/bootstrap4-toggle.min.js"></script>
	<!--DataTables-->
	<link href="/vendor/datatables.net/datatables.min.css" rel="stylesheet"> 
	<script src="/vendor/datatables.net/datatables.min.js"></script>
	<script src="/vendor/datatables.net/pdfmake.min.js"></script>
	<script src="/vendor/datatables.net/vfs_fonts.js"></script>	
	
</head>

<body id="page-top">
<form method='POST' name="form1" action="">
<div class="form-check form-switch">
	<input class="form-check yetki" type="checkbox" name="y_admin" id="y_admin" />
	<button class="btn btn-success" id="test" type="button">Test</button>
</div>
	<button class="btn btn-default" id="submit" type="submit">Submit</button>
</form>
<script>
$('#test').on("click", function(){ 
    $('#y_admin').prop("checked",true); 
    console.log('y_admin:'+$('#y_admin').prop("checked"));
});
</script>
</body>
</html>