<?php
/*
	User_sync: LDAP ile MongoDB senkronizasyonu LDAP->MongoDB
*/
error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
include("../sess.php");	
if($_SESSION['user']==""&&$_SESSION['y_admin']!=1){
	header('Location: /login.php');
}
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['pairing']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.js"></script>
	<script src="/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
    <script src="/js/portal_functions.js"></script>
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['pairing']."  (AD->DB)";/*Kullanıcı Eşleştirme*/?></h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					  <!-- DataTables Example -->
                      <div class="card shadow mb-4 w-100">
                        <div class="card-header py-3">
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
							<form name="form1" id="form1" method="POST" action="set_sync_users.php">
                                <table class="table table-bordered" id="b_list" width="100%" cellspacing="0">
                                    <thead>
									<tr>
										<th colspan="2"><b><i><?php echo $gtext['s_gsettings'];/*Genel*/?></i></b></th>
									</tr>
                                    </thead>
                                    <tbody>
									<tr>
										<td><?php echo $gtext['s_domain'];/*Alan Adı*/?></td>
                                        <td><?php echo $ini['domain']; ?></td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_ldapserver'];/*LDAP(AD) Sunucusu*/?></td>
                                        <td><?php echo $ini['ldap_server']; ?></td>
									</tr>
									<tr>
										<td><?php echo $gtext['a_passfornewuser'];/*Yeni kullanıcılar için Şifre:*/?></td>
                                        <td><input class="form-control" type="text" name="newpass" id="newpass" value=""/></td>
									</tr>
									
									<tr>
										<td class="text-center" colspan="2">
											<button class="btn btn-secondary" type="button" id="dep_pairing"><?php echo $gtext['a_department']." ".$gtext['pairing'];/*Birim eşleştirme*/?></button>
											<button class="btn btn-primary" type="button" id="per_pairing"><?php echo $gtext['user_pairing'];/*Kullanıcı Eşleştirme*/?></button>
										</td>
									</tr>
                                    </tbody>
								</table>
								</form>
								<div id="rt"></div>
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

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include($docroot."/footer.php"); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<script>
$('form').find(':input').change(function(){ $('#per_pairing').prop("disabled", false ); }); 
$(document).ready(function() {
	$('#per_pairing').on("click", function(){ //user eşleştir
		$('#rt').html('');
		var opt={
			type	: 'POST',
			url 	: './set_sync_users.php',
			target	: '#rt',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('Are You Sure?'); 
				if(y==false){ window.location.reload(); }
				$('#per_pairing').prop('disabled', true);
			},
			success: function(data){  
				if(data=='login'){ alert('Please Login!'); location.assign('../login.php');}
				alert('<?php echo $gtext['a_OK'];/*İşlem tamamlandı!*/?>'); 
			}
		}
		$('#form1').ajaxForm(opt); //*/
		$('#form1').submit();
	});
	$('#dep_pairing').on("click", function(){ //birim eşleştir
		$('#rt').html('');
		var opt={
			type	: 'POST',
			url 	: './set_sync_ous.php',
			target	: '#rt',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){				
				var y=confirm('Are You Sure?'); 
				if(y==false){ window.location.reload(); }
				$('#dep_pairing').prop('disabled', true);
			},
			success: function(data){ 
				if(data=='login'){ alert('Please Login!'); location.assign('../login.php');}
				alert('<?php echo $gtext['a_OK'];/*İşlem tamamlandı!*/?>');  
			}
		}
		$('#form1').ajaxForm(opt); //*/
		$('#form1').submit();
	});
});
</script>
</body>

</html>