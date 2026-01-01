<!DOCTYPE html>
<?php
/*
	Fixture_types.php
*/
include("../set_mng.php"); //for Mongo DB connection, if needed. If not; write first line: include('/get_ini.php');
error_reporting(0);
include($docroot."/sess.php");
if($user==""){ //if auth pages needed...
	//header('Location: /login.php');
}
//
$now=date("Y-m-d H:i:s", strtotime("now"));
$collections = $db->listCollections();
$collectionNames = [];
foreach ($collections as $collection) {
  $collectionNames[] = $collection->getName();
}
$exists = in_array('Fixture_types', $collectionNames);
if(!$exists){
		$db->createCollection('Fixture_types',[
	]);
}
//
@$collection = $db->Fixture_types;
$cursor = $collection->aggregate([
	[
		'$match'=>[ 'code'=>[ '$ne'=>null ] ] 
	],
	[
		'$sort' => [
		  'crdate' => -1, 
		],
	],
]);
$fsatir=[]; $fsay=0;  
if($cursor){  
	foreach ($cursor as $formsatir) { 
		if($id!=""&&$fsay==0){ $text="(".$formsatir->code.")".$formsatir->type; }
		$satir=[]; 
		$satir['id']		=$formsatir->_id;  
		$satir['code']		=$formsatir->code; 
		$satir['type']		=$formsatir->type; 
		if($formsatir->chdate!=''){
			$satir['chdate']=date($ini['date_local']." H:i:s", strtotime($formsatir->chdate));
		}
		$satir['crdate']	=date($ini['date_local']." H:i:s", strtotime($formsatir->crdate));
		$satir['cruser']	=$formsatir->cruser;
		$satir['chuser']	=$formsatir->chuser;
		$satir['default']	=$formsatir->default;
		$satir['state']		=$formsatir->state;
		$fsatir[]=$satir;
		$fsay++;
	}
}
?>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['fixture']." ".$gtext['types']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['fixture']." ".$gtext['types']; ?></h1>
                        <a id="fxtmodalac" href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#fxttypeModal"><i
                                class="fas fa-download fa-sm text-white-50"></i> <?php echo "Tip Oluştur";?></a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<div class="table-responsive">
						  <table class="table table-striped" id="fixtlist" width="100%" cellspacing="0">
							<thead>
							  <tr>
								<TH class="text-center"><?php echo $gtext['code'];/*Kod*/?></a></TH>
								<TH class="text-center"><?php echo $gtext['type'];/*Tip*/?></TH>
								<TH class="text-center"><?php echo $gtext['state'];/*Durum*/?></TH>
								<TH class="text-center"><?php echo $gtext['chdate'];/*Tarih*/?></TH>
								<TH class="text-center"><?php echo $gtext['process'];/*İşlem*/?></TH>
							  </tr>
							</thead>
							<tfoot>
							  <tr>
								<TH class="text-center"><?php echo $gtext['code'];/*Kod*/?></TH>
								<TH class="text-center"><?php echo $gtext['type'];/*Tip*/?></TH>
								<TH class="text-center"><?php echo $gtext['state'];/*Durum*/?></TH>
								<TH class="text-center"><?php echo $gtext['chdate'];/*Tarih*/?></TH>
								<TH class="text-center"><?php echo $gtext['process'];/*İşlem*/?></TH>
							  </tr>
							</tfoot>
							<tbody><?php echo "\n";
					for($i=0; $i<$fsay; $i++){ 
						echo "<TR id='".$i."'>\n";
						echo "<TD title='".$gtext['code']."' target='_tab'>".$fsatir[$i]['code']."</a></TD>\n";
						echo "<TD>".$fsatir[$i]['type']."</TD>\n";
						switch($fsatir[$i]['state']){ 
							case 1 		: $state=$gtext['active']; break; 
							case "1" 	: $state=$gtext['active']; break; 
							case "on" 	: $state=$gtext['active']; break; 
							case 0 		: $state=$gtext['passive']; break; 
							case "0" 	: $state=$gtext['passive']; break; 
							case "off" 	: $state=$gtext['passive']; break; 
							case "" 	: $state=$gtext['passive']; break; 
						} 
						echo "<TD class='text-center'>".$state."</TD>\n";
						echo "<TD>".$fsatir[$i]['chdate']."</TD>\n"; 
						echo "<TD>"; ?>
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle" type="button" id="ftype<?php echo $i; ?>" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
											<?php echo $gtext['procs'];/*İşlemler*/?>
										</button>
										<div class="dropdown-menu" aria-labelledby="ddmb<?php echo $fsatir[$i]['type']; ?>">
											<li><a class="dropdown-item text-dark" href="#" onClick="$('#fxtmodalac').click();" data-bs-toggle="modal" data-bs-target="#fxttypeModal"><?php echo $gtext['change'];/*Değiştir*/?></a></li>
										</div>
									</div><?php
						echo "</TD>\n"; 
						echo "</TR>\n";
					}?>
							</tbody>
						  </table>
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
	
	<!-- Modal-->
	<div class="modal fade" id="fxttypeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="ft_form" method="POST" action="set_fxt_type.php">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalLabel"><?php echo $gtext['type']." ".$gtext['change'].": ";/*Zimmet Değiştir*/?><span id="m_type"></span></h5>
					<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
				<input type="hidden" name="id" id="id" value=""/>
				<table class="table table-striped">
				<tr>
					<td class="text-right"><?php echo $gtext['code'];/*Kodu*/?>:<br>(*)(**)</td>
					<td>
						<span id="i_code">
							<input type="hidden" name="o_code" id="o_code" value=""/>
							<input class="form-control" type="text" name="code" id="code" value=""/>
						</span>
					</td>
					<td class="text-right"></td>
					<td></td>
				</tr>
				<tr>
					<td class="text-right"><?php echo $gtext['type'];/*Tanım*/?>:</td>
					<td colspan="3">
						<span id="i_type">
							<input type="hidden" name="o_type" id="o_type" value=""/>
							<input class="form-control" type="text" name="type" id="type" value=""/>
						</span>						
					</td>
				</tr>
				<tr>
					<td class="text-right"><?php echo $gtext['chdate'];/*Oluşturma Tarihi*/?>:</td>
					<td>
						<span id="chdate"></span>
					</td>
					<td class="text-right"><?php echo $gtext['crdate'];/*Değişiklik Tarihi*/?>:</td>
					<td>
						<span id="crdate"></span><br>
					</td>
				</tr>
				<tr>
					<td class="text-right"><?php echo $gtext['default']; ?></td>
					<td>
						<input type="hidden" name="o_default" id="o_default" value=""/>
						<input class="form-control form-check-input" type="checkbox" role="switch" name="default" id="default" data-toggle="toggle" data-on="<?php echo $gtext['yes'];/*Öntanımlı*/?>" data-off="<?php echo $gtext['no'];/*Değil*/?>" />
					</td>
					<td class="text-right"><?php echo $gtext['state']; ?></td>
					<td>
						<input type="hidden" name="o_state" id="o_state" value=""/>
						<input class="form-control form-check-input" type="checkbox" role="switch" name="state" id="state" data-toggle="toggle" data-on="<?php echo $gtext['active'];/*Aktif*/?>" data-off="<?php echo $gtext['passive'];/*Pasif*/?>" />
					</td>
				</tr>
				<tr>
					<td class="text-right"><?php echo $gtext['user'];/*Kullanıcı*/?>:</td>
					<td>
						<span id="chuser"></span>
					</td>
					<td class="text-right"><?php echo $gtext['user'];/*Kullanıcı*/?>:</td>
					<td>
						<span id="cruser"></span>
					</td>
				</tr>
				</table>
				</div>
				<div class="modal-footer">
					<small>(*) <?php echo $gtext['u_fieldmustnotblank'];?><br>
					(**) <?php echo $gtext['u_fieldmustunique'];?></small>
					<span>
						<button class="btn btn-secondary" type="button" id="cancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
						<button class="btn btn-primary" id="record" disabled type="submit"><?php echo $gtext['save']; ?></button>
					</span>
				</div>
				</form>
			</div>
		</div>
	</div>
	<!-- modal sonu-->
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
	<script src="/js/sb-admin-2.js"></script>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
<script>
var dturl="<?php echo $_SESSION['lang'];?>"; 
var lang_row='<?php echo $gtext['row'];?>'; 
var messagetop='<?php echo $gtext['date'].":".date("d.m.Y H:i", strtotime("now")); ?>';
var messagebottom='<?php echo $gtext['user'].":".$user;?>';
var searchValue='';
if(searchValue!=''){ searchValue='"'+searchValue+'"'; }
var chrow='';
const objc=JSON.parse('<?php echo json_encode($fsatir); ?>'); 
var keys="";
$(document).ready(function(){	
	var table=$('#fixtlist').DataTable({
		language: {
			url :"../vendor/datatables.net/"+dturl+".json",
			buttons: {
				pageLength: {
					_: ' %d '+lang_row,
					'-1': '<?php echo $gtext['allof']; /*Tümü*/?>'
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
				titleAttr: '<?php echo $gtext['tabledata'].":".$gtext['export']; /*table data Export*/?>->CSV',
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
					rows: '', 
					columns: [0, 1, 2, 3]
				},
			},
			{
				extend: 'excelHtml5',
				text: '<i class="fas fa-file-excel"></i>',
				titleAttr: '<?php echo $gtext['tabledata'].":".$gtext['export']; /*table data Export*/?>->XLSX',
				className: 'btn btnExport',
				header: true,
				messageTop: messagetop,
				messageBottom: messagebottom,
				exportOptions: {
					rows: '', 
					columns: [0, 1, 2, 3]
				},
			},
			{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i>',
				titleAttr: '<?php echo $gtext['tabledata'].":".$gtext['export']; /*table data Export*/?>->PDF',
				className: 'btn btnExport',
				header: true,
				messageTop: messagetop,
				messageBottom: messagebottom,
				exportOptions: {
					rows: '', 
					columns: [0, 1, 2, 3]
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
				titleAttr: '<?php echo $gtext['tabledata'].":".$gtext['print']; /*table data:Print*/ ?>',
				className: 'btn btnExport',
				header: true,
				messageTop: messagetop,
				messageBottom: messagebottom,
				exportOptions: {
					rows: '', 
					columns: [0, 1, 2, 3]
				},
			}, 
		],
	}); 
	table.on('click', 'tbody tr', (e) => {
		chrow=e.currentTarget.id;
		let classList = e.currentTarget.classList;		
		if (classList.contains('selected')) { classList.remove('selected');	}
		else {
			table.rows('.selected').nodes().each((row) => row.classList.remove('selected')); 
			classList.add('selected');
		}
	});
	$('#fxtmodalac').on('click', function(){ //console.log('fxtmodalac:'+chrow);
		if(chrow!=''){ //arraydan bilgileri al - chrow
			$('#id').val(objc[chrow]['id']['$oid']);
			$('#code').val(objc[chrow]['code']);
			$('#type').val(objc[chrow]['type']);
			$('#o_default').val(objc[chrow]['default']);
			$('#o_state').val(objc[chrow]['state']);
			$('#crdate').html(objc[chrow]['crdate']);
			$('#cruser').html(objc[chrow]['cruser']);
			$('#chdate').html(objc[chrow]['chdate']);
			$('#chuser').html(objc[chrow]['chuser']);
			var cdefault="checked";
			switch(objc[chrow]['default']){ 
				case 1 : cdefault='on'; break; 
				case 0 : cdefault='off'; break; 
			} 
			$('#default').bootstrapToggle(cdefault);
			//
			var cstate="checked";
			switch(objc[chrow]['state']){ 
				case 1 : cstate='on'; break; 
				case 0 : cstate='off'; break; 
			} 
			$('#state').bootstrapToggle(cstate);
		}//else{ $('#state').attr('checked', "checked"); }	console.log('state: '+$('#state').attr('checked'));
	});
	var options={
		type:	'POST',
		url : 'set_fxt_type.php',
		contentType: 'application/x-www-form-urlencoded;charset=utf-8',
		beforeSubmit : function(){
			return confirm('<?php echo $gtext['q_rusure'];?>');
		},
		success: function(data){ 
			if(data=='login'){ confirm('<?php echo $gtext['u_mustlogin'];?>'); location.open('/login.php'); }
			if(data.indexOf('!!')>-1){ alert(data); $('#code').focus(); exit;}
			$('#record').attr("disabled", true);
			if(confirm(data)){ 
				location.reload(); 
			}				
		}
	}
	$('#ft_form').ajaxForm(options);
});

var obj=JSON.parse('<?php echo json_encode($fsatir);?>'); 
$('#ft_form').find(':input').change(function(){ $('#record').prop("disabled", false ); });
$('#close').on('click', function(){ $('#record').prop("disabled", true ); });//*/
</script>
</body>

</html>