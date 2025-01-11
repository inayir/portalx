<?php
/*
	Departments 
	From DB. if use LDAP, must Sync LDAP to DB.
*/
include('../set_mng.php');
//error_reporting(0);
include($docroot."/sess.php");
if($_SESSION['user']==""&&$_SESSION['y_admin']!=1){
	header('Location: \login.php');
}//*/
$ksay=0; 
$fsatir=Array(); 
	@$collection = $db->departments; //
	$cursor = $collection->aggregate([
		[
			'$match'=>[
				'dp'=>['$ne'=>null],
			],
		],
		['$lookup'=>
			[
				'from'=>"departments",
				'localField'=>"company",
				'foreignField'=>"ou",
				'as'=>"deps"
			]
		],
		['$unwind'=>'$deps'],
		['$addFields'=> [
				'depou' => '$deps.ou',
				'depname' => '$deps.description',
			],
		],
		['$sort' => [
			  'description' => 1, 
			],
		],
	]);
	if(isset($cursor)){	
		$ksay=0;
		foreach ($cursor as $formsatir) {
			$satir=[];
			$satir['dp']=$formsatir->dp;
			$satir['ou']=$formsatir->ou;
			$satir['description']=$formsatir->description;
			$satir['managedby']=$formsatir->managedby;
			$man=$formsatir->managedby;
			$man=substr($man, 3);
			$man=substr($man, 0, strpos($man, ',OU='));
			if($man==''){ $man="-"; }
			$satir['manager']=$man;
			$satir['company']=$formsatir->depname;
			if($ksay==0){ $company=$satir['ou'];}
			$fsatir[]=$satir;
			$ksay++;
		} 
	}else{
		echo "Hata oluştu.";
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

    <title><?php echo $gtext['a_managerlist'];/*Yönetici Listesi*/?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- Bootstrap Toogle-->
	<link href="/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
	<!--DataTables-->
	<link href="/vendor/datatables.net/datatables.min.css" rel="stylesheet"> 
	<script src="/vendor/datatables.net/pdfmake.min.js"></script>
	<script src="/vendor/datatables.net/vfs_fonts.js"></script>
	<script src="/vendor/datatables.net/datatables.min.js"></script>
<?php include($docroot."/set_page.php"); ?>
<style>
* {
  box-sizing: border-box;
}
#perlist {
  list-style-type: none;
  padding: 0;
  margin: 0;
  max-height: 150px;
}
#perlist li a {
  border: 1px solid #ddd;
  margin-top: -1px; /* Prevent double borders */
  background-color: #f6f6f6;
  padding: 2px;
  text-decoration: none;
  font-size: 12px;
  color: black;
  display: block;
}
#perlist li a:hover:not(.header) {
  background-color: #eee;
}
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['a_managerlist'];/**/?></h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<div class="col-xl-12 col-lg-12 p-1">
							<!-- DataTables Üst Birim -->
							<div class="card shadow mb-2">
							  <div class="card-header py-2 d-sm-flex align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary"><?php echo $gtext['a_managerlist'];/*Üst Birimler*/?></h6>
								</span>
							  </div>
							  <div class="card-body">
								<div class="table-responsive">
									<table id="clist" class="table table-striped" width="100%" cellspacing="0">
									<thead>
									<tr>
										<td><b><?php echo $gtext['percompany'];/*Yönetici*/?></b></td>
										<td><b><?php echo $gtext['a_department'];/*Üst Birim*/?></b></td>
										<td><b><?php echo $gtext['manager'];/*Yönetici*/?></b></td>
										<td style="width:200px;"></td>
									</tr>
									</thead>
									<tfoot>
									<tr>
										<td><b><?php echo $gtext['percompany'];/*Üst Birim*/?></b></td>
										<td><b><?php echo $gtext['a_department'];/*Üst Birim*/?></b></td>
										<td><b><?php echo $gtext['manager'];/*Yönetici*/?></b></td>
										<td></td>
									</tr>
									</tfoot>
									<tbody><?php 
									for($b=0;$b<$ksay;$b++){ 
									?><tr class="<?php if($b==0){ /*echo "selected";*/ $secimou=$fsatir[$b]['ou']; $secimdesc=$fsatir[$b]['description']; }?>" id="<?php echo $fsatir[$b]['ou']; ?>">
										<td><?php echo $fsatir[$b]['company']; if($fsatir[$b]['company']==""){ echo "-"; }?></td>
										<td><?php echo $fsatir[$b]['description']; ?></td>
										<td><?php echo $fsatir[$b]['manager']; ?></td>
										<td>
											<button class="btn btn-secondary cupdate" id="c-<?php echo $b; ?>"><?php echo $gtext['assign'];/*Ata*/?></button>
											<button class="btn btn-outline-danger cclear" id="c-<?php echo $b; ?>"><?php echo $gtext['empty'];/*Boşalt*/?></button>
										</td>
									</tr>
									<?php } ?>
									</tbody>
									</table>
								</div>
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
			<!-- Modal-->
				<div class="modal fade" id="depModal" tabindex="-1" role="dialog" aria-labelledby="depModalLabel"
					aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form id="myForm" action="set_manager.php" method="POST">
							<input type="hidden" name="department" id="department" value=""/>
							<div class="modal-header">
								<h5 class="modal-title" id="depModalLabel"><?php echo $gtext['a_dep_ins_edit'];/*Birim Ekleme/Değiştirme*/?></h5>
								<button class="close" type="button" data-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<table class="table table-bordered">
								<thead>
								<tr>
									<th colspan="2" id="c_desc"></th>
								</tr>
								</thead>
								<tbody>
								<tr class="w-100">
									<td class="w-25"><?php echo $gtext['manager'];/*Yönetici*/?>: </td>
									<td id="dmanager"></td>
								</tr>
								<tr class="newmanager">
									<td class="w-25"><?php echo $gtext['new']." ".$gtext['manager'];/*Yönetici*/?>: </td>
									<td class="w-75">
										<input class="form-control-sm" type="text" name="manager" id="manager" onkeyup="searchp();" placeholder="<?php echo $gtext['search'];?>..." />
										<ul id="perlist">
										  
										</ul>
									</td>
								</tr>
								</tbody>
								</table>
							</div>
							<div id="rp">
							</div>
							<div class="modal-footer">
								<button class="btn btn-primary" id="record" disabled type="button"><?php echo $gtext['assign']; ?></button>
								<button class="btn btn-secondary" type="button" id="cancel" data-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
							</div>
							</form>
						</div>
					</div>
				</div>
			<!-- modal sonu-->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<script>
var secimou="<?php echo $secimou;?>";
var secimdesc="<?php echo $secimdesc;?>";
var domain="<?php echo $ini['domain']; ?>";
var dturl="<?php echo $_SESSION['lang'];?>"; 
var lang_row='<?php echo $gtext['row'];?>';
const objc=JSON.parse('<?php echo json_encode($fsatir); ?>'); 
var objper="", keys="", assman="";
$('#description').on("blur", function(){
	if($('#description').val()!=''){	
		var dep=crea_name($('#description').val(), 99, '', 0, '',0);
		if($('#ou').val()==''){
			$('#ou').val(dep);
		}
	}	
});
$(document).ready(function() {
	var table=$('#clist').DataTable( {
        language: {
			url :"../vendor/datatables/"+dturl+".json",
			buttons: {
				pageLength: {
					_: ' %d '+lang_row,
					'-1': 'Tümü'
				}
			}
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
				extension: '.csv',
				fieldSeparator: ';',
				fieldBoundary: '',
				bom: true,
				exportOptions: {
					columns: [0, 1, 2]
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
					columns: [0, 1, 2]
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
					columns: [0, 1, 2]
				}
			}, 
		]
	}); 
	table.on('click', 'tbody tr', (e) => {
		let classList = e.currentTarget.classList;		
		if (!classList.contains('selected')) { 
			table.rows('.selected').nodes().each((row) => row.classList.remove('selected')); 
			classList.add('selected');
		}
	});	
	$('.cupdate').on('click', function(e){ 
		var c=$(this).attr('id'); 
		c=c.replace('c-',''); 
		$('#ou').val(objc[c]['ou']);  
		$('#c_desc').html(objc[c]['description']); 
		$('#department').val(objc[c]['ou']); 
		$('#manager').val(''); 		
		if($('.newmanager').css('display')=='none'){ $('.newmanager').css('display','inline'); } 
		$('#dmanager').html(objc[c]['manager']); 
		$('#perlist li').remove();	
		$('#perlist').css('display', 'inline');
		jQuery.noConflict();
		$('#depModal').modal('show');
	});	
	$('.cclear').on('click', function(e){
		var c=$(this).attr('id'); 
		c=c.replace('c-',''); 
		$('#ou').val(objc[c]['ou']);  
		$('#c_desc').html(objc[c]['description']); 
		$('#department').val(objc[c]['ou']); 
		assman="";
		$('#manager').val(''); 
		$('.newmanager').css('display','none'); 
		$('#dmanager').html(objc[c]['manager']); 
		$('#perlist').css('display', 'none');
		$('#record').html('<?php echo $gtext['empty']; ?>');
		$('#record').prop("disabled", false );
		jQuery.noConflict();
		$('#depModal').modal('show');		
	});	
	$('#record').on('click', function(){
		var options={
			type:	'POST',
			url : 'set_manager.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			data: { username: assman},
			beforeSubmit : function(){
				confirm('<?php echo $gtext['q_rusure'];?>');
			},
			success: function(data){ 
				if(data=='login'){ confirm('<?php echo $gtext['u_mustlogin'];?>'); location.open('/login.php'); }
				$('#record').attr("disabled", true);
				if(confirm(data)){ location.reload(); }				
			}
		}
		$('#myForm').ajaxForm(options);
		$('#myForm').submit();
	});
});
function searchp() {
	$('#perlist').css('display', 'inline');
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("manager"); 
    if(input.value.length>2){
		filter = input.value.toUpperCase(); 
		ul = document.getElementById("perlist");
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
	$('#manager').val(displayname);
	assman=username;
	$('#perlist').css('display', 'none');
}
function listegetir(){
	var yol="get_per_list.php"; 
	keys=['username','displayname','title','description'];
	$.ajax({
		url: yol,
		type: "POST",
		datatype: 'json',
		async: false,
		data: { 'keys': keys },
		success: function(response){ 
			if(response=='login'){ location.reload(); }
			objper=JSON.parse(response);
		},
		error: function(response){ alert('Hata!'); }
	});
}
listegetir();
$('form').find(':input').change(function(){ $('#record').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#record').prop("disabled", true ); });

</script>
</body>

</html>