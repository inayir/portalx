<!DOCTYPE html>
<?php
/*
	Fixture_assign.php
*/
include("../set_mng.php"); //for Mongo DB connection, if needed. If not; write first line: include('/get_ini.php');
include($docroot."/sess.php");
if($user==""){ //if auth pages needed...
	//header('Location: /login.php');
}
?>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['fixtassigndoc']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">

<?php include($docroot."/set_page.php"); ?>
<style>
* {
  box-sizing: border-box;
}
#perlist {
  list-style-type: none;
  padding: 0;
  margin: 0;
  max-height: 150px;
}
#perlist li a {
  border: 1px solid #ddd;
  margin-top: -1px; /* Prevent double borders */
  background-color: #f6f6f6;
  padding: 2px;
  text-decoration: none;
  font-size: 12px;
  color: black;
  display: block;
}
#perlist li a:hover:not(.header) {
  background-color: #eee;
}
div.dt-search {
    float: right;
}
div.dt-info {
    float: left;
    margin-top: 0.8em;
}
div.dt-paging {
    float: right;
    margin-top: 0.5em;
}
body {
  font: 90%/1.45em "Helvetica Neue", HelveticaNeue, Verdana, Arial, Helvetica, sans-serif;
  margin: 0;
  padding: 0;
  color: #333;
  background-color: #fff;
}
</style>
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['fixtassigndoc']; ?></h1>
                        <!--a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a-->
                    </div>
					<form name="fs_form" id="fs_form" method="POST" action="fxt_debit_doc.php" target="_tab">
						<input type="hidden" name="form" id="form" value="YFR-101"/>
                    <!-- Content Row -->
                    <div class="row">
						<div class="table-responsive">
                            <table class="table table-striped" id="a" width="100%" cellspacing="0">
							<tr>
								<td>Personel:</td>
								<td>
									<input class="form-control-sm" type="text" name="searchper" id="searchper" onkeyup="searchpers();" placeholder="<?php echo $gtext['search'];?>..." title="<?php echo $gtext['searchtitle'];?>"/>
									<input type="hidden" name="username" id="username" value=""/>
									<ul id="perlist">
										<li></li>
									</ul>
								</td>
							</tr>
							<tr>
								<td>Tutanak:</td>
								<td>
									<select class="form-select-sm" name="doc_side" id="doc_side">
									<option value="to">Personele teslim tutanağı</option>
									<option value="from">Personelden alım tutanağı</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2" class="text-center">
									<input class="btn btn-primary" type="button" id="getfixtures" value="<?php echo $gtext['getinfos'];/*Bilgileri getir*/?>" width=70 style="display: none" />
								</td>
							</tr>
							</table>
						</div>                        
                    </div>

                    <!-- Content Row -->
                    <div class="row" id="fixlistdiv" style="display: none;">
						<div class="text-center fw-bold">Personele zimmetlenmiş Demirbaşlar</div>
						<div class="table-responsive">
                            <table class="table table-striped" id="fxtlist" width="100%" cellspacing="0">
							<thead>
							<tr>
								<th><input type="checkbox" class="chall" value="" title="Tümünü Seç"/></td>
								<th>Tip</td>
								<th>Kod</td>
								<th>Tanım</td>
								<th>Seri no</td>
								<th>Yer</td>
								<th>Zimmet Tarihi</td>
							</tr>
							</thead>
							<tfoot>
							<tr>
								<th><input type="checkbox" class="chall" value="" title="Tümünü Seç"/></td>
								<th>Tip</td>
								<th>Kod</td>
								<th>Tanım</td>
								<th>Seri no</td>
								<th>Yer</td>
								<th>Zimmet Tarihi</td>
							</tr>
							</tfoot>
							<tbody>
							</tbody>
							</table>
						</div>
						<div class="text-center">
							<button class="btn btn-info" type="submit" id="isl" disabled ><?php echo $gtext['print'];/*Yazdır*/?></button>
									<input class="btn btn-danger" type="button" id="close" value="<?php echo $gtext['clear'];/*Temizle*/?>" width=70/>
						</div>
                    </div>
					</form>
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
    <!-- Core plugin JavaScript-->
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script> 
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/js/sb-admin-2.js"></script>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
<script>
var objper="";
function searchpers() { 
	$('#perlist').css('display', 'inline');
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("searchper"); 
    if(input.value.length>2){
		filter = input.value.toUpperCase(); 
		$('#perlist li').remove();
		$.each(objper, function(i, key, username, displayname, title){ 
			var k=objper[i].key, v=objper[i].displayname; 
			if(v.toUpperCase().indexOf(filter)>=0){ 
				var li='<li><a href="#" onClick="sec(\''+objper[i].username+'\',\''+v+'\');">'+v+' ('+objper[i].title+')('+objper[i].description+')</a></li>';
				$('#perlist').append(li); 
			}		
		}); 
	}
}
function sec(username, displayname){ 
	$('#searchper').val(displayname);
	$('#username').val(username);
	$('#perlist').css('display', 'none');
	$('#fixlistdiv').css("display", 'inline'); 
	$('#getfixtures').click(); 
}
function listegetir(){ 
	var yol="/app/get_per_list.php"; 
	var keys=['username','displayname','title','description'];
	$.ajax({
		url: yol,
		type: "POST",
		datatype: 'json',
		async: false,
		data: { 'keys': keys },
		success: function(response){ 
			if(response=='login'){ location.reload(); }
			objper=JSON.parse(response);
		},
		error: function(response){ alert('Hata!'); }
	});
}
listegetir();
$("#searchper").prop("autocomplete", "off");
//
$('.chall').on('click',function(){  
	var cha=$( this ).prop('checked');
	for(var s=0;s<ksay;s++){
		$('#fxt_'+s).prop('checked', cha);
	}
	$('.chall').prop('checked', cha);
	if(cha){ $('#isl').attr('disabled', false); }else{ $('#isl').attr('disabled', true); }
});
var ksay=0;
$('#getfixtures').on('click', function(){ 
	var yol="/FXT/get_fixtures.php"; var satir='';
	var fkeys=['code','type','description','serialnumber','place','debitdate'];
	$.ajax({
		url: yol,
		type: "POST",
		datatype: 'json',
		async: false,
		data: { 'user': $('#username').val(), 'keys': fkeys },
		success: function(response){ console.log(response);
			if(response=='login'){ location.reload(); }
			objper=JSON.parse(response);
			$('#fxtlist tbody tr').remove();
			for(var i=0;i<objper.length;i++){
				satir='<tr id="s_'+i+'">'
				+'<td><input class="fxtcb" type="checkbox" name="fxt_'+i+'" id="fxt_'+i+'" value="'+objper[i]['code']+'" title="fxt_'+i+'" onChange="ched();"/>'
				+'</td><td>'+objper[i]['type']
				+'</td><td>'+objper[i]['code']
				+'</td><td>'+objper[i]['description']
				+'</td><td>'+objper[i]['serialnumber']
				+'</td><td>'+objper[i]['place']
				+'</td><td>'+objper[i]['debitdate']
				+'</td></tr>';
				$('#fxtlist tbody').append(satir);
				$("fxt_"+i).appendTo('#fs_form');
				ksay++;
			}			
		},
		error: function(response){ alert('Hata!'); }
	});	
});
function ched(){ $('#isl').attr('disabled', false); }
$('#close').on('click', function(){ 
	$('#fxtlist tbody tr').remove();
	$('#fixlistdiv').css("display", 'none'); 
	$('#searchper').val('').attr('placeholder', '<?php echo $gtext['search'];?>...'); 
});//*/
</script>

</body>

</html>