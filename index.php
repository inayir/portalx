<?php
/*
	Anasayfa
*/
include('set_mng.php');
//error_reporting(0);
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
			'$and'=>[['dh'=>'H'],['dh_sgtar'=>['$gte'=>$bugun]]],
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
		}
		$satir['aktif']=$formsatir->aktif;
		$fsatir[]=$satir;
	}catch(Exception $e){
		
	}
}
$fisay=count($fsatir); //echo "fisay:".$fisay; //var_dump($fsatir); exit;
//Duyuru
$cursord = $collection->aggregate([
	[
		'$match'=>[
			'$and'=>[['dh'=>'D'],['dh_sgtar'=>['$gte'=>$bugun]]],
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
		if(@$formsatird->dh_resim!=null){ $satird['dh_resim']=$formsatird->dh_resim;	}
		if($formsatird->dh_ytar!=null){ $satird['dh_ytar']=$formsatird->dh_ytar->toDateTime()->format($ini['date_local']); }
		if($formsatird->dh_sgtar!=null){ 
			$satird['dh_sgtar']=$formsatird->dh_sgtar->toDateTime()->format($ini['date_local']); 
		}
		$satird['aktif']=$formsatird->aktif;
		$fsatird[]=$satird;
	}catch(Exception $e){
		
	}
}
$fisayd=count($fsatird); //echo "fisayd:".$fisayd; //var_dump($fsatird); exit;
//Kurumsal Duyuru
$cursork = $collection->aggregate([
	[
		'$match'=>[
			'$and'=>[['dh'=>'K'],['dh_sgtar'=>['$gte'=>$bugun]]],
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
$fsatirk=[];
foreach ($cursork as $formsatirk) {
	try{
		$satirk=[]; 
		$satirk['_id']=$formsatirk->_id;  
		if(@$formsatirk->dh_baslik!=null){ $satirk['dh_baslik']=$formsatirk->dh_baslik;	}	
		if(@$formsatirk->dh_icerik!=null){ 
			$g=$formsatirk->dh_icerik;
			$satirk['dh_icerik']=addslashes($g);			
		}	
		//dh_resim bunda yok.
		if($formsatirk->dh_ytar!=null){ $satirk['dh_ytar']=$formsatirk->dh_ytar->toDateTime()->format($ini['date_local']); }
		if($formsatirk->dh_sgtar!=null){ 
			$satirk['dh_sgtar']=$formsatirk->dh_sgtar->toDateTime()->format($ini['date_local']); 
		}
		$satirk['aktif']=$formsatirk->aktif;
		$fsatirk[]=$satirk;
	}catch(Exception $e){
		
	}
}
$fisayk=count($fsatirk); //echo "fisayk:".$fisayk; //var_dump($fsatirk); exit;
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
	$months[]=$gtext['month'.$a];
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

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>    
	<!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
	<script src="/js/sb-admin-2.js"></script>
	<script src="/js/portal_functions.js"></script>
<style>
.title {
	text-shadow: 3px 2px 1px #000;
}
.carousel-control-prev-icon{width: 50px;height: 50px; border-radius:50%; border:2px solid white}
.carousel-control-next-icon{width: 50px;height: 50px; border-radius:50%; border:2px solid white}
</style>

<?php include("set_page.php"); ?>
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
                        <div class="col-xl-9 col-lg-8 mb-4">
							<!-- Page Heading -->
							<!--div class="d-sm-flex align-items-center justify-content-between mb-4 shadow">
								<h1 class="h3 mb-1 m-1 text-gray-800">Anasayfa</h1>
							</div-->
							<div id="ci" class="carousel slide" data-ride="carousel">
							  <ol class="carousel-indicators"><?php  for($i=0; $i<$fisay; $i++){ ?><li data-target="#ci" data-slide-to="<?php echo $i; ?>" <?php if($i==0){ echo 'class="active"'; } ?>></li>
							  <?php } ?>
							  </ol>
							  <div class="carousel-inner"><?php 
							  for($ii=0; $ii<$fisay; $ii++){ 
							  if($fsatir[$ii]['dh_resim']!="") { $resimyolu=$fsatir[$ii]['dh_resim']; }
							  else{ $resimyolu='img/undraw_posting_photo.svg'; } //echo "resimyolu:".$fsatir[$ii]['dh_resim'];?>
								<div class="carousel-item p-2 <?php if($ii==0){ echo 'active'; } ?>">
								  <a target="_blank" rel="nofollow" href="Corporate/dh_card?u=<?php echo $fsatir[$ii]['_id']; ?>">
								  <img class="d-block w-100" src="<?php echo $resimyolu; ?>" alt="slide"><?php
								  if($fsatir[$ii]['dh_capt_on']==1){ ?>
								  <div class="carousel-caption d-none d-md-block d-lg-block">
									<h4 class="text-center title"><?php echo $fsatir[$ii]['dh_baslik']; ?></h4>
									<p class="text-center title"><?php $hhi=$fsatir[$ii]['dh_icerik']; echo substr($hhi, 0, 80); if(strlen($hhi)>80){ echo "...<br><small>Haberin Devamı</small>";}?></p>
								  </div>
								  <?php } ?>
								  </a>
								</div>
								<?php } ?>
							  </div>
							  <a class="carousel-control-prev" href="#ci" role="button" data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only"><?php echo $gtext['previous']; /*Önceki*/?></span>
							  </a>
							  <a class="carousel-control-next" href="#ci" role="button" data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only"><?php echo $gtext['next']; /*Sonraki*/?></span>
							  </a>
							</div>
							<div class="text-center border-top p-2">
								<div><a target="_blank" rel="nofollow" href="Corporate/dh_all.php?dh=H"><?php echo $gtext['all']." ".$gtext['news']; /*Tüm Haberler*/ ?></a></div>
							</div>	
						</div>
							<!-- Haberler Sonu-->
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
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
										$simdi=$formsatirym->ym_tarih->toDateTime()->format($ini['date_local']);
										echo date("d", strtotime($simdi))." ";
										echo $months[date("m", strtotime($simdi))]." ";
										echo date("Y", strtotime($simdi));
										$day=date("w", strtotime($simdi));
										echo " ".$days[$day]; //*/  ?>
                                    </div>
                                    <div class="pt-4 pb-2 text-center">
                                        <span><b>Kahvaltı</b></span><br>
                                        <span><?php echo $formsatirym->k1; ?></span><br><?php 
										if($formsatirym->k2!=""){ ?>
                                        <span><?php echo $formsatirym->k2; ?></span><br><?php }
										if($formsatirym->k3!=""){ ?>
                                        <span><?php echo $formsatirym->k3; ?></span><br><?php } ?>
                                        <span><b>Öğle Yemeği</b></span><br>
                                        <span><?php echo $formsatirym->o1; ?></span><br>
                                        <span><?php echo $formsatirym->o2; ?></span><br>
                                        <span><?php echo $formsatirym->o3; ?></span><br>
                                        <span><?php echo $formsatirym->o4; ?></span><br>
                                        <span><?php echo $formsatirym->o5; ?></span><br>
                                        <span><b>Akşam Yemeği</b></span><br>
                                        <span><?php echo $formsatirym->a1; ?></span><br>
                                        <span><?php echo $formsatirym->a2; ?></span><br>
                                        <span><?php echo $formsatirym->a3; ?></span><br>
                                        <span><?php echo $formsatirym->a4; ?></span><br>
                                        <span><?php echo $formsatirym->a5; ?></span>
                                    </div>
                                    <div class="mt-4 text-center small align-bottom">
                                        * Menü değişiklik gösterebilir.
                                    </div>
									<?php } ?>
                                </div>
                            </div>
                        </div>
						<?php } ?>
						<!-- menuofday sonu -->
                    </div>
					<div class="row">
					  <div class="col-xl-9 col-lg-8 mb-4">
							<!-- Acordeon-- Dropdown Kurumsal Duyurular -->
								<div class="accordion" id="accoKD">
								<!-- Card Header - Dropdown -->
									<div
										class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
										<h6 class="m-0 font-weight-bold text-primary">Kurumsal Duyurular</h6>
										<div class="dropdown no-arrow">
											<a class="dropdown-toggle" href="#" role="button" id="dropdownKDLink"
												data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
												aria-labelledby="dropdownKDLink">
												<a class="dropdown-item" href="Corporate/dh_all.php?dh=K">Tüm Kurumsal Duyurular</a>
											</div>
										</div>
									</div>
				<?php if($fisayk>0){ 
							for($ik=0; $ik<$fisayk; $ik++){ ?>
								  <div class="card">
									<div class="card-header" id="heading<?php echo $ik;?>">
									  <h2 class="mb-0">
										<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?php echo $ik; ?>" aria-expanded="false" aria-controls="collapse<?php echo $ik;?>">
										  <?php echo $fsatirk[$ik]['dh_baslik']; ?>
										</button>
									  </h2>
									</div>

									<div id="collapse<?php echo $ik;?>" class="collapse <?php if($ik==0){ echo "show"; }?>" aria-labelledby="heading<?php echo $ik;?>" data-parent="#accoKD">
									  <div class="card-body">
										<?php echo $fsatirk[$ik]['dh_icerik']; ?><br>
										<a target="_blank" rel="nofollow" href="Corporate/dh_card?u=<?php echo $fsatirk[$ik]['_id']; ?>">
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
						<div class="col-xl-3 col-lg-4">
                            <div class="card shadow mb-4 h-100">
                            <!-- Duyurular -->
                            <div class="card shadow mb-4"><!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"><?php echo $gtext['announcements']; /*Duyurular*/ ?></h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownDLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownDLink">
                                            <a class="dropdown-item" href="/Corporate/dh_all.php?dh=D"><?php echo $gtext['all']." ".$gtext['announcements']; /*Tüm Duyurular*/ ?></a>
                                        </div>
                                    </div>
                                </div><?php
								for($id=0; $id<$fisayd; $id++){ 
								if($fsatird[$id]['dh_resim']!="") { $resimyolu=$fsatird[$id]['dh_resim']; }//else { $resimyolu='img/undraw_posting_photo.svg'; }
								?>
                                <div class="card-body <?php if($ii>0){ echo 'border-top'; } ?>" title="Detay için başlığa tıklayınız...">
                                    <div class="text-center">
                                        <a target="_blank" rel="nofollow" href="Corporate/dh_card?u=<?php echo $fsatird[$id]['dh_uid']; ?>">
										<?php if($fsatird[$id]['dh_resim']!="") { //resim yoksa joker getirilmez.?>
										<img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 15rem;"
										src="<?php echo $fsatird[$id]['dh_resim']; ?>" alt="..."><?php } ?>
										<h6 class="text-center"><?php echo $fsatird[$id]['dh_baslik']; ?></h6>
										</a>
                                    </div>
                                    <p><?php $dhi=$fsatird[$id]['dh_icerik']; echo substr($dhi, 0, 80); if(strlen($dhi)>80){ echo "...<small>Devamı için başlığa tıklayınız.</small>";}?></p>
                                    <!--a target="_blank" rel="nofollow" href="Corporate/dh_all.php">Devamı...</a-->
									<p class="text-center"><small><?php echo $gtext['announce_date']; /*Duyuru Tarihi*/?>:<?php echo date($ini['date_local'], strtotime($fsatird[$id]['dh_ytar'])); ?></small></p>
                                </div>
								<?php } ?>
								<div class="text-center border-top mt-auto align-bottom"><a target="_blank" rel="nofollow" href="Corporate/dh_all.php?dh=D"><?php $gtext['all']." ".$gtext['announcements'];/*Tüm Duyurular*/?></a></div>
                            </div>
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<script>

</script>
</body>

</html>