<!DOCTYPE html>
<?php
/*
	Places.php:Yerleri kaydeder.
*/
include("../set_mng.php");  //for Mongo DB connection, if needed. If not; write first line: include('/get_ini.php');
include($docroot."/sess.php");
if($user==""){ //if auth pages needed...
	header('Location: /login.php');
}
@$collection=$db->Places;
$cursor = $collection->find(
	[
		'$or'=>[['state'=>'A'],['state'=>'P']],
	],
	[
		'limit' => 0,
		'projection' => [
		],
		'sort'=>['description'=>1],
	]
);
$fsatir=[]; 
foreach ($cursor as $formsatir) {
	$satir=[]; 
	$satir['_id']=$formsatir->_id;  
	$satir['code']=$formsatir->code; 
	$satir['type']=$formsatir->type; 
	$satir['description']=$formsatir->description; 
	$satir['state']=$formsatir->state;
	$satir['default']=$formsatir->default;
	$fsatir[]=$satir;
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

    <title><?php echo $ini['title']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.js"></script>	
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
	<!-- Bootstrap Toogle-->
	<link href="/vendor/bootstrap-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
	<script src="/vendor/bootstrap-toggle/js/bootstrap4-toggle.min.js"></script>

<?php include($docroot."/set_page.php"); ?>
</head>
<body id="page-top">
<script>
function get_infos(code){
	var keys=['_id','code','description','type','streetaddress','district','st','country','state','telephonenumber','opened_date'];
	$.ajax({
		type: 'POST',
		url: './get_place.php',
		data: { p: code },
		success: function (response){ 
			if(response=='login'){ alert('Please Login!'); location.assign('../login.php');}
			if(response!='!'){ 
				var obj=JSON.parse(response);
				kontt=obj.length; var sonuc=0;
				$.each(obj, function(i, key, value){ sonuc=0;
					var k=obj[i].key, v=obj[i].value; 
					if(keys.find(k=>k)) { 						
						if(k=='state'){ 
							if(v=='A'){ $('#'+k).bootstrapToggle("on");}
							else{ $('#'+k).bootstrapToggle("off"); }
						}else{
							$('#'+k).val(v); //$('#o_'+k).val(v);
						}
					}
				});
				$('#code').css('readonly', true);				
			}else{ alert('<?php echo $gtext['u_error']; ?>');}
		}
	});
}
function get_info(code,isl){ 
	get_infos(code);
	if(isl=='E'){ 
		$('#chbtn').css('display', 'inline');
		$('#eklebtn').html("<?php echo $gtext['save']; ?>");
		$('#eklebtn').prop("disabled","disabled");
		$('.isl').prop("disabled", "disabled"); 		
	}
	if(isl=='D'){ 
		$('#eklebtn').html("<?php echo $gtext['delete']; ?>"); 
		$('.isl').prop("disabled", "disabled"); 
	}
	$('#plModal').modal('show');
}
</script>
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['places']; ?></h1>
                        <a id="pl_ekle" href="#" data-bs-toggle="modal" data-bs-target="#plModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-building"></i> <?php echo $gtext['place']." ".$gtext['insert']; ?></a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<div class="card-body">
                            <div class="table-responsive">
                                <TABLE class="table table-striped" id="list" width="100%" cellspacing="0">
								<THEAD>
									<TH><?php echo $gtext['code'];/*Kodu*/?></TH>
									<TH><?php echo $gtext['description'];/*Tanım*/?></TH>
									<TH><?php echo $gtext['type'];/*Tip*/?></TH>
									<TH><?php echo $gtext['state'];/*Durum*/?></TH>
									<TH><span title="<?php echo $gtext['default'];/*Öntanımlı*/?>">D</span></TH>
									<TH><?php echo $gtext['process'];/*İşlem*/?></TH>
								</THEAD>				
								<TFOOT>
									<TH><?php echo $gtext['fixtcode'];/*Kodu*/?></TH>
									<TH><?php echo $gtext['description'];/*Tanım*/?></TH>
									<TH><?php echo $gtext['type'];/*Tip*/?></TH>
									<TH><?php echo $gtext['state'];/*Durum*/?></TH>
									<TH><span title="<?php echo $gtext['default'];/*Öntanımlı*/?>">D</span></TH>
									<TH><?php echo $gtext['process'];/*İşlem*/?></TH>
								</TFOOT>
								<TBODY><?php for($i=0;$i<$fsay;$i++){ ?>								
								<TR>
									<TD><?php echo $fsatir[$i]['code'];?></TD>
									<TD><?php echo $fsatir[$i]['description'];?></TD>
									<TD><?php switch($fsatir[$i]['type']){
										case 'B' : echo $gtext['building']; break;
										case 'T' : echo $gtext['facility']; break; 
										case 'F' : echo $gtext['factory']; break; 
										case 'S' : echo $gtext['store']; break; 
										case 'WP' : echo $gtext['workplace']; break; 
										default : echo $gtext['workplace']; 
									}?>
									</TD>
									<TD><?php if($fsatir[$i]['state']=='A'){ echo $gtext['active'];}else{ echo $gtext['passive'];}?></TD>
									<TD class="align-center"><?php if($fsatir[$i]['default']==1){ echo "<span title='".$gtext['default']."'><b>*</b></span>";}else{ echo "";}?></TD>
									<TD>
										<div class="dropdown">
										  <button class="btn btn-secondary dropdown-toggle" type="button" id="p_<?php echo $fsatir[$i]['code']; ?>" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
											<?php echo $gtext['procs'];/*İşlemler*/?>
										  </button>
										  <div class="dropdown-menu" aria-labelledby="p_<?php echo $fsatir[$i]['code']; ?>">
											<li><a class="dropdown-item text-dark" href="javascript:get_info('<?php echo $fsatir[$i]['code']; ?>','E');"><?php echo $gtext['view']."/".$gtext['change'];/*Görüntüle/Değiştir*/?></a></li>
											<?php //hareketi yoksa silinebilir... açılan ekranda hareket varsa uyarılıp engellenir.?>
											<li><a class="dropdown-item text-dark" href="javascript:get_info('<?php echo $fsatir[$i]['code']; ?>','D');"><?php echo $gtext['delete'];/*Sil*/?></a></li>
											</div>
										</div>									
									</TD>
								</TR><?php
}?>
								</TBODY>
								</TABLE>
							</div>
						</div>
                        
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("../footer.php"); ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

	<!-- dosya yükle Modal-->
	<div class="modal fade" id="plModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="form1" method="POST" action="set_place.php">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalLabel"><?php echo $gtext['place']." ".$gtext['ins_edit'];/*Yer Ekle/Değiştir*/?></h5>
					<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
				<input type="hidden" name="_id" id="_id" value=""/>
				<input type="hidden" name="tip" id="tip" value=""/>
				<input type="hidden" name="del" id="del" value="0"/>
				<table class="table table-striped">
				<tr>
					<td class="text-right"><?php echo $gtext['place']." ".$gtext['code'];/*Yer Kodu*/?>:</td>
					<td><input class="form-control isl" type="text" name="code" id="code" size="5" value=""/></td>			
					<td class="text-right"><?php echo $gtext['type'];/*Tip*/?>:</td>
					<td>
						<SELECT class="form-control isl" id="type" name="type">
						<OPTION value="B"><?php echo $gtext['building'];?></OPTION>
						<OPTION value="T"><?php echo $gtext['facility'];?></OPTION>
						<OPTION value="F"><?php echo $gtext['factory'];?></OPTION>
						<OPTION value="S"><?php echo $gtext['store'];?></OPTION>
						<OPTION value="WP"><?php echo $gtext['workplace'];?></OPTION>
						</SELECT>
					</td>
				</tr>
				<tr>
					<td class="text-right"><?php echo $gtext['description'];/*Belge Tanımı*/?>:</td>
					<td colspan="3"><input class="form-control isl" type="text" name="description" id="description" size="50" value=""/></td>
				</tr>
				<tr>
					<td class="text-right"><?php echo $gtext['streetaddress'];/*Adres*/?>:</td>
					<td colspan="3"><input class="form-control isl" type="text" name="streetaddress" id="streetaddress" size="70" value=""/></td>
				</tr>
				<tr>
					<td class="text-right"><?php echo $gtext['district'];/*İlçe*/?>:</td>
					<td><input class="form-control isl" type="text" name="district" id="district" size="30" value=""/></td>
					<td class="text-right"><?php echo $gtext['st'];/*İl*/?>:</td>
					<td><input class="form-control isl" type="text" name="st" id="st" size="30" value=""/></td>
				</tr>
				<tr>
					<td class="text-right"><?php echo $gtext['country'];/*Ülke*/?>:</td>
					<td><input class="form-control isl" type="text" name="country" id="country" size="30" value=""/></td>
					<td class="text-right"><?php echo $gtext['telephonenumber'];/*telephonenumber*/?>:</td>
					<td><input class="form-control isl" type="text" name="telephonenumber" id="telephonenumber" size="30" value=""/></td>
				</tr>
				<tr>
					<td class="text-right"><?php echo $gtext['opening']." ".$gtext['date'];/*Açılış Tarihi*/?>:</td>
					<td>
						<input class="form-control datepicker isl" type="date" name="opened_date" id="opened_date" size="15" value="<?php echo date("d.m.Y", strtotime("now")); ?>"/>
					</td>
					<td class="text-right"><?php echo $gtext['active'];/*Aktif*/?>:</td>
					<td>
						<div class="form-check form-switch p-0 m-0">
							<input class="form-control form-check-input isl" type="checkbox" role="switch" name="state" id="state" data-toggle="toggle" data-on="<?php echo $gtext['active'];/*Aktif*/?>" data-off="<?php echo $gtext['passive'];/*Pasif*/?>"/>
						</div>
					</td>
				</tr>
				<tr>					
					<td class="text-right"><?php echo $gtext['default'];/*Öntanımlı*/?>:</td>
					<td>
						<div class="form-check form-switch p-0 m-0">
							<input class="form-control form-check-input isl" type="checkbox" role="switch" name="default" id="default" data-toggle="toggle" data-on="<?php echo $gtext['default'];/*Öntanımlı*/?>" data-off="<?php echo $gtext['normal'];/*Normal*/?>"/>
						</div>
					</td>
					<td colspan="2"></td>
				</tr>
				</table>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success" id="chbtn" type="button" style="display:none;"><?php echo $gtext['change']; ?></button>
					<button class="btn btn-primary" id="eklebtn" disabled type="submit"><?php echo $gtext['insert']; ?></button>
					<button class="btn btn-secondary" type="button" id="cancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
				</div>
				</form>
			</div>
		</div>
	</div>
<!--dosya yükle modal sonu-->
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="/js/sb-admin-2.js"></script>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<script>
$(document).ready(function() {
	var opt={
		type	: 'POST',
		url 	: './set_place.php',
		data	: { },
		contentType: 'application/x-www-form-urlencoded;charset=utf-8',
		beforeSubmit : function(){
			if($('#code').val()==''){
				alert('<?php echo $gtext['u_fieldmustnotblank']; ?>');
				return false;
			}
			return confirm('<?php echo $gtext['q_rusure']; //Emin misiniz? ?>');
		},
		success: function(data){ 
			if(data=='login'){ alert('Please Login!'); location.assign('../login.php');}
			if(data.indexOf('!')){ alert(data); location.reload(); }
			else { alert('<?php echo $gtext['u_error'];?>'); }
		}
	}
	$('#form1').ajaxForm(opt);
});
$('#chbtn').on('click', function(){ 
	$('.isl').removeAttr("disabled");
	$(this).css('display','none');
});
$('#form1').find(':input').change(function(){ $('#eklebtn').prop("disabled", false ); });
$('#canceln').on('click', function(){ $('#eklebtn').prop("disabled", true ); });//*/
</script>
</body>

</html>