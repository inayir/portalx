<?php
/*
	Translating lang1 -> lang2  
*/
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
//if($user==""){ header('Location: /login.php');}
//
$get_lang=explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
$dila=explode('-', $get_lang[0]); 
$tolang=$dila[1];
//
$tolangfile=$docroot.'/lang/'.$tolang.'.php';
if(file_exists($tolangfile)){ include($tolangfile);	}
?>
<!DOCTYPE html>
<html lang="<?php echo $tolang;?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>PortalX Translate</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"> 
	<!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

<?php include($docroot."/set_page.php"); ?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar --><?php 
		include($docroot."/sidebar.php"); ?><!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php //include($docroot."/topbar.php"); ?><!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Translate</h1>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 mb-3">
						<form name="form1" id="form1" method="POST" action="translate.php">
						  <input type="hidden" name="lang" id="lang" value="<?php echo $lang; ?>">
						  <div class="card-body">
                            <div class="text-center">
								<label>From:
								<SELECT name="lang" class="form-control">
								<OPTION value='EN'>EN</OPTION>
								<OPTION value='TR' <?php if($tolang=='TR'){ echo "selected";}?>>TR</OPTION>
								</SELECT>
								</label>
							</div>
                          </div>
                        </div>
						<div class="col-xl-3 col-lg-3 mb-3">
						  <div class="card-body">
                            <div class="text-center">
								<label>To:
								<SELECT name="tolang" class="form-control">
								<OPTION value="chlang">Choose Language</OPTION>
								<?php
								if($tolang=='TR'){ $tolang='EN'; }
								echo "<OPTION value='".$tolang."'>".$tolang."</OPTION>";
								?>
								</SELECT>
								</label>
							</div>
						  </div>
						</div>  
					</div>
					<div class="row">
						<div class="col-xl-6 col-lg-6 mb-6">
							<div class="text-center align-text-bottom">
								<button name="submit" id="submit" class="btn btn-primary" type="submit">Translate</button>
							</div>
                        </div>
						</form>
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
    <!-- Custom scripts for all pages-->
	<script src="/js/sb-admin-2.js"></script>

<script>
$(document).ready(function() {
	$('#savebtn').on("click", function(){ 
		var opt={
			type	: 'POST',
			url 	: 'translate.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('Are you sure?');
				return y;
			},
			success: function(data){  //console.log(data);
				if(data.indexOf('!')>-1){ alert('Error:Something went wrong!'); }
				else { alert(data); location.reload(); }
			}
		}
		$('#form1').ajaxForm(opt); 
	});
});
$('form').find(':input').change(function(){ $('#savebtn').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#savebtn').prop("disabled", true ); });
</script>
</body>

</html>