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
			'displayname'=>['$ne'=>null],

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
		  'company' => 1,'department' => 1, 
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
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
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
	<script>	
	function userclose(una){
		var c=confirm('<?php echo $gtext['a_exclamation']." ".$gtext['a_close_ask']; ?>');
		if(c==true){
			$.ajax({
				type: 'POST',
				url: '../AD/M_user_close.php',
				data: { username: una },
				success: function (response){ 
					if(response=='login'){ alert('Please Login!'); location.assign('../login.php');}
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
		var c=confirm('<?php echo $gtext['a_reopen_ask']; ?>');
		if(c==true){ 
			$.ajax({
				type: 'POST',
				url: '../AD/M_user_reopen.php',
				data: { username: una },
				success: function (response){ 
					if(response=='login'){ alert('Please Login!'); location.assign('../login.php');}
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
		$("#usrn").html(y); 
		$("#usernamen").val(username);
		$("#ppass").val(''); 
		$("#prepass").val(''); 
		if($("#prepass").css('display')=='none'){ 
			$("#prepass").css('display', 'inline'); 
		}
	}
	function userunlock(una){
		$.ajax({
			target: '#rp',
			type: 'POST',
			url: '../AD/M_user_unlock.php',
			data: { u: una },
			success: function (response){ 
				if(response=='login'){ alert('Please Login!'); location.assign('../login.php');}
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
				if(response=='login'){ alert('Please Login!'); location.assign('../login.php');}
				if(response!='!'){
					alert(response);
				}else{ alert('<?php echo $gtext['u_error']; ?>');}
			}
		});
	}
	</script>
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
						<span id="userspan" style="display: inline;"><a id="eklebtn" href="/AD/User.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" target="_blank"><i class="fas fa-user fa-sm text-white-50"></i> <?php echo $gtext['user']." ".$gtext['insert'];/*Kullanıcı Ekle*/?></a></span>
                    </div>
					<ul class="nav nav-fill nav-tabs" role="tablist">
					  <li class="nav-item" role="presentation">
						<a class="nav-link active bg-secondary text-white" id="tab01" data-bs-toggle="tab" href="#tp01" role="tab" aria-controls="tp01" aria-selected="true" onClick="getfxt();"> <?php echo $gtext['users']; /*Kullanıcılar*/?> </a>
					  </li>
					  <li class="nav-item" role="presentation">
						<a class="nav-link" id="tab02" data-bs-toggle="tab" href="#tp02" role="tab" aria-controls="tp02" aria-selected="false" onClick="getfar();"><?php echo $gtext['closedaccs']; /*Kapalı hesaplar*/?></a>
					  </li>
					</ul>
					<div class="tab-content" id="tab-content">
					  <DIV class="tab-pane active" id="tp01" role="tabpanel" aria-labelledby="tab01">
						<!-- Content Row -->
						<div class="row">
						  <!-- DataTables Example -->
						  <div class="card shadow mb-4">
							<div class="card-header py-3 text-right">
								<label><?php echo $gtext['closedaccs'];/*Kapalı hesaplar?*/?> <input class="form-check-input m-1" type="checkbox" role="switch" data-toggle="toggle" data-on="E" data-off="H" id="closedaccs"/></label>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="perlist_A" class="table table-striped" width="100%" cellspacing="0">
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
													  <button class="btn btn-secondary dropdown-toggle" type="button" id="ddmb<?php echo $fsatir[$i]['description']; ?>" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
														<?php echo $gtext['procs'];/*İşlemler*/?>
													  </button>
													  <div class="dropdown-menu" aria-labelledby="ddmb<?php echo $fsatir[$i]['description']; ?>">
														<li><a class="dropdown-item text-dark" href="/AD/User.php?u=<?php echo $fsatir[$i]['username']; ?>" target="_blank"><?php echo $gtext['change'];/*Değiştir*/?></a></li>
														
														<li><a class="dropdown-item text-dark" <?php if($userdis==1){ echo "disabled";}?> href="/AD/User_move.php?u=<?php echo $fsatir[$i]['username']; ?>" target="_blank" style="cursor: pointer;"><?php echo $gtext['a_move'];/*Birim Değiştir*/?></a></li>
														
														<?php if($userdis==0){ ?><li><a class="dropdown-item text-dark" onClick="javascript:userclose('<?php echo $fsatir[$i]['username']; ?>');"><?php echo $gtext['a_close'];/*Hesabı Kapat*/?></a></li><?php 
														}else{ ?>
														<li><a class="dropdown-item text-dark" onClick="javascript:userreopen('<?php echo $fsatir[$i]['username']; ?>');" style="cursor: pointer;"><?php echo $gtext['a_reopen'];/*Hesabı Yeniden Aç*/?></a></li><?php } 
														//
														if($ini['usersource']=='LDAP'){ ?>
														<li><a class="dropdown-item text-dark" <?php if($userdis==1){ echo "disabled";}?> onClick="javascript:userunlock('<?php echo $fsatir[$i]['username']; ?>');" style="cursor: pointer;"><?php echo $gtext['a_unlock'];/*Kilidi Aç*/?></a></li><?php } ?>
														
														<li><a class="dropdown-item  text-dark" <?php if($userdis==1){ echo "disabled";}?> onClick="javascript:usernewpass('<?php echo $fsatir[$i]['username']; ?>','<?php echo $fsatir[$i]['distinguishedname']; ?>');" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#upwModal"><?php echo $gtext['renew_pass'];/*Şifre Yenile*/?></a></li>
														
														<li><a class="dropdown-item text-dark" <?php if($userdis==1){ echo "disabled";}?> style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#ymModal" data-bs-whatever="<?php echo $fsatir[$i]['username']; ?>"><?php echo $gtext['permission'];/*Yetki*/?></a></li>
														
														<?php $d=strpos($fsatir[$i]['displayname'], $dis); if($d!=''&&$d>-1){ ?>
														<li><a class="dropdown-item text-dark" onClick="javascript:userdel('<?php echo $fsatir[$i]['username']; ?>');" style="cursor: pointer;"><?php echo $gtext['a_delete'];/*Hesabı Sil*/?></a></li><?php } ?>
														
														<li><a class="dropdown-item text-dark" style="cursor: pointer;" href="Personel_actions.php?u=<?php echo $fsatir[$i]['username']; ?>" target="_tab"><?php echo $gtext['actions'];/*Hareketler*/?></a></li>
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
					  </DIV>
					  <DIV class="tab-pane" id="tp02" role="tabpanel" aria-labelledby="tab02">
						<div class="row">
						  <!-- DataTables Closed -->
						  <div class="card shadow mb-4">
							<!--div class="card-header py-3 text-right">
								<label><?php echo $gtext['closedaccs'];/*Kapalı hesaplar?*/?> <input class="form-check-input m-1" type="checkbox" role="switch" data-toggle="toggle" data-on="E" data-off="H" id="closedaccs"/></label>
							</div-->
							<div class="card-body">
								<div class="table-responsive">
									<table id="perlist_P" class="table table-striped" width="100%" cellspacing="0">
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
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						</div>
					  </DIV>
					</div>
				</div>
                <!-- /.container-fluid -->
            <!-- End of Main Content -->
			</div>
            <!-- Footer -->
            <?php include($docroot."/footer.php"); ?>
            <!-- End of Footer -->			
			<!-- upwModal-->
			<div class="modal fade" id="upwModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="upwModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog modal-lg centered">
					<div class="modal-content">
						<form id="form_pass" name="form_pass" method="POST" action="./set_pss.php">
						<div class="modal-header">
							<h5 class="modal-title" id="upwModalLabel"><?php echo $gtext['renew_pass'];/*Şifre Yenile*/ echo "<br>".$gtext['user'];/*Kullanıcı*/?>: <span id='usrn'></span><br></h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
						<input class="form-control" type="hidden" name="usernamen" id="usernamen" />
						<table class="table table-striped">
						<tr>
							<td class="w-50 text-right"><?php echo $gtext['pass'];/**/?>:</td>
							<td>
								<input type="text" name="pass" id="ppass" value=""/>
								<button class="btn btn-outline-info" type="button" id="stdpss" title="Standard Password">Std</button>
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['repass'];/**/?>:</td>
							<td><input type="text" name="repass" id="prepass" value="" style="display: inline;"/></td>				
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['rechpass'];/**/?></td>
							<td>
								<input type="checkbox" name="rechpass" id="rechpass"  data-bs-toggle="toggle" data-on="<?php echo $gtext['yes'];/*Evet*/?>" data-off="<?php echo $gtext['no'];/*Hayır*/?>" />
							</td>				
						</tr>
						</table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" id="canceln" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
							<button class="btn btn-primary" id="userpbtn" disabled type="button"><?php echo $gtext['save']; ?></button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<!--upwModal sonu-->
			<!-- user yetki Modal-->
			<div class="modal fade" id="ymModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ymModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog centered">
					<div class="modal-content">
						<form id="form_usery" name="form_usery" method="POST" action="set_user_yetki.php">
						<div class="modal-header">
							<h5 class="modal-title" id="ymModalLabel"><?php echo $gtext['prereqadd'];/*Yetki Ekleme/Değiştirme*/ echo "<br>".$gtext['user'];/*Kullanıcı*/?> :<span id='usr'><input type="hidden" name="displayname" id="displayname"/></span><br></h5>
							<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
						<input class="form-control" type="text" name="username" id="username" style="display: none;"/>
						<table class="table table-striped">
						<tr>
							<td>
								<div class="form-check form-switch">
									<label><input class="form-check-input yetki" type="checkbox" role="switch" name="y_fixtures" id="y_fixtures" data-toggle="toggle" data-on="E" data-off="H"/> <?php echo $gtext['fixtures'];/*Demirbaşlar*/?></label>
								</div>
							</td>
							<td>
								
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-check form-switch">
									<label><input class="form-check-input yetki" type="checkbox" role="switch" name="y_addinfoduyuru" id="y_addinfoduyuru" data-toggle="toggle" data-on="E" data-off="H"/> <?php echo $gtext['a_prereqannonunce'];/*Duyuru Ekleme/Değiştirme*/?></label>
								</div>
							</td>
							<td>
								<div class="form-check form-switch">
									<label><input class="form-check-input yetki" type="checkbox" role="switch" name="y_addinfohaber" id="y_addinfohaber" data-toggle="toggle" data-on="E" data-off="H"/> <?php echo $gtext['a_prereqnews'];/*Haber Ekleme/Değiştirme*/?></label>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-check form-switch">
									<label><input class="form-check-input yetki" type="checkbox" role="switch" name="y_addinfoser" id="y_addinfoser" data-toggle="toggle" data-on="E" data-off="H"/> <?php echo $gtext['a_prereqshuttle'];/*Servis Ekleme/Değiştirme*/?></label>
								</div>
							</td>
							<td>
								<div class="form-check form-switch">
									<label><input class="form-check-input yetki" type="checkbox" role="switch" name="y_addinfomenu" id="y_addinfomenu" data-toggle="toggle" data-on="E" data-off="H"/> <?php echo $gtext['a_prereqmenu'];/*Yemek Menüsü Ekleme/Değiştirme*/?></label>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-check form-switch">
									<label><input class="form-check-input yetki" type="checkbox" role="switch" name="y_bq" id="y_bq" data-toggle="toggle" data-on="E" data-off="H"/> <?php echo $gtext['a_prereqbq'];/*Kalite Belgeleri Ekleme/Değiştirme*/?></label>
								</div>
							</td>
							<td>
								<div class="form-check form-switch">
									<label><input class="form-check-input yetki" type="checkbox" role="switch" name="y_bo" id="y_bo" data-toggle="toggle" data-on="E" data-off="H"/> <?php echo $gtext['a_prereqbo'];/*Kurumsal Belgeler Ekleme/Değiştirme*/?></label>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-check form-switch">
									<label><input class="form-check-input yetki" type="checkbox" role="switch" name="y_rcall" id="y_rcall" data-toggle="toggle" data-on="E" data-off="H"/> <?php echo $gtext['a_rcall'];/*Kurumsal Belgeler Ekleme/Değiştirme*/?></label>
								</div>
							</td>
							<td>
								<div class="form-check form-switch">
									<label><input class="form-check-input yetki" type="checkbox" role="switch" name="y_admin" id="y_admin" data-toggle="toggle" data-on="E" data-off="H"/> <?php echo $gtext['a_prereqadmin'];/*Yetki Admin*/?></label>
								</div>
							</td>
						</tr>
						<tr>
						</tr>
						</table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" id="cancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
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

    <!-- Core plugin JavaScript-->
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script> 
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="/js/sb-admin-2.js"></script>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<script>
var isl='';
var dturl='<?php echo $dil;?>'; 
var messagetop='<?php echo $gtext['date'].":".date("d.m.Y H:i", strtotime("now")); ?>';
var messagebottom='<?php echo $gtext['user'].":".$user;?>';
var lang_row='<?php echo $gtext['row'];?>';
var searchValue='<?php echo $_GET['sea'];?>';
if(searchValue!=''){ searchValue='"'+searchValue+'"'; }
$(document).ready(function(){
	jQuery(document).ready(function($){
		var tableA=$('#perlist_A').DataTable( {
			language: {
				url :"../vendor/datatables/"+dturl+".json",
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
						rows: '.d-show', 
						columns: [0, 1, 2, 3, 4]
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
						rows: '.d-show', 
						columns: [0, 1, 2, 3, 4]
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
					titleAttr: '<?php echo $gtext['tabledata'].":".$gtext['print']; /*table data:Print*/ ?>',
					className: 'btn btnExport',
					header: true,
					messageTop: messagetop,
					messageBottom: messagebottom,
					exportOptions: {
						rows: '.d-show', 
						columns: [0, 1, 2, 3, 4]
					}
				}, 
			],
		}); 
		tableA.on('click', 'tbody tr', (e) => {
			let classList = e.currentTarget.classList;		
			if (classList.contains('selected')) { classList.remove('selected');	}
			else {
				tableA.rows('.selected').nodes().each((row) => row.classList.remove('selected')); 
				classList.add('selected');
			}
		});	
		var tableP=$('#perlist_P').DataTable( {
			language: {
				url :"../vendor/datatables/"+dturl+".json",
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
						rows: '.d-show', 
						columns: [0, 1, 2, 3, 4]
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
						rows: '.d-show', 
						columns: [0, 1, 2, 3, 4]
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
					titleAttr: '<?php echo $gtext['tabledata'].":".$gtext['print']; /*table data:Print*/ ?>',
					className: 'btn btnExport',
					header: true,
					messageTop: messagetop,
					messageBottom: messagebottom,
					exportOptions: {
						rows: '.d-show', 
						columns: [0, 1, 2, 3, 4]
					}
				}, 
			],
		}); 
		tableP.on('click', 'tbody tr', (e) => {
			let cclassList = e.currentTarget.classList;		
			if (cclassList.contains('selected')) { cclassList.remove('selected'); }
				tableP.rows('.selected').nodes().each((row) => row.classList.remove('selected')); 
				cclassList.add('selected');
			}
		});	
		
	}); 
	$("#userpbtn").on('click', function(){ //upwModal
		$.ajax({
			type: 'POST',
			url: 'set_pss.php',
			data:{ username: $('#usernamen').val(), pass: $('#ppass').val(), repass: $('#prepass').val(), rechpass: $('#rechpass').prop('checked'), t:0 },
			beforesubmit: function(){ 
				if($('#ppass').val()!=$('#prepass').val()){ 
					var c=confirm('<?php echo $gtext['u_passnotsame'];?>'); 
				}
			},
			success: function (data){ 
				if(data=='login'){ alert('Please Login!'); location.assign('../login.php');}
				if(data==''||data.indexOf('!!!')>-1||data.indexOf('error')>-1){ alert('<?php echo $gtext['u_error']; ?>\n'+data); return false; }
				alert(data); $('#upwModal').modal('hide');
				if($('#prepass').css('display')=='none'){ 
					$('#prepass').css('display', 'inline');
				}
			}
		});
		
	});
	$('#stdpss').on('click', function(){ 
		$('#ppass').val('<?php echo $ini['stdpass'];?>');
		$('#prepass').val('<?php echo $ini['stdpass'];?>');
		$('#prepass').css('display', 'none');
		$('#userpbtn').prop("disabled", false );
	});		
	const ymModal = document.getElementById('ymModal')
	if (ymModal) {
	  ymModal.addEventListener('show.bs.modal', event => {
		const button = event.relatedTarget;
		const recipient = button.getAttribute('data-bs-whatever');
		$.ajax({
			type: 'POST',
			url: './M_user_yetki.php',
			data: { u: recipient},
			success: function (response){ 
				if(response=='login'){ alert('Please Login!'); location.assign('../login.php');}
				if(response!=''){ 
					var obj=JSON.parse(response); 
					$('#userybtn').prop("disabled", true);
					$('#username').val(recipient); 
					$('#usr').html(recipient); 
					var sonuc=false;
					var arr=['y_fixtures','y_addinfoduyuru','y_addinfohaber','y_addinfoser','y_addinfomenu','y_bq','y_bo','y_rcall','y_admin'];
					for(var i=0; i<arr.length;i++){
						sonuc="off";
						if(obj[arr[i]]==1){ sonuc="on"; } 
						$('#'+arr[i]).bootstrapToggle(sonuc); 
					}
					isl='D';
				}else{ alert('<?php echo $gtext['u_error']; ?>');}
			}
		});	
	  });
	}
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
				if(data=='login'){ alert('Please Login!'); location.assign('../login.php');}
				if(data.indexOf('!')){ alert(data); location.reload(); }
				else { alert('<?php echo $gtext['u_error'];?>'); }
			}
		}
		$('#form_usery').ajaxForm(opt); 
	});	
	$(".yetki").change(function() { $('#userybtn').prop("disabled", false ); });
	$('#cancel').on('click', function(){ $('#userybtn').prop("disabled", true ); });
	$('#form_pass').find(':input').change(function(){ $('#userpbtn').prop("disabled", false ); });
	$('#canceln').on('click', function(){ $('#userpbtn').prop("disabled", true ); });//*/
});
var objper=[]; var ksay=0;
function bilgilerigetir(gstate, ptype){
	var keys=['displayname','givenname','sn','mail','description','title','mobile','otherMobile','company','department','distinguishedname','telephonenumber','otherTelephone','physicaldeliveryofficename','manager','useraccountcontrol','ptype','note','streetaddress','district','st','co','sdate','resigndate'];
	$.ajax({
		url: 'get_users.php',
		type: "POST",
		datatype: 'json',
		async: false,
		data: { state: gstate, t: ptype, keys: keys },
		success: function(response){  //console.log(response);				
			objper=JSON.parse(response);
			$('#perlist_P tbody tr').remove();
			for(var i=0;i<objper.length;i++){
				satir='<tr>'
				+'</td><td>'+objper[i]['displayname']
				+'</td><td>'+objper[i]['description']
				+'</td><td>'+objper[i]['title']
				+'</td><td>'+objper[i]['department']
				+'</td><td>'+objper[i]['mail']
				+'</td></tr>';
				$('#perlist_P tbody').append(satir);
				ksay++;
			}			
		},
		error: function(response){ alert('Hata!'); }
	});
}
function getfxt(){
	$('#userspan').css('display', 'inline');
	$('#closeduserspan').css('display', 'none');
	$('#tab01').addClass('bg-secondary text-white');
	$('#tab02').removeClass('bg-secondary text-white');
}
function getfar(){ 
	$('#userspan').css('display', 'none');
	$('#closeduserspan').css('display', 'inline');
	$('#tab01').removeClass('bg-secondary text-white');
	$('#tab02').addClass('bg-secondary text-white');
	bilgilerigetir('A','P');
}

</script>
</body>

</html>