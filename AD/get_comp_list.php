<?php
// $ds is a valid LDAP\Connection instance for a directory server
include("../set_mng.php");
include("../sess.php");
//error_reporting(E_ALL);
include('../ldap.php');
if($_SESSION['user']==""&&$_SESSION['y_admin']!=1){
	//header('Location: /login.php');
}
$basedn = "OU=User_Computers,DC=aselsankonya,DC=com,DC=tr"; //$ini['dom_dn']; 
//echo $basedn."<br>";
$filter = array("name","lastlogon","lastlogontimestamp");

$sr = ldap_list($conn, $basedn, "(objectClass=computer)", $filter);

$info = ldap_get_entries($conn, $sr);
//echo $info["count"]." Computer<br>
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo "Computer List";?></title>

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
	<!--DataTables-->
	<link href="/vendor/datatables.net/datatables.min.css" rel="stylesheet"> 
	<script src="/vendor/datatables.net/datatables.min.js"></script>
	<script src="/vendor/datatables.net/pdfmake.min.js"></script>
	<script src="/vendor/datatables.net/vfs_fonts.js"></script>
	<!-- Bootstrap Toogle-->
	<link href="/vendor/bootstrap-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
	<script src="/vendor/bootstrap-toggle/js/bootstrap4-toggle.min.js"></script>
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
                        <h1 class="h3 mb-0 text-gray-800"> <?php echo "Computer List";?></h1>
						
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					  <!-- DataTables Example -->
                      <div class="card shadow mb-4">
                        <div class="card-header py-3 text-right">
							<?php $yilonce=date("d.m.Y", strtotime("-366 days")); 
							echo "366 days before:".$yilonce; ?>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="per_ylist" class="table table-striped" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th><?php echo "Name";?></th>
                                            <th><?php echo "Last Logon Date/Time";/**/?></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th><?php echo "Name";?></th>
                                            <th><?php echo "Last Logon Date/Time";/**/?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
<?php
for ($i=0; $i < $info["count"]; $i++) {
	$lld=$info[$i]["lastlogontimestamp"][0]; if($lld==""){ $lld=$info[$i]["lastlogon"][0];}
	$dd=date("d.m.Y", $lld/10000000-11644473600);
	
	$diff = strtotime($yilonce) - strtotime($dd);
	$days = floor(($diff)/(60*60*24));

	if($days>0){ $trc="text-danger"; }else{ $trc="text-default"; }
	echo "<tr>";
    echo "<td>".$info[$i]["name"][0]."</td>";
	echo "<td class='".$trc."'>".$dd." ";
	//echo $days;
	echo "</td>";
	echo "<td>";
	if($days>0){ echo "<button class='btn btn-danger'>Sil</button>"; }
	echo "</td>";
	echo "</tr>";	
}
echo "</tbody>";
echo "</table>";
?>
							</div>
						</div>
                      </div>
					</div>
                <!-- /.container-fluid -->

				</div>
            <!-- End of Main Content -->
			</div>
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
var isl='';
var dturl="<?php echo $_SESSION['lang'];?>"; 
var lang_row='<?php echo $gtext['row'];?>';
var searchValue='<?php echo $_GET['sea'];?>';
$(document).ready(function() {
	var table=$('#per_ylist').DataTable( {
        language: {
			url :"../vendor/datatables.net/"+dturl+".json",
			buttons: {
				pageLength: {
					_: ' %d '+lang_row,
					'-1': 'Tümü'
				}
			},
			"columnDefs": [
				{
					"targets": 1,
					"type": "num",
				}
			]
		},
		search: {"search": searchValue},
		dom: 'Bfrtip',
		buttons: [
			{
				extend: 'pageLength'
			},
			{
				extend: 'csv',
				text: '<i class="fas fa-file-csv"></i>',
				className: 'btn btnExport',
				charset: 'utf-8',				
				extension: '.csv',
				fieldSeparator: ';',
				fieldBoundary: '',
				bom: true,
				exportOptions: { 
					columns: [0, 1]
				},
			},
			{
				extend: 'excel',
				text: '<i class="fas fa-file-excel"></i>',
				className: 'btn btnExport',
			},
			{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i>',
				className: 'btn btnExport',
				exportOptions: {
					columns: [0, 1]
				},
				customize: function(doc) {
				  doc.content[1].table.body[0].forEach(function(h) {
					 h.fillColor = 'green';
				  });
				}
			}, 
			{
				extend: 'print',
				text: '<i class="fas fa-print"></i>',
				className: 'btn btnExport',
				exportOptions: {
					columns: [0, 1]
				}
			}, 
		],
	}); 
}); 
</script>
</body>

</html>