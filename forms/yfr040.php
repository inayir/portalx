<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<?php
error_reporting(0);
$ini = parse_ini_file("config/config.ini");
include("../sess.php");
if($user==""){ //gerekliyse.
	header('Location: /login.php');
}
$qp="SELECT * FROM personel WHERE username='".$_SESSION['user']."'";
$presult = $baglan->query($qp); 
$prow = mysqli_fetch_assoc($presult);
$simdi=date("d.m.Y H:i", strtotime("now"));
?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Test-İzin Talep Formu</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/datepicker/datepicker.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("../sidebar.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
				
                <!-- Topbar -->
                <?php include("../topbar.php"); ?>
                    
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">İzin Talep Formu</h1>
                        <!--a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a-->
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					<div class="col-xl-6 col-lg-6">
					<table class="table table-striped">
					<tbody>
					<tr>
						<td class="col-4">Adı Soyadı</td>
						<td><?php echo $prow['adisoyadi']; ?></td>
					</tr>
					<tr>
						<td>Ünvanı</td><td><?php echo $prow['unvan']; ?></td>
					</tr>
					<tr>
						<td>Bölümü</td><td><?php echo $prow['birim']; ?></td>
					</tr>
					<tr>
						<td>Sicil No</td><td><?php echo $prow['sicilno']; ?>
							<input type="hidden" name="sicilno" id="sicilno" value=""></td>
					</tr>
					<tr>
						<td>Yöneticisi</td><td><input type="text" class="form-control" name="yonetici" id="yonetici" value="<?php //echo $prow['sicilno']; ?>" placeholder="Yönetici, yoksa vekili">
						</td>
					</tr>
					<tr>
						<td colspan="2"><hr></td>
					</tr>
					<tr>
						<td>İzin Başlama Tarihi/Saati</td>
						<td><div class="row g-2">
							<div class="col-md">
							<div class="input-group date" data-provide="datepicker">
								<input type="text" class="form-control mb-3" name="startdate" id="startdate" value="<?php echo date($ini['date_local'], strtotime($simdi)); ?>">
								<div class="input-group-addon">
									<span class="fas fa-calendar"></span>
								</div>
							</div>							
							</div>
							<div class="col-md"> Saat: </div>
							<div class="col-md"><input type="text" class="form-control" name="startdatetime" id="startdatetime" value="07:30">
							</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>İzin Bitiş Tarihi/Saati</td>
						<td><div class="row g-2">
							<div class="col-md">
							<div class="input-group date" data-provide="datepicker">
								<input type="text" class="form-control mb-3" name="enddate" id="enddate" value="<?php echo date($ini['date_local'], strtotime($simdi)); ?>">
								<div class="input-group-addon">
									<span class="fas fa-calendar"></span>
								</div>
							</div>							
							</div>
							<div class="col-md"> Saat: </div>
							<div class="col-md"><input type="text" class="form-control" name="enddatetime" id="enddatetime" value="17:00">
							</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>İşe Başlama Tarihi/Saati</td>
						<td><div class="row g-2">
							<div class="col-md">
							<div class="input-group date" data-provide="datepicker">
								<input type="text" class="form-control mb-3" name="workdate" id="workdate" value="">
								<div class="input-group-addon">
									<span class="fas fa-calendar"></span>
								</div>
							</div>							
							</div>
							<div class="col-md"> Saat: </div>
							<div class="col-md"><input type="text" class="form-control" name="workdatetime" id="workdatetime" value="7:30">
							</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>Kullanılan İzin Süresi</td>
						<td>
							<input type="text" class="form-control" name="izsure" id="izsure" value="1">
							<SELECT class="form-control" name="izsurec" id="izsurec">
								<option value="D">Gün</option>
								<option value="H">Saat</option>
							</SELECT>
						</td>
					</tr>
					<tr>
						<td>İzin Türü</td>
						<td>
							<div class="row g-2">
								<div class="form-check m-1">
								  <input class="form-check-input" type="radio" name="iztip" value="M" id="flexCheckDefault1" checked >
								  <label class="form-check-label" for="flexCheckDefault1">Mazeret İzni</label>
								</div>
								<div class="form-check m-1">
								  <input class="form-check-input" type="radio" value="Y" name="iztip" id="flexCheckDefault2" >
								  <label class="form-check-label" for="flexCheckDefault2">Yıllık İzin</label>
								</div>
								<div class="form-check m-1">
								  <input class="form-check-input" type="radio" value="E" name="iztip" id="flexCheckDefault3" >
								  <label class="form-check-label" for="flexCheckDefault3">Evlilik İzni</label>
								</div>
								<div class="form-check m-1">
								  <input class="form-check-input" type="radio" value="D" name="iztip" id="flexCheckDefault4" >
								  <label class="form-check-label" for="flexCheckDefault4">Doğum İzni</label>
								</div>
								<div class="form-check m-1">
								  <input class="form-check-input" type="radio" value="O" name="iztip" id="flexCheckDefault5" >
								  <label class="form-check-label" for="flexCheckDefault5">Ölüm İzni</label>
								</div>
								<div class="form-check m-1">
								  <input class="form-check-input" type="radio" value="I" name="iztip" id="flexCheckDefault6" >
								  <label class="form-check-label" for="flexCheckDefault6">İdari İzin</label>
								</div>
								<div class="form-check m-1">
								  <input class="form-check-input" type="radio" value="U" name="iztip" id="flexCheckDefault7" >
								  <label class="form-check-label" for="flexCheckDefault7">Ücretsiz İzin</label>
								</div>
								<div class="form-check m-1">
								  <input class="form-check-input" type="radio" value="L" name="iztip" id="flexCheckDefault8" >
								  <label class="form-check-label" for="flexCheckDefault8">Ödül İzni</label>
								</div>
								<div class="form-check m-1">
								  <input class="form-check-input" type="radio" value="S" name="iztip" id="flexCheckDefault9" >
								  <label class="form-check-label" for="flexCheckDefault9">Süt İzni</label>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>İznin geçirileceği adres<br><small>(İkametgahtan farklı ise)</small></td>
						<td><input type="text" class="form-control" name="adres" id="adres" value="" placeholder="Adres"></td>
					</tr>
					<tr>
						<td>* İzin Açıklaması</td>
						<td><input type="text" class="form-control" name="ack" id="ack" value="" placeholder="Açıklama Yazınız..."></td>
					</tr>
					</tbody>
					</table>
                        <p>*İzin talebinde bulunan personel, bağlı bulunduğu tüm yöneticilerden hiyerarşik düzende onay imzalarını almak vetalep formunu Kurumsal Yönetim Direktörlüğüne / İnsan Kaynaklarına iletmekle yükümlüdür.</p>
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

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../vendor/datepicker/datepicker.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<script>
$(document).ready(function () {
 $('#sdate').datepicker({
   weekStart: 1 // day of the week start. 0 for Sunday - 6 for Saturday
 });
});
</script>
</body>

</html>