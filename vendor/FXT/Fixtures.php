<!DOCTYPE html>
<?php
/*
	For a new page
*/
include("../set_mng.php");  //for Mongo DB connection, if needed. If not; write first line: include('/get_ini.php');
error_reporting(0);
include($docroot."/sess.php");
if($user==""){ //if auth pages needed...
	header('Location: /login.php');
}
//Fixture_types
$tcol=$db->Fixture_types;
$tcursor=$tcol->find(
	[
		'code'=>['$ne'=>null]
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort' => [
			'type'=>1,
		],
	],
);
if($tcursor){
	$ftsatir=[];
	foreach ($tcursor as $tformsatir) {
		$tsatir=[];
		$tsatir['code']	=$tformsatir->code;
		$tsatir['type']	=$tformsatir->type;
		$ftsatir[]=$tsatir;
	}
}
//department
$dcol=$db->departments;
$dcursor=$dcol->find(
	[
		'state'=>['$ne'=>null]
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort' => [
			'ou'=>1,
			'description'=>1,
		],
	],
);
if($dcursor){
	$fdsatir=[];
	foreach ($dcursor as $dformsatir) {
		$dsatir=[];
		$dsatir['ou']			=$dformsatir->ou;
		$dsatir['description']	=$dformsatir->description;
		$fdsatir[]=$dsatir;
	}
}
//place
$pcol=$db->Places;
$pcursor=$pcol->find(
	[
		'state'=>['$ne'=>null]
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort' => [
			'code'=>1,
			'description'=>1,
		],
	],
);
if($pcursor){
	$fpsatir=[];
	foreach ($pcursor as $pformsatir) {
		$psatir['code']			=$pformsatir->code;
		$psatir['description']	=$pformsatir->description;
		$fpsatir[]=$psatir;
	}
}
//
@$collection=$db->Fixtures;
$cursor = $collection->aggregate([
	[
		'$match'=>[ 'state'=>'A']
	],
    ['$lookup'=>
		[
			'from'=>"personel",
			'localField'=>"username",
			'foreignField'=>"username",
			'as'=>"persons"
		]
	],
	['$unwind'=>'$persons'],
	[
       '$addFields'=> [
			'displayname'=> '$persons.displayname',
			'usrname'=> '$persons.username',
			'department'=> '$persons.department',
		],
    ],
	[
		'$sort' => [
		  'code' => -1, 
		],
	],
]);//*/
$fsatir=[]; $fsay=0;
foreach ($cursor as $formsatir) {
	$satir=[]; 
	$satir['_id']			=$formsatir->_id;  
	$satir['code']			=$formsatir->code; 
	$satir['description']	=$formsatir->description; 
	$satir['serialnumber']	=$formsatir->serialnumber; 
	$satir['username']		=$formsatir->username;
	$satir['displayname']	=$formsatir->displayname;
	//
	$ti=array_search($formsatir->type, array_column($ftsatir, 'code')); 
	if($ti!=false||$ti!=''){ $satir['typer']=$ftsatir[$ti]['type']; }else{ $satir['typer']=$formsatir->type; }
	//
	$di=array_search($formsatir->department, array_column($fdsatir, 'ou'));
	if($di!=false||$di!=''){ $satir['deptdesc']=$fdsatir[$di]['description']; }else{ $satir['deptdesc']=$formsatir->department; }
	//
	$pi=array_search($formsatir->place, array_column($fpsatir, 'code'));
	if($pi!=false||$pi!=''){ $satir['placedesc']=$fpsatir[$pi]['description']; }else{ $satir['placedesc']=$formsatir->place; }
	//
	$satir['fixtaccrecord']=$formsatir->fixtaccrecord;
	$satir['state']=$formsatir->state;
	$fsatir[]=$satir;
	$fsay++;
}
//FAR kayıtları
@$xcollection=$db->FixtAccRecords;
$xcursor = $xcollection->find(
	[
		'description'=>['$ne'=>null]
	],
	[
		'limit' => 0,
		'projection' => [
		],
	]
    
);
$farsatir=[];
foreach ($xcursor as $xformsatir) {
	$xsatir=[]; 
	$xsatir['_id']		  	=$xformsatir->_id;  
	$xsatir['farecord']	  	=$xformsatir->farecord; 
	$xsatir['type']		  	=$xformsatir->type; 
	$xsatir['description']	=$xformsatir->description; 
	$xsatir['boughtfrom']	=$xformsatir->boughtfrom; 
	$xsatir['invdate']		=$xformsatir->invdate; 
	$xsatir['inv_no']		=$xformsatir->inv_no; 
	$farsatir[]=$xsatir;
}
?>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['fixtures']; ?></title>

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
<script>
function getfxt(){
	$('#fxteklespan').css('display', 'inline');
	$('#fareklespan').css('display', 'none');
	$('#tab01').addClass('bg-secondary text-white');
	$('#tab02').removeClass('bg-secondary text-white');
}
function getfar(){ 
	$('#fxteklespan').css('display', 'none');
	$('#fareklespan').css('display', 'inline');
	$('#tab01').removeClass('bg-secondary text-white');
	$('#tab02').addClass('bg-secondary text-white');
}
</script>
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['fixtures'];/*Demirbaşlar*/?></h1>
						<span id="fxteklespan" style="display: inline;"><a href="Fixture.php" target="_tab" id="fxtekle" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" target="_tab"><i class="fas fa-download fa-sm text-white-50"></i> <?php echo $gtext['fixture']." ".$gtext['insert'];?></a></span>
                        <span id="fareklespan" style="display: none;"><a href="Farecord.php" target="_tab" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm" target="_tab"><i class="fas fa-download fa-sm text-white-50"></i> <?php echo $gtext['fixtaccrecord']." ".$gtext['insert'];?></a></span>
                    </div>
                    <ul class="nav nav-fill nav-tabs" role="tablist">
					  <li class="nav-item" role="presentation">
						<a class="nav-link active bg-secondary text-white" id="tab01" data-bs-toggle="tab" href="#tp01" role="tab" aria-controls="tp01" aria-selected="true" onClick="getfxt();"> <?php echo $gtext['fixtures']; /*Demirbaşlar*/?> </a>
					  </li>
					  <li class="nav-item" role="presentation">
						<a class="nav-link" id="tab02" data-bs-toggle="tab" href="#tp02" role="tab" aria-controls="tp02" aria-selected="false" onClick="getfar();"><?php echo $gtext['fixtaccrecords']; /*Muhasebe Demirbaş Kayıtları*/?></a>
					  </li>
					</ul>
					<div class="tab-content" id="tab-content">
					  <DIV class="tab-pane active" id="tp01" role="tabpanel" aria-labelledby="tab01">	
						<input type="hidden" id="filename" value=""/>
					  <div class="card shadow lg-11">
                        <!--div class="card-header py-2"><?php echo $gtext['fixture_list']; /*Demirbaş Listesi*/?></div-->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="fixtlist" width="100%" cellspacing="0">
								<thead>
								  <tr>
									<TH class="text-center"><?php echo $gtext['fixtcode'];/*Kodu*/?></TH>
									<TH class="text-center"><?php echo $gtext['type'];/*Tip*/?></TH>
									<TH class="text-center"><?php echo $gtext['fixtdesc'];/*Tanım*/?></TH>
									<TH class="text-center"><?php echo $gtext['serialnumber'];/*Gönderiler*/?></TH>
									<TH class="text-center"><?php echo $gtext['debit'];/*Zimmet sahibi*/?></TH>
									<TH class="text-center"><?php echo $gtext['a_department'];/*Zimmet sahibi birimi*/?></TH>
									<TH class="text-center"><?php echo $gtext['place'];/*Yer*/?></TH>
									<TH class="text-center"><span title="<?php echo $gtext['fixtaccrecord']; ?>"><?php echo $gtext['Accountant'];/*Muhasebe*/?></span></TH>
									<TH class="text-center"><?php echo $gtext['process'];/*İşlem*/?></TH>
								  </tr>
								</thead>				
								<tfoot>
								  <tr>
									<TH class="text-center"><?php echo $gtext['fixtcode'];/*Kodu*/?></TH>
									<TH class="text-center"><?php echo $gtext['type'];/*Tip*/?></TH>
									<TH class="text-center"><?php echo $gtext['fixtdesc'];/*Tanım*/?></TH>
									<TH class="text-center"><?php echo $gtext['serialnumber'];/*Gönderiler*/?></TH>
									<TH class="text-center"><?php echo $gtext['debit'];/*Zimmet sahibi*/?></TH>
									<TH class="text-center"><?php echo $gtext['a_department'];/*Zimmet sahibi birimi*/?></TH>
									<TH class="text-center"><?php echo $gtext['place'];/*Yer*/?></TH>
									<TH class="text-center"><span title="<?php echo $gtext['fixtaccrecord']; ?>"><?php echo $gtext['Accountant'];/*Muhasebe*/?></span></TH>
									<TH class="text-center"><?php echo $gtext['process'];/*İşlem*/?></TH>
								  </tr>
								</tfoot>
								<tbody><?php for($i=0;$i<$fsay;$i++){ ?><TR>
									<TD class="text-center"><?php echo $fsatir[$i]['code'];?></TD>
									<TD class="text-center"><?php echo $fsatir[$i]['typer'];?></TD>
									<TD class="word-wrap"><?php echo $fsatir[$i]['description'];?></TD>
									<TD><?php echo $fsatir[$i]['serialnumber'];?></TD>
									<TD><?php echo $fsatir[$i]['displayname'];?></TD>
									<TD class="text-center"><small><?php echo $fsatir[$i]['deptdesc'];?></small></TD>
									<TD class="text-center"><small><?php echo $fsatir[$i]['placedesc'];?></small></TD>
									<TD class="text-center"><small><?php echo $fsatir[$i]['fixtaccrecord'];?></small></TD>
									<TD class="text-items-center"><?php if($satir['_id']!=''){ ?>
										<div class="dropdown">
										  <button class="btn btn-secondary dropdown-toggle" type="button" id="pm_<?php echo $fsatir[$i]['serialnumber']; ?>" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
											<?php echo $gtext['procs'];/*İşlemler*/?>
										  </button>
										  <div class="dropdown-menu" aria-labelledby="pm_<?php echo $fsatir[$i]['serialnumber']; ?>">
											<li><a class="dropdown-item text-dark" href="Fixture.php?id=<?php echo $fsatir[$i]['_id']; ?>" target="_blank"><?php echo "<b>".$gtext['view']."/".$gtext['change']."</b>";/*Görüntüle/Değiştir*/?></a></li>
											<li><a class="dropdown-item text-dark" href="Fixture.php?plch=1&id=<?php echo $fsatir[$i]['_id']; ?>" target="_blank"><?php echo "<b>".$gtext['place']." ".$gtext['change']."</b>";/*Yer Değiştir*/?></a></li>
											<?php //Zimmeti boşalt
											if($fsatir[$i]['username']!='mainstock'){ ?>
											<li><a class="dropdown-item text-dark" href="#" onClick="debitleave('<?php echo $fsatir[$i]['_id'];?>','<?php echo $fsatir[$i]['code'];?>','<?php echo $fsatir[$i]['description'];?>','<?php echo $fsatir[$i]['username'];?>');"><?php echo "<b>".$gtext['debitleave']."</b>";/*Zimmeti Bırak*/?></a></li>
											<?php }else{?>
											<li><a class="dropdown-item text-dark" href="#" onClick="debit('<?php echo $fsatir[$i]['_id'];?>','<?php echo $fsatir[$i]['code'];?>','<?php echo $fsatir[$i]['description'];?>');"><?php echo "<b>".$gtext['debitset']."</b>";/*Zimmetle*/?></a></li><?php } ?>
											<li><a class="dropdown-item text-dark" href="#" onClick="printlabel('<?php echo $fsatir[$i]['code']; ?>','<?php echo $fsatir[$i]['description']; ?>','<?php echo $fsatir[$i]['serialnumber']; ?>');"><?php echo "<b>".$gtext['label']." ".$gtext['print']."</b>";/*Etiket Yazdır*/?></a></li>
											<li><a class="dropdown-item text-dark" href="Fixture_actions.php?id=<?php echo $fsatir[$i]['_id']; ?>" target="_blank"><?php echo "<b>".$gtext['actions']."</b>";/*Hareketler*/?></a></li>
											</div>
										</div><?php } ?>								
									</TD>
								</TR><?php
}?>
								</tbody>
								</table>
							</div>
						</div>
					  </div>					  
					  </DIV>
					  <DIV class="tab-pane" id="tp02" role="tabpanel" aria-labelledby="tab02">
						<div class="card shadow lg-11">
							<!--div class="text-center border bg-secondary text-white"><?php echo $gtext['fixtaccrecords']; /*Muhasebe Demirbaş Listesi*/?></div-->
                          <div class="card-body bg-light">
                            <div class="table-responsive">
                                <TABLE class="table table-striped" id="farlist" width="100%" cellspacing="0">
								<THEAD>
								  </tr>
									<TH class="text-center"><?php echo $gtext['code'];/*Kodu*/?></TH>
									<TH class="text-center"><?php echo $gtext['type'];/*Tip*/?></TH>
									<TH class="text-center"><?php echo $gtext['description'];/*Tanım*/?></TH>
									<TH style="visible: false;"><?php echo $gtext['boughtfrom'];/*Satın alınan firma*/?></TH>
									<TH style="visible: false;"><?php echo $gtext['invdate'];/*Fatura Tarihi*/?></TH>
									<TH style="visible: false;"><?php echo $gtext['inv_no'];/*Fatura No*/?></TH>
									<TH class="text-center"><?php echo $gtext['process'];/*İşlem*/?></TH>
								  </tr>
								</THEAD>				
								<TFOOT>
								  <tr>
									<TH class="text-center"><?php echo $gtext['code'];/*Kodu*/?></TH>
									<TH class="text-center"><?php echo $gtext['type'];/*Tip*/?></TH>
									<TH class="text-center"><?php echo $gtext['description'];/*Tanım*/?></TH>
									<TH style="visible: false;"><?php echo $gtext['boughtfrom'];/*Tanım*/?></TH>
									<TH style="visible: false;"><?php echo $gtext['invdate'];/*Fatura Tarihi*/?></TH>
									<TH style="visible: false;"><?php echo $gtext['inv_no'];/*Fatura No*/?></TH>
									<TH class="text-center"><?php echo $gtext['process'];/*İşlem*/?></TH>
								  </tr>
								</TFOOT>
								<TBODY><?php
								$farsay=count($farsatir); //echo "fsay:".$fsay."<br>";
								for($x=0;$x<$farsay;$x++){ 
								echo '<TR>';
								echo '<TD class="text-left">'.$farsatir[$x]['farecord'].'</TD>';
								echo '<TD class="text-center">'.$farsatir[$x]['type'].'</TD>';
								echo '<TD class="word-wrap">'.$farsatir[$x]['description'].'</TD>';
								echo '<TD style="visible: false;">'.$farsatir[$x]['boughtfrom'].'</TD>';
								echo '<TD style="visible: false;">'.$farsatir[$x]['invdate'].'</TD>';
								echo '<TD style="visible: false;">'.$farsatir[$x]['inv_no'].'</TD>';
								echo '<TD class="text-items-center">';
								echo '<div class="dropdown">';
								echo '<button class="btn btn-secondary dropdown-toggle" type="button" id="far_'.$farsatir[$x]['farecord'].'" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">'.$gtext['procs'].'</button>';
								echo '<div class="dropdown-menu" aria-labelledby="r_'.$farsatir[$x]['farecord'].'">';
								echo '<li><a class="dropdown-item text-dark" href="Farecord.php?r='.$farsatir[$x]['farecord'].'" target="_blank"><b>'.$gtext['view'].'/'.$gtext['change'].'</b></a></li>';
								echo '<li><a class="dropdown-item text-dark" href="#" onClick="fxtinsitems('.$farsatir[$x]['farecord'].');">'.$gtext['fixture'].' '.$gtext['insert'].'</a></li>';
								echo '</div></div></TD>';
								echo '</TR>';	
								}
								?>
								</TBODY>
								</TABLE>
							</div>
						  </div>
						</div>					  
					  </DIV>
					</div>
                <!-- /.container-fluid -->
				</div>
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
	<!-- Zimmetle Modal-->
	<div class="modal fade" id="fxtdebitModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="fs_form" method="POST" action="set_fxt_debit.php">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalLabel"><?php echo $gtext['debit']." ".$gtext['change'].": ";/*Zimmet Değiştir*/?><span id="m_code"></span></h5>
					<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
				<input type="hidden" name="_id" id="_id" value=""/>
				<table class="table table-striped">
				<tr>
					<td class="text-right"><?php echo $gtext['fixture'];/*Demirbaş*/?>:</td>
					<td><span id="m_description"></span></td>
				</tr>
				<tr>
					<td class="text-right"><?php echo $gtext['debit'];/*Zimmet*/?>:</td>
					<td><span id="usrtr" style="display: inline;">
						<input type="hidden" name="o_username" id="o_username" value=""/>
						<input class="form-control-sm" type="text" name="searchper" id="searchper" onkeyup="searchpers();" placeholder="<?php echo $gtext['search'];?>..." title="<?php echo $gtext['searchtitle'];?>"/>
						<input type="hidden" name="username" id="username" value=""/>
						<ul id="perlist">
							<li><?php echo $gtext['choose']; //Seçiniz... ?></li>
						</ul>
						</span>
						<span id="debitmsg" style="display:none;"><?php echo $gtext['debitleaving']; //Zimmet bırakılıyor? ?>
						</span>
					</td>
				</tr>
				<tr>
					<td class="text-right"><?php echo $gtext['date'];/*Tarih*/?>:</td>
					<td colspan="3">
						<div class="input-group date">
							<input type="hidden" name="o_debitdate" value=""/>
							<input type="form-control-sm datetime-local" name="debitdate" id="debitdate" value="2025-10-19 17:00"/>
							<span class="input-group-addon\">  
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
					</td>
				</tr>
				</table>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="record" disabled type="submit"><?php echo $gtext['debit']; ?></button>
					<button class="btn btn-secondary" type="button" id="cancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
				</div>
				</form>
			</div>
		</div>
	</div>
	<!--Zimmetle modal sonu-->
	<!-- Label-101 Modal-->
	<div class="modal fade" id="label101Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-2" role="dialog" aria-labelledby="label101ModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="label101ModalLabel"><?php echo $gtext['label']." ".$gtext['print'];/*Etiket Yazdır*/?></h5>
					<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body align-items-center">
					<div class="border printTable" id="printTable">
					<table>
					<tr>
						<td><span><?php echo $gtext['code'];?>:</span><span class="f_code"></span></td>
						<td rowspan="2"><div class="p-2" id="divkarekod" width="20"></div></td>
					</tr>
					<tr>
						<td><span><?php echo $gtext['serialnumbershort'];?>:</span><span class="f_serial"></span></td>
					</tr>
					</table>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="lprint" type="button" onClick="printContent('printTable');"><?php echo $gtext['print']; ?></button>
					<button class="btn btn-secondary" type="button" id="lcancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
				</div>
			</div>
		</div>
	</div>
	<!--Label-101 modal sonu-->
	<!-- Core plugin JavaScript-->
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script> 
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script type="text/javascript" src="/vendor/jquery-qrcode/jquery.qrcode.min.js"></script>
    <script src="/js/sb-admin-2.js"></script>

<script>
var dturl="<?php echo $_SESSION['lang'];?>"; 
var lang_row='<?php echo $gtext['row'];?>';
var fsatir='<?php echo json_encode($fsatir); ?>'; 
var today="<?php echo date("Y-m-d", strtotime("now"));?>"; 
var now="<?php echo date("Y-m-d H:i", strtotime("now"));?>"; 
var messagetop='<?php echo $gtext['date'].":".date("d.m.Y H:i", strtotime("now")); ?>';
var messagebottom='<?php echo $gtext['user'].":".$user;?>';
var searchValue='';
if(searchValue!=''){ searchValue='"'+searchValue+'"'; }
var objper="", keys="";
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
				titleAttr: 'Export table data as CSV',
				className: 'btn btnExport',
				charset: 'utf-8',				
				extension: '.csv',
				header: true,
				messageTop: messagetop,
				messageBottom: messagebottom,
				fieldSeparator: ';',
				fieldBoundary: '',
				bom: true,
				exportOptions: {
					rows: '', 
					columns: [0, 1, 2, 3, 4, 5, 6]
				},
			},
			{
				extend: 'excelHtml5',
				text: '<i class="fas fa-file-excel"></i>',
				titleAttr: 'Export table data as XLSX',
				className: 'btn btnExport',
				header: true,
				messageTop: messagetop,
				messageBottom: messagebottom,
				exportOptions: {
					rows: '', 
					columns: [0, 1, 2, 3, 4, 5, 6]
				},
			},
			{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i>',
				titleAttr: 'Export table data as PDF',
				className: 'btn btnExport',
				header: true,
				messageBottom: messagebottom,
				exportOptions: {
					rows: '', 
					columns: [0, 1, 2, 3, 4, 5, 6]
				},
				customize: function(doc) {
				  doc.content[1].table.body[0].forEach(function(h) {
					 h.fillColor = 'green';
				  });
				}
			}, 
			{
				extend: 'print',
				messageTop: messagetop,
				messageBottom: messagebottom,
				text: '<i class="fas fa-print"></i>',
				titleAttr: 'Print table data',
				className: 'btn btnExport',
				exportOptions: {
					rows: '', 
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
	//FAR list
	var fartable=$('#farlist').DataTable( {
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
				header: true,
				title:'<?php echo $gtext['fixtaccrecords']; ?>',
				extension: '.csv',
				fieldSeparator: ';',
				fieldBoundary: '',
				bom: true,
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5]
				},
			},
			{
				extend: 'excelHtml5',
				text: '<i class="fas fa-file-excel"></i>',
				className: 'btn btnExport',
				header: true,
				title:'<?php echo $gtext['fixtaccrecords']; ?>',
				exportOptions: {
				   columns: [0, 1, 2, 3, 4, 5]
				},
			},
			{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i>',
				className: 'btn btnExport',
				header: true,
				title:'<?php echo $gtext['fixtaccrecords']; ?>',
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5]
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
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5]
				}
			}, 
		],
	}); 
	fartable.on('click', 'tbody tr', (e) => {
		let classList = e.currentTarget.classList;		
		if (classList.contains('selected')) { classList.remove('selected');	}
		else {
			fartable.rows('.selected').nodes().each((row) => row.classList.remove('selected')); 
			classList.add('selected');
		}
	});
});
function debitleave(id, code, desc, o_usr){ //zimmeti bırak	console.log('desc:'+desc);
	//modale yazılsın, tuşa basılsın.
	$('#_id').val(id); //fixture id
	$('#m_description').html(code+' - '+desc);  //fixture description
	$('#o_username').val(o_usr);  //debit from
	$('#username').val('mainstock');  //debit to
	$('#usrtr').css('display', 'none');  
	$('#debitmsg').css('display', 'inline');  
	$('#debitdate').val(today+' 17:00');
	$('#record').attr("disabled", false);
	$('#record').html("<?php echo $gtext['debitleave']; ?>");
	$('#fxtdebitModal').modal('show');
}
function debit(id, code, desc){ //zimmetle penceresini aç
	$('#_id').val(id); //fixture id
	$('#m_description').html(code+' - '+desc);  //fixture description
	$('#usrtr').css('display', 'inline');  
	$('#debitmsg').css('display', 'none');  
	$('#debitdate').val(now);
	$('#record').html("<?php echo $gtext['debit']; ?>");
	listegetir(); 
	$('#fxtdebitModal').modal('show'); 
}
var opt={
	type	: 'POST',
	url 	: './set_fxt_debit.php',
	contentType: 'application/x-www-form-urlencoded;charset=utf-8',
	beforeSubmit : function(){
		if($('#code').val()==''||$('#type').val()==''||$('#serialnumber').val()==''||$('#description').val()==''||$('#fixtaccrecord').val()==''){
			alert('<?php echo $gtext['u_fieldmustnotblank']; ?>');
			return false;
		}
		return confirm('<?php echo $gtext['q_rusure']; //Emin misiniz? ?>');
	},
	success: function(data){ console.log(data);
		if(data=='login'){ alert('Please Login!'); location.assign('../login.php');}
		if(data.indexOf('!')){ //
			$('#record').attr("disabled", true);
			var c=confirm(data);
			if(c){ location.reload(); }
		}else { alert('<?php echo $gtext['u_error'];?>'); }	
		$('#usrtr').css('display', true);  		
	}
}
$('#fs_form').ajaxForm(opt);
function fxtinsitems(far){  //insert item to fixt acc record
	var link="Fixture.php?far="+far;
	window.open(link);
}
function searchpers() { 
	$('#perlist').css('display', 'inline');
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("searchper"); 
    if(input.value.length>2){
		filter = input.value.toUpperCase(); 
		//ul = document.getElementById("perlist");
		$('#perlist li').remove();
		$.each(objper, function(i, key, username, displayname, title){ 
			var k=objper[i].key, v=objper[i].displayname; 
			if(v.toUpperCase().indexOf(filter)>=0){ 
				var li='<li><a href="#" onClick="sec(\''+objper[i].username+'\',\''+v+'\');">'+v+' ('+objper[i].title+')('+objper[i].description+')</a></li>';
				$('#perlist').append(li); 
			}		
		}); 
	}
}
function sec(username, displayname){ 
	$('#searchper').val(displayname);
	$('#username').val(username);
	$('#perlist').css('display', 'none');
	$('#record').prop("disabled", false );
}
function listegetir(){ 
	var yol="/app/get_per_list.php"; 
	keys=['username','displayname','title','description'];
	$.ajax({
		url: yol,
		type: "POST",
		datatype: 'json',
		async: false,
		data: { 'keys': keys },
		success: function(response){ //		console.log(response);
			if(response=='login'){ location.reload(); }
			objper=JSON.parse(response);
		},
		error: function(response){ alert('Hata!'); }
	});
}
listegetir();
function mplus(){
	var m=$('#pieces').val()+1;
	$('#pieces').val(m);
}

function printlabel(code, desc, sn){ //label print penceresini aç 
	$('.f_code').html(code);  //fixture code 
	$('.f_serial').html(sn);  //fixture code 
	$('#divkarekod').html('');
	//$('#divkarekod').qrcode({width: 64,height: 64,render:"table",text: code}); //creates qrcode
	$('#label101Modal').modal('show'); 
}
<!--
function printContent(id){
	/*Source: https://stackoverflow.com/questions/11634153/how-to-add-a-print-button-to-a-web-page */
	var str=document.getElementById(id).innerHTML
	newwin=window.open('','printwin');
	newwin.document.write('<HTML moznomarginboxes mozdisallowselectionprint>\n<HEAD>\n');
	newwin.document.write('<TITLE>.</TITLE>\n');
	newwin.document.write('<script>\n');
	newwin.document.write('function chkstate(){\n');
	newwin.document.write('if(document.readyState=="complete"){\n');
	newwin.document.write('window.close()\n');
	newwin.document.write('}\n');
	newwin.document.write('else{\n');
	newwin.document.write('setTimeout("chkstate()",2000)\n');
	newwin.document.write('}\n');
	newwin.document.write('}\n');
	newwin.document.write('function print_win(){\n');
	newwin.document.write('window.print();\n');
	newwin.document.write('chkstate();\n');
	newwin.document.write('}\n');
	newwin.document.write('<\/script>\n');
	newwin.document.write('	<style>\n');
	newwin.document.write('@page{ width: 35mm; height: 25mm; margin:0.5mm; border: 0.1mm solid; border-radius:1mm; }\n');
	newwin.document.write('@media print { size: 35mm 25mm; }\n');
	newwin.document.write('</style>\n');
	newwin.document.write('</HEAD>\n');
	newwin.document.write('<BODY onload="print_win()">\n');
	newwin.document.write(str);
	newwin.document.write('</BODY>\n');
	newwin.document.write('</HTML>\n');
	newwin.document.close();
}
//-->
$("#searchper").prop("autocomplete", "off");
$('#fs_form').find(':input').change(function(){ $('#record').prop("disabled", false ); });
$('#close').on('click', function(){ $('#record').prop("disabled", true ); });//*/
</script>
</body>

</html>