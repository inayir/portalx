<?php
/*
	For a new page
*/
include("../set_mng.php");  //for Mongo DB connection, if needed. If not; write first line: include('/get_ini.php');
//error_reporting(0);
include($docroot."/app/php_functions.php");
include($docroot."/sess.php");
if($user==""){ //if auth pages needed...
	header('Location: /login.php');
}
$lastbackup="-"; 
//Backup
@$xdir=$_POST['xdir'];
if($xdir==""){ $xdir="C:\YourBackupPath"; }
else{ 
	if(str_ends_with($xdir, "\\")||str_ends_with($xdir, "/")){ $xdir=substr($xdir, 0, -1); }
	//if(str_ends_with($xdir, chr(057))||str_ends_with($xdir, chr(134))){ $xdir=substr($xdir, 0, -1); }
	$files1 = scandir($xdir);
	$f=0; $files=[];
	while($f<count($files1)){
		if($files1[$f]!="."||$files1[$f]!=".."){
			$files[$f]['name']=$files1[$f];
			$files[$f]['fileatime']=date("Y-m-d H:i:s.", fileatime($xdir."\\".$files1[$f]));
			$f++;
		}
	}
	//sırala
	$filess = xarray_sort($files, 'fileatime', SORT_ASC);
	for($i=0;$i<count($filess);$i++){
		$lastbackup=$filess[$i]['fileatime'];
	}
}
$msg="";
//
if(isset($_POST['backup'])&&@$_POST['xdir']!=''){ $msg=$gtext['done'];
	//xdir yoksa...
	$comm=$docroot."\mongodump.exe /uri ".$ini['MongoConnection']." /db ".$ini['MongoDB']." /o ".$xdir." ";
	$ret=exec($comm, $output, $retval);
	print_r($output);
	if($ret===false){ $msg=$gtext['nothingdone']."!"; }
}
if(isset($_POST['restore'])&&@$_POST['xdir']!=''){ $msg=$gtext['done'];
	$comm=$docroot."\mongorestore.exe /uri ".$ini['MongoConnection']." /db ".$ini['MongoDB']." /verbose ".$xdir." ";
	$ret=exec($comm, $output, $retval);
	print_r($output);
	if($ret===false){ $msg=$gtext['nothingdone']."!"; }
}
?>
<!DOCTYPE html>
<html lang="en">
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
	<!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
	<link href="/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
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
                    <div class="d-sm-flex justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['s_database']." ".$gtext['backup']."/".$gtext['restore'];/*Database Backup-Restore*/?></h1>
                        <!--a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a-->
                    </div>

                    <!-- Content Row -->
                    <div class="row">
					  <form name="formb" method="POST" action="">
						<table class="table table-striped">
						<tr>
							<th colspan="2"><?php echo $gtext['parameters'];/*Parametreler*/?></th>
						</tr>
						<tr>
							<td class=""><?php echo $gtext['s_database'];/*Veritabanı*/?>: </td><td><?php echo $ini['MongoDB'];?></td>
						</tr>
						<tr>
							<td class="align-middle"><?php echo $gtext['directory'];/*Klasör*/?>:</td>
							<td><input class="form-control" type="text" name="xdir" id="xdir" value="<?php echo $xdir;?>" placeholder="Write path as C:\YourDrive"/></td>
						</tr>
						<tr>
							<td class="align-middle"><?php echo $gtext['lastbackup'];/*Son Yedek Zamanı*/?>:</td>
							<td><p><?php echo $lastbackup; ?></p></td>
						</tr>
						<tr>
							<td colspan="2">
								<div class="d-flex justify-content-center">
									<button class="btn btn-primary" type="submit" name="backup"><?php echo $gtext['backup'];/*Yedekle*/?></button>&nbsp;&nbsp;
									<button class="btn btn-secondary" type="submit" name="restore"><?php echo $gtext['restore'];/*Yedekten Geri Al*/?></button>
								</div>
							</td>
						</tr>
						</table>
					  </form>
					</div>
                    <!-- Content Row -->
                    <div class="row">
					<?php echo $msg; ?>
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

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.js"></script>

<script>
function getDir(el) {
	var fileselector = document.getElementById('backupdir');
	console.log('fs:'+fileselector.value);
}
</script>
</body>

</html>