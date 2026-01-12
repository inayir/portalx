<?php
/*
	Anasayfa
*/
include('set_mng.php');
error_reporting(0);
include($docroot."/sess.php"); 
include($docroot."/app/php_functions.php");
//
$bugun=datem(date("Y-m-d", strtotime("now")).'T00:00:00.000+00:00'); 
$yarin=datem(date("Y-m-d", strtotime("+1 day")).'T00:00:00.000+00:00'); 
$g15=datem(date("Y-m-d", strtotime("+15 day")).'T00:00:00.000+00:00');

$collections = $db->listCollections();
$collectionNames = [];
foreach ($collections as $collection) {
  $collectionNames[] = $collection->getName();
}
$exists = in_array('k_dhaber', $collectionNames);
if(!$exists){ 
	$db->createCollection('k_dhaber',[
	]);
}
@$collection=$db->k_dhaber;
//Haber			
$cursor = $collection->aggregate([
	[
		'$match'=>[
			'$and'=>[['dh'=>'H'],['dh_sgtar'=>['$gte'=>$bugun]],['lang'=>$dil]],
		],
	],
	[
		'$sort' => [
		  'dh_ytar' => -1, 
		],
	],
	[
		'$limit'=>10,
	],
]);
$fsatir=[];
foreach ($cursor as $formsatir) {
	try{
		$satir=[]; 
		$satir['_id']=$formsatir->_id;  
		if(@$formsatir->dh_baslik!=null){ $satir['dh_baslik']=$formsatir->dh_baslik;	}	
		if(@$formsatir->dh_icerik!=null){ 
			$g=$formsatir->dh_icerik;
			$satir['dh_icerik']=addslashes($g);			
		}	
		if(@$formsatir->dh_resim!=null){ $satir['dh_resim']=$formsatir->dh_resim;	}
		if($formsatir->dh_ytar!=null){ $satir['dh_ytar']=$formsatir->dh_ytar->toDateTime()->format($ini['date_local']); }
		if($formsatir->dh_sgtar!=null){ 
			$satir['dh_sgtar']=$formsatir->dh_sgtar->toDateTime()->format($ini['date_local']); 
		}else{ $satir['dh_sgtar']=date($ini['date_local']." 23:59:59", strtotime("+1 day")); }
		$satir['dh_capt_on']=$formsatir->dh_capt_on;
		$satir['aktif']=$formsatir->aktif;
		$fsatir[]=$satir;
	}catch(Exception $e){
		
	}
}
$fisay=count($fsatir); //echo "fisay:".$fisay; //var_dump($fsatir); //exit;
//Duyuru
$cursord = $collection->aggregate([
	[
		'$match'=>[
			'$and'=>[['dh'=>'D'],['dh_sgtar'=>['$gte'=>$bugun]],['lang'=>$dil]],
		],
	],
	[
		'$sort' => [
		  'dh_ytar' => -1, 
		],
	],
	[
		'$limit'=>10,
	],
]);
$fsatird=[];
foreach ($cursord as $formsatird) {
	try{
		$satird=[]; 
		$satird['_id']=$formsatird->_id;  
		if(@$formsatird->dh_baslik!=null){ $satird['dh_baslik']=$formsatird->dh_baslik;	}	
		if(@$formsatird->dh_icerik!=null){ 
			$g=$formsatird->dh_icerik;
			$satird['dh_icerik']=addslashes($g);			
		}	
		if(@$formsatird->dh_resim!=null){ $satird['dh_resim']=$formsatird->dh_resim;	}else{ $satird['dh_resim']=""; }
		if($formsatird->dh_ytar!=null){ $satird['dh_ytar']=$formsatird->dh_ytar->toDateTime()->format($ini['date_local']); }
		if($formsatird->dh_sgtar!=null){ 
			$satird['dh_sgtar']=$formsatird->dh_sgtar->toDateTime()->format($ini['date_local']); 
		}else{ $satird['dh_sgtar']=date($ini['date_local']." 23:59:59", strtotime("+1 day")); }
		$satird['aktif']=$formsatird->aktif;
		$fsatird[]=$satird;
	}catch(Exception $e){
		
	}
}
$fisayd=count($fsatird); 
//Günün Yemeği
$ymexists = in_array('ymenu', $collectionNames);
if(!$ymexists){ 
	$db->createCollection('ymenu',[
	]);
}
@$collectionym=$db->ymenu;
@$cursorym = $collectionym->find(
    [
        'ym_tarih' => ['$gte'=>$bugun]
    ],
    [
        'limit' => 1,
        'projection' => [
        ],
    ],
	[
		'sort'=>['ym_tarih'=>1]
	]
);
foreach ($cursorym as $formsatirym) { 
	//var_dump($formsatirym);
}
for($g=1;$g<=7;$g++){
	$days[]=$gtext['day'.$g];
}
for($a=1;$a<=12;$a++){
	$months[$a]=$gtext['month'.$a];
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

    <title><?php echo $ini['title']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
<?php include("set_page.php"); ?>
<style>
.title {
	text-shadow: 3px 2px 1px #000;
}
.carousel-control-prev-icon{width: 3em;height: 3em; }
.carousel-control-next-icon{width: 3em;height: 3em; }
.carousel .carousel-item {
    min-height:40%;
    max-height:50%;
}

.carousel-item img {
    object-fit:cover;
    max-height:50%;
	position: relative;
}
.carousel{ position: relative }
.carousel-indicators{ position: absolute }
.carousel-indicators { bottom: 10rem; }
.carousel-caption {
  right: 1%;
  left: auto;
  top: 30%;
  bottom: 20%;
  transform: translateY(-50%);
  z-index: 10;
  text-align: center;
}
</style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar --><?php 
		include("sidebar.php"); ?><!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php include("topbar.php"); ?><!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">                    
                    <!-- Content Row -->	
                    <!-- Carousel--------------------------------------------- -->
                    <div class="row">
						<div class="col-xl-12 col-lg-12 mb-6">
							<!-- Page Heading -->
							<!--div class="d-sm-flex align-items-center justify-content-between mb-4 shadow">
								<h1 class="h3 mb-1 m-1 text-gray-800">Anasayfa</h1>
							</div-->
							<div id="ci" class="carousel carousel-dark slide carousel-fade" data-bs-ride="carousel">
							  <div class="carousel-indicators"><?php  for($i=0; $i<$fisay; $i++){ ?><button type="button" data-bs-target="#ci" data-bs-slide-to="<?php echo $i; ?>" <?php if($i==0){ echo 'class="active" aria-current="true"'; } ?> ></button><?php } ?></div>
							  <div class="carousel-inner"><?php 
							  for($ii=0; $ii<$fisay; $ii++){ 
							  if($fsatir[$ii]['dh_resim']!="") { $resimyolu=$fsatir[$ii]['dh_resim']; }
							  else{ $resimyolu='img/undraw_posting_photo.svg'; } //echo "resimyolu:".$fsatir[$ii]['dh_resim'];?>
								<div class="carousel-item p-2<?php if($ii==0){ echo ' active'; } ?> data-bs-interval="10000">
								  <a target="_blank" rel="nofollow" href="Corporate/dh_card?u=<?php echo $fsatir[$ii]['_id']; ?>">
								  <img class="d-block w-100" style="height: 50%;" src="<?php echo $resimyolu; ?>" alt="slide"><?php if($fsatir[$ii]['dh_capt_on']==1){ ?>
								  <div class="carousel-caption d-md-block d-lg-block">
									<h4 class="title"><?php echo $fsatir[$ii]['dh_baslik']; ?></h4>
									<p  class="title"><?php $hhi=$fsatir[$ii]['dh_icerik']; echo substr($hhi, 0, 80); if(strlen($fsatir[$ii]['dh_icerik'])>80){ echo "...<br><small>".$gtext['n_more']."</small>";}?></p>
								  </div>
								  <?php } ?>
								  </a>
								</div>
								<?php } ?>
							  </div>
							  <button class="carousel-control-prev" type="button" data-bs-target="#ci" data-bs-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden"><?php echo $gtext['previous']; /*Önceki*/?></span>
							  </button>
							  <button class="carousel-control-next" type="button" data-bs-target="#ci" data-bs-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="visually-hidden"><?php echo $gtext['next']; /*Sonraki*/?></span>
							  </button>
							</div>
							<div class="text-center border-top p-2">
								<div><a target="_blank" rel="nofollow" href="Corporate/dh_all.php?dh=H"><?php echo $gtext['all']." ".$gtext['news']; /*Tüm Haberler*/ ?></a></div>
							</div>	
						</div>
							<!-- Haberler Sonu-->
                    </div>
					<div class="row"><?php $cla="col-xl-12 col-lg-12 mb-6"; if($ini['menuofday']==1){ $cla="col-xl-9 col-lg-8 mb-4"; } ?>
                        <div class="<?php echo $cla; ?>">
							<!-- Acordeon-- Dropdown Kurumsal Duyurular -->
							<div class="accordion" id="accoKD">
							<!-- Card Header - Dropdown -->
								<div
									class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">Kurumsal Duyurular</h6>
									<div class="dropdown no-arrow">
										<a class="dropdown-toggle" href="#" role="button" id="dropdownKDLink"
											data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
											aria-labelledby="dropdownKDLink">
											<a class="dropdown-item" href="Corporate/dh_all.php?dh=K">Tüm Kurumsal Duyurular</a>
										</div>
									</div>
								</div>
			<?php if($fisayd>0){ 
						for($ik=0; $ik<$fisayd; $ik++){ ?>
							  <div class="card">
								<div class="card-header" id="heading<?php echo $ik;?>">
								  <h2 class="mb-0">
									<button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $ik; ?>" aria-expanded="false" aria-controls="collapse<?php echo $ik;?>">
									  <?php echo $fsatird[$ik]['dh_baslik']; ?>
									</button>
								  </h2>
								</div>

								<div id="collapse<?php echo $ik;?>" class="collapse <?php if($ik==0){ echo "show"; }?>" aria-labelledby="heading<?php echo $ik;?>" data-parent="#accoKD">
								  <div class="card-body">
									<?php echo $fsatird[$ik]['dh_icerik']; ?><br>
									<a target="_blank" rel="nofollow" href="Corporate/dh_card?u=<?php echo $fsatird[$ik]['_id']; ?>">
										<span class="text-center"><?php echo $gtext['more']; /*Devamı*/?>...</span>
										</a>
								  </div>
								</div>
							  </div>
				<?php 	} 
					}  
							if($fisayk>0){ ?>
								<div class="text-center border-top p-2">
									<div><a target="_blank" rel="nofollow" href="Corporate/dh_all.php?dh=K"><?php echo $gtext['all']." ".$gtext['organizational']." ".$gtext['announcements']; /*Tüm Kurumsal Duyurular*/?></div></a>
									<hr>
								</div>
							<?php } ?>
							</div>							
							<!-- Acordeon Sonu-->							
						</div>

						<!-- menuofday -->
<?php if($ini['menuofday']==1){ ?>
                        <div class="col-xl-3 col-lg-4">
                            <div class="card shadow mb-4 h-100">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"><?php echo $gtext['menuofday']; /*Bugünün Menüsü*/?></h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header"></div>
                                            <a class="dropdown-item" id="aylikmenu" href="/Corporate/yemekmenu.php"><?php echo $gtext['menuofmonth']; /*Aylık Menü*/?></a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body"><?php 
									if(isset($formsatirym->ym_tarih)){ ?>
                                    <div class="text-center"><?php 
										$simdi=date($ini['date_local'], strtotime("now")); //$formsatirym->ym_tarih->toDateTime()->format($ini['date_local']);
										echo date("d", strtotime($simdi))." ";
										$ay=date("m", strtotime($simdi)); //echo "ay:".$ay;
										echo $months[$ay]." ";
										echo date("Y", strtotime($simdi));
										$day=date("w", strtotime($simdi));
										echo " ".$days[$day]; //*/  ?>
                                    </div>
                                    <div class="pt-4 pb-2 text-center">
                                        <span><b><?php echo $gtext['breakfast'];?></b></span><br>
                                        <span><?php echo $formsatirym->k1; ?></span><br><?php 
										if($formsatirym->k2!=""){ ?>
                                        <span><?php echo $formsatirym->k2; ?></span><br><?php }
										if($formsatirym->k3!=""){ ?>
                                        <span><?php echo $formsatirym->k3; ?></span><br><?php } ?>
                                        <span><b><?php echo $gtext['lunch'];?></b></span><br>
                                        <span><?php echo $formsatirym->o1; ?></span><br>
                                        <span><?php echo $formsatirym->o2; ?></span><br>
                                        <span><?php echo $formsatirym->o3; ?></span><br>
                                        <span><?php echo $formsatirym->o4; ?></span><br>
                                        <span><?php echo $formsatirym->o5; ?></span><br>
                                        <span><b><?php echo $gtext['dinner'];?></b></span><br>
                                        <span><?php echo $formsatirym->a1; ?></span><br>
                                        <span><?php echo $formsatirym->a2; ?></span><br>
                                        <span><?php echo $formsatirym->a3; ?></span><br>
                                        <span><?php echo $formsatirym->a4; ?></span><br>
                                        <span><?php echo $formsatirym->a5; ?></span>
                                    </div>
                                    <div class="mt-4 text-center small align-bottom">
                                        * <?php echo $gtext['menuchangable'];?>
                                    </div>
									<?php } ?>
                                </div>
                            </div>
                        </div>
						<?php } ?>
						<!-- menuofday sonu -->
                        </div>
                      </div>
					</div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("footer.php"); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->	
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script> 
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="/js/sb-admin-2.js"></script>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
<script>
$.fn.normalizeHeight = function () {
    $(this).css("height", Math.max.apply(null, $(this).children().map(function () {
        return $(this).height();
    })) + "px");

    return this;
};
$("#slider .carousel-inner").normalizeHeight();

$(window).on('resize', function () {
  $("#slider .carousel-inner").normalizeHeight();
});
</script>
</body>

</html>