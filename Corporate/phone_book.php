<?php
/*
	DB yada LDAP'tan telefon rehberini getirir.
*/
include("../set_mng.php");
error_reporting(0);
include("../sess.php");

@$collection=$db->personel;
@$cursor = $collection->find(
    [
        'title' => ['$eq'=>'Phone']
    ],
    [
        'limit' => 0,
        'projection' => [
            'displayname' => 1,
            'telephonenumber' => 1,
            'bgcolor' => 1,
            'color' => 1,
        ],
    ],
	[
		'sort'=>['order'=>1]
	]
);
//var_dump($cursor);
$fsatir=[];
foreach ($cursor as $formsatir) {
	$satir=[];
	$satir['id']=$formsatir->_id;
	$satir['displayname']=$formsatir->displayname;
	$satir['telephonenumber']=$formsatir->telephonenumber;
	$satir['bgcolor']=$formsatir->bgcolor;
	$satir['color']=$formsatir->color;
	$fsatir[]=$satir;
}; 
$fisay=count($fsatir); 
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $ini['sb_phonebook']; /*Rehber*/?> </title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <link href="/css/sb-admin-2.css" rel="stylesheet">
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
                        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-phone-square"></i> Telefon Rehberi</h1>		
						<form id="myForm" onsubmit="return false">
							<div class="input-group mb-3">
								<input type="text" id="search" class="form-control" placeholder="Aranan" aria-label="Aranan" aria-describedby="basic-addon2">
								<div class="input-group-append">
									<button id="searchbtn" class="btn btn-outline-secondary" type="button">Ara</button>
								</div>
								<button id="temizle" class="btn btn-outline-default" type="button">Temizle</button>
								<div class="d-flex align-self-center">								
									<div class="form-check form-check-inline">
										<input type="radio" name="dp" id="dp_p" value="P" checked />
										<label class="form-check-label p-1 m-1" for="dp"> Personel  </label>
									</div>
									<div class="form-check form-check-inline">
										<input type="radio" name="dp" id="dp_d" value="D"/>
										<label class="form-check-label p-1 m-1" for="dp"> Birim </label>
									</div>										
								</div>
							</div>							
						</form>
						<div id="rp"></div> 
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<div class="col-xl-9 col-lg-8 mb-4">
							<div class="card shadow mb-4">
								<table class="" id="tellist" align="center" width="50%">
									<tr><th style="text-align: center"></th></tr>
								</table>
							</div>
						</div>
						<div class="col-xl-3 col-lg-4">
                            <div class="card shadow mb-4">
							<table width="100%"><?php //dbden gelir.....
							for($t=0;$t<$fisay;$t++){ ?>
								<tr>
									<td class="bg-<?php echo $fsatir[$t]['bgcolor']." text-".$fsatir[$t]['color'];?> w-25"><span class="m-1"><b><?php echo$fsatir[$t]['telephonenumber'];?> : </b></span></td>
									<td class="bg-<?php echo $fsatir[$t]['bgcolor']." text-".$fsatir[$t]['color'];?>"><span class="m-1"><b><?php echo$fsatir[$t]['displayname'];?></b></span></td>
								<tr><?php
							} ?>
							</table>
							</div>
						</div>
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


    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.min.js"></script>
<script>
var usersource="<?php echo $ini['usersource'];?>";
var userinfo="";
$(document).ready(function() {	
	$('#search').on("keydown", function(event){
		if ( event.which == 13 ) {
			$('#searchbtn').click(); 
		}
	});
	$('#temizle').on("click", function(){ 
		$('#tellist tbody').empty();	
	});
	$('#searchbtn').on("click", function(){ 
		$('#tellist tbody').empty();
		if($('#search').val()==""){ alert('Arama kriteri boş olamaz!'); return false; }
		if($('#search').val().length<3){ alert('Arama kriteri en az 3 harf olmalıdır...'); return false; } 
		var userfrom="get_tel.php";
		if(usersource=='LDAP'){ userfrom="get_tel_ldap.php";} 
		$.ajax({
			url: userfrom,
			type: "POST",
			datatype: 'json',
			data: { sea: $('#search').val(), dp: $("#myForm input[type='radio']:checked").val() },
			success: function(response){  //console.log(response);
				if(response.indexOf('!')>-1){
					alert('<?php echo $gtext['notfound']; ?>');
				}else{
					userinfo=JSON.parse(response);
					var uz=userinfo.length; 
					$('#tellist tr').remove();
					$('#tellist > tbody:last-child').append('<tr><th style="text-align: center"><?php echo $gtext['list'];?></th></tr>'); 
					var s=0;
					for(var i=0; i<uz; i++){
						var uinf=userinfo[i]; 
						var tab1="<tr><td>"
						+"<table class='table table-striped shadow-sm' style='border-style: solid; border-width:1px;'>"
						+"<tr><td width='150'>Adı Soyadı </td><td><b>"+uinf["displayname"]+"</b></td></tr>"
						+"<tr><td><?php echo $gtext['pernumber']; /*Sicil No*/?> </td><td>"+uinf["description"]+"</td></tr>"
						+"<tr><td><?php echo $gtext['pertitle']; /*Unvan*/?> </td><td>"+uinf["title"]+"</td></tr>";
						if(uinf["company"]!=uinf["department"]){
							tab1+="<tr><td><?php echo $gtext['percompany']; /*Üst Birim*/?> </td><td>"+uinf["company"]+"</td></tr>";
						}
						tab1+="<tr><td><?php echo $gtext['a_department']; /*Birim*/?> </td><td>"+uinf["department"]+"</td></tr>"
						+"<tr><td><?php echo $gtext['a_mail']; /*Mail*/?> </td><td><b>"+uinf["mail"]+"</b></td></tr>"
						+"<tr><td><?php echo $gtext['telephonenumber']; /*Dahili Tel*/?> </td><td><b>"+uinf["telephonenumber"]+"</b></td></tr>"
						+"<tr><td><?php echo $gtext['mobile']; /*GSM*/?> </td><td>"+uinf["mobile"]+"</td></tr>";
						if(uinf["manager"]!=''){
							tab1+="<tr><td><?php echo $gtext['manager']; /*Manager*/?> </td><td>"+uinf["manager"]+" <button class='btn btn-outline-dark' onclick='javascript:seabyname("+i+");'><i class='fas fa-info-circle'></i></button></td></tr>";
						}
						tab1+="</table></td></tr>";
						$('#tellist > tbody:last-child').append(tab1); //table içindeki her satıra bir table
						s++;
					}
					$('#tellist > tbody:last-child').append('<tr><td style="text-align: center"><small>'+s+' <?php echo $gtext['pb_listed'];?><small></td></tr>'); 
				}
			},
			error: function(response){ alert('Hata!'); }
		});
	});
}); 

	function seabyname(s){ 
		$('#dp_p').prop('checked', true);
		var nam=userinfo[s]["manager"];
		$('#search').val(nam);
		$('#searchbtn').click();
	}
</script>

</body>

</html>