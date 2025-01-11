<?php
include('../set_mng.php');
//
error_reporting(0);
include($docroot."/sess.php"); 
$bugun=date("Y-m-d H:i:s", strtotime("now"));
@$dh=$_GET['dh']; if($dh==""){ $dh="H"; }
@$yetki=false;
switch($dh){
	case "H": if($_SESSION['y_addinfohaber']==1){ $yetki=true; } break;
	case "D": if($_SESSION['y_addinfoduyuru']==1){ $yetki=true; } break;
	case "K": if($_SESSION['y_addinfoduyuru']==1){ $yetki=true; } break;
	default: if($_SESSION['y_addinfohaber']==1){ $yetki=true; }
}
$bastar=date("Y-m-d", strtotime("-1 year"));
$bugun=date("Y-m-d", strtotime("now"));
$ilktar=new \MongoDB\BSON\UTCDateTime(strtotime($bastar)*1000);
$sontar=new \MongoDB\BSON\UTCDateTime(strtotime($bugun)*1000);
//
@$collection=$db->k_dhaber;
$cursor = $collection->aggregate([
	[
		'$match'=>['dh'=>$dh]
	],
    ['$lookup'=>
		[
			'from'=>"personel",
			'localField'=>"kullanici",
			'foreignField'=>"username",
			'as'=>"persons"
		]
	],
	['$unwind'=>'$persons'],
	[
       '$addFields'=> [
			'adsoyad'=> '$persons.adisoyadi',
			'brm'=> '$persons.birim',
			'email'=> '$persons.email',
		],
    ],
	[
		'$sort' => [
		  'dh_ytar' => -1, 
		],
	],
]);
$fsatir=[];
foreach ($cursor as $formsatir) {
	try{
		$satir=[]; 
		$satir['_id']=$formsatir->_id;  
		$satir['adsoyad']=$formsatir->adsoyad; 
		$satir['email']=$formsatir->email; 
		$satir['dh_sdkullanici']=$formsatir->dh_sdkullanici;//*/
		if(@$formsatir->dh_baslik!=null){ $satir['dh_baslik']=$formsatir->dh_baslik;	}	
		if(@$formsatir->dh_icerik!=null){ 
			$g=$formsatir->dh_icerik;
			$satir['dh_icerik']=addslashes($g);			
		}	
		if($formsatir->dh_ytar!=null){ $satir['dh_ytar']=$formsatir->dh_ytar->toDateTime()->format($ini['date_local']); }
		if($formsatir->dh_sgtar!=null){ 
			$satir['dh_sgtar']=$formsatir->dh_sgtar->toDateTime()->format($ini['date_local']); 
		}
		$satir['aktif']=$formsatir->aktif;
		$fsatir[]=$satir;
		//echo "|||"; var_dump($satir); echo "<br><br>";
	}catch(Exception $e){
		
	}
}
$fisay=count($fsatir); //echo "fisay:".$fisay;

$json=addslashes(json_encode($fsatir)); 
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $ini['title']." ".$gtext['onenews']."/".$gtext['announcement'];/*Haber/Duyuru*/?></title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.css" rel="stylesheet">
    <link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="../vendor/jquery/jquery.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script><!-- Page level plugins -->
    <script src="/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
	<script src="/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
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
                        <h1 class="h3 mb-0 text-gray-800"><?php 
						switch($dh){
							case 'H': echo '<i class="fas fa-paper-plane"></i> '.$gtext['news'];/*Haberler';*/ break; 
							case 'D': echo '<i class="fas fa-bullhorn"></i> '.$gtext['announcements'];/*Duyurular';*/ break;
							case 'K': echo '<i class="fas fa-bullhorn"></i> '.$gtext['organizational'].' '.$gtext['announcements'];/*Kurumsal Duyurular';*/ break;
						}
						?></h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					  <div class="card shadow mb-2 w-100">
						<div class="card-header py-2 d-sm-flex align-items-center justify-content-between">
							<h6 class="m-0 font-weight-bold text-primary"><?php echo $gtext['news']; /*Haberler*/?></h6>
							<span id='ret'>
							<a href="adm_dh_ins.php?dh=<?php echo $dh;?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-plus-circle fa-sm text-white-50"></i> <?php echo $gtext['insert'];/*Ekle*/?> </a>
							</span>
						</div>
						<div class="card-body">
						  <div class="table-responsive">
							<table id="list" class="table table-striped" cellspacing="0">
							<thead>
							<tr>
							<th class="text-center"><?php echo $gtext['a_title'];/*Başlık*/?></th>
							<th class="text-center"><?php echo $gtext['contentsummary'];/*İçerik Özeti*/?></th>
							<th class="text-center" title='Yayım Tarihi'><?php echo $gtext['pubdate'];/*Yayım Tar*/?></th>
							<th class="text-center" title='Son Gösterim Zamanı'><?php echo $gtext['lvdate'];/*SG.Tar*/?></th>
							<th class="text-center" title=''><?php echo $gtext['active']."/ ".$gtext['passive'];/*Aktif/Pasif*/?></th>
							<th class="text-center" title='İşlemler'></th>
							</tr>
							</thead>
							<tbody>
	<?php
			for($i=0; $i<$fisay; $i++){ 
				echo "<tr>";
				echo "<td>".$fsatir[$i]['dh_baslik']."</td>";
				echo "<td>";			
					if($fsatir[$i]['dh_icerik']!=""){ 
						$ic=substr($fsatir[$i]['dh_icerik'], 0, 80); 
						if(strlen($fsatir[$i]['dh_icerik'])>80){ $ic.="...<br><small>".$gtext['a_more']."</small>";} /*Devamı Var*/
						echo $ic;
					}
				echo "</td>";
				echo "<td>".$fsatir[$i]['dh_ytar']."</td>";
				echo "<td>".$fsatir[$i]['dh_sgtar']."</td>";
				echo "<td class='text-center'>";
				if($fsatir[$i]['aktif']==1){ echo $gtext['active']; }else{ echo $gtext['passive']; }
				echo "<br>";
				if($fsatir[$i]['dh_ytar']<=$bugun&&$fsatir[$i]['dh_sgtar']>=$bugun){ echo $gtext['a_publishing']; /*Yayında*/}
				else{ echo $gtext['a_end'];/*Gösterim zamanı bitti*/}
				echo "</td>";
				if($yetki){
					echo "<td><a class='btn btn-primary' href='adm_dh_ins.php?u=".$fsatir[$i]['_id']."'><i class='fas fa-edit fa-sm text-white-50'></i> ".$gtext['edit']."</a></td>";
				}
				echo "</tr>";
			}
	?>
							</tbody>
						</table>
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
            <?php include("../footer.php"); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<script>
var dturl="<?php echo $_SESSION['lang'];?>"; 
$(document).ready(function() {
	var table=$('#list').DataTable( {
        "language": {
			url :"../vendor/datatables/"+dturl+".json",
		}
	});
});
</script>
</body>

</html>