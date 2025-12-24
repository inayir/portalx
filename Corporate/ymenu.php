<?php
/*
			Yemek Menüsü MongoDB ile çalışır...
*/
include('../set_mng.php');
//
error_reporting(0);
include($docroot."/sess.php");
for($g=0;$g<7;$g++){
	$days[]=$gtext['day'.$g];
}
for($a=0;$a<7;$a++){
	$months[]=$gtext['month'.$a];
}
@$aysec=$_POST['aysecim'];  //echo "Post aysec:".$aysec;

//if(@$user==""){ header('Location: /login.php'); }
//
if($aysec==""){ 
	$yil=date("Y", strtotime("now")); 
	$ay=date("m", strtotime("now"));
}else{ 
	$yil=substr($aysec, 0, 4);
	$ay=substr($aysec, 5, 2);	
}  
$aysec=$yil."-".$ay;
$ay1=$ay+1; $yil1=$yil; if($ay1>12){ $ay1=1; $yil1=$yil+1;} //echo "aysec:".$aysec." :".$yil."-".$ay."-01    ";
$aybasi="";
$aysonu=""; //echo " aybasi:".$aybasi." aysonu:".$aysonu." ";
$aybasi=date("Y-m-d H:i:s", strtotime($yil."-".$ay."-01 00:00:00"));
$aysonu=date("Y-m-d H:i:s", strtotime($yil1."-".$ay1."-01 -1 day"));  //

//Form kaydı getirilir........................
$date1 = new \MongoDB\BSON\UTCDateTime(strtotime($aybasi)*1000);
$date2 = new \MongoDB\BSON\UTCDateTime(strtotime($aysonu)*1000);
//
$collections = $db->listCollections();
$collectionNames = [];
foreach ($collections as $collection) {
  $collectionNames[] = $collection->getName();
}
$ymexists = in_array('ymenu', $collectionNames);
if(!$ymexists){ 
	$db->createCollection('ymenu',[
	]);
}
@$collection=$db->ymenu;

@$cursor = $collection->find(
    [
        'ym_tarih' => ['$gte'=>$date1, '$lte'=>$date2]
    ],
    [
        'limit' => 0,
        'projection' => [
            'ym_tarih' => 1,
            'k1' => 1,'k2' => 1,'k3' => 1,
            'o1' => 1,'o2' => 1,'o3' => 1,'o4' => 1,'o5' => 1,
            'a1' => 1,'a2' => 1,'a3' => 1,'a4' => 1,'a5' => 1,
        ],
    ],
	[
		'sort'=>['ym_tarih'=>1]
	]
);
$fsatir=[];
foreach ($cursor as $formsatir) {
	$satir=[];
	$satir['id']=$formsatir->_id;
	$satir['ym_tarih']=$formsatir->ym_tarih->toDateTime()->format($ini['date_local']);
	$satir['k1']=$formsatir->k1;
	$satir['k2']=$formsatir->k2;
	$satir['k3']=$formsatir->k3;
	$satir['o1']=$formsatir->o1;
	$satir['o2']=$formsatir->o2;
	$satir['o3']=$formsatir->o3;
	$satir['o4']=$formsatir->o4;
	$satir['o5']=$formsatir->o5;
	$satir['a1']=$formsatir->a1;
	$satir['a2']=$formsatir->a2;
	$satir['a3']=$formsatir->a3;
	$satir['a4']=$formsatir->a4;
	$satir['a5']=$formsatir->a5;
	$fsatir[]=$satir;
	//var_dump($satir);
}; 
$fisay=count($fsatir); //echo "fisay:".$fisay; //for($ii=0; $ii<$fisay-1;$ii++){ }///exit;//*/
//var_dump($fsatir); exit;
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['sb_mealmenu'];/*Yemek Menüsü*/?></title>

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
    <!-- Custom scripts for all pages-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
    <!-- Page level plugins -->
    <link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>

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
                <?php include($docroot."/topbar.php"); 
				//önceki ay	echo "Gelen yıl:".$yil." gelen ay:".$ay;
				$oncekiyil=$yil; $sonrakiyil=$yil;
				if($ay<=1){ $oncekiayinarr=12; $oncekiyil--; }else{ $oncekiayinarr=$ay-1; }
				//geçerli ay
				$ayinarr=$ay; //if($ayinarr<0){ $ayinarr=0; }
				//sonraki ay
				if($ay>=12){ $sonrakiayinarr=1; $sonrakiyil++; } else { $sonrakiayinarr=$ay+1; } ?>                    
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-utensils"></i> <?php echo $gtext['sb_mealmenu']." "; /*Yemek Menüsü*/ echo " ".$months[$ayinarr-1]." ".$yil; ?></h1>
						<form method="POST" name="form_aysecim" id="form_aysecim" action="">
						<div class="input-group mb-3">
							<select class="form-select" name="aysecim"><?php echo "
							<option value='".$oncekiyil."-".($oncekiayinarr)."'>".$months[$oncekiayinarr-1]." ".$oncekiyil."</option>"; 							
							echo "
							<option value='".$aysec."' selected >".$months[$ayinarr-1]." ".$yil."</option>";
							echo "
							<option value='".$sonrakiyil."-".($sonrakiayinarr)."'>".$months[$sonrakiayinarr-1]." ".$sonrakiyil."</option>"; ?>
							</select>
							<button type="submit" class="btn btn-primary" ><?php echo $gtext['get'];/*Getir*/?></button>
							</form>
						</div><?php if($_SESSION['y_addinfomenu']==1){ ?>
						<div class="col-auto">
							<a id="eklebtn" href="#" data-bs-toggle="modal" data-bs-target="#ymModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
						class="fas fa-shuttle-van fa-sm text-white-50"></i> <?php echo $gtext['addmenu'];/*Menü Ekle*/?> </a>
						</div><?php } ?>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					<div class="card-body">
					  <div class="card shadow mb-2 w-100">
					  <div class="table-responsive">
						<table id="list" class="table table-striped" style="border: 1px; border-style: solid;">
						<thead>
						<tr>
							<th class="text-center"><?php echo $gtext['date'];/*Tarih*/?></th>
							<th class="text-center"><?php echo $gtext['day'];/*Gün*/?></th>
							<th class="text-center"><?php echo $gtext['menu'];/*Menü*/?></th>
							<th></th>
						</tr>
						</thead>
						<TBODY><?php 
if($fisay<1){ 
	echo "<tr><td colspan='4'>".$gtext['sb_nomealmenu']."</td></tr>"; //Menü bulunamadı!
}else{
	for($i=0; $i<$fisay; $i++){ //echo "<tr><td colspan='4'>?.</td></tr>";  ?>
						<tr>
							<td><?php $t=$fsatir[$i]['ym_tarih']; echo $t; ?></td>
							<td><?php echo $days[$fsatir[$i]['ym_tarih']]; ?></td>
							<td><small>
								<span style='background-color: #F5CECE;'><?php echo $fsatir[$i]['k1']; ?></span>
								<span><?php echo $fsatir[$i]['k2']; ?></span>
								<span style='background-color: #F5CECE;'><?php echo $fsatir[$i]['k3']; ?></span>
								<br>
								<span style='background-color: #CEE9F5;'><?php echo $fsatir[$i]['o1']; ?></span>
								<span><?php echo $fsatir[$i]['o2']; ?></span>
								<span style='background-color: #CEE9F5;'><?php echo $fsatir[$i]['o3']; ?></span>
								<span><?php echo $fsatir[$i]['o4']; ?></span>
								<span style='background-color: #CEE9F5;'><?php echo $fsatir[$i]['o5']; ?></span>
								<br>
								<span style='background-color: #E4F5CE;'><?php echo $fsatir[$i]['a1']; ?></span>
								<span><?php echo $fsatir[$i]['a2']; ?></span>
								<span style='background-color: #E4F5CE;'><?php echo $fsatir[$i]['a3']; ?></span>
								<span><?php echo $fsatir[$i]['a4']; ?></span>
								<span style='background-color: #E4F5CE;'><?php echo $fsatir[$i]['a5']; ?></span>
								</small>
							</td>
							<td><?php if($_SESSION['y_addinfomenu']==1){ ?>
							<button class='btn btn-primary' onClick="javascript:menuedit('<?php echo date("Y-m-d", strtotime($fsatir[$i]['ym_tarih'])); ?>');"><i class='fas fa-edit fa-sm text-white-50'></i> Düzenle</button><?php } ?>
							</td>
						</tr><?php	}
} ?>
						</TBODY>						
						</tfoot>
						<tr>
							<th class="text-center"><?php echo $gtext['date'];/*Tarih*/?></th>
							<th class="text-center"><?php echo $gtext['day'];/*Gün*/?></th>
							<th class="text-center"><?php echo $gtext['menu'];/*Menü*/?></th>
							<th></th>
						</tr>
						</tfoot>
						</table>
					  </div>
					  </div>
					</div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("/footer.php"); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
<!-- menu ekle Modal-->
				<div class="modal fade" id="ymModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<form id="form_ymekle" method="POST" action="set_ymenu.php">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Yemek Menüsü Ekleme</h5>
								<button class="close" type="button" data-bs-dismiss="modal" aria-label="<?php echo $gtext['close'];?>">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
							<table class="table table-striped">
							<tr>
								<td><?php echo $gtext['date'];/*Tarih*/?></td>
								<td>
									<input class="form-control" type="text" name="ym_tarih" id="ym_tarih" value="" />
									<!--input type="hidden" name="id" id="id" value="" /-->
								</td>
								<td class="text-right">Durum</td>
								<td>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="aktif" id="aktif_1" value="1" checked />
										<label class="form-check-label" for="aktif_1"> <?php echo $gtext['active'];/*Aktif*/?></label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="aktif" id="aktif_0" value="0" />
										<label class="form-check-label" for="aktif_0"> <?php echo $gtext['passive'];/*Pasif*/?></label>
									</div>
								</td>
							</tr>
							<tr>
								<td class="text-right">Kahvaltı 1</td><td><input class="form-control" style="max-width:30;" type="text" name="k1" id="k1" value="" /></td>
								<td></td><td></td>
							</tr>
							<tr>
								<td class="text-right">Kahvaltı 2</td><td><input class="form-control" type="text" name="k2" id="k2" /></td>
								<td></td><td></td>
							</tr>
							<tr>
								<td class="text-right">Kahvaltı 3</td><td><input class="form-control" type="text" name="k3" id="k3" /></td>
								<td></td><td></td>
							</tr>
							<tr>
								<td class="text-right">Öğle 1</td>
								<td><input class="form-control" type="text" name="o1" id="o1" value="" />
								<td class="text-right">Akşam 1</td>
								<td><input class="form-control" type="text" name="a1" id="a1" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">Öğle 2</td>
								<td><input class="form-control" type="text" name="o2" id="o2" value="" /></td>
								<td class="text-right">Akşam 2</td><td><input class="form-control" type="text" name="a2" id="a2" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">Öğle 3</td><td><input class="form-control" type="text" name="o3" id="o3" value="" /></td>
								<td class="text-right">Akşam 3</td><td><input class="form-control" type="text" name="a3" id="a3" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">Öğle 4</td>
								<td><input class="form-control" type="text" name="o4" id="o4" value="" /></td>
								<td class="text-right">Akşam 4</td>
								<td><input class="form-control" type="text" name="a4" id="a4" value="" /></td>
							</tr>
							<tr>
								<td class="text-right">Öğle 5</td>
								<td><input class="form-control" type="text" name="o5" id="o5" value="" /></td>
								<td class="text-right">Akşam 5</td>
								<td><input class="form-control" type="text" name="a5" id="a5" value="" /></td>
							</tr>
							</table>
							<span>Oluşturan: <span id="olusturan"></span> (<span id="gtar"></span>) Son Değişiklik: <span id="son_deg_per"></span> (<span id="son_deg_tar"></span>)</span>
							</div>
							<div class="modal-footer">
								<button class="btn btn-secondary" type="reset" id="cancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
								<button class="btn btn-primary" id="ymekle" disabled type="submit"><?php echo $gtext['insert']; ?></button>
							</div>
							</form>
						</div>
					</div>
				</div>
			<!--menu ekle sonu-->
	<script src="/js/sb-admin-2.js"></script>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
<script>
var isl='';
var dturl="<?php echo $_SESSION['lang'];?>"; 
$(document).ready(function() {
	var table=$('#list').DataTable( {
        "language": {
			url :"../vendor/datatables.net/"+dturl+".json",
		}
	});
	$('#ymekle').on("click", function(){ //ekle/değiştir ajaxform
		var opt={
			type	: 'POST',
			url 	: './set_ymenum.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('Emin Misiniz?');
			},
			success: function(data){ //				
				console.log('Önizleme :'+data);
				if(data!=''){ if(confirm(data)){ location.reload(); }}
				else { alert('Bir hata oluştu!'); }
			}
		}
		$('#form_ymekle').ajaxForm(opt); //*/
	});
});
function menuedit(tar){ //bilgiler getirilir...önceki console.log(tar);
	$.ajax({
		type: 'POST',
		url: 'get_ymenu_infom.php',
		data: { 'ym': tar },
		success: function (data){ //console.log(data);
			const obj=JSON.parse(data);	
			if(obj[0].durum!='Bulunamadı!'){
				$('#ym_tarih').val(obj[0].ym_tarih); 
				$('#ym_gun').val(obj[1].ym_gun); 
				$('#k1').val(obj[2].k1); 
				$('#k2').val(obj[3].k2); 
				$('#k3').val(obj[4].k3); 
				$('#o1').val(obj[5].o1); 
				$('#o2').val(obj[6].o2); 
				$('#o3').val(obj[7].o3); 
				$('#o4').val(obj[8].o4); 
				$('#o5').val(obj[9].o5); 
				$('#a1').val(obj[10].a1); 
				$('#a2').val(obj[11].a2); 
				$('#a3').val(obj[12].a3); 
				$('#a4').val(obj[13].a4); 
				$('#a5').val(obj[14].a5); 
				$('#aktif_'+obj[15].aktif).attr('checked', true);  
				$('#olusturan').html(obj[16].olusturan);
				$('#gtar').html(obj[17].gtar);
				$('#son_deg_per').html(obj[18].son_deg_per);
				$('#son_deg_tar').html(obj[19].son_deg_tar);
				//$('#id').val(obj[20]._id);
				$('#ymekle').html('Değiştir');
				isl='E';
				$('#eklebtn').click();
			}else{
				alert(obj[0].durum);
			}//*/
		}
	});	
}
$('#eklebtn').on('click', function(){
	if(isl==''){ 
		$("#cancel").click(); //son girilen tarih getirilir...
		$.ajax({
			type: 'POST',
			url: 'get_ymenu_infom.php',
			data: { st: 'st' },
			success: function (data){ //
				console.log(data);
				$('#ym_tarih').val(data);
			}
		});	
	}
	isl='';
});
$('form').find(':input').change(function(){ $('#ymekle').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#ymekle').prop("disabled", true ); });
</script>
</body>

</html>