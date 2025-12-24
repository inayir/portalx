<?php
/*
	News or Announcesment Insert/Edit
*/
include('../set_mng.php');
error_reporting(0);
include($docroot."/config/config.php");
include($docroot."/sess.php"); 

@$now=date($ini['date_local'], strtotime("now"))." 00:00";
@$sgtar=date($ini['date_local'], strtotime($now."+180 days"))." 23:59";
@$id=$_GET['u']; 
@$dh=$_GET['dh'];
if($dh==""){ @$dh=$_POST['dh']; } 

if($id!=""){ 	//zduyuru_haber dosyasından liste getirilir.
	@$db=$client->$dbi;
	@$collection=$db->k_dhaber;
	@$cursor = $collection->find(
		[
			'_id'=>new \MongoDB\BSON\ObjectId($id)
		],
		[
			'limit' => 0,
			'projection' => [
			],
			'sort'=>['dh_ytar'=>-1]
		]
	);
	//$fsatir=[]; //var_dump($cursor);
	foreach ($cursor as $formsatir) {
		$satir=[]; 
		$satir['_id']=$formsatir->_id;
		$dh_uid=$formsatir->_id;
		if($formsatir->dh_ytar!=null){ $satir['dh_ytar']=$formsatir->dh_ytar->toDateTime()->format($ini['date_local']." H:i"); }
		$satir['dh_baslik']=$formsatir->dh_baslik;  
		$satir['dh_icerik']=$formsatir->dh_icerik; 
		$satir['dh_resim']=$formsatir->dh_resim; 
		if($formsatir->dh_url!=''){ $satir['dh_url']=$formsatir->dh_url; }
		$satir['dh_sdkullanici']=$formsatir->dh_sdkullanici; 
		if($formsatir->dh_sgtar!=null){ $satir['dh_sgtar']=$formsatir->dh_sgtar->toDateTime()->format($ini['date_local']." H:i");	} 
		$satir['kullanici']=$formsatir->kullanici;
		if($formsatir->dh_sdtar!=null){ $satir['dh_sdtar']=$formsatir->dh_sdtar->toDateTime()->format($ini['date_local']." H:i"); }
		$satir['dh_capt_on']=$formsatir->dh_capt_on;
		$dh=$formsatir->dh;
		$satir['aktif']=$formsatir->aktif;
		//$fsatir[]=$satir;
	}
	//$fisay=count($fsatir); //echo "fisay:".$fisay." dosya:".$fsatir[0]['orgs_dosya'];//var_dump($fsatir);
}else{
	$satir['_id']="";
	$satir['dh_ytar']=date("d.m.Y H:i", strtotime("now"));
	$satir['dh_baslik']="";
	$satir['dh_icerik']="";
	$satir['dh_resim']="";
	$satir['dh_link']="";
	$satir['dh_url']="";
	$satir['dh_sdkullanici']="";
	$satir['kullanici']="";
	$satir['dh_sgtar']=date("d.m.Y H:i", strtotime("+60 days"));
	$satir['dh_sdtar']=date("d.m.Y H:i", strtotime("+60 days"));
	$satir['dh_capt_on']=1;
	$satir['aktif']=1;
	$dh_uid="";
}
switch($dh){ //D Duyuru, K Kurumsal, H haber
	case "D" : $dha=" ".$gtext['announcement']; $dhi="bullhorn"; break; //Duyuru
	case "K" : $dha=" ".$gtext['organizational']." ".$gtext['announcement']; $dhi="bullhorn"; break; //Kurumsal Duyuru
	case "H" : $dha=" ".$gtext['onenews']; $dhi="paper-plane"; break;  //Haber
	default : $dh="D"; $dha=" ".$gtext['announcement']; $dhi="bullhorn"; //Duyuru
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

    <title><?php echo $dha.$gtext['ins_edit']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
    <script src="/js/portal_functions.js"></script>
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <script src="/js/sb-admin-2.js"></script>
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
                <?php include($docroot."/topbar.php"); ?>
                    
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-<?php echo $dhi;?>"></i> <?php echo $dha." ".$gtext['ins_edit']; /*Ekle/Değiştir*/ ?></h1>
						<!--a href="javascript:d_ekle();" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-bullhorn fa-sm text-white-50"></i> Duyuru Ekle </a-->
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<form id="form_dh" method="POST" action="set_dhm.php">
						<input type="hidden" name="_id" id="_id" value="<?php echo $satir['_id']; ?>" />
						<!--input type="hidden" name="dh" id="dh" value="<?php echo $satir['dh']; ?>" /-->
						<div class="">
						<table class="table table-striped" style="">
						<tr>
							<td width="30%"><?php echo $gtext['date'];/*Tarih*/?> </td>
							<td>
								<input class="form-control" type="text" name="dh_ytar" id="dh_ytar" value="<?php echo $satir['dh_ytar']; ?>" />
							</td>
							<td><?php echo $gtext['lvdate'];/*Son Gösterim Tarihi*/?> </td>
							<td>
								<input class="form-control" type="text" name="dh_sgtar" id="dh_sgtar" value="<?php echo $satir['dh_sgtar']; ?>" />
							</td>							
						</tr>
						<tr>
							<td><?php echo $gtext['a_title'];/*Başlık*/?></td>
							<td colspan="3">
								<input class="form-control" type="text" name="dh_baslik" id="dh_baslik" value="<?php echo $satir['dh_baslik']; ?>" />
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['q_contenttitle'];/*Ana Sayfa Carouselinde<br>Başlık Görünsün mü?*/?></td>
							<td>
								<div class="form-check">									
									<input class="form-check-input" type="radio" name="dh_capt_on" id="dh_capt_on_1" value="1" <?php if($satir['dh_capt_on']=='1'){ echo "checked"; }?> />
									<label class="form-check-label" for="dh_capt_on_1"> <?php echo $gtext['visible'];/*Görünür*/?></label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="dh_capt_on" id="dh_capt_on_0" value="0" <?php if($satir['dh_capt_on']=='0'){ echo "checked"; }?> />
									<label class="form-check-label" for="dh_capt_on_0"> <?php echo $gtext['hidden'];/*Gizli*/?></label>
								</div>
							</td>
							<td class="text-right"></td>
							<td>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['contentsummary'];/*İçerik*/?></td>
							<td colspan="3"><textarea class="form-control" type="text" name="dh_icerik" id="dh_icerik" cols="100" rows="20"><?php echo $satir['dh_icerik']; ?></textarea>
							<small>* <?php echo $gtext['u_content'];/*html kodlarla yazabilirsiniz. Resim yapıştırmak da mümkündür.*/?></small>
							</td>
						</tr>
						<?php if($dh!='K'){ ?>
						<tr>
							<td><?php echo $gtext['a_dhbgtext'];/*Arkaplan ya da yazı üstünde Resim*/?></td>
							<td colspan="3">
								<img id="img_dh_resim" src="<?php echo $satir['dh_resim']; ?>" style="max-height:250px;">
								<input class="form-control" type="file" name="dh_resim" id="dh_resim" value="<?php echo $satir['dh_resim']; ?>"/>
								<small>* <?php echo $gtext['u_dhbgtext'];/*Resmi değiştirmek için yeni dosya seçebilirsiniz. Değiştirmek istemiyorsanız tekrar seçmenize gerek yoktur.*/?></small>
							</td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $gtext['q_filedir'];/*Dosya Yolu*/?></td>
							<td colspan="3"><input class="form-control" style="max-width:30;" type="text" name="dh_link" id="dh_link" value="<?php echo $satir['dh_link']; ?>" />
							<small>* <?php echo $gtext['u_filedir'];/*Sisteme yüklenmiş bir dokümanın yolu*/?></small>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['a_weblink'];/*Web Sayfa linki*/?></td>
							<td colspan="3"><input class="form-control" style="max-width:30;" type="text" name="dh_url" id="dh_url" value="<?php echo $satir['dh_url']; ?>" />
							<small>* <?php echo $gtext['u_weblink'];/*Portale yüklenmiş bir dokümanın adresi (veya başka bir web sayfası)*/?></small>
							</td>
						</tr>
						<tr>
							<td class="text-left"><div><?php echo $gtext['type'];/*Tip*/?>
							<p><small> (*) <?php echo $gtext['u_dhtype'];/*Gerekmedikçe değiştirmeyiniz.*/?></small></p></div></td>
							<td>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="dh" id="dh_D" value="D" <?php if($dh=='D'){ echo "checked"; }?> />
									<label class="form-check-label" for="dh_D"><?php echo $gtext['announcement'];/*Duyuru*/?></label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="dh" id="dh_K" value="K" <?php if($dh=='K'){ echo "checked"; }?> />
									<label class="form-check-label" for="dh_K"><?php echo $gtext['organizational']." ".$gtext['announcement'];/*Kurumsal Duyuru*/?></label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="dh" id="dh_H" value="H" <?php if($dh=='H'){ echo "checked"; }?> />
									<label class="form-check-label" for="dh_H"> <?php echo $gtext['news'];/*Haber*/?></label>
								</div>
							</td>
							<td class="text-right"><?php echo $gtext['state'];/*Durum*/?></td>
							<td>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="aktif" id="aktif_1" value="1" <?php if($satir['aktif']=='1'){ echo "checked"; }?> />
									<label class="form-check-label" for="aktif_1"> <?php echo $gtext['active'];/*Aktif*/?></label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="aktif" id="aktif_0" value="0" <?php if($satir['aktif']=='0'){ echo "checked"; }?>/>
									<label class="form-check-label" for="aktif_0"> <?php echo $gtext['passive'];/*Pasif*/?></label>
								</div>
							</td>
						</tr>
						</table><?php if(@$dh_uid!=""){ ?>
								<span><small><?php echo $gtext['a_creator']."/".$gtext['date'];/*Oluşturan/Tarih*/?>: <?php echo $satir['kullanici']."/".date("Y-m-d H:i:s", strtotime($satir['dh_tarih'])); ?></small> </span>
								<span><small> <?php echo $gtext['a_lastediting']."/".$gtext['date'];/*Son Düzenleyen/Tarih*/?>: <?php echo $satir['dh_sdkullanici']."/".date("Y-m-d H:i:s", strtotime($satir['dh_sdtar'])); ?></small></span>
							<?php } ?>
						</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" id="cancel" data-bs-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
							<button class="btn btn-primary" type="submit" id="ekle" disabled><?php 
							if($id==""){ 
								echo "<i class='fas fa-plus-circle fa-sm text-white-50'></i> ".$gtext['insert'];/*Insert*/ }
							else{ 
								echo "<i class='fas fa-plus-circle fa-sm text-white-50'></i> ".$gtext['edit'];/*"Değiştir";*/ 
							}?></button>
							<button class="btn btn-danger" name="delete" id="delete" type="button"><?php echo "<i class='fas fa-minus-circle fa-sm text-white-50'></i> ".$gtext['delete'];/*Sil*/?></button>
						</div>
						</form>
					</div>

                    <!-- Content Row -->

                    <div class="row">

                    </div>

                    <!-- Content Row -->
                    <div class="row">
					<?php //echo $dhq; ?>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include($docroot."/footer.php"); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
<script>
var uid="<?php echo $dh_uid; ?>";
$(document).ready(function() {
	$('#ekle').on("click", function(){ //ekle/değiştir ajaxform
		var opt={
			type	: 'POST',
			url 	: './set_dhm.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('<?php echo $gtext['q_rusure'];/*Emin Misiniz?*/?>');
			},
			success: function(data){ //
				console.log('Değiştirme :'+data); 
				if(data.indexOf('!')>-1){ alert('<?php echo $gtext['u_error'];/*Bir hata oluştu!*/?>\n'+data); }
				//else { alert(data); location.reload(); }
			}
		}
		$('#form_dh').ajaxForm(opt); //*/
	});
	$('#delete').on("click", function(){ //ekle/değiştir ajaxform
		$.ajax({
			type: 'POST',
			url: './set_dhm.php',
			data: { 'del':'1', '_id': uid },
			beforeSend : function(){
				return confirm('<?php echo $gtext['u_deleted'].", ".$gtext['q_rusure'];/*Silinecek, Emin Misiniz?*/?>');
				
			},
			success: function (data){//
				console.log('Silme :'+data);
				if(data.indexOf('!')>-1){ alert('<?php echo $gtext['u_error'];/*Bir hata oluştu!*/?>\n'+data); }
				else { alert(data); history.go(-1);  }
			}
		});//*/
	});
	$('#dh_resim').on('change', function(){
		readURL(this, 'img_dh_resim'); //pf.js
	});
});

$('form').find(':input').change(function(){ $('#ekle').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#ekle').prop("disabled", true ); });
if(uid!=''){ $('#ekle').html('Değiştir'); $('#delete').prop("disabled", false  ); }
else{ $('#dh_ytar').val('<?php echo $now; ?>'); $('#dh_sgtar').val('<?php echo $sgtar; ?>'); }
</script>
</body>

</html>