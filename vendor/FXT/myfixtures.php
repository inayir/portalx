<?php
/*Fixture_list : */
include("../set_mng.php"); 
error_reporting(0);
include($docroot."/sess.php");
if($user==""){
	//echo "login"; exit;
}
@$username=$_SESSION['user']; 
$now=date("Y-m-d H:i:s", strtotime("now"));
@$log=$now.";"; $sea='';

@$collection=$db->Fixtures;
$cursor = $collection->find(
	[
		'username'=>$username
	],	
	[
		'limit' => 0,
		'projection' => [
		],
	]
);

$fsatir=[]; $fsay=0;
foreach ($cursor as $formsatir) {
	$satir=[]; 
	$satir['_id']			=$formsatir->_id;  
	$satir['code']			=$formsatir->code; 
	$satir['type']			=$formsatir->type; 
	$satir['description']	=$formsatir->description; 
	$satir['serialnumber']	=$formsatir->serialnumber; 
	$satir['username']		=$formsatir->username;
	$satir['place']			=$formsatir->place;
	$satir['privcode1']		=$formsatir->privcode1;
	$satir['privcode2']		=$formsatir->privcode2;
	$fsatir[]=$satir;
	$fsay++;//*/
}
$fsay=count($fsatir); 
?>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['myfixtures']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script> 

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom scripts for all pages-->
	<script src="/js/sb-admin-2.js"></script>
	<!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.js"></script>
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
					<div class="align-middle justify-content-between mb-2 pt-1">
                        <h1 class="h4 text-center"><?php echo $gtext['myfixtures'];/*Demirbaşlarım*/?></h1>
                    </div>
					<div class="table-responsive">
					  <table class="table table-striped" id="fixtlist" width="100%" cellspacing="0">
						<thead>
						  <tr>
							<TH class="word-wrap"><?php echo $gtext['fixtcode'];/*Kodu*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['type'];/*Tip*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['fixtdesc'];/*Tanım*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['serialnumber'];/*Gönderiler*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['place'];/*Yer*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['privcode']." 1";/*Özel Kod*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['privcode']." 2";/*Özel Kod*/?></TH>
						  </tr>
						</thead>
						<tfoot>
						  <tr>
							<TH class="word-wrap"><?php echo $gtext['fixtcode'];/*Kodu*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['type'];/*Tip*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['fixtdesc'];/*Tanım*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['serialnumber'];/*Gönderiler*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['place'];/*Yer*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['privcode']." 1";/*Özel Kod*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['privcode']." 2";/*Özel Kod*/?></TH>
						  </tr>
						</tfoot>
						<tbody><?php echo "\n";
						for($i=0; $i<$fsay; $i++){ 
							echo "<TR>\n";
							echo "   <TD>".$fsatir[$i]['code']."</TD>\n";
							echo "   <TD>".$fsatir[$i]['type']."</TD>\n";
							echo "   <TD>".$fsatir[$i]['description']."</TD>\n"; 
							echo "   <TD>".$fsatir[$i]['serialnumber']."</TD>\n"; 
							echo "   <TD>".$fsatir[$i]['place']."</TD>\n";
							echo "   <TD>".$fsatir[$i]['privcode1']."</TD>\n";
							echo "   <TD>".$fsatir[$i]['privcode2']."</TD>\n";
							echo "</TR>\n";
						}
						?>
						</tbody>
					  </table>
					</div>
				</div>
			</div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
<script>
var dturl="<?php echo $_SESSION['lang'];?>"; 
var lang_row='<?php echo $gtext['row'];?>';
var searchValue='<?php echo $sea;?>';
if(searchValue!=''){ searchValue='"'+searchValue+'"'; }
var messagetop='<?php echo $gtext['date'].":".date("d.m.Y H:i", strtotime("now")); ?>';
var messagebottom='<?php echo $gtext['user'].":".$user;?>';
$(document).ready(function(){
	var table=$('#fixtlist').DataTable({
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
				header: true,
				messageTop: messagetop,
				messageBottom: messagebottom,
				charset: 'utf-8',				
				extension: '.csv',
				fieldSeparator: ';',
				fieldBoundary: '',
				bom: true,
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5, 6]
				},
			},
			{
				extend: 'excel',
				text: '<i class="fas fa-file-excel"></i>',
				className: 'btn btnExport',
				header: true,
				messageTop: messagetop,
				messageBottom: messagebottom,
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5, 6]
				},
			},
			{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i>',
				className: 'btn btnExport',
				header: true,
				messageTop: messagetop,
				messageBottom: messagebottom,
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5, 6]
				},
				customize: function(doc) {
				  doc.content[1].table.body[0].forEach(function(h) {
					 h.fillColor = 'blue';
				  });
				}
			}, 
			{
				extend: 'print',
				text: '<i class="fas fa-print"></i>',
				className: 'btn btnExport',
				header: true,
				messageTop: messagetop,
				messageBottom: messagebottom,
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5, 6]
				},
			}, 
		],
	});
	table.on('click', 'tbody tr', (e) => {
		let classList = e.currentTarget.classList;		
		if (classList.contains('selected')) { classList.remove('selected');	}
		else {
			table.rows('.selected').nodes().each((row) => row.classList.remove('selected')); 
			classList.add('selected');
		}
	});
});
</script>
</body>

</html>