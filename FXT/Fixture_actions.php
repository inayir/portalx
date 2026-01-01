<?php
/*Fixture actions list : */
include("../set_mng.php"); 
//error_reporting(0);
include($docroot."/sess.php");
if($user==""){
	//echo "login"; exit;
}
//@$username=$_SESSION['user']; 
$now=date("Y-m-d H:i:s", strtotime("now"));
@$log=$now.";"; $sea='';
@$id=$_GET['id']; 
@$username=$_GET['u']; 
if($id==''){ 	
	if($username!=""){ $filter=['$match'=>[ 'username'=>[ '$eq'=>$username ] ] ]; }
	else{ $filter=['$match'=>[ 'fid'=>[ '$ne'=>'' ] ] ]; }
}else{ $filter=['$match'=>[ 'fid'=>['$eq'=>$id]]]; }

@$collection=$db->Fixture_act;  //activity
$cursor = $collection->aggregate([
	$filter,
	[
		'$sort' => [
		  'actdate' => -1, 
		],
	],
]);
$fsatir=[]; $fsay=0;  
if($cursor){  
	foreach ($cursor as $formsatir) { 
		if($id!=""&&$fsay==0){ $text="(".$formsatir->code.")".$formsatir->description; }
		$satir=[]; 
		$satir['id']		=$formsatir->_id; 
		$satir['action']	=$formsatir->action; 
		$satir['code']		=$formsatir->code; 
		$satir['description']=$formsatir->description; //*/
		$satir['fid']=$formsatir->fid; //*/
		$cd=$formsatir->changedata;
		$cd=str_replace(";", $ini['act_seperator'], $cd);
		$satir['changedata']=$cd;
		$satir['actdate']	=$formsatir->actdate;
		$fsatir[]=$satir;
		$fsay++;
	}
	//$fsay=count($fsatir); 
}
?>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['fixtures'].":".$gtext['actions']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
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
                        <h1 class="h4 text-left"><?php 
						if($id!=''){ echo $gtext['fixtures'].": ".$text; }else{ echo $gtext['fixture']; }
						echo " ".$gtext['actions'];/*Demirbaş Hareketleri*/?></h1>
                    </div>
					<div class="table-responsive">
					  <table class="table table-striped" id="fixtlist" width="100%" cellspacing="0">
						<thead>
						  <tr>
							<TH class="word-wrap"><?php echo $gtext['action'];/*Hareket*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['code'];/*Kod*/?></a></TH>
							<TH class="word-wrap"><?php echo $gtext['description'];/*Kod*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['changedata'];/*İşlem detayı*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['act_date'];/*Tarih*/?></TH>
						  </tr>
						</thead>
						<tfoot>
						  <tr>
							<TH class="word-wrap"><?php echo $gtext['action'];/*Hareket*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['code'];/*Kod*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['description'];/*Kod*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['changedata'];/*İşlem detayı*/?></TH>
							<TH class="word-wrap"><?php echo $gtext['act_date'];/*Tarih*/?></TH>
						  </tr>
						</tfoot>
						<tbody><?php echo "\n";
						for($i=0; $i<$fsay; $i++){ 
							echo "<TR>\n";
							echo "   <TD>".$gtext[$fsatir[$i]['action']]."</TD>\n";
							echo "   <TD title='".$gtext['u_codemaychange']."'><a href='Fixture.php?id=".$fsatir[$i]['fid']."' target='_tab'>".$fsatir[$i]['code']."</a></TD>\n";
							echo "   <TD>".$fsatir[$i]['description']."</TD>\n";
							echo "   <TD>".$fsatir[$i]['changedata']."</TD>\n"; 
							echo "   <TD>".$fsatir[$i]['actdate']."</TD>\n"; 
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
    <!-- Custom scripts for all pages-->
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script> 
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="/js/sb-admin-2.js"></script>
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
					columns: [0, 1, 2, 3, 4]
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
					columns: [0, 1, 2, 3, 4]
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
					columns: [0, 1, 2, 3, 4]
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
					columns: [0, 1, 2, 3, 4]
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