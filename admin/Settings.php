<?php
/*
	Ayarları yapar.
*/
//error_reporting(0);
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
include($docroot."/sess.php");
@$kur=@$_SESSION['k']; //echo "k:".$kur." LA:".$_SESSION['LAST_ACTIVITY'];
if($kur!=''){
	$_SESSION['user']=$kur; //install.php->admin
	$_SESSION['y_admin']=1;
	$_SESSION['y_ayar01']=1;	
}
if($_SESSION['user']==''){
	header('Location: /login.php');
}
$y=$_SESSION['y_admin'];
if(!$y||$_SESSION['user']==''){ echo "yetki gerekir...";
	echo $gtext['reqprerequsity']; exit;
}
$MongoDB=$ini['MongoDB'];
if($MongoDB==''){ $MongoDB="DB01"; }
$timezone=$ini['timezone']; if($timezone==''){ $timezone="Europe/Istanbul"; }
$timezones=[];
//AMerica
$timezones[]="America/Adak";
$timezones[]="America/Anchorage";
$timezones[]="America/Anguilla";
$timezones[]="America/Antigua";
$timezones[]="America/Araguaina";
$timezones[]="America/Argentina/Buenos_Aires";
$timezones[]="America/Argentina/Catamarca";
$timezones[]="America/Argentina/Cordoba";
$timezones[]="America/Argentina/Jujuy";
$timezones[]="America/Argentina/La_Rioja";
$timezones[]="America/Argentina/Mendoza";
$timezones[]="America/Argentina/Rio_Gallegos";
$timezones[]="America/Argentina/Salta";
$timezones[]="America/Argentina/San_Juan";
$timezones[]="America/Argentina/San_Luis";	
$timezones[]="America/Argentina/Tucuman";
$timezones[]="America/Argentina/Ushuaia";
$timezones[]="America/Aruba";
$timezones[]="America/Asuncion";
$timezones[]="America/Atikokan";
$timezones[]="America/Bahia";
$timezones[]="America/Bahia_Banderas";
$timezones[]="America/Barbados";
$timezones[]="America/Belem";
$timezones[]="America/Belize";
$timezones[]="America/Blanc-Sablon";
$timezones[]="America/Boa_Vista";
$timezones[]="America/Bogota";
$timezones[]="America/Boise";
$timezones[]="America/Cambridge_Bay";
$timezones[]="America/Campo_Grande";
$timezones[]="America/Cancun";
$timezones[]="America/Caracas";
$timezones[]="America/Cayenne";
$timezones[]="America/Cayman";
$timezones[]="America/Chicago";
$timezones[]="America/Chihuahua";
$timezones[]="America/Ciudad_Juarez";
$timezones[]="America/Costa_Rica";
$timezones[]="America/Coyhaique";
$timezones[]="America/Creston";
$timezones[]="America/Cuiaba";
$timezones[]="America/Curacao";
$timezones[]="America/Danmarkshavn";
$timezones[]="America/Dawson";
$timezones[]="America/Dawson_Creek";
$timezones[]="America/Denver";
$timezones[]="America/Detroit";
$timezones[]="America/Dominica";
$timezones[]="America/Edmonton";
$timezones[]="America/Eirunepe";
$timezones[]="America/El_Salvador";
$timezones[]="America/Fort_Nelson";
$timezones[]="America/Fortaleza";
$timezones[]="America/Glace_Bay";
$timezones[]="America/Goose_Bay";
$timezones[]="America/Grand_Turk";
$timezones[]="America/Grenada";
$timezones[]="America/Guadeloupe";
$timezones[]="America/Guatemala";
$timezones[]="America/Guayaquil";
$timezones[]="America/Guyana";
$timezones[]="America/Halifax";
$timezones[]="America/Havana";
$timezones[]="America/Hermosillo";
$timezones[]="America/Indiana/Indianapolis";
$timezones[]="America/Indiana/Knox";
$timezones[]="America/Indiana/Marengo";
$timezones[]="America/Indiana/Petersburg";
$timezones[]="America/Indiana/Tell_City";
$timezones[]="America/Indiana/Vevay";
$timezones[]="America/Indiana/Vincennes";
$timezones[]="America/Indiana/Winamac";
$timezones[]="America/Inuvik";
$timezones[]="America/Iqaluit";
$timezones[]="America/Jamaica";
$timezones[]="America/Juneau";
$timezones[]="America/Kentucky/Louisville";
$timezones[]="America/Kentucky/Monticello";
$timezones[]="America/Kralendijk";
$timezones[]="America/La_Paz";
$timezones[]="America/Lima";
$timezones[]="America/Los_Angeles";
$timezones[]="America/Lower_Princes";
$timezones[]="America/Maceio";
$timezones[]="America/Managua";
$timezones[]="America/Manaus";
$timezones[]="America/Marigot";
$timezones[]="America/Martinique";
$timezones[]="America/Matamoros";
$timezones[]="America/Mazatlan";
$timezones[]="America/Menominee";
$timezones[]="America/Merida";
$timezones[]="America/Metlakatla";
$timezones[]="America/Mexico_City";
$timezones[]="America/Miquelon";
$timezones[]="America/Moncton";
$timezones[]="America/Monterrey";
$timezones[]="America/Montevideo";
$timezones[]="America/Montserrat";
$timezones[]="America/Nassau";
$timezones[]="America/New_York";
$timezones[]="America/Nome";
$timezones[]="America/Noronha";
$timezones[]="America/North_Dakota/Beulah";
$timezones[]="America/North_Dakota/Center";
$timezones[]="America/North_Dakota/New_Salem";
$timezones[]="America/Nuuk";
$timezones[]="America/Ojinaga";
$timezones[]="America/Panama";
$timezones[]="America/Paramaribo";
$timezones[]="America/Phoenix";
$timezones[]="America/Port-au-Prince";
$timezones[]="America/Port_of_Spain";
$timezones[]="America/Porto_Velho";
$timezones[]="America/Puerto_Rico";
$timezones[]="America/Punta_Arenas";
$timezones[]="America/Rankin_Inlet";
$timezones[]="America/Recife";
$timezones[]="America/Regina";
$timezones[]="America/Resolute";
$timezones[]="America/Rio_Branco";
$timezones[]="America/Santarem";
$timezones[]="America/Santiago";
$timezones[]="America/Santo_Domingo";
$timezones[]="America/Sao_Paulo";
$timezones[]="America/Scoresbysund";
$timezones[]="America/Sitka";
$timezones[]="America/St_Barthelemy";
$timezones[]="America/St_Johns";
$timezones[]="America/St_Kitts";
$timezones[]="America/St_Lucia";
$timezones[]="America/St_Thomas";
$timezones[]="America/St_Vincent";
$timezones[]="America/Swift_Current";
$timezones[]="America/Tegucigalpa";
$timezones[]="America/Thule";
$timezones[]="America/Tijuana";
$timezones[]="America/Toronto";
$timezones[]="America/Tortola";
$timezones[]="America/Vancouver";
$timezones[]="America/Whitehorse";
$timezones[]="America/Winnipeg";
$timezones[]="America/Yakutat";
//Europe
$timezones[]="Europe/Amsterdam";
$timezones[]="Europe/Andorra";
$timezones[]="Europe/Astrakhan";
$timezones[]="Europe/Athens";
$timezones[]="Europe/Belgrade";
$timezones[]="Europe/Berlin";
$timezones[]="Europe/Bratislava";
$timezones[]="Europe/Brussels";
$timezones[]="Europe/Bucharest";
$timezones[]="Europe/Budapest";
$timezones[]="Europe/Busingen";
$timezones[]="Europe/Chisinau";
$timezones[]="Europe/Copenhagen";
$timezones[]="Europe/Dublin";
$timezones[]="Europe/Gibraltar";
$timezones[]="Europe/Guernsey";
$timezones[]="Europe/Helsinki";
$timezones[]="Europe/Isle_of_Man";
$timezones[]="Europe/Istanbul";
$timezones[]="Europe/Jersey";
$timezones[]="Europe/Kaliningrad";
$timezones[]="Europe/Kirov";
$timezones[]="Europe/Kyiv";
$timezones[]="Europe/Lisbon";
$timezones[]="Europe/Ljubljana";
$timezones[]="Europe/London";
$timezones[]="Europe/Luxembourg";
$timezones[]="Europe/Madrid";
$timezones[]="Europe/Malta";
$timezones[]="Europe/Mariehamn";
$timezones[]="Europe/Minsk";
$timezones[]="Europe/Monaco";
$timezones[]="Europe/Moscow";
$timezones[]="Europe/Oslo";
$timezones[]="Europe/Paris";
$timezones[]="Europe/Podgorica";
$timezones[]="Europe/Prague";
$timezones[]="Europe/Riga";
$timezones[]="Europe/Rome";
$timezones[]="Europe/Samara";
$timezones[]="Europe/San_Marino";
$timezones[]="Europe/Sarajevo";
$timezones[]="Europe/Saratov";
$timezones[]="Europe/Simferopol";
$timezones[]="Europe/Skopje";
$timezones[]="Europe/Sofia";
$timezones[]="Europe/Stockholm";
$timezones[]="Europe/Tallinn";
$timezones[]="Europe/Tirane";
$timezones[]="Europe/Ulyanovsk";
$timezones[]="Europe/Vaduz";
$timezones[]="Europe/Vatican";
$timezones[]="Europe/Vienna";
$timezones[]="Europe/Vilnius";
$timezones[]="Europe/Volgograd";
$timezones[]="Europe/Warsaw";
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['s_psettings'];/*Portal Ayarları*/?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!--link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"-->
	<link
 href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
	<link href="/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">

    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>

    <link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="/js/portal_functions.js"></script>
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
                        <?php if($kur==1){ ?><h6 class="m-0 font-weight-bold text-primary"><?php echo $gtext['s_psettingswelcome'];/*Portal Kurulumuna Hoşgeldiniz...*/ echo ".<small>".$gtext['s_psettingsfirst']."</small>";?></h6><?php }else{ ?><h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['s_psettings'];/*Portal Ayarları*/?></h1><?php } ?>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					  <!-- DataTables Example -->
                      <div class="card shadow mb-4 w-100">
                        <div class="card-header py-3">
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
							<form name="form1" id="form1" method="POST" action="set_settings.php">
								<input type="hidden" name="kur" id="kur" value="<?php echo $kur;?>"/>
                                <table class="table table-bordered" id="b_list" width="100%" cellspacing="0">
                                    <thead>
									<tr>
										<th colspan="2"><b><i><?php echo $gtext['s_gsettings'];/*Genel Ayarlar*/?></i></b></th>
									</tr>
                                    </thead>
                                    <tbody>
									<tr>
										<td><?php echo $gtext['s_firm'];/*Firma Adı*/?></td>
                                        <td>
											<input class="form-control" type="text" name="firm" id="firm" value="<?php echo @$ini['firm']; ?>" placeholder="Portal-X İşletmesi Ltd."/>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_site'];/*Site Tanımı*/?></td>
                                        <td>
											<input class="form-control" type="text" name="title" id="title" value="<?php echo @$ini['title']; ?>" placeholder="Personel Portalı"/>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_logo'];/*Logo*/?></td>
                                        <td>
											<img id="img_logo" src="<?php echo $ini['logo']; ?>" style="max-height:200px; max-width:250px; background-color:#FFFAF0;">
											<br>
											<label for="logo" class="form-label"><input class="form-control" type="file" name="logo" id="logo" value="<?php echo @$ini['logo']; ?>"/></label>
											<br>
											<small>(*)<?php echo $gtext['s_logorules'];/*Png, jpg veya bmp olarak, 800*120-150 ebatlarında bir resim konulabilir.*/?></small>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_sbcolor'];/*Sidebar Arkaplan Rengi*/?></td>
                                        <td>
											<div class="input-group">
												<span class="input-group-text">#</span>
												<input class="form-control" type="text" name="bg_set" id="bg_set" value="<?php echo @$ini['bg_set']; ?>" placeholder="<?php echo $bg_set;?>"/>
											</div>
											<br>
											<small>(*)<?php echo $gtext['s_colorrules'];/*RGB değeri girilmelidir. Örnek: 2d4357*/?></small>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_loginbgpicture'];/*Login Arkaplan Dosyası*/?></td>
                                        <td>
											<div class="input-group">
												<span class="input-group-text">#</span>
												<input class="form-control" type="text" name="bg_login" id="bg_login" value="<?php echo @$ini['bg_login']; ?>" placeholder="<?php echo $bg_login;?>"/>
											</div>
											<br>
											<small>(**)<?php echo $gtext['s_loginpic_rules'];/* png, jpg...*/?></small>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_dateformat'];/*Tarih Görünümü*/?></td>
                                        <td><?php $tg=@$ini['date_local']; if($tg==''){ $tg="d.m.Y"; }?>
											<input class="form-control" type="text" name="date_local" id="date_local" value="<?php echo $tg; ?>"/>
											<br>
											<small><?php echo $gtext['s_dateformatrules'];/*Örnek: d.m.Y = 31.12.2024*/?></small>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_timezone'];/*TimeZone*/?></td>
                                        <td>
											<SELECT class="form-control" type="text" name="timezone" id="timezone"><?php
											for($tz=0;$tz<count($timezones);$tz++){ 
												echo '<OPTION value="'.$timezones[$tz].'" ';
												if($timezone==$timezones[$tz]){ echo "selected"; } 
												echo '>'.$timezones[$tz].'</OPTION>'; 
											} ?>
											</SELECT>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_act_seperator'];/*Hareketler Ayracı*/?></td>
                                        <td><?php $as=@";"; if($as==''){ $as="<br>"; }?>
											<input class="form-control" name="act_seperator" id="act_seperator" value="<?php echo $as; ?>"/>
											<br>
											<small><?php echo $gtext['s_act_seperator_ex'];/*Örnek: <br>*/?></small>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_sesstime'];/*Oturum Zamanı*/?></td>
                                        <td>
											<div class="input-group">
												<input class="form-control" type="text" name="sess_time" id="sess_time" value="<?php echo @$ini['sess_time']; ?>" placeholder="1800"/>
												<span class="input-group-text"><?php echo $gtext['second']; /*sn.*/?></span>
											</div>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_wd_saturday'];/*Cumartesi Günü Çalışma*/?></td>
                                        <td>
											<label class="btn btn-outline-primary">
												<input class="form-control" type="checkbox" data-bs-toggle="toggle" name="menu_gun6" id="menu_gun6" data-on="<?php echo $gtext['exist'];/*Var*/?>" data-off="<?php echo $gtext['notexist'];/*Yok*/?>" style="border-color: black;" data-width="80px"/>
											</label>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_wd_sunday'];/*Pazar Günü Çalışma*/?> **</td>
                                        <td>
											<label class="btn btn-outline-primary">	
												<input class="form-control" type="checkbox" data-bs-toggle="toggle" name="menu_gun0" id="menu_gun0" data-on="<?php echo $gtext['exist'];/*Var*/?>" data-off="<?php echo $gtext['notexist'];/*Yok*/?>" data-width="80px"/>
											</label>
											<br>
											<small>** <?php echo $gtext['s_u_wd'];/*Yemek listesi gibi noktalarda kullanılır.*/?></small>
										</td>
									</tr>
                                    <thead>
									<tr>
										<th colspan="2"><b><i><?php echo $gtext['s_dbsettingmongo'];/*DataBase Ayarları (Mongo)*/?></i></b></th>
									</tr>
                                    </thead>
									<tr>
										<td><?php echo $gtext['s_mongodbconn'];/*Mongo DB Bağlantısı*/?></td>
                                        <td><?php if(@$ini['MongoConnection']!=""){ $MongoConn=@$ini['MongoConnection']; }else{ $MongoConn="mongodb://localhost:27017"; } ?>
											<input class="form-control" type="text" name="MongoConnection" id="MongoConnection" value="<?php echo $MongoConn; ?>"/>
											<br>
											<small><?php echo $gtext['u_mongodbconn'];?></small>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_mongodb'];/*Mongo DB*/?>*</td>
                                        <td><div id="divMongoDB"><?php echo @$ini['MongoDB']; ?></div>
											<input type="hidden" name="MongoDB" id="MongoDB" alt="Lutfen MongoDB için bir isim giriniz..." value="<?php echo $MongoDB; ?>"/>
											<!--br>
											<small><?php echo $gtext['u_mongodb'];/*Not: Boşluk kullanmayınız.*/?></small-->
										</td>
									</tr>
                                    <thead>
									<tr>
										<th colspan="2"><b><i><?php echo $gtext['apps'];/*Uygulamalar*/?></i></b></th>
									</tr>
                                    </thead>
									<tr>
										<td><?php echo $gtext['fixtures'];/*Demirbaşlar*/?></td>
                                        <td>
											<label class="btn btn-outline-primary">
												<input class="form-control" type="checkbox" data-bs-toggle="toggle" name="Fixtures" id="Fixtures" data-on="<?php echo $gtext['exist'];/*Var*/?>" data-off="<?php echo $gtext['notexist'];/*Yok*/?>" data-width="80px"/>
											</label>
										</td>
									</tr>
                                    <thead>
									<tr>
										<th colspan="2"><b><i><?php echo $gtext['docs'];/*Belgeler*/?></i></b></th>
									</tr>
                                    </thead>
									<tr>
										<td><?php echo $gtext['orgschemes'];/*Organizasyon Şeması*/?></td>
                                        <td>
											<label class="btn btn-outline-primary">
												<input class="form-control" type="checkbox" data-bs-toggle="toggle" name="Org_Sema" id="Org_Sema" data-on="<?php echo $gtext['exist'];/*Var*/?>" data-off="<?php echo $gtext['notexist'];/*Yok*/?>" data-width="80px"/>
											</label>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_orgschemedir'];/*Org.Şema Klasörü*/?></td>
                                        <td><input class="form-control" type="text" name="Org_Sema_Dir" id="Org_Sema_Dir" value="<?php echo @$ini['Org_Sema_Dir']; ?>"</td>
									</tr>
									<tr>
										<td><?php echo $gtext['certs'];/*Sertifikalar*/?></td>
                                        <td>
											<label class="btn btn-outline-primary">
												<input class="form-control" type="checkbox" data-bs-toggle="toggle" name="Sertifikalar" id="Sertifikalar" id="Sertifikalar" data-on="<?php echo $gtext['exist'];/*Var*/?>" data-off="<?php echo $gtext['notexist'];/*Yok*/?>" data-width="80px"/>
											</label>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_certsdir'];/*Sertifika Klasörü*/?></td>
                                        <td><input class="form-control" type="text" name="b_certs_url" id="b_certs_url" value="<?php echo @$ini['b_certs_url']; ?>"/></td>
									</tr>
									<tr>
										<td><?php echo $gtext['quals'];/*Kalifikasyonlar*/?></td>
                                        <td>
											<label class="btn btn-outline-primary">
												<input class="form-control" type="checkbox" data-bs-toggle="toggle" name="Kalifikasyonlar" id="Kalifikasyonlar" data-on="<?php echo $gtext['exist'];/*Var*/?>" data-off="<?php echo $gtext['notexist'];/*Yok*/?>" data-width="80px"/>
											</label>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_qualsdir'];/*Kalifikasyon Klasörü*/?></td>
                                        <td><input class="form-control" type="text" name="b_quals_url" id="b_quals_url" value="<?php echo @$ini['b_quals_url']; ?>"/></td>
									</tr>
									<tr>
										<td><?php echo $gtext['forms'];/*Formlar*/?></td>
                                        <td>											
											<label class="btn btn-outline-primary">
												<input class="form-control" type="checkbox" data-bs-toggle="toggle" name="Formlar" id="Formlar" data-on="<?php echo $gtext['exist'];/*Var*/?>" data-off="<?php echo $gtext['notexist'];/*Yok*/?>" data-width="80px"/>
											</label>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_formsdir'];/*Form Klasörü*/?></td>
                                        <td><input class="form-control" type="text" name="b_forms_url" id="b_forms_url" value="<?php echo @$ini['b_forms_url']; ?>"/></td>
									</tr>
                                    <thead>
									<tr>
										<th colspan="2"><b><i><?php echo $gtext['s_personel'];/*Personel*/?></i></b></th>
									</tr>
                                    </thead>
									<tr>
										<td><?php echo $gtext['menuofdayinroot'];/*Anasayfada Günün Menüsü*/?></td>
                                        <td>
											<label class="btn btn-outline-primary">
												<input class="form-control" type="checkbox" data-bs-toggle="toggle" name="menuofday" id="menuofday" data-on="<?php echo $gtext['exist'];/*Var*/?>" data-off="<?php echo $gtext['notexist'];/*Yok*/?>" data-width="80px"/>
											</label>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_dtelview'];/*Şoför Telefon numarası gösterimi*/?></td>
                                        <td>
											<label class="btn btn-outline-primary">
												<input class="form-control" type="checkbox" data-bs-toggle="toggle" name="pservis_sofor_tel_gosterim" id="pservis_sofor_tel_gosterim" data-on="<?php echo $gtext['exist'];/*Var*/?>" data-off="<?php echo $gtext['notexist'];/*Yok*/?>" data-width="80px"/>
											</label>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['pano'];/*İletişim Panosu*/?></td>
                                        <td>
											<label class="btn btn-outline-primary">
												<input class="form-control" type="checkbox" data-bs-toggle="toggle" name="personel_pano" id="personel_pano" data-on="<?php echo $gtext['exist'];/*Var*/?>" data-off="Yok " data-width="80px"/>
											</label>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['usercanedit'];/*user can edit*/?></td>
                                        <td>
											<input class="form-control" type="checkbox" data-bs-toggle="toggle" name="usercanedit" id="usercanedit" data-on="<?php echo $gtext['yes'];/*Evet*/?>" data-off="<?php echo $gtext['no'];/*Hayır*/?>" data-width="80px"/>
										</td>
									</tr><!-- usernameflow -->
									<tr>
										<td><?php echo $gtext['usernameflow'];/*username flow*/?></td>
                                        <td>
											<label class="form-check"><input class="form-check-input" type="radio" name="usernameflow" id="usernameflowNS" value="NS" checked /> <?php echo $gtext['name']." ".$gtext['surname'];?></label>
											<label class="form-check"><input class="form-check-input" type="radio" name="usernameflow" id="usernameflowSN" value="SN"/> <?php echo $gtext['surname']." ".$gtext['name'];?></label>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['givenname_length'];/*Adı Uzunluk*/?></td>
                                        <td>
											<input class="form-control" type="text" name="givenname_length" id="givenname_length" value="<?php echo @$ini['givenname_length']; ?>" placeholder="1"/>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['asayrac'];/*Ad Soyad Ayracı*/?></td>
                                        <td>
											<input class="form-control" type="text" name="asayrac" id="asayrac" value="<?php echo @$ini['asayrac']; ?>"/>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['sn_length'];/*Soyadı Uzunluk*/?></td>
                                        <td>
											<input class="form-control" type="text" name="sn_length" id="sn_length" value="<?php echo @$ini['sn_length']; ?>" placeholder="99"/>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['passformat'];/*Şifre formatı*/?></td>
                                        <td>
											<input class="form-control" type="text" name="passformat" id="passformat" value="<?php echo @$ini['passformat']; ?>" placeholder="aaaAA99!"/>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['stdpass'];/*Std Şifre*/?></td>
                                        <td>
											<input class="form-control" type="text" name="stdpass" id="stdpass" value="<?php echo @$ini['stdpass']; ?>"/>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['disabledname'];/*Disabledname*/?></td>
                                        <td>
											<input class="form-control" type="text" data-bs-toggle="toggle" name="disabledname" id="disabledname" value="<?php echo @$ini['disabledname']; ?>"/>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['disabledmailuser'];/*DisabledMailUser*/?></td>
                                        <td>
											<input class="form-control" type="text" data-bs-toggle="toggle" name="disabledmailuser" id="disabledmailuser" value="<?php echo @$ini['disabledmailuser']; ?>"/>
										</td>
									</tr>
                                    <thead>
									<tr>
										<th colspan="2"><b><i><?php echo $gtext['s_domainsettings'];/*Domain_Settings*/?></i></b>
											<br>
											<small><?php echo $gtext['u_domainsettings'];/*Girişte kullanıcı doğrulamak için kullanılacaktır.*/?></small>
										</th>
									</tr>
                                    </thead>
									<tr class="ldap">
										<td><?php echo $gtext['s_usersource'];/*Kullanıcı Doğrulama Kaynağı*/?></td>
                                        <td>
											<div class="form-check form-check-inline">
												<input class="form-check-input usersource" type="radio" name="usersource" id="usersource_db" value="Database"/><label  class="form-check-label" for="usersource_db"> <?php echo $gtext['s_database'];/*Database*/?></label>
											</div>
											<div class="form-check form-check-inline">
												<input class="form-check-input usersource" type="radio" name="usersource" id="usersource_ldap" value="LDAP"/><label  class="form-check-label" for="usersource_ldap"> <?php echo $gtext['s_ldap'];/*LDAP */?></label>
											</div>
										</td>
									</tr>
									<tr class="ldap">
										<td><?php echo $gtext['s_domain'];/*Alan Adı*/?></td>
                                        <td><input class="form-control ldap" type="text" name="domain" id="domain" value="<?php echo @$ini['domain']; ?>"/></td>
									</tr>
									<tr class="ldap">
										<td><?php echo $gtext['s_ldapserver'];/*LDAP(AD) Sunucusu*/?></td>
                                        <td><input class="form-control ldap" type="text" name="ldap_server" id="ldap_server" value="<?php echo @$ini['ldap_server']; ?>"/></td>
									</tr>
									<tr class="ldap">
										<td><?php echo $gtext['s_ldapserver']." 2";/*LDAP Sunucusu*/?></td>
                                        <td><input class="form-control ldap" type="text" name="ldap_server2" id="ldap_server2" value="<?php echo @$ini['ldap_server2']; ?>"/></td>
									</tr>
									<tr class="ldap">
										<td><?php echo $gtext['s_base_dn'];/*Temel DN*/?></td>
                                        <td><input class="form-control ldap" type="text" name="base_dn" id="base_dn" value="<?php echo @$ini['base_dn']; ?>"/></td>
									</tr>
									<tr class="ldap">
										<td><?php echo $gtext['disabledou'];/*Kısa Ad*/?></td>
                                        <td><input class="form-control ldap" type="text" name="disabledou" id="disabledou" value="<?php echo @$ini['disabledOU']; ?>"/></td>
									</tr>
									<tr class="ldap">
										<td><?php echo $gtext['s_domshort'];/*Kısa Ad*/?></td>
                                        <td><input class="form-control ldap" type="text" name="domshort" id="domshort" value="<?php echo @$ini['domshort']; ?>"/></td>
									</tr>
									<tr class="ldap">
										<td><?php echo $gtext['s_ldap']." ".$gtext['auth_username'];/*Yetkili Kullanıcı*/?></td>
                                        <td>											
											<input class="form-control ldap" type="text" name="auth_username" id="auth_username" value="<?php echo @$ini['auth_username']; ?>"/>
										</td>
									</tr>
									<tr>
										<td><?php echo $gtext['homedir'];/*Homedir*/?></td>
                                        <td><input class="form-control ldap" type="text" name="homedir" id="homedir" value="<?php echo @$ini['homedir']; ?>"/></td>
									</tr>
									<tr>
										<td><?php echo $gtext['homedrive'];/*HomeDrive*/?></td>
                                        <td><input class="form-control ldap" type="text" name="homedrive" id="homedrive" value="<?php echo @$ini['homedrive']; ?>"/></td>
									</tr>
									<tr>
										<td><?php echo $gtext['drive_permission'];
										//Sürücü yetkileri: OI:Object Inherit CI:Container Inherit M:Modify with Delete F:Full access
										$dp=@$ini['drive_permission']; ?></td>
                                        <td>
											<SELECT class="form-control" name="drive_permission" id="drive_permission">
											<OPTION value="M" <?php if($dp=='M'){ echo "selected"; } ?> >M Modify with Delete</OPTION>
											<OPTION value="F" <?php if($dp=='F'){ echo "selected"; } ?> >F Full Access</OPTION>
											<OPTION value="OI" <?php if($dp=='OI'){ echo "selected"; } ?> >OI Object Inherit</OPTION>
											<!--OPTION value="CI" <?php if($dp=='CI'){ echo "selected"; } ?> >CI Container Inherit</OPTION-->
											</SELECT>
										</td>
									</tr>
                                    <thead>
									<tr>
										<th colspan="2"><b><i><?php echo $gtext['ldap_groups'];/*Groups (LDAP/AD)*/?></i></b>
										</th>
									</tr>
                                    </thead>
									<tr>
										<td><?php echo $gtext['ldap_groups_point'];/*Groups_Point*/?></td>
                                        <td><textarea class="form-control" name="groups_point" id="groups_point" cols="5" width="100%"><?php @$gp=$ini['groups_point']; if($gp==""){ $gp="Custom";} echo $gp; ?></textarea></td>
									</tr>
									<tr>
										<td><?php echo $gtext['ldap_groups'];/*ldap_groups*/?></td>
                                        <td><textarea class="form-control" name="group" id="group" cols="5" width="100%"><?php echo @$ini['group']; ?></textarea></td>
									</tr>
                                    <thead>
									<tr>
										<th colspan="2"><b><i><?php echo $gtext['s_messages'];/*Messagess*/?></i></b>
										</th>
									</tr>
                                    </thead>
									<tr>
										<td><?php echo $gtext['s_message']." 1";/*User Mesajı 1*/?> (*)</td>
                                        <td><textarea class="form-control" name="uaddmsg1" id="uaddmsg1" cols="5" width="100%"><?php echo @$ini['uaddmsg1']; ?></textarea></td>
									</tr>
									<tr>
										<td><?php echo $gtext['s_message']." 2";/*User Mesajı 1*/?> (*)</td>
                                        <td><textarea class="form-control" name="uaddmsg2" id="uaddmsg2" cols="5" width="100%"><?php echo @$ini['uaddmsg2']; ?></textarea></td>
									</tr>
									<tr>
										<td class="text-center" colspan="2">
											<button class="btn btn-primary" id="send_set" disabled type="submit" ><?php echo $gtext['send'];/*Gönder*/?></button>
										</td>
									</tr>
                                    </tbody>
								</table>
								<div>(*) <?php echo $gtext['s_message_ack']; ?></div>
								<div id="rt"></div>
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

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Custom scripts for all pages-->
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
    <script src="/js/sb-admin-2.js"></script>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<script>
var kur='<?php echo $kur;?>';
var cl='<?php if(isset($_SESSION['cloud'])){ echo $_SESSION['cloud']; }?>';
$(document).ready(function() {
	$('#send_set').on("click", function(){ //ekle/değiştir ajaxform
		var opt={
			type	: 'POST',
			url 	: './set_settings.php',
			target	: '#rt',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				//kontroller yapılacak...
				if($('#MongoDB').val()==''){ alert($('#MongoDB').attr("alt")); return false; }
				var y=confirm('<?php echo $gtext['q_rusure'];/*Emin Misiniz?*/?>');
			},
			success: function(data){ 
				if(data.indexOf('!')>-1){ alert('<?php echo $gtext['u_error'];/*Bir hata oluştu!*/?>\n'+data); }
				else { 
					if(kur!=''){ data+='\n<?php echo $gtext['u_next'];?>'; }
					alert(data); 
					if(kur==''){ location.href='/index.php'; }else{ location.href='/install.php'; }
				}
			}
		}
		$('#form1').ajaxForm(opt); 
	});
	$('#logo').on('change', function(){ //view logo
		readURL(this, 'img_logo');
	});
	var menu_gun0="<?php echo @$ini['menu_gun0']; ?>";
	if(menu_gun0==1){ $('#menu_gun0').bootstrapToggle('on'); }else{ $('#menu_gun0').bootstrapToggle('off'); } 
	var menu_gun6="<?php echo @$ini['menu_gun6']; ?>";
	if(menu_gun6==1){ $('#menu_gun6').bootstrapToggle('on'); }else{ $('#menu_gun6').bootstrapToggle('off'); } 
	var Fixtures="<?php echo @$ini['Fixtures']; ?>";
	if(Fixtures==1){ $('#Fixtures').bootstrapToggle('on'); }else{ $('#Fixtures').bootstrapToggle('off'); } 
	var Org_Sema="<?php echo @$ini['Org_Sema']; ?>";
	if(Org_Sema==1){ $('#Org_Sema').bootstrapToggle('on'); }else{ $('#Org_Sema').bootstrapToggle('off'); } 
	var Sertifikalar="<?php echo @$ini['Sertifikalar']; ?>";
	if(Sertifikalar==1){ $('#Sertifikalar').bootstrapToggle('on'); }else{ $('#Sertifikalar').bootstrapToggle('off'); } 
	var Kalifikasyonlar="<?php echo @$ini['Kalifikasyonlar']; ?>";
	if(Kalifikasyonlar==1){ $('#Kalifikasyonlar').bootstrapToggle('on'); }else{ $('#Kalifikasyonlar').bootstrapToggle('off'); } 
	var Formlar="<?php echo @$ini['Formlar']; ?>";
	if(Formlar==1){ $('#Formlar').bootstrapToggle('on'); }else{ $('#Formlar').bootstrapToggle('off'); } 
	var menuofday="<?php echo @$ini['menuofday']; ?>";
	if(menuofday==1){ $('#menuofday').bootstrapToggle('on'); }else{ $('#menuofday').bootstrapToggle('off'); }
	var pservis_sofor_tel_gosterim="<?php echo @$ini['pservis_sofor_tel_gosterim']; ?>";
	if(pservis_sofor_tel_gosterim==1){ $('#pservis_sofor_tel_gosterim').bootstrapToggle('on'); }else{ $('#pservis_sofor_tel_gosterim').bootstrapToggle('off'); }  
	var personel_pano="<?php echo @$ini['personel_pano']; ?>";
	if(personel_pano==1){ $('#personel_pano').bootstrapToggle('on'); }else{ $('#personel_pano').bootstrapToggle('off'); } 
	var usercanedit="<?php echo @$ini['usercanedit']; ?>";
	if(usercanedit==1){ $('#usercanedit').bootstrapToggle('on'); }else{ $('#usercanedit').bootstrapToggle('off'); } 
	var usernameflow="<?php echo @$ini['usernameflow']; ?>"; if(usernameflow=''){ usernameflow='NS';}
	if(usernameflow=='NS'){ $('#usernameflowNS').prop('checked', true); }else{ $('#usernameflowSN').prop('checked',true); } 
	var usersrc="<?php $us=@$ini['usersource']; if($us==''){ $us='Database'; } echo $us; ?>"; 	
	if(usersrc=='LDAP'){ 
		$('#usersource_db').attr('checked', false); 
		$('#usersource_ldap').attr('checked', true);
	}else{ 
		$('#usersource_db').attr('checked', true); 
		$('#usersource_ldap').attr('checked', false); 
	} 
	$('form').find(':input').change(function(){ $('#send_set').attr("disabled", false ); });
	$('#firm').change(function(){ 
		if(cl==''){
			var f=$('#firm').val();
			f=f.replaceAll(' ','');
			f=f.replaceAll('.','');
			//türkçe char ayıklama
			var k="çÇöÖşŞıİüÜğĞ";
			var d="cCoOsSiIuUgG"; 
			for(i=0;i<f.length;i++){ 
				if(f.indexOf(k[i])>-1){ f=f.replaceAll(k[i],d[i]);	}
			}
			var dbname=f.substring(0,20);
			if(cl==1){ 
				dbname=dbnamecont(dbname);
				$('#divMongoDB').html(dbname); 
				$('#MongoDB').val(dbname); 
			}
		}
	});
	function dbnamecont(dbname){
		$.ajax({
			type: 'POST',
			url: 'get_dbname.php',
			data:{ dbname: dbname },
			beforesubmit: function(){ 
			},
			success: function (data){ //console.log('donen:'+data);
				return data;
			}
		});
	}
	$('.usersource').on('change', function(){ $('.ldap').attr("disabled", $('#usersource_db').is(':checked'));	});
});
</script>
</body>

</html>