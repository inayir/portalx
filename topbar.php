                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>	
					
					<!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Telefon Rehberi -->                        
						<a class="nav-link d-flex align-items-center" href="/Corporate/phone_book.php" id="telrehber" role="button" target="_tab">
							<h1 class="h1 mb-0 text-gray-800"><i class="fas fa-phone-square"></i></h1>	
						</a>
						<div class="align-middle">
						<form name="langform" id="langform" method="POST" action="#"><br>
						<SELECT class="form-input-sm" name="langs" id="langs"><?php 
			$dir    = $docroot.'\lang';
			$files1 = scandir($dir);
			$f=0;
			while($f<count($files1)){
				if($files1[$f]=='index.php'||$files1[$f]=='translate.php'||$files1[$f]=='.'||$files1[$f]=='..'){ $f++; }
				else{ 
					$flang=substr($files1[$f],0,strpos($files1[$f],'.php')); $f++; 
					echo "<option value=".$flang." ";
					if($_SESSION['lang']==$flang){ echo "selected"; }
					echo ">".$flang."</option>";
				}
			} 
			@$avtr=@$_SESSION['picture'];
			if($avtr==''){ $avtr="/img/undraw_profile.svg"; } ?>
						</SELECT>
                        </form>
						</div>
						<div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown"><?php if(@$_SESSION['user']!=''){ ?>
							<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600"><?php echo @$_SESSION['name']; ?></span>
                                <img class="img-profile rounded-circle" src="<?php echo $avtr; ?>">
                            </a>
							<!-- Dropdown - User Information -->
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="/Profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    <?php echo $gtext['profile'];/*Profil*/?>
                                </a></li><?php if(@$ini['Fixtures']==1){ ?>
								<li><a class="dropdown-item" href="/FXT/myfixtures.php" target="_tab">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    <?php echo $gtext['myfixtures'];/*Demirbaşlarım*/?>
                                </a></li><?php } ?>
                                <li><a class="dropdown-item" href="/Profile.php#passform">
                                    <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                                    <?php echo $gtext['chng_pass'];/*Şifre Değiştir*/?>
                                </a></li>
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item" href="#logoutModal" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    <span class="d-none d-lg-inline" id="myInput"><?php echo $gtext['logout'];/*Logout*/?></span>
                                </a></li>
                            </ul><?php }else{ ?>
							<br><a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm align-middle" href="/login.php">
								<i class="fas fa-download fa-sm text-white-50"></i> <?php echo $gtext['login']; /*Giriş*/?></a>
							<?php } ?>
						</li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
				<!-- logout_modal-->
				<div class="modal fade" id="logoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
					aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel"><?php echo $gtext['q_logout'];/*Çıkmak İstiyor musunuz?*/?></h5>
								<button class="close" type="button" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<!--div class="modal-body">Select "Logout" below if you are ready to end your current session.</div-->
							<div class="modal-footer">
								<a class="btn btn-primary" href="/logout.php"><?php echo $gtext['yes'];/*Evet*/?></a>
								<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php echo $gtext['cancel']; ?></button>
							</div>
						</div>
					</div>
				</div>
			<!--logout_modal sonu-->
			<script src="/vendor/form-master/dist/jquery.form.min.js"></script>
			<script>
			const myModal = document.getElementById('logoutModal')
			const myInput = document.getElementById('myInput')

			myModal.addEventListener('shown.bs.modal', () => {
			  myInput.focus()
			})
				$('#langs').on('change', function(){ 
					$('#langform').submit();
				});
			</script>