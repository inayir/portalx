<?php
/*
	Translating lang1 -> lang2  
*/
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot."/config/config.php");
//if($user==""){ header('Location: /login.php');}
//
$lang=explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
$dila=explode('-', $lang[0]); 
$dil=$dila[1];
//
@$lang=$_POST['lang'];
if($lang==""){ @$lang=$_GET['lang']; }
@$tolang=$_POST['tolang'];
if($tolang==""){ $tolang=$_GET['tolang']; }
if($tolang==""){ $tolang=$dil; }
//
if($lang==''){ $lang='EN'; }
//if($tolang==''){ $tolang='EN'; }
//
$langfile=$docroot.'/lang/'.$lang.'.php';
include($langfile);
$text=$gtext;
$tolangfile=$docroot.'/lang/'.$tolang.'.php';
if(file_exists($tolangfile)){ include($tolangfile);	}
//
if(isset($_POST['savebtn'])){ 
	$s="<?php\n/*PortalX ".$tolang." translate*/\n"; 
	touch($tolangfile);
	$tolangfil = fopen($tolangfile, 'w');
	$posts=$_POST;
	$keyso=array_keys($posts); 
	$valso=array_values($posts); 
	for($p=0;$p<count($keyso);$p++){
		if($keyso[$p]=='lang'||$keyso[$p]=='tolang'||$keyso[$p]=='savebtn'){
		}else{
			$s.="$"."gtext['".$keyso[$p]."']='".$valso[$p]."'; \n";
		}
	}
	if(fwrite($tolangfil, $s) == FALSE){ echo "Can NOT Save!"; }else{ echo "Saved.";  }
	fclose($tolangfil); 	
	exit;
}
//
$from=array_values($text);
$fromkeys=array_keys($text);
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>PortalX Translate</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"> 
	<!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

<?php include($docroot."/set_page.php"); ?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar --><?php 
		include($docroot."/sidebar.php"); ?><!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php //include($docroot."/topbar.php"); ?><!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Translate</h1>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 mb-4">
							<form name="form1" id="form1" method="POST" action="translate.php">
							<input type="hidden" name="lang" id="lang" value="<?php echo $lang; ?>">
							<input type="hidden" name="tolang" id="tolang" value="<?php echo $tolang; ?>">
							<div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="translate_tbl" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="50%">Key</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Key</th>
                                            <th>Value</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
									<tr>
										<td class="text-center"><b><?php echo $lang; ?></b></td>
										<td class="text-center"><b><?php echo $tolang; ?></b>
											<div class="text-center"><b>Language Direction</b>
												<SELECT name="textdir">
												<OPTION value="ltr" <?php if($gtext['textdir']=='ltr'){ echo "selected";}?> >Left-To-Right</OPTION>
												<OPTION value="rtl" <?php if($gtext['textdir']=='rtl'){ echo "selected";}?> >Right-To-Left</OPTION>
												</SELECT>
											</div>
										</td>
									</tr>
									<?php for($i=1; $i<count($from); $i++){ 
									$fk=$fromkeys[$i];
									$toval=$gtext[$fk]; ?>
									<tr>
										<td><?php echo $from[$i]; ?></td>
										<td><input class="form-control form-input-sm<?php if($toval==$from[$i]){ echo " text-danger"; }?>" type="text" name="<?php echo $fromkeys[$i]; ?>" id="<?php echo $fromkeys[$i]; ?>" value="<?php echo $toval; ?>"/></td>
									</tr>
									<?php } ?></tbody>
                                </table>                 
							</div>
							<div class="text-center">
								<button name="savebtn" id="savebtn" class="btn btn-primary" type="submit">Save</button>
							</div>
							</form>
                        </div>						
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
    <!-- Custom scripts for all pages-->
	<script src="/js/sb-admin-2.js"></script>

<script>
$(document).ready(function() {
	$('#savebtn').on("click", function(){ 
		var opt={
			type	: 'POST',
			url 	: 'translate.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
				var y=confirm('Are you sure?');
				return y;
			},
			success: function(data){  //console.log(data);
				if(data.indexOf('!')>-1){ alert('Error:Something went wrong!'); }
				else { alert(data); location.reload(); }
			}
		}
		$('#form1').ajaxForm(opt); 
	});
});
$('form').find(':input').change(function(){ $('#savebtn').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#savebtn').prop("disabled", true ); });
</script>
</body>

</html>