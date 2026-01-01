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
$farecord=$_GET['r']; 
$newrecord=true;
if($farecord!=''){ 
	@$collection=$db->FixtAccRecords;
	$cursor = $collection->aggregate([
		[
			'$match'=>['farecord'=>$farecord],
		],
		[
			'$sort'=>[
				'description'=>1,
			]
		]
	]);//echo "serialnumber=".$id."  "; //var_dump($cursor); exit;
	if($cursor){
		foreach ($cursor as $formsatir) {	
			$satir=[];
			$satir['_id']			=$formsatir->_id;
			$satir['farecord']		=$formsatir->farecord;
			$satir['type']			=$formsatir->type;
			$satir['description']	=$formsatir->description;
			$satir['boughtfrom']	=$formsatir->boughtfrom;
			$satir['inv_no']		=$formsatir->inv_no;
			$satir['invdate']		=$formsatir->invdate;
			$satir['items']			=$formsatir->items;
			if($satir['items']!=null){
				$allitems = json_decode(json_encode ( $satir['items']	 ) , true);
				$itemcount=count($allitems);
			}
		}
		$newrecord=false;
	}
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

    <title><?php echo $gtext['fixtaccrecord']; //Farecord ?></title>

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
    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.js"></script>
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['fixturekn']; //Fixture's Knowledges...?></h1>
                    </div>
                    <!-- Content Row -->
					<div class="table-responsive">
						<form id="fs_form" method="POST" action="set_fixaccrecs.php">
						<table id="per_ylist" class="table table-striped" width="100%" cellspacing="0">
						<tr>
							<td class="text-right"><?php echo $gtext['code'];/*Code*/?>:</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['farecord']; ?></span>
							  <span class="ch" style="display:none;">
								<input  type="hidden" name="o_farecord" value="<?php echo $satir['farecord']; ?>"/>
								<input class="form-control" type="text" id="farecord" name="farecord" value="<?php echo $satir['farecord']; ?>"/>
							  </span>
							  <input type="hidden" name="_id" value="<?php echo $satir['_id']; ?>"/>
							</td>
							<td class="text-right"><?php echo $gtext['type'];/*Tip*/?>:</td>
							<td colspan="3">
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['type']; ?></span>
							  <span class="ch" style="display:none;">
								<input class="form-control" type="text" id="type" name="type" value="<?php echo $satir['type']; ?>"/>
							  </span>
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['description'];/*Tanım*/?>:</td>
							<td colspan="3">
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['description']; ?></span>
							  <span class="ch" style="display:none;">
								<input class="form-control" type="text" id="description" name="description" value="<?php echo $satir['description']; ?>"/>
							  </span>								
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['boughtfrom'];/*Satın Alındığı Yer*/?>:</td>
							<td colspan="3">
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['boughtfrom']; ?></span>
							  <span class="ch" style="display:none;">
								<input class="form-control" type="text" id="boughtfrom" name="boughtfrom" value="<?php echo $satir['boughtfrom']; ?>"/>
							  </span>								
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['inv_no'];/*Fatura No*/?>:</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php echo $satir['inv_no']; ?></span>
							  <span class="ch" style="display:none;">
								<input class="form-control" type="text" id="inv_no" name="inv_no" value="<?php echo $satir['inv_no']; ?>"/>
							  </span>								
							</td>
							<td class="text-right"><?php echo $gtext['invdate'];/*Fatura Tarihi*/?>:</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php echo date("d.m.Y", strtotime($satir['invdate'])); ?></span>
							  <span class="ch" style="display:none;">
								<input class="form-control" type="date" id="invdate" name="invdate" value="<?php echo $satir['invdate']; ?>"/>
							  </span>								
							</td>
						</tr>
						<tr>
							<td class="text-right"><?php echo $gtext['fixtures'].":";/*Demirbaşlar*/
							if($newrecord){ ?>
							<br><small>(*)<?php echo $gtext['i_serials']; /*Serinolar uyarisi*/?></small><?php } ?>
							</td>
							<td>
							  <span class="fw-bold noch" style="display:inline;"><?php if($itemcount>0){ echo $itemcount." ".$gtext['fixture']; } ?></span>
							  <span class="noch" style="display:<?php if($newrecord===false){ echo "inline"; }else{ echo "none"; };?>;">
								<p class="bg-white"><?php 								
								if($itemcount>0){
									for($r=0;$r<$itemcount;$r++){ 
										if($r>0){ echo "<br>"; }
										echo $allitems[$r]; 
									}
								}
								?></p>
							  </span>		
							  <span style="display:<?php if($newrecord===true){ echo "inline"; }else{ echo "none"; };?>">
								<textarea class="form-control" id="items" name="items" rows="6"></textarea>
							  </span>								
							</td>
							<td class="text-right">
								<span style="display:<?php if($newrecord===false){ echo "none"; }else{ echo "inline"; };?>">
								<?php echo $gtext['codeprefix'];/*Kod Öneki*/?>:</span>
							</td>
							<td>
								<span style="display:<?php if($newrecord===false){ echo "none"; }else{ echo "inline"; };?>">
								<input type="text" name="i_codeprefix" value="<?php echo $gtext['new'];/*Yeni*/?>"/>
								</span>
							</td>
						</tr>
						<tr>
							<td colspan="4" class="text-center">									
								<input class="btn btn-success" type="button" id="change" value="<?php echo $gtext['change'];/*Değiştir*/?>" width="70"  style="display:<?php if(!$newrecord){ echo "inline";}else{ echo "none"; }?>"/>
								<input class="btn btn-primary" type="submit" id="record" value="<?php echo $gtext['save'];/*Kaydet*/?>" width=70 disabled />
								<input class="btn btn-danger" type="button" id="close" value="<?php echo $gtext['close'];/*Kapat*/?>" width=70/>
							</td>
						</tr>
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
<script>
var newrecord="<?php echo $newrecord; ?>";
$('#change').on('click', function(){ enter(); });
function enter(){
	$('.noch').css('display', 'none');
	$('.ch').css('display', 'inline');
	$('#change').attr('disabled', true);
	$('#record').attr('disabled', false);
};
var opt={
	type	: 'POST',
	url 	: './set_fixaccrecs.php',
	contentType: 'application/x-www-form-urlencoded;charset=utf-8',
	beforeSubmit : function(){
		if($('#farecord').val()==''||$('#type').val()==''||$('#description').val()==''||$('#boughtfrom').val()==''||$('#inv_no').val()==''||$('#invdate').val()==''){
			alert('<?php echo $gtext['u_fieldmustnotblank']; ?>');
			return false;
		}
		return confirm('<?php echo $gtext['q_rusure']; //Emin misiniz? ?>');
	},
	success: function(data){ 
		if(data=='login'){ alert('Please Login!'); location.assign('../login.php');}
		if(data.indexOf('!')){ //console.log(data);
			$('#record').attr("disabled", "disabled");
			var c=confirm(data);
			//if(c){ location.reload(); }
		}else { alert('<?php echo $gtext['u_error'];?>'); }			
		
	}
}
$('#fs_form').ajaxForm(opt);
$('#close').on("click", function(){
	window.close();
});
$('#farecord').on('blur', function(){  //console.log(this.value);
	$.ajax({
		url: 'get_f_code.php',
		type: "POST",
		datatype: 'json',
		async: false,
		data: { 'code': this.value, f: 'FAR' },
		success: function(response){ //console.log(response);
			if(response=='login'){ location.reload(); }
			if(response=='nOK'){ confirm('Bu kod kullanılmış, farklı bir kod kullanmak ister misiniz?'); $('#record').prop("disabled", true ); }
		},
		error: function(response){ alert('Hata!'); }
	});
});
if(newrecord){ enter(); }
$('#fs_form').find(':input').change(function(){ $('#record').prop("disabled", false ); });
$('#close').on('click', function(){ $('#record').prop("disabled", true ); });//*/
</script>
</body>

</html>