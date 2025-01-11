<?php
include("set_mng.php");
include("sess.php");
if($_SESSION['user']==""){
	//header('Location: login.php');
}
@$collection = $db->personel; //
	try{
		$cursor = $collection->aggregate([
			[
				'$match'=>[
					'username'=> $_SESSION['user']
				],
			],
			['$lookup'=>
				[
					'from'=>"departments",
					'localField'=>"department",
					'foreignField'=>"ou",
					'as'=>"deps"
				]
			],
			['$unwind'=>'$deps'],
			['$lookup'=>
				[
					'from'=>"departments",
					'localField'=>"company",
					'foreignField'=>"ou",
					'as'=>"comps"
				]
			],
			['$unwind'=>'$comps'],
			['$addFields'=> [
					'depname' => '$deps.description',
					'compname' => '$comps.description',
				],
			],
			['$sort' => [
					'givenname' => 1,
					'sn' => 1,
					'mail' => 1,
					'description' => 1,
					'title' => 1,
					'department' => 1,
					'company' => 1,
					'manager' => 1,
					'physicaldeliveryofficename' => 1,
					'mobile' => 1,
					'telephonenumber' => 1,
					'sdate' => 1,
					'streetaddress' => 1,
					'district' => 1,
					'st' => 1,
					'co' => 1,
					'distinguishedname' => 1,
				],
			],
		]);
		if(isset($cursor)){ 
			foreach($cursor as $formsatir){
				
			}
		}else{
			echo "Hata oluştu.";
		}
	}catch(Exception $e){
		echo 'Caught exception: ',  $e->getMessage(), "\n";
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

    <title><?php echo $gtext['profile'];/*Profile*/?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
 href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
   
	<!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="/vendor/jquery/jquery.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['profile']; ?></h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<div class="w-50">
							<form name="formp" method="POST" action="AD/set_user.php">
							<table class="table table-striped">
							<tr>
								<td><?php echo $gtext['username']; ?>:</td>
								<td>
									<?php echo $formsatir->username;  ?>						
									<input type="hidden" name="username" id="username" value="<?php echo $formsatir->username;  ?>">
									<input type="hidden" name="distinguishedname" id="distinguishedname" value="<?php echo $formsatir->distinguishedname;  ?>">
								</td>
							</tr>
							<tr>
								<td><?php echo $gtext['name']." ".$gtext['surname']; ?>:</td>
								<td><?php echo $formsatir->givenname." ".$formsatir->sn;  ?></td>
							</tr>
							<tr>
								<td><?php echo $gtext['pernumber']; ?>:</td>
								<td><?php echo $formsatir->description; ?></td>
							</tr>
							<tr>
								<td><?php echo $gtext['title']; ?>:</td>
								<td><?php echo $formsatir->title; ?></td>
							</tr>
							<tr>
								<td><?php echo $gtext['a_department']; ?>:</td>
								<td><?php echo $formsatir->depname; ?></td>
							</tr>
							<tr>
								<td><?php echo $gtext['percompany']; ?>:</td>
								<td><?php echo $formsatir->compname; ?></td>
							</tr>
							<tr>
								<td><?php echo $gtext['manager']; ?>:</td>
								<td><?php echo $formsatir->manager; ?></td>
							</tr>
							<tr>
								<td><?php echo $gtext['physicaldeliveryofficename']; ?>:</td>
								<td><?php echo $formsatir->physicaldeliveryofficename; ?></td>
							</tr>
							<tr>
								<td><?php echo $gtext['mobile']; ?>:</td>
								<td><?php if($ini['usercanedit']==1){ ?>
									<input class="form-control" type="text" name="mobile" id="mobile" value="<?php echo $formsatir->mobile;  ?>"/>
						<?php }else{ echo $formsatir->mobile; }?>
								</td>
							</tr>
							<tr>
								<td><?php echo $gtext['telephonenumber']; ?>:</td>
								<td><?php echo $formsatir->telephonenumber; ?></td>
							</tr>
							<tr>
								<td><?php echo $gtext['sdate']; ?>:</td>
								<td><?php echo date($ini['date_local'], strtotime($formsatir->sdate)); ?></td>
							</tr>
							<tr>
								<td><?php echo "<div>".$gtext['streetaddress'].":</div>"; ?></td>
								<td><?php if($ini['usercanedit']==1){ ?>
									<div><textarea class="form-control" name="streetaddress" id="streetaddress"><?php echo $formsatir->streetaddress; ?></textarea></div>
						<?php }else{ 
							echo "<div>".$formsatir->streetaddress."</div>"; }?>
								</td>
							</tr>
							<tr>
								<td><?php echo "<div>".$gtext['district'].":</div>"; 
								echo "<div>".$gtext['st'].":</div>"; 
								echo "<div>".$gtext['country'].":</div>"; ?></td>
								<td><?php if($ini['usercanedit']==1){ ?>
									<div><input class="form-control" type="text" name="district" id="district" value="<?php echo $formsatir->district; ?>"/></div>
									<div><input class="form-control" type="text" name="st" id="st" value="<?php echo $formsatir->st; ?>"/></div>
									<div><input class="form-control" type="text" name="co" id="co" value="<?php echo $formsatir->co; ?>"/></div>
						<?php }else{ 
							echo "<div>".$formsatir->district."</div>"; 
							echo "<div>".$formsatir->city."</div>"; 
							echo "<div>".$formsatir->co."</div>"; }?>
								</td>
							</tr><?php if($ini['usercanedit']==1){ ?>
							<tr>
								<td class="text-right" colspan="2">
									<button class="btn btn-primary upd" type="button" id="profile-update"><?php echo $gtext['profile']." ".$gtext['update'];?></button>
								</td>
							</tr><?php } ?>
							</table>
							</form>
						</div>
                    </div>
                    <!-- Content Row -->
                    <div class="row" id="passform">
						<div class="w-50">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel"><?php echo $gtext['chng_pass'];/*Şifre Değiştir*/?></h5>
							</div>
							<div class="modal-body">
								<input type="hidden" name="user" id="user" value="<?php echo $_SESSION['user']; ?>"/>
							   <table class="table table-striped">
								<tr>
									<td><?php echo $gtext['gpass'];/*Geçerli Şifre*/?></td>
									<td>
										<div class="input-group">
											<input class="form-control" type="password" name="gpass" id="gpass" value=""/>
											<button class="btn btn-outline-secondary sfrbtn" id="gpassbtn" ><i class="fas fa-eye"></i></button>
										</div>
									</td>
								</tr>
								<tr>
									<td><?php echo $gtext['pass'];/*Yeni Şifre*/?></td>
									<td>
										<div class="input-group">
											<input class="form-control" type="password" name="pass" id="pass" value=""/>
											<button class="btn btn-outline-secondary sfrbtn" id="passbtn" ><i class="fas fa-eye"></i></button>
										</div>
									</td>
								</tr>
								<tr>
									<td><?php echo $gtext['repass'];/*Yeni Şifre Tekrar*/?></td>
									<td>
										<div class="input-group">
											<input class="form-control" type="password" name="repass" id="repass" value=""/>
											<button class="btn btn-outline-secondary sfrbtn" id="repassbtn" ><i class="fas fa-eye"></i></button>
										</div>
									</td>
								</tr>
							   </table>
							</div>
							<div class="modal-footer">
								<button class="btn btn-primary" type="button" id="gonder"><?php echo $gtext['change'];/*Değiştir*/?></button>
								<button class="btn btn-secondary" type="button"><?php echo $gtext['clear'];/*Temizle*/?></button>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

<script>
	$(".sfrbtn").on('mousedown', function(){ console.log('sfrbtn');
		var et=$(this).attr('id'); et=et.replace('btn',''); 
		$('#'+et).attr('type', 'text');
	});
	$(".sfrbtn").on('mouseup', function(){
		var et=$(this).attr('id'); et=et.replace('btn',''); 
		$('#'+et).attr('type', 'password');
	});
	$('#gonder').on("click", function(){ //ekle/değiştir ajaxform
		$.ajax({
			type	: 'POST',
			url 	: '/admin/set_pss.php',
			target	: '#rt',
			data: { u: $('#user').val(), gpass: $('#gpass').val(), pass: $('#pass').val(), repass: $('#repass').val(), t:0 },
			beforeSend : function(){
				//kontroller yapılacak...
				if(($('#gpass').val()=='')||($('#pass').val()=='')||($('#repass').val()=='')){ 
					alert('<?php echo $gtext['u_fieldisnotblank'];?>');
					return false;
				}
				if($('#pass').val()!=$('#repass').val()){ 
					alert('<?php echo $gtext['u_passnotsame'];?>');
					return false;
				}
				var y=confirm('Emin Misiniz?');
			},
			success: function(data){ //console.log('Dönüş :'+data); 
				if(data.indexOf('!')>-1){ alert('<?php echo $gtext['u_error'];?>\n'+data); }
				//else { alert(data); location.reload(); }
			}
		}); 
	});
	$('.upd').on("click", function(){ 
		$.ajax({
			type	: 'POST',
			url 	: '/AD/set_user.php',
			target	: '#rt',
			data: { 'username': $('#username').val(), 'o_username': $('#username').val(), 'distinguishedname': $('#distinguishedname').val(), 'mobile': $('#mobile').val(), 'streetaddress': $('#streetaddress').html(), 'district': $('#district').val(), 'city': $('#city').val(), 'co': $('#co').val() },
			beforeSend : function(){
				var y=confirm('Emin Misiniz? ');
			},
			success: function(data){ //
			console.log('Dönüş :'+data); 
				if(data.indexOf('!')>-1){ alert('<?php echo $gtext['u_error'];?>\n'+data); }
				//else { alert(data); location.reload(); }
			}
		}); 
	});
</script>

</body>

</html>