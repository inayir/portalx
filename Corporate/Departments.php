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
if($ini['usersource']=='LDAP'){ require($docroot."/ldap.php"); }
$collectionNames = [];
foreach ($collections as $collection) {
  $collectionNames[] = $collection->getName();
}
$exists = in_array('departments', $collectionNames);
if(!$exists){
		$db->createCollection('personel',[
	]);
}
$ksay=0; 
$fsatir=Array(); 
@$collection = $db->departments; //
try{
	$cursor = $collection->find(
		[
			'dp'=>'C'
		],
		[
			'limit' => 0,
			'projection' => [
				'dp' => 1,
				'ou' => 1,
				'description' => 1,
				'managedby' => 1,
			],
			'sort'=>['order'=>1, 'description'=>1],
		]
	);
	if(isset($cursor)){	
		$ksay=0;
		foreach ($cursor as $formsatir) {
			$satir=[];
			$satir['dp']=$formsatir->dp;
			$satir['ou']=$formsatir->ou;
			$satir['description']=$formsatir->description;
			$satir['managedby']=$formsatir->managedby;
			if($ksay==0){ $company=$satir['ou'];}
			$fsatir[]=$satir;
			$ksay++;
		} 
	}
}catch(Exception $e){
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}	
$dsay=0;
try{
	$dcursor = $collection->find(
		[
			'dp'=>'D', 'company'=>$company,
		],
		[
			'limit' => 0,
			'projection' => [
				'dp' => 1,
				'ou' => 1,
				'company' => 1,
				'description' => 1,
				'managedby' => 1,
			],
			'sort'=>['order'=>1, 'description'=>1],
		]
	);
	if(isset($dcursor)){	
		foreach ($dcursor as $dformsatir) {
			$satir=[];
			$satir['dp']=$dformsatir->dp;
			$satir['ou']=$dformsatir->ou;
			$satir['company']=$dformsatir->company;
			$satir['description']=$dformsatir->description;
			$satir['managedby']=$dformsatir->managedby;
			$dfsatir[]=$satir;	
			$dsay++;
		} 
	}
}catch(Exception $e){
	echo 'Caught exception: ',  $e->getMessage(), "\n";
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

    <title><?php echo $gtext['a_departments'];/*Departments*/?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
 href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <script src="/vendor/jquery/jquery.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<link href="/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>	
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
	<!--DataTables-->
	<link href="/vendor/datatables.net/datatables.min.css" rel="stylesheet">
	<script src="/vendor/datatables.net/datatables.min.js"></script>
    <!-- Page level custom scripts -->
	<script src="/AD/ad_functions.js"></script>
<?php include($docroot."/set_page.php"); ?>

</head>
<body id="page-top">
<script>
	var secimou="";
	var secimdesc="";
	
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['percompanys']."/".$gtext['a_departments'];/*Birim Ekle/Değiştir*/?></h1>
						<div style="display: none;">
                        <a href="#" id="modalac" data-toggle="modal" data-target="#depModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" >Btn</a>
						</div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<div class="col-xl-6 col-lg-6 p-1">
							<!-- DataTables Üst Birim -->
							<div class="card shadow mb-2">
							  <div class="card-header py-2 d-sm-flex align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary"><?php echo $gtext['percompanys'];/*Üst Birimler*/?></h6>
								<span id='ret'><a href="#" id="cekle" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" ><i class="fas fa-download fa-sm text-white-50"></i> <?php echo $gtext['percompany']." ".$gtext['insert'];/*Üst Birim Ekle*/?></a>
								</span>
							  </div>
							  <div class="card-body">
								<div class="table-responsive">
									<table id="clist" class="table table-striped" width="100%" cellspacing="0">
									<thead>
									<tr>
										<td><b><?php echo $gtext['percompany'];/*Üst Birim*/?></b></td>
										<td></td>
									</tr>
									</thead>
									<tfoot>
									<tr>
										<td><b><?php echo $gtext['percompany'];/*Üst Birim*/?></b></td>
										<td></td>
									</tr>
									</tfoot>
									<tbody><?php 
									for($b=0;$b<$ksay;$b++){ 
									?><tr class="<?php if($b==0){ echo "selected"; $secimou=$fsatir[$b]['ou']; $secimdesc=$fsatir[$b]['description']; }?>" id="<?php echo $fsatir[$b]['ou']; ?>">
										<td><?php echo $fsatir[$b]['description']; ?></td>
										<td><button class="btn btn-info cupdate" id="c-<?php echo $b; ?>"><?php echo $gtext['change'];/*Değiştir*/?></button></td>
									</tr>
									<?php } ?>
									</tbody>
									</table>
								</div>
							  </div>
							</div>
						</div>
						<script>
							secimou="<?php echo $secimou;?>";
							secimdesc="<?php echo $secimdesc;?>";
						</script>
						<div class="col-xl-6 col-lg-6 p-1">
							<!-- DataTables Birim -->
							<div class="card shadow mb-2">
							  <div class="card-header py-2 d-sm-flex align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary"><?php echo $gtext['a_departments'];/*Birimler*/?></h6>
								<span id='ret'><a href="#" id="dekle" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" ><i class="fas fa-download fa-sm text-white-50"></i> <?php echo $gtext['a_department']." ".$gtext['insert'];/*Birim Ekle*/?></a>
							  </div>
							  <div class="card-body">
								<div class="table-responsive">
									<table id="deplist" class="table table-striped" width="100%" cellspacing="0">
									<thead>
									<tr>
										<td  style="text-align:center;"><b><?php echo $gtext['a_department'];/*Birim*/?></b></td>
										<td></td>
									</tr>
									</thead>
									<tfoot>
									<tr>
										<td style="text-align: center"><b><?php echo $gtext['a_department'];/*Birim*/?></b></td>
										<td></td>
									</tr>
									</tfoot>
									<tbody><?php 
									for($d=0;$d<$dsay;$d++){ ?>
									<tr>
										<td><?php echo $dfsatir[$d]['description']; ?></td>
										<td>
											<button class="btn btn-outline-info" onClick="javascript:dupdate('<?php echo $d;?>');"><?php echo $gtext['change'];/*Değiştir*/?></button>
											<button class="btn btn-outline-danger" onclick="javascript:tasi(<?php echo $d;?>);"><?php echo $gtext['move'];/*Taşı*/?></button>
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
				<div class="modal fade" id="depModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
					aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form id="myForm" action="set_department.php" method="POST">
							<input type="hidden" name="o_dn" id="o_dn" value="C"/>
							<input type="hidden" name="dp" id="dp" value="C"/>
							<input type="hidden" name="o_dp" id="o_dp"/>
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel"><?php echo $gtext['a_dep_ins_edit'];/*Birim Ekleme/Değiştirme*/?></h5>
								<button class="close" type="button" data-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<table class="table table-bordered">
								<tr>
									<td colspan="2" align="center" class="text-bold" id="dp_desc"></td>
								</tr>
								<tr id="comsat">
									<td><?php echo $gtext['percompany'];/*Company*/?>: </td>
									<td width="70%">
										<SELECT name="company" id="company" style="display:none"><?php
								for ($i=0; $i < $ksay; $i++){
									$a=$fsatir[$i]["ou"];
									if($a[0]!="_"&&$fsatir[$i]["description"]!=""){ 
										echo "<option value='".$a."'>";
										echo $fsatir[$i]["description"]."</option>\n"; 
									}
								} ?>
										</SELECT>
										<span id="c_desc"></span>
										<input type="hidden" name="o_company" id="o_company"/>
									</td>
								</tr>
								<tr id="descsat">
									<td><?php echo $gtext['description'];/*Tanım*/?>: </td>
									<td>
										<input type="text" name="description" id="description" value="" style="width:100%;"/>
										<input type="hidden" name="o_description" id="o_description"/>
									</td>
								</tr>
								<tr>
									<td><?php echo "OU"; ?>: </td>
									<td>										
										<input type="text" name="ou" id="ou" value="" readonly />
										<input type="hidden" name="o_ou" id="o_ou"/>
									</td>
								</tr>
								<tr>
									<td><?php echo $gtext['manager'];/*Yönetici*/?>: </td>
									<td>
										<input type="hidden" name="manager" id="manager" value=""/>
										<input type="hidden" name="o_manager" id="o_manager" value=""/>
									</td>
								</tr>
								</table>
							</div>
							<div class="modal-footer">
								<button class="btn btn-primary" id="record" disabled type="button"><?php echo $gtext['insert']; ?></button>
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
var domain="<?php echo $ini['domain']; ?>";
var dturl="<?php echo $_SESSION['lang'];?>"; 
const objc=JSON.parse('<?php echo json_encode($fsatir); ?>'); 
const objd=JSON.parse('<?php echo json_encode($dfsatir); ?>'); 
var btn="";
$(document).ready(function() {
	var ctable=$('#clist').DataTable( {
        language: {
			url :"../vendor/datatables/"+dturl+".json",
		}
	});	
	function getdepartments(cou){ //get departments according to it's company.	
		$.ajax({
			type: 'POST',
			url: './get_depsbyc.php',
			data: { company: cou },
			success: function (response){ 
				if(response==''){ alert('You must login!'); location.reload(); }
				if(response.indexOf('!')>=0){ alert(response); location.reload(); }
				else{					
					var dep=JSON.parse(response);
					var uz=dep.length; 
					$('#deplist tr').remove();
					if(uz>0){
						var bas='<tr><th style="text-align: center"><?php echo $gtext['a_department'];?></th><th>...</th></tr>';
						$('#deplist > tbody:last-child').append(bas); 
						var s=0;
						//
						for(var od=0;od<objd.length;od++){ objd.splice(od); }						
						for(var odp=0;odp<uz;odp++){ objd.push(dep[odp]); } 
						for(var i=0; i<uz; i++){
							var uinf=dep[i]; 
							var tab1='<tr><td>'
							+uinf['description']+'</td>'
							+'<td>'
							+'<button class="btn btn-outline-info" onclick="javascript:dupdate('+i+');"><?php echo $gtext['change']; ?></button>'
							+'<button class="btn btn-outline-danger" onclick="javascript:tasi('+i+');"><?php echo $gtext['move']; ?></button>'
							+'</td>'
							+'</tr>';
							$('#deplist > tbody:last-child').append(tab1); //table içindeki her satıra bir table
							s++;
						}
						$('#deplist > tbody:last-child').append(bas); 
					}
				}
			}
		});
	}
	ctable.on('click', 'tbody tr', (e) => {
		let classList = e.currentTarget.classList;		
		if (!classList.contains('selected')) { 
			ctable.rows('.selected').nodes().each((row) => row.classList.remove('selected')); 
			classList.add('selected');
		}
		//get depatments 
		secimou=ctable.row('.selected').id();
		secimdesc=ctable.row('.selected').data()[0];
		$('#c_desc').html(secimdesc); 
		getdepartments(secimou);
	});	
	var dtable=$('#deplist').DataTable( {
        "language": {
			url :"../vendor/datatables/"+dturl+".json",
		}
	});
	$('#record').on('click', function(){
		var options={
			type:	'POST',
			url : 'set_department.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				confirm('Emin misiniz?');
			},
			success: function(data){
				$('#record').attr("disabled", true);
				if(confirm(data)){ location.reload(); }				
			}
		}
		$('#myForm').ajaxForm(options);
		$('#myForm').submit();
	});
	$('#cekle').on('click', function(){
		$('#dp').val('C');		
		var d='<?php echo $gtext['percompany'];/*Company*/?>';
		$('#dp_desc').html(d);
		$('#comsat').hide();
		$('#company').val('');
		$('#modalac').click();
	});
	$('#dekle').on('click', function(){
		$('#dp').val('D');
		var d='<?php echo $gtext['a_department'];/*Department*/?>';
		$('#dp_desc').html(d);
		$('#comsat').show();
		$('#company').val(secimou); 
		var cd=$('#company :selected').html();
		$('#c_desc').html(cd); 
		//$('#company').attr('disabled', true); 
		$('#description').val(''); 
		$('#ou').val(''); 
		$('#modalac').click();
	});
	$('.cupdate').on('click', function(){
		$('#dp').val('C');
		var d='<?php echo $gtext['percompany'];/*Company*/?>';
		$('#dp_desc').html(d);
		$('#comsat').hide();
		var c=$(this).attr('id'); 
		c=c.replace('c-','');
		$('#ou').val(objc[c]['ou']);  
		$('#description').val(objc[c]['description']); 
		$('#dmanager').val(objc[c]['managedby']); 
		$('#manager').val(objc[c]['managedby']); 
		$('#modalac').click();
	});
	$('#description').on("blur", function(){ 
		var dep=dep_name($('#description').val(),99,0);
		if($('#ou').val()==''){
			$('#ou').val(dep);
		}
	});
	function dupdate(kd){ 
			$('#dp').val('D');
			var d='<?php echo $gtext['a_department'];//Department?>';
			$('#dp_desc').html(d);
			$('#comsat').show();
			$('#o_dn').val(objd[kd]['distinguishedname']); 
			$('#ou').val(objd[kd]['ou']); 
			$('#o_ou').val(objd[kd]['ou']); 
			$('#company').val(secimou); 
			$('#o_company').val(secimou); 
			$('#c_desc').html(secimdesc); 
			$('#company').css('display', 'none'); 
			$('#description').val(objd[kd]['description']); 
			$('#o_description').val(objd[kd]['description']); 
			$('#dmanager').val(objd[kd]['managedby']); 
			$('#manager').val(objd[kd]['managedby']); 
			$('#o_manager').val(objd[kd]['managedby']); 
			$('#modalac').click(); //*/
			$('#record').html('<?php echo $gtext['change'];//Değiştir?>');
		};
		function tasi(kd){ 
			$('#dp').val('D');
			$('#comsat').show();
			$('#ou').val(objd[kd]['ou']); 
			$('#company').val(secimou); //
			$('#company').css('display', 'inline'); 
			$('#description').attr('disabled', true); 
			$('#description').val(objd[kd]['description']); 
			$('#modalac').click();
		};
	$('form').find(':input').change(function(){ $('#record').prop("disabled", false ); });
	$('#cancel').on('click', function(){ $('#record').prop("disabled", true ); });
});
</script>
</body>

</html>