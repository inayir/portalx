<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<?php
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
include($docroot."/sess.php");
?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['a_ldaprules']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['a_ldaprules'];/*LDAP Kuralları*/ ?></h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					  <div class="table-responsive">
						<table class="table table-bordered" width="100%" cellspacing="1">
						<thead>
							<tr><th><b>Kullanıcı adı kuralları:</b></th></tr>
						</thead>
						<tbody>
							<tr><td>Kullanıcı adı mümkün olan en kısa biçimde olmalıdır.</td></tr>
							<tr><td>Kullanıcı adı içinde boşluk, özel ve türkçe karakterler gibi karakterler kullanılmaz.</td></tr>
							<tr><td>Kullanıcı adı içinde ayraç olarak ".", "-" karakterleri kullanılabilir. Boşluk ve diğer özel karakterler ile türkçe karakterler kullanılmaz.</td></tr>
							<tr><td>Kullanıcı adı isim ve soyad şeklinde oluşturulabilir. </td></tr>
							<tr><td>İsim ve soyad için belirlenen uzunluğa göre sistem kullanıcı adını önerecektir. Eğer kullanıcı adını değiştirirseniz mutlaka "Kont" tuşu ile uygunluğunu test ediniz.</td></tr>
							<tr><td>İsim ve soyad için uzunluklar 0-99 arasında belirlenebilir. 0: kullanılmasın, 99: tümü kullanılsın demektir. Örnek: adı Hayati Durmaz olan kullanıcıya; İsim 1, ayraç "." ve soyad tüm soyad olsun: h.durmaz olacaktır.</td></tr>
							<tr><td>Mail adresi kullanıcı adı ile aynı şekilde oluşturulacaktır ancak sunucuda açılmayacaktır.</td></tr>						
						<thead>
							<tr><th></th></tr>
							<tr><th><b>Birim oluşturma kuralları:</b></th></tr>
						</thead>
							<tr><td>Üst Birim ve Birim oluşturulmuş olmalıdır.</td></tr>
							<tr><td>Üst birim ve birim adlarında boşluk, özel ve türkçe karakterler gibi karakterler kullanılmaz.</td></tr>
							<tr><td>Her Üst Birim ve Birim altında "Gruplar OU" alanında belirtilen OU açılmalıdır. Gruplar bu OU altında oluşturulacaktır.</td></tr>												
						<thead>
							<tr><th></th></tr>
							<tr><th><b>Grup kuralları:</b></th></tr>
						</thead>
							<tr><td>Gruplar "Gruplar OU" altında oluşturulur. </td></tr>
							<tr><td>Üst Birim: {company} ve Birim {department} şeklinde grup adına eklenebilir.</td></tr>
							<tr><td>LDAP/AD altında gruplar oluşturulurken boşluk, özel ve türkçe karakterler gibi karakterler kullanılmaz.</td></tr>
							<tr><td>Eğer kullanıcının üye olacağı grup adında Üst Birim ve Alt Birim tanımlanmamışsa domain altındaki "Gruplar OU" altındaki adı geçen gruba üye yapılır.</td></tr>						
						<thead>
							<tr><th></th></tr>
							<tr><th><b>LDAP/AD Alanları:</b></th></tr>
						</thead>
							<tr><td>{givenname} İsim</td></tr>
							<tr><td>{sn} Soyadı</td></tr>
							<tr><td>{mail} Mail Adresi </td></tr>
							<tr><td>{title} Ünvan </td></tr>
							<tr><td>{firm} Firma Adı</td></tr>
							<tr><td>{company} Üst Birim</td></tr>
							<tr><td>{department} Birim </td></tr>
						</tbody>
						</table>
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.js"></script>

</body>

</html>