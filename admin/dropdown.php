<?php
include('../set_mng.php');
error_reporting(0);
include($docroot."/sess.php");
if($_SESSION['user']==""&&$_SESSION['y_admin']!=1){
	header('Location: /login.php');
} 
?>
<!DOCTYPE html>
<html lang="TR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['perlist'];/*Personel Listesi*/?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="../vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
	<!--JQuery-->
	<script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script> 
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
<?php include($docroot."/set_page.php"); ?>
<style>
div.dt-search {
    float: right;
}
div.dt-info {
    float: left;
    margin-top: 0.8em;
}
div.dt-paging {
    float: right;
    margin-top: 0.5em;
}
body {
  font: 90%/1.45em "Helvetica Neue", HelveticaNeue, Verdana, Arial, Helvetica, sans-serif;
  margin: 0;
  padding: 0;
  color: #333;
  background-color: #fff;
}
a.disabled {
  pointer-events: none;
  cursor: default; opacity: 0.2;
}
</style>
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

                    <!-- Content Row -->
					<div class="btn-group">
					  <button id="dLabel" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
						Dropdown trigger
					  </button>
					  <ul class="dropdown-menu" aria-labelledby="dLabel">
						<li><a class="dropdown-item" href="/AD/User.php" target="_blank">Ekle</a></li>
						<li><a class="dropdown-item" href="/AD/User.php?u=inayir" target="_blank">Değiştir</a></li>
						<li><a class="dropdown-item text-dark" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#ymModal" data-bs-whatever="inayir"><?php echo $gtext['permission'];/*Yetki*/?></a></li>
					  </ul>
					</div>
                <!-- /.container-fluid -->
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ymModal" data-bs-whatever="inayir">ymModal açılış</button>
				</div>
				</div>
            <!-- Footer -->
            <?php include($docroot."/footer.php"); ?>
            <!-- End of Footer -->	
            <!-- End of Main Content -->
			</div>		
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
<!-- upwModal-->
			<div class="modal fade" id="upwModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="upwModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog modal-lg centered">
					<div class="modal-content">
						<form id="form_pass" name="form_pass" method="POST" action="./set_pss.php">
						<div class="modal-header">
							<h5 class="modal-title fs-5" id="upwModalLabel"><?php echo $gtext['renew_pass'];/*Şifre Yenile*/ echo "<br>".$gtext['user'];/*Kullanıcı*/?>: <span id='usrn'></span><br></h5>
							<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
						<input class="form-control" type="hidden" name="usernamen" id="usernamen" />
						<table class="table table-striped">
						<tr>
							<td class="w-50 text-right"><?php echo $gtext['pass'];/**/?>:</td>
							<td>
								<input type="text" name="pass" id="ppass" value=""/>
								<button class="btn btn-outline-info" type="button" id="stdpss" title="Standard Password">Std</button>
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['repass'];/**/?>:</td>
							<td><input type="text" name="repass" id="prepass" value="" style="display: inline;"/></td>				
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['rechpass'];/**/?></td>
							<td>
								<input type="checkbox" name="rechpass" id="rechpass"  data-bs-toggle="toggle" data-on="<?php echo $gtext['yes'];/*Evet*/?>" data-off="<?php echo $gtext['no'];/*Hayır*/?>" />
							</td>				
						</tr>
						</table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" id="canceln" data-bs-dismiss="modal" data-bs-target="#upwModal"><?php echo $gtext['cancel']; ?></button>
							<button class="btn btn-primary" id="userpbtn" disabled type="button" data-bs-target="#upwModal"><?php echo $gtext['save']; ?></button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<!--upwModal sonu-->
			<!-- user yetki Modal-->
			<div class="modal fade" id="ymModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"aria-labelledby="ymModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog centered">
					<div class="modal-content">
						<form id="form_usery" name="form_usery" method="POST" action="set_user_yetkim.php">
						<div class="modal-header">
							<h5 class="modal-title fs-5" id="ymModalLabel"><?php echo $gtext['prereqadd'];/*Yetki Ekleme/Değiştirme*/ echo "<br>".$gtext['user'];/*Kullanıcı*/?> :<span id='usr'></span><br></h5>
							<!--button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
								<span aria-hidden="true">×</span>
							</button-->
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
							  <span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
						<input class="form-control" type="text" name="username" id="username" style="display: none;"/>
						<table class="table table-striped">
						<tr>
							<td>
								<div class="form-check">
									<input class="form-check yetki" type="checkbox" name="y_addinfoduyuru" id="y_addinfoduyuru"/>
								</div>
							</td>
							<td><?php echo $gtext['a_prereqannonunce'];/*Duyuru Ekleme/Değiştirme*/?></td>
						</tr>
						<tr>
							<td>
								<div class="form-check">
									<input class="form-check yetki" type="checkbox" name="y_addinfohaber" id="y_addinfohaber"/>
								</div>
							</td>
							<td><?php echo $gtext['a_prereqnews'];/*Haber Ekleme/Değiştirme*/?></td>
						<tr>
							<td>
								<div class="form-check">
									<input class="form-check yetki" type="checkbox" name="y_addinfoser" id="y_addinfoser"/>
								</div>
							</td>
							<td><?php echo $gtext['a_prereqshuttle'];/*Servis Ekleme/Değiştirme*/?></td>
						</tr>
						<tr>
							<td>
								<div class="form-check">
									<input class="form-check yetki" type="checkbox" name="y_addinfomenu" id="y_addinfomenu"/>
								</div>
							</td>
							<td><?php echo $gtext['a_prereqmenu'];/*Yemek Menüsü Ekleme/Değiştirme*/?></td>
						</tr>
						<tr>
							<td>
								<div class="form-check">
									<input class="form-check yetki" type="checkbox" name="y_bq" id="y_bq"/>
								</div>
							</td>
							<td><?php echo $gtext['a_prereqbq'];/*Kalite Belgeleri Ekleme/Değiştirme*/?></td>
						</tr>
						<tr>
							<td>
								<div class="form-check">
									<input class="form-check yetki" type="checkbox" name="y_bo" id="y_bo"/>
								</div>
							</td>
							<td><?php echo $gtext['a_prereqbo'];/*Kurumsal Belgeler Ekleme/Değiştirme*/?></td>
						</tr>
						<tr>
							<td>
								<div class="form-check">
									<input class="form-check yetki" type="checkbox" name="y_rcall" id="y_rcall"/>
								</div>
							</td>
							<td><?php echo $gtext['a_rcall'];/*Kurumsal Belgeler Ekleme/Değiştirme*/?></td>
						</tr>
						<tr>
							<td>
								<div class="form-check">
									<input class="form-check yetki" type="checkbox" name="y_admin" id="y_admin"/>
								</div>
							</td>
							<td><?php echo $gtext['a_prereqadmin'];/*Yetki Admin*/?></td>
						</tr>
						<tr>
						</tr>
						</table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" id="cancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
							<button class="btn btn-primary" id="userybtn" disabled type="submit"><?php echo $gtext['change']; ?></button>
						</div>
						</form>
					
					</div>
				</div>
			</div>
			<!--user yetki modal sonu-->
			
			

			<div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">New message</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				  </div>
				  <div class="modal-body">
					<form>
					  <div class="mb-3">
						<label for="recipient-name" class="col-form-label">Recipient:</label>
						<input type="text" class="form-control" id="recipient-name">
					  </div>
					  <div class="mb-3">
						<label for="message-text" class="col-form-label">Message:</label>
						<textarea class="form-control" id="message-text"></textarea>
					  </div>
					</form>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Send message</button>
				  </div>
				</div>
			  </div>
			</div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<script>
const ymModal = document.getElementById('ymModal')
if (ymModal) {
  ymModal.addEventListener('show.bs.modal', event => {
	const button = event.relatedTarget;
	const recipient = button.getAttribute('data-bs-whatever');
	$.ajax({
		type: 'POST',
		url: './M_user_yetki.php',
		data: { u: recipient},
		success: function (response){ 
			if(response=='login'){ alert('Please Login!'); location.assign('../login.php');}
			if(response!=''){ 
				var obj=JSON.parse(response); //console.log(response);
				$('#userybtn').prop("disabled", true);
				$('#username').val(recipient); 
				$('#usr').html(recipient); 
                var sonuc=false;
                var arr=['y_addinfoduyuru','y_addinfohaber','y_addinfoser','y_addinfomenu','y_bq','y_bo','y_rcall','y_admin'];
                for(var i=0; i<arr.length;i++){
                    sonuc=false;
                    if(obj[arr[i]]==1){ sonuc=true; } 
                    $('#'+arr[i]).prop("checked", sonuc);  
                }
                isl='D';
			}else{ alert('<?php echo $gtext['u_error']; ?>');}
		}
	});	
  });
}
</script>
</body>

</html>