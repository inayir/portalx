<?php
/*
Form listeler->cursor tipine geçecek.
*/
function get_mongodata($table, $f, $o){
	global $mongoconn;
	$client = new MongoDB\Driver\Manager($mongoconn);	
	$sorgu = new MongoDB\Driver\Query($f, $o);
	return $client->executeQuery($table, $sorgu);
}
error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
include($docroot."/sess.php"); $fisay=0;
//
@$snf=$_GET['s']; $fsatir=[];
$mongoconn=$ini['MongoConnection'];
//Form kaydı getirilir........................
$document=$ini['MongoDB'].'.Forms';
$filter  = [];    //filtre yoksa...
if($snf!=""){ $filter  = ["formclass"=>$snf]; } 
$options = ['sort' => ['name' => 1]];
$formsonuc=get_mongodata($document, $filter, $options);
foreach ($formsonuc as $formsatir){ 
	$satir=[];
	$satir[]=$formsatir->formclass;
	$satir[]=$formsatir->form;
	$satir[]=$formsatir->tanimi;
	$fsatir[]=$satir;
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
    <link

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
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
                    </div>

                    <!-- Content Row -->
                    <!-- Content Row -->
                    <div class="row">
						<!-- DataTables Example -->
                      <div class="card shadow mb-4 w-100">
                        <div class="card-header py-3">
                        </div>
                        <div class="card-body">
                            <div class="table-responsive"><div class='text-right'><a class='btn btn-secondary' href='des_form.php'><i class='fas fa-edit fa-sm text-white-50'></i> <?php echo $gtext['form']." ".$gtext['insert'];?></a></div>
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
									for($i=0; $i<$fisay; $i++){ 
										echo "<tr>";
										echo "<td>".$fsatir[$i][0]."</td>";
										echo "<td><a target='_blank' href='/forms/get_form.php?f=".$fsatir[$i][1];
										if($key2!="") { echo "&key2=".$key2; }
										echo "'>".$fsatir[$i][1]."</a></td>";
										echo "<td>".$fsatir[$i][2]."</td>";
										echo "<td class='text-right'>";
										if($_SESSION['y_bo']==1){ 
											echo "<a class='btn btn-primary' href='des_form.php?f=".$fsatir[$i][1]."'><i class='fas fa-edit fa-sm text-white-50'></i> ".$gtext['change']."</a>"; 
											echo "<a class='btn btn-warning' href='des_form.php?f=".$fsatir[$i][1]."&c=C'><i class='fas fa-copy fa-sm text-white-50'></i> ".$gtext['copy']."</a>"; 
										}
										echo "</td>";
										echo "</tr>";
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
<script>
var isl='';
var dturl="<?php echo $_SESSION['lang'];?>";
$(document).ready(function() {
	var table = $('#b_list').DataTable( {
        "language": {
			url :"../vendor/datatables/"+dturl+".json",
		}
	});

	$('#eklebtn').on("click", function(){ 
		var opt={
			type	: 'POST',
			url 	: './set_belge.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('Emin Misiniz?');
			},
			success: function(data){ 
				if(data.indexOf('MEdi')>0){ alert('Bir hata oluştu!'); }
				else { alert(data); location.reload(); }
			}
		}
		$('#form1').ajaxForm(opt);
	});
});

$('form').find(':input').change(function(){ $('#eklebtn').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#eklebtn').prop("disabled", true ); });
</script>
</body>

</html>