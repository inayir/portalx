<?php
include('../set_mng.php');
error_reporting(0);
include($docroot."/sess.php");
if($_SESSION['user']==""&&$_SESSION['y_admin']!=1){
	header('Location: /login.php');
}
//if($_SESSION['y_admin']!=1){ echo "Bu sayfaya giriş yetki gerektirir!"; header('Location: /login.php'); }
$dis=$ini['disabledname'];
@$collection=$db->personel;
$cursor = $collection->aggregate([
	[
		'$match'=>[
			'$and'=>[['displayname'=>['$ne'=>null]],['state'=>['$ne'=>'D']]]
		],
	],
	['$lookup'=>
		[
			'from'=>"departments",
			'localField'=>"department",
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
		  'department' => 1, 
		],
	],
]);
$fsatir=Array();
foreach ($cursor as $formsatir) {	
	$satir=[];
	$satir['description']=$formsatir->description;
	$satir['username']=$formsatir->username;
	$satir['displayname']=$formsatir->displayname;
	$satir['department']=$formsatir->depname; 
	$satir['title']=$formsatir->title; 
	$satir['mail']=$formsatir->mail;
	$satir['distinguishedname']=$formsatir->distinguishedname;
	$fsatir[]=$satir;
}
//var_dump($fsatir);
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
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
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"> <?php echo $gtext['s_personel']."/".$gtext['users'];/*Personel/Kullanıcılar*/?></h1>
						<a id="eklebtn" href="/AD/User_add.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" target="_blank"><i class="fas fa-user fa-sm text-white-50"></i> <?php echo $gtext['user']." ".$gtext['insert'];/*Birim Ekle*/?></a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					  <!-- DataTables Example -->
                      <div class="card shadow mb-4">
                        <div class="card-header py-3 text-right">
							<label><?php echo $gtext['closedaccs'];/*Kapalı hesaplar?*/?> <input class="form-check-input yetki m-1" type="checkbox" role="switch" data-toggle="toggle" data-on="E" data-off="H" id="closedaccs"/></label>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="per_ylist" class="table table-striped" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th><?php echo $gtext['name'];/*İsim*/?></th>
                                            <th><?php echo $gtext['pernumber'];/*Sicil*/?></th>
                                            <th><?php echo $gtext['title'];/*Unvan*/?></th>
                                            <th><?php echo $gtext['a_department']; /*Bölüm*/?></th>
                                            <th><?php echo $gtext['a_mail'];/*Mail*/?></th>
                                            <th><?php echo $gtext['process'];/*İşlem*/?></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th><?php echo $gtext['name'];/*İsim*/?></th>
                                            <th><?php echo $gtext['pernumber'];/*Sicil*/?></th>
                                            <th><?php echo $gtext['title'];/*Unvan*/?></th>
                                            <th><?php echo $gtext['a_department'];/*Bölüm*/?></th>
                                            <th><?php echo $gtext['a_mail'];/*Mail*/?></th>
                                            <th><?php echo $gtext['process'];/*İşlem*/?></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
										<?php for($i=0; $i<count($fsatir); $i++){ $userdis=0; $userdisclass="show";
										$userdis=strpos($fsatir[$i]['displayname'],$dis);
										if($userdis!=''&&$userdis>-1){ $userdis=1; $userdisclass="none"; }?><tr class="d-<?php echo $userdisclass;?>">
                                            <td><?php echo $fsatir[$i]['displayname']; ?></td>
                                            <td><div class="text-right"><?php echo $fsatir[$i]['description']; ?></div></td>
                                            <td><?php echo $fsatir[$i]['title']; ?></td>
                                            <td><?php echo $fsatir[$i]['department']; ?></td>
                                            <td><?php echo $fsatir[$i]['mail']; ?></td>
                                            <td>
											  <div class="dropdown">
												  <button class="btn btn-secondary dropdown-toggle" type="button" id="ddmb<?php echo $fsatir[$i]['description']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<?php echo $gtext['procs'];/*İşlemler*/?>
												  </button>
												  <div class="dropdown-menu" aria-labelledby="ddmb">
												    <a class="dropdown-item" href="/AD/User_add.php?u=<?php echo $fsatir[$i]['username']; ?>" target="_blank"><?php echo $gtext['change'];/*Değiştir*/?></a>
												    <a class="dropdown-item <?php if($userdis==1){ echo "disabled";}?>" href="/AD/User_move.php?u=<?php echo $fsatir[$i]['username']; ?>" target="_blank" style="cursor: pointer;"><?php echo $gtext['a_move'];/*Birim Değiştir*/?></a>
													<?php if($userdis==0){ ?><a class="dropdown-item" onClick="javascript:userclose('<?php echo $fsatir[$i]['username']; ?>');"><?php echo $gtext['a_close'];/*Hesabı Kapat*/?></a><?php }else{ ?><a class="dropdown-item" onClick="javascript:userreopen('<?php echo $fsatir[$i]['username']; ?>');" style="cursor: pointer;"><?php echo $gtext['a_reopen'];/*Hesabı Yeniden Aç*/?></a>
													<?php } 
													if($ini['usersource']=='LDAP'){ ?>
													<a class="dropdown-item <?php if($userdis==1){ echo "disabled";}?>" onClick="javascript:userunlock('<?php echo $fsatir[$i]['username']; ?>');" style="cursor: pointer;"><?php echo $gtext['a_unlock'];/*Kilidi Aç*/?></a><?php } ?>
													<a class="dropdown-item <?php if($userdis==1){ echo "disabled";}?>" onClick="javascript:usernewpass('<?php echo $fsatir[$i]['username']; ?>','<?php echo $fsatir[$i]['distinguishedname']; ?>');" style="cursor: pointer;"><?php echo $gtext['renew_pass'];/*Şifre Yenile*/?></a>
													<a class="dropdown-item <?php if($userdis==1){ echo "disabled";}?>" onClick="javascript:usery('<?php echo $fsatir[$i]['username']; ?>');" style="cursor: pointer;"><?php echo $gtext['permission'];/*Yetki*/?></a>
													<?php $d=strpos($fsatir[$i]['displayname'], $dis); if($d!=''&&$d>-1){ ?>
													<a class="dropdown-item" onClick="javascript:userdel('<?php echo $fsatir[$i]['username']; ?>');" style="cursor: pointer;"><?php echo $gtext['a_delete'];/*Hesabı Sil*/?></a><?php } ?>
													</div>
												</div>
											</td>
                                        </tr>
										<?php }
									//} ?>
									</tbody>
                                </table>                        
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
			<!-- upwModal-->
			<div class="modal fade" id="upwModal" tabindex="-1" role="dialog" aria-labelledby="upwModalLabel"
				aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form id="form_pass" name="form_pass" method="POST" action="./set_pss.php">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php echo $gtext['renew_pass'];/*Şifre Yenile*/ echo "<br>".$gtext['user'];/*Kullanıcı*/?>: <span id='usrn'></span><br></h5>
							<button class="close" type="button" data-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
						<input class="form-control" type="hidden" name="usernamen" id="usernamen" />
						<table class="table table-striped">
						<tr>
							<td><?php echo $gtext['pass'];/**/?></td>
							<td>
								<input type="text" name="pass" id="ppass" value=""/>
								<button type="button" id="stdpss" title="Standard Password">Std</button>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['repass'];/**/?></td>
							<td><input type="text" name="repass" id="prepass" value="" style="dispay: inline;"/></td>				
						</tr>
						</table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" id="canceln" data-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
							<button class="btn btn-primary" id="userpbtn" disabled type="button"><?php echo $gtext['save']; ?></button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<!--upwModal sonu-->
			<!-- user yetki Modal-->
			<div class="modal fade" id="ymModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form id="form_usery" name="form_usery" method="POST" action="set_user_yetki.php">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php echo $gtext['prereqadd'];/*Yetki Ekleme/Değiştirme*/ echo "<br>".$gtext['user'];/*Kullanıcı*/?> :<span id='usr'><input type="hidden" name="displayname" id="displayname"/></span><br></h5>
							<button class="close" type="button" data-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
						<input class="form-control" type="text" name="username" id="username" style="display: none;"/>
						<table class="table table-striped">
						<tr>
							<td><?php echo $gtext['a_prereqannonunce'];/*Duyuru Ekleme/Değiştirme*/?></td>
							<td>
								<div class="form-check form-switch">
									<input class="form-check-input yetki" type="checkbox" role="switch" name="y_addinfoduyuru" id="y_addinfoduyuru" data-toggle="toggle" data-on="E" data-off="H"/>
								</div>
							</td>							
						</tr>
						<tr>
							<td><?php echo $gtext['a_prereqnews'];/*Haber Ekleme/Değiştirme*/?></td>
							<td>
								<div class="form-check form-switch">
									<input class="form-check-input yetki" type="checkbox" role="switch" name="y_addinfohaber" id="y_addinfohaber" data-toggle="toggle" data-on="E" data-off="H"/>
								</div>
							</td>							
						</tr>
						<tr>
							<td><?php echo $gtext['a_prereqshuttle'];/*Servis Ekleme/Değiştirme*/?></td>
							<td>
								<div class="form-check form-switch">
									<input class="form-check-input yetki" type="checkbox" role="switch" name="y_addinfoser" id="y_addinfoser" data-toggle="toggle" data-on="E" data-off="H"/>
								</div>
							</td>							
						</tr>
						<tr>
							<td><?php echo $gtext['a_prereqmenu'];/*Yemek Menüsü Ekleme/Değiştirme*/?></td>
							<td>
								<div class="form-check form-switch">
									<input class="form-check-input yetki" type="checkbox" role="switch" name="y_addinfomenu" id="y_addinfomenu" data-toggle="toggle" data-on="E" data-off="H"/>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['a_prereqbq'];/*Kalite Belgeleri Ekleme/Değiştirme*/?></td>
							<td>
								<div class="form-check form-switch">
									<input class="form-check-input yetki" type="checkbox" role="switch" name="y_bq" id="y_bq" data-toggle="toggle" data-on="E" data-off="H"/>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['a_prereqbo'];/*Kurumsal Belgeler Ekleme/Değiştirme*/?></td>
							<td>
								<div class="form-check form-switch">
									<input class="form-check-input yetki" type="checkbox" role="switch" name="y_bo" id="y_bo" data-toggle="toggle" data-on="E" data-off="H"/>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['a_rcall'];/*Kurumsal Belgeler Ekleme/Değiştirme*/?></td>
							<td>
								<div class="form-check form-switch">
									<input class="form-check-input yetki" type="checkbox" role="switch" name="y_rcall" id="y_rcall" data-toggle="toggle" data-on="E" data-off="H"/>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['a_prereqadmin'];/*Yetki Admin*/?></td>
							<td>
								<div class="form-check form-switch">
									<input class="form-check-input yetki" type="checkbox" role="switch" name="y_admin" id="y_admin" data-toggle="toggle" data-on="E" data-off="H"/>
								</div>
							</td>
						</tr>
						<tr>
						</tr>
						</table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" id="cancel" data-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
							<button class="btn btn-primary" id="userybtn" disabled type="submit"><?php echo $gtext['change']; ?></button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<!--user yetki modal sonu-->
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
$(document).ready(function() {
	var table=$('#per_ylist').DataTable( {
        language: {
			url :"../vendor/datatables/"+dturl+".json",
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
				extension: '.csv',
				fieldSeparator: ';',
				fieldBoundary: '',
				bom: true,
				exportOptions: {
					rows: '.d-show', 
					columns: [0, 1, 2, 3, 4]
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
					rows: '.d-show', 
					columns: [0, 1, 2, 3, 4]
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
					rows: '.d-show', 
					columns: [0, 1, 2, 3, 4]
				}
			}, 
		]
	}); 
	table.on('click', 'tbody tr', (e) => {
		let classList = e.currentTarget.classList;		
		if (classList.contains('selected')) { classList.remove('selected');	}
		else {
			table.rows('.selected').nodes().each((row) => row.classList.remove('selected')); 
			classList.add('selected');
		}
	});	
	$("#userybtn").on("click", function(){ 
		var opt={
			type	: 'POST',
			url 	: './set_user_yetkim.php',
			data	: { u: $('#username').val() },
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('Emin Misiniz?');
			},
			success: function(data){ 
				if(response=='login'){ alert('Please Login!'); location.href('../login.php');}
				if(data.indexOf('!')){ alert(data); location.reload(); }
				else { alert('<?php echo $gtext['u_error'];/*Bir hata oluştu!*/?>'); }
			}
		}
		$('#form_usery').ajaxForm(opt); 
	});	  
	$("#closedaccs").change(function(){
		var dis="<?php echo $dis;?>";
		table.rows().eq(0).each( function ( index ) {
			var row = table.row( index ).node();
			var data = table.row( index ).data();
			if($("#closedaccs").prop("checked")==true){				
				$(row).removeClass('d-none');
				$(row).addClass('d-show');
			}else{
				if(data[0].indexOf(dis)>-1){ 
					$(row).removeClass('d-show');
					$(row).addClass('d-none'); 
				}else{ 
					$(row).removeClass('d-none');
					$(row).addClass('d-show'); 
				}
			}
		});//*/
	});
}); 
function usery(una){ 
	$.ajax({
		target: '#rp',
		type: 'POST',
		url: './M_user_yetki.php',
		data: { u: una},
		success: function (response){ 
			if(response=='login'){ alert('Please Login!'); location.href('../login.php');}
			if(response!=''){
				var obj=JSON.parse(response); 
				$('#userybtn').prop("disabled", true);
				$('#username').val(una); 
				$('#usr').html(una); 
				if(obj['y_addinfoduyuru']==1){ 	$("#y_addinfoduyuru").prop("checked",true); }else{ $("#y_addinfoduyuru").prop("checked",false); } 
				if(obj['y_addinfohaber']==1){ 	$("#y_addinfohaber").prop("checked",true);  }else{ $("#y_addinfohaber").prop("checked",false); } 
				if(obj['y_addinfoser']==1){ 	$("#y_addinfoser").prop("checked",true); 	}else{ $("#y_addinfoser").prop("checked",false); } 
				if(obj['y_addinfomenu']==1){ 	$("#y_addinfomenu").prop("checked",true); 	}else{ $("#y_addinfomenu").prop("checked",false); } 
				if(obj['y_bq']==1){ 			$("#y_bq").prop("checked",true); 			}else{ $("#y_bq").prop("checked",false); } 
				if(obj['y_bo']==1){ 			$("#y_bo").prop("checked",true); 			}else{ $("#y_bo").prop("checked",false); } 
				if(obj['y_rcall']==1){ 			$("#y_rcall").prop("checked",true); 		}else{ $("#y_rcall").prop("checked",false)	 }
				if(obj['y_admin']==1){ 			$("#y_admin").prop("checked",true); 		}else{ $("#y_admin").prop("checked",false); }			
				isl='D';
			}else{ alert('<?php echo $gtext['u_error']; ?>');}
		}
	});	
	jQuery.noConflict();
	$("#ymModal").modal('show');
}
function userclose(una){
	var c=confirm('<?php echo $gtext['a_exclamation']." ".$gtext['a_close_ask']; ?>');
	if(c==true){
		$.ajax({
			type: 'POST',
			url: '../AD/M_user_close.php',
			data: { username: una },
			success: function (response){ //console.log('donen:'+response);
				if(response=='login'){ alert('Please Login!'); location.href('../login.php');}
				if(response.indexOf('!!')>-1||response==''||response.indexOf('error')>-1){ 
					alert('<?php echo $gtext['u_error']; ?>\n'+response); 
					return false; 
				}
				if(response!=''){
					if(confirm(response)){
						location.reload();
					}				
				}else{ alert('<?php echo $gtext['u_error']; ?>');}
			}
		});
	}
}
function userreopen(una){ 
	var c=confirm('<?php echo $gtext['a_close_ask']; ?>');
	if(c==true){ 
		$.ajax({
			type: 'POST',
			url: '../AD/M_user_reopen.php',
			data: { username: una },
			success: function (response){ 
				if(response=='login'){ alert('Please Login!'); location.href('../login.php');}
				if(response.indexOf('!')>-1||response.indexOf('error')>-1){ alert('Bir hata oluştu!\n'+response); return false; }
				if(response!=''){
					if(confirm(response)){
						location.reload();
					}				
				}else{ alert('<?php echo $gtext['u_error']; ?>');}
			}
		});
	}
}
function usernewpass(username, dn){ 
	var x=dn.indexOf(',OU'); 
	var y=dn.substring(3, x)+' ('+username+')';
	$("#usrn").html(y); //*/
	$("#usernamen").val(username);
	$("#ppass").val(''); 
	$("#prepass").val(''); 
	if($("#prepass").css('display')=='none'){ 
		$("#prepass").css('display', 'inline'); 
	}
	jQuery.noConflict();
	$("#upwModal").modal('show');
}
$("#userpbtn").on('click', function(){ //upwModal
	$.ajax({
		type: 'POST',
		url: 'set_pss.php',
		data:{ username: $('#usernamen').val(), pass: $('#ppass').val(), repass: $('#prepass').val(), t:0 },
		beforesubmit: function(){ 
			if($('#ppass').val()!=$('#prepass').val()){ 
				var c=confirm('<?php echo $gtext['u_passnotsame'];?>'); 
			}
		},
		success: function (data){ //
		console.log('donen:'+data);
			if(data=='login'){ alert('Please Login!'); location.href('../login.php');}
			if(data.indexOf('!')>-1||data.indexOf('error')>-1){ alert('Bir hata oluştu!\n'+data); return false; }
			if(data!=''){ alert(data); $('#upwModal').modal('hide'); }
			else{ alert('<?php echo $gtext['u_error']; ?>');}
			if($('#prepass').css('display')=='none'){ 
				//$('#prepass').css('display', 'inline');
			}
		}
	});
	
});
function userunlock(una){
	$.ajax({
		target: '#rp',
		type: 'POST',
		url: '../AD/M_user_unlock.php',
		data: { u: una },
		success: function (response){ 
			if(response=='login'){ alert('Please Login!'); location.href('../login.php');}
			if(response!=''){
				alert(response);
			}else{ alert('<?php echo $gtext['u_error']; ?>');}
		}
	});
}
function userdel(una){
	$.ajax({
		type: 'POST',
		url: '../AD/M_user_delete.php',
		data: { u: una },
		beforeSubmit : function(){
			var c=confirm('<?php echo $gtext['user'];?>: '+una+'\n<?php echo $gtext['a_delete_ask'];?>'); 
		},
		success: function (response){ 
			if(response=='login'){ alert('Please Login!'); location.href('../login.php');}
			if(response!='!'){
				alert(response);
			}else{ alert('<?php echo $gtext['u_error']; ?>');}
		}
	});
}
$('#stdpss').on('click', function(){ 
	$('#ppass').val('<?php echo $ini['stdpass'];?>');
	$('#prepass').val('<?php echo $ini['stdpass'];?>');
	$('#prepass').css('display', 'none');
	$('#userpbtn').prop("disabled", false );
});
$(".yetki").change(function() { $('#userybtn').prop("disabled", false ); });

$('#cancel').on('click', function(){ $('#userybtn').prop("disabled", true ); });
$('#form_pass').find(':input').change(function(){ $('#userpbtn').prop("disabled", false ); });
$('#canceln').on('click', function(){ $('#userpbtn').prop("disabled", true ); });
</script>
</body>

</html>