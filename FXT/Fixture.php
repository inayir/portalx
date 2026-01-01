<?php
/*
	Fixture specs find-Computer
*/
include("../set_mng.php");  //for Mongo DB connection, if needed. If not; write first line: include('/get_ini.php');
include($docroot."/sess.php");
error_reporting(0);
if($user==""){ //if auth pages needed...
	//header('Location: /login.php');
}
@$id=$_GET['id']; 
@$far=$_GET['far']; 
@$placechange=$_GET['plch'];
$newrecord=1; 

	//type ---------------
	$tcol=$db->Fixture_types;
	$tcursor=$tcol->find(
		[
			'state'=>1
		],
		[
			'limit' => 0,
			'projection' => [
			],
			'sort' => [
				'code'=>1,
				'type'=>1,
			],
		],
	);
	$deftype="";
	if($tcursor){
		$ftsatir=[];
		foreach ($tcursor as $tformsatir) {
			$tsatir['code']	=$tformsatir->code;
			$tsatir['type']	=$tformsatir->type;
			$tsatir['default']	=$tformsatir->default;
			if($tformsatir->default==1){ $deftype=$tformsatir->code; }
			$ftsatir[]=$tsatir;
		}
	}
	//place ---------------
	$pcol=$db->Places;
	$pcursor=$pcol->find(
		[
			'state'=>'A'
		],
		[
			'limit' => 0,
			'projection' => [
			],
			'sort' => [
				'code'=>1,
				'description'=>1,
				'default'=>1,
			],
		],
	);
	$defplace="";
	if($pcursor){
		$fpsatir=[];
		foreach ($pcursor as $pformsatir) {
			$psatir['code']			=$pformsatir->code;
			$psatir['description']	=$pformsatir->description;
			$psatir['default']		=$pformsatir->default;
			
			if($pformsatir->default==1){ $defplace=$pformsatir->code; }
			$fpsatir[]=$psatir;
		}
	}
	//----------
if($id!=''){
	@$collection=$db->Fixtures;
	$cursor = $collection->aggregate([
		[
			'$match'=>['_id'=>new \MongoDB\BSON\ObjectId($id)],
		],
		['$lookup'=>
			[
				'from'=>"personel",
				'localField'=>"username",
				'foreignField'=>"username",
				'as'=>"pers"
			]
		],
		['$unwind'=>'$pers'],
		['$lookup'=>
			[
				'from'=>"departments",
				'localField'=>"pers.department",
				'foreignField'=>"ou",
				'as'=>"deps"
			]
		],
		['$unwind'=>'$deps'],
		['$lookup'=>
			[
				'from'=>"Places",
				'localField'=>"place",
				'foreignField'=>"code",
				'as'=>"places"
			]
		],
		['$unwind'=>'$places'],
		['$addFields'=> [
				'fname' => '$pers.displayname',
				'fpernumber' => '$pers.description',
				'fdep' => '$deps.description',
				'fplace' => '$places.description',
			],
		]
	]);
	if($cursor){
		foreach ($cursor as $formsatir) { 	
			$satir=[];
			$satir['_id']			=$formsatir->_id;
			$satir['code']			=$formsatir->code;
			$satir['description']	=$formsatir->description;
			$satir['username']		=$formsatir->username;
			$satir['fname']			=$formsatir->fname;
			$satir['fpernumber']	=$formsatir->fpernumber;
			$satir['fdep']			=$formsatir->fdep;  
			$satir['fplace']		=$formsatir->fplace;  //Yer tanımı
			$satir['place']			=$formsatir->place;
			$satir['hostname']		=$formsatir->hostname;
			$satir['type']			=$formsatir->type;
			$satir['serialnumber']	=$formsatir->serialnumber;
			$satir['fixtaccrecord']	=$formsatir->fixtaccrecord;
			$satir['state']			=$formsatir->state;
			$satir['privcode1']		=$formsatir->privcode1;
			$satir['privcode2']		=$formsatir->privcode2;
		}
		$newrecord=0;
		$text=$gtext['update'];
	}
}else{
	$satir=[];
	$satir['fixtaccrecord']	=$far;
	//tip ve tanım gelmeli.
	$farcol=$db->FixtAccRecords;
	$farcor=$farcol->find(
		[
			'farecord'=>$far
		],
		[
			'limit' => 1,
			'projection' => [
				'type'=>1,
				'description'=>1,
			],
		],
	);
	if($farcor){
		foreach ($farcor as $farsatir) {
			$satir['type']=$farsatir->type;
			$satir['description']=$farsatir->description;
		}
	}
	$satir['_id']			="";
	$satir['code']="";
	$satir['description']	="";
	$satir['username']		="";
	$satir['fname']			="";
	$satir['fpernumber']	="";
	$satir['fdep']			="";
	$satir['fplace']		="";
	$satir['place']			=$defplace;	
	$satir['hostname']		="";
	if(@$satir['type']==''){ $satir['type']=$deftype; }
	$satir['serialnumber']	="";
	$satir['fixtaccrecord']	="";
	$satir['state']			="";
	$satir['privcode1']		="";
	$satir['privcode2']		="";	
	$text=$gtext['insert'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['fixture']; //Fixture ?></title>

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
	<!--DataTables-->
	<link href="/vendor/datatables.net/datatables.min.css" rel="stylesheet">
	<script src="/vendor/datatables.net/datatables.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>	
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
	<!-- Bootstrap Toogle-->
	<link href="/vendor/bootstrap-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
	<script src="/vendor/bootstrap-toggle/js/bootstrap4-toggle.min.js"></script>
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['fixturekn']." - ".$text; //Fixture's Knowledges...?></h1>
                    </div>
                    <!-- Content Row -->
					<div class="table-responsive">
						<form id="fs_form" method="POST" action="set_fixture.php">
						<input type="hidden" name="_id" value="<?php echo $satir['_id']; ?>"/>
						<table id="per_ylist" class="table table-striped" width="100%" cellspacing="0">
						<tr>
							<td class="text-right"><?php echo $gtext['code'];/*Code*/?>:<br>(*)(**)</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['code']; ?></span>
							  <span class="ch" style="display:none;">
								<input type="hidden" id="o_code" name="o_code" value="<?php echo $satir['code']; ?>"/>
								<input class="form-control" type="text" id="code" name="code" value="<?php echo $satir['code']; ?>"/>
							  </span>
							</td>
							<td class="text-right"><?php echo $gtext['type'];/*Tip*/?>:</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['type']; ?></span>
							  <span class="ch" style="display:none;">
								<input type="hidden" id="o_type" name="o_type" value="<?php echo $satir['type']; ?>"/>
								<select class="form-control form-select" id="type" name="type">
								<?php 
								for($t=0;$t<count($ftsatir);$t++){
									echo "<option value=".$ftsatir[$t]['code'];
									if($satir['type']==$ftsatir[$t]['code']){ echo " selected"; }
									echo ">".$ftsatir[$t]['type']."</option>"; 
								} //*/
								?>
								</select>
							  </span>
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['description'];/*Tanım*/?>:</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['description']; ?></span>
							  <span class="ch" style="display:none;">
								<input type="hidden" id="o_description" name="o_description" value="<?php echo $satir['description']; ?>"/>
								<input class="form-control" type="text" id="description" name="description" value="<?php echo $satir['description']; ?>"/>
							  </span>								
							</td>
							<td class="text-right"><?php echo $gtext['hostname'];/*Makine Adı*/?>:</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['hostname']; ?></span>
							  <span class="ch" style="display:none;">
								<input type="hidden" id="o_hostname" name="o_hostname" value="<?php echo $satir['hostname']; ?>"/>
								<input class="form-control" type="text" id="hostname" name="hostname" value="<?php echo $satir['hostname']; ?>"/>
							  </span>								
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['serialnumber'];/*Seri no*/?>:</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['serialnumber']; ?></span>
							  <span class="ch" style="display:none;">
								<input type="hidden" id="o_serialnumber" name="o_serialnumber" value="<?php echo $satir['serialnumber']; ?>"/>
								<input class="form-control" type="text" id="serialnumber" name="serialnumber" value="<?php echo $satir['serialnumber']; ?>"/>
							  </span>						
							</td>
							<td class="text-right"><?php echo $gtext['fixtaccrecord'];/**/?>:</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['fixtaccrecord']; ?></span>
							  <span class="ch" style="display:none;">
								<input type="hidden" id="o_fixtaccrecord" name="o_fixtaccrecord" value="<?php echo $satir['fixtaccrecord']; ?>"/>
								<div class="input-group">
								<input class="form-control" type="text" id="fixtaccrecord" name="fixtaccrecord" value="<?php echo $satir['fixtaccrecord']; ?>" <?php if($id!=''||$far!=''){ echo "readonly"; } ?>/>
							  </span>
								<?php if($satir['fixtaccrecord']!=''){ ?><button class="btn btn-outline-primary" type="button" onClick="viewfar('<?php echo $satir['fixtaccrecord'];?>');"><?php echo $gtext['see'];?></button><?php } ?>
								</div>
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['privcode']." 1"; /*Özel Kod*/?>:</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['privcode1']; ?></span>		  
							  <span class="ch" style="display:none;">
								<input type="hidden" id="o_privcode1" name="o_privcode1" value="<?php echo $satir['privcode1']; ?>"/>
								<input class="form-control w-50" type="text" id="privcode1" name="privcode1" value="<?php echo $satir['privcode1']; ?>"/>
							  </span>
							</td>
							<td class="text-right"><?php echo $gtext['privcode']." 2"; /*Özel Kod*/?>:</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['privcode2']; ?></span>
							  <span class="ch" style="display:none;">
								<input type="hidden" id="o_privcode2" name="o_privcode2" value="<?php echo $satir['privcode2']; ?>"/>
								<input class="form-control w-50" type="text" id="privcode2" name="privcode2" value="<?php echo $satir['privcode2']; ?>"/>
							  </span>
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['username']; /*Kullanıcı*/?>:</td>
							<td>
							  <span id="s_username" class="fw-bold" style="display:inline;"><?php echo $satir['fname']; ?></span>
							  <input type="hidden" name="o_username" value="<?php echo $satir['username']; ?>"/>
							  <input class="form-control w-50" type="hidden" id="username" name="username" value="<?php echo $satir['username']; ?>"/>
							</td>
							<td class="text-right"><?php echo $gtext['pernumber'].":"; /*Sicil*/?></td>
							<td>
							  <span class="text-left fw-bold"><?php echo $satir['fpernumber']; ?></span>								
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['a_department']; /*Birim*/?>:</td>
							<td>
							  <span id="s_deps" class="fw-bold" style="display:inline;"><?php echo $satir['fdep']; ?></span>
							</td>
							<td class="text-right"><?php echo $gtext['hostname']; /*Hostname*/?>:</td>
							<td>
							  <span id="s_hostname" class="fw-bold" style="display:inline;"><?php echo $satir['hostname']; ?></span>
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['place'];/*Yer*/?>:</td>
							<td>
							  <span class="w-75 fw-bold" id="s_place"><?php echo $satir['fplace']; ?></span>
							  <span>
								<input type="hidden" name="o_place" value="<?php echo $satir['place']; ?>"/>
								<select class="form-select" id="place" name="place" style="display:<?php if($placechange==0){ echo "none"; }else{ echo "inline"; }?>"><?php
								for($p=0;$p<count($fpsatir);$p++){ ?>
								<option value="<?php echo $fpsatir[$p]['code']; ?>" <?php if($satir['place']==$fpsatir[$p]['code']){ echo "selected"; } ?>><?php echo $fpsatir[$p]['code']." ".$fpsatir[$p]['description']; ?></option><?php } ?>
								</select>
							  </span>
							</td>
							<td class="text-right"><?php echo $gtext['active'];/*Aktif*/?>:</td>
							<td>
								<div class="form-check form-switch p-0 m-0"><?php
								switch($satir['state']){ 
								case "A" : $cstate="checked"; $dstate=$gtext['active']; break; 
								case "" : $cstate="checked"; $dstate=$gtext['active']; break; 
								case "P" : $cstate=""; $dstate=$gtext['passive']; break; 
								} ?>
								  <span id="s_state" class="fw-bold noch" style="display:inline;"><?php echo $dstate; ?></span>
								  <span id="i_state" style="display:none;" class="ch">
									<input type="hidden" name="o_state" id="o_state" value="<?php echo $satir['state']; ?>"/>
									<input class="form-control form-check-input isl" type="checkbox" role="switch" name="state" id="state" data-toggle="toggle" data-on="<?php echo $gtext['active'];/*Aktif*/?>" data-off="<?php echo $gtext['passive'];/*Pasif*/?>" <?php echo $cstate; ?> />
								  </span>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4" class="text-center">								
								<input class="btn btn-success" type="button" id="change" value="<?php echo $gtext['change'];/*Değiştir*/?>" width="70"/ style="display: <?php if($newrecord==0){ echo 'inline';}else{ echo 'none';} ?>">
								<input class="btn btn-primary" type="submit" id="record" value="<?php echo $gtext['save'];/*Kaydet*/?>" width=70 disabled />
								<input class="btn btn-danger" type="button" id="close" value="<?php echo $gtext['close'];/*Kapat*/?>" width=70/>
							</td>
						</tr>
						</tr>
							<td colspan="4">
							(*) <?php echo $gtext['u_fieldmustnotblank'];?>  
							(**) <?php echo $gtext['u_fieldmustunique'];?>
							</td>
						<tr>
						</table>
						</form>
					</div>
                <!-- /.container-fluid -->
				</div>
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <?php include("../footer.php"); ?>
            <!-- End of Footer -->

			</div>
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.js"></script>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
<script>
var newrecord="<?php echo $newrecord; ?>";
$('#change').on('click', function(){
	$('.noch').css('display', 'none');
	$('.ch').css('display', 'inline');
	$('#change').attr('disabled', true);
	$('#record').attr('disabled', false);
});
var opt={
	type	: 'POST',
	url 	: './set_fixture.php',
	contentType: 'application/x-www-form-urlencoded;charset=utf-8',
	beforeSubmit : function(){
		if($('#code').val()==''||$('#type').val()==''||$('#serialnumber').val()==''||$('#description').val()==''||$('#fixtaccrecord').val()==''){
			alert('<?php echo $gtext['u_fieldmustnotblank']; ?>');
			return false;
		}
		return confirm('<?php echo $gtext['q_rusure']; //Emin misiniz? ?>');
	},
	success: function(data){ 
		if(data=='login'){ alert('Please Login!'); location.assign('../login.php');}
		if(data.indexOf('!')){ 
			if(data=='login'){ confirm('<?php echo $gtext['u_mustlogin'];?>'); location.open('/login.php'); }
			if(data.indexOf('!!')>-1){ alert(data); $('#code').focus(); exit;}
			$('#record').attr("disabled", true);
			if(confirm(data)){ 
				location.reload(); 
			}
		}else { alert('<?php echo $gtext['u_error'];?>'); }			
		
	}
}
$('#fs_form').ajaxForm(opt);

$('#close').on("click", function(){
	window.close();
});
function viewfar(far){ console.log(far);
	var newurl='Farecord.php?r='+far;
	window.open(newurl, '_tab');
}
if(newrecord=="1"){ $('#change').click(); }
$('#fs_form').find(':input').change(function(){ $('#record').prop("disabled", false ); });
$('#close').on('click', function(){ $('#record').prop("disabled", true ); });//*/
</script>
</body>

</html>