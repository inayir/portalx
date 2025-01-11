        <ul class="navbar-nav bg-set sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/index.php">
                <div><img src="<?php echo @$ini['logo']; ?>" class="logo-dark" alt="logo" width="200"></div>
            </a>
             <div class="sidebar-brand-text text-center mx-2 text-white"><?php echo @$ini['title']; ?></div>

            <!-- Divider -->
            <!--hr class="sidebar-divider my-0"-->

            <!-- Nav Item - Anasayfa -->
            <li class="nav-item active">
                <a class="nav-link" href="/index.php">
                    <i class="fas fa-fw fa-home"></i>
                    <span><?php echo $gtext['sb_mainpage'];/*Anasayfa*/?></span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                <?php echo $gtext['organizational'];/*Kurumsal*/?>
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-faucet"></i>
                    <span><?php echo $gtext['sb_guides'];/*Rehberler*/?></span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
					<?php if(@$ini['Org_Sema']==1){ ?>
                        <a class="collapse-item" href="/Corporate/org_sema.php"><i class="fas fa-fw fa-sitemap"></i> <?php echo $gtext['orgscheme'];/*Organizasyon Şeması*/?></a>
					<?php } ?>
                        <a class="collapse-item" href="/Corporate/phone_book.php"><i class="fas fa-phone-square"></i> <?php echo $gtext['sb_phonebook'];/*Telefon Rehberi*/?></a>
                        <a class="collapse-item" href="/Corporate/pservis.php"><i class="fas fa-fw fa-route"></i> <?php echo $gtext['shuttleroutes'];/*Servis Güzergahları*/?></a>
                        <a class="collapse-item" href="/Corporate/ymenu.php"><i class="fas fa-fw fa-utensils"></i> <?php echo $gtext['sb_mealmenu'];/*Yemek Menüsü*/?></a>
                    </div>
                </div>
            </li><?php if(@$ini['personel_pano']==1){ ?>
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="/pano.php">
                    <i class="fas fa-fw fa-chalkboard"></i>
                    <span><?php echo $gtext['pano'];/*İletişim Panosu*/?></span>
                </a>
            </li>
			<?php } ?>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                <?php echo $gtext['docs'];/*Belgeler*/?>
            </div><?php
if($_SERVER['REMOTE_ADDR']=="..1"||$_SERVER['REMOTE_ADDR']=="127.0.0.1"){ ?>
            <!-- Nav Item - Pages Collapse Menu -->
			<li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse3"
                    aria-expanded="true" aria-controls="collapse3">
                    <i class="fas fa-fw fa-certificate"></i>
                    <span><?php echo $gtext['sb_qdocs'];/*Kalite Belgeleri*/?></span>
                </a>
                <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
					<?php if(@$ini['Sertifikalar']==1){ ?>
                        <a class="collapse-item" href="/Corporate/b_all.php?tip=b_certs"><?php echo $gtext['certs'];/*01 Sertifikalar*/?></a>
					<?php } 
					if(@$ini['Kalifikasyonlar']==1){ ?>
                        <a class="collapse-item" href="/Corporate/b_all.php?tip=b_quals"><?php echo $gtext['quals'];/*02 Kalifikasyonlar*/?></a>
					<?php } ?>
                    </div>
                </div>
            </li>
			<?php if(@$ini['Formlar']==1){ ?>
			<!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="/forms/form_list.php">
                    <i class="fas fa-fw fa-folder"></i>
                    <span><?php echo $gtext['forms'];/*Yönetsel Formlar*/?></span>
                </a>
            </li>
			<?php } ?>
<?php }  
//Yönetim
if(@$_SESSION['y_admin']==1||@$_SESSION['y_ayar01']==1||@$_SESSION['y_addinfoduyuru']==1||@$_SESSION['y_addhaber']==1||@$_SESSION['y_addinfoser']==1||@$_SESSION['y_addinfomenu']==1){ ?>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
			<div class="sidebar-heading">
                <?php echo $gtext['sb_siteman'];/*Site Yönetimi*/?>
            </div><?php
		if($_SESSION['y_ayar01']==1||$_SESSION['y_addinfoduyuru']==1||$_SESSION['y_addhaber']==1||$_SESSION['y_addinfoser']==1||$_SESSION['y_addinfomenu']==1||$_SESSION['y_admin']==1){ ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
                    aria-expanded="true" aria-controls="collapseThree">
                    <i class="fas fa-fw fa-cog"></i>
                    <span><?php echo $gtext['sb_datafeed'];/*Bilgi Girişi*/?></span>
                </a>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded"><?php 
					if($_SESSION['y_addinfohaber']==1){ ?>
						<!-- y_addinfohaber -->
						<a class="collapse-item" href="/Corporate/adm_dhm.php?dh=H">
							<i class="fas fa-fw fa-paper-plane"></i>
							<span><?php echo $gtext['news'];/*Haberler*/?></span>
						</a><?php 
					} 
					if($_SESSION['y_addinfoduyuru']==1){ ?>
						<!-- y_addinfoduyuru -->
						<a class="collapse-item" href="/Corporate/adm_dhm.php?dh=D">
							<i class="fas fa-fw fa-bullhorn"></i>
							<span><?php echo $gtext['announcements'];/*Duyurular*/?></span>
						</a>
						<!-- y_addinfoduyuru K -->
						<a class="collapse-item" href="/Corporate/adm_dhm.php?dh=K">
							<i class="fas fa-fw fa-bullhorn"></i>
							<span><?php echo $gtext['organizational']." ".$gtext['announcements'];/*Kurumsal Duyurular*/?></span>
						</a><?php 
					}
					if($_SESSION['y_bo']==1){ ?>
						<!-- y_bo Kurumsal-->
						<a class="collapse-item" href="/Corporate/adm_org_sema.php">
							<i class="fas fa-fw fa-route"></i>
							<span><?php echo $gtext['orgschemes'];/*Organizasyon Şemaları*/?></span>
						</a><?php 
					} 
					if($_SESSION['y_rcall']==1){ ?>						
                        <a class="collapse-item" href="/Corporate/Phone_add.php">
							<i class="fas fa-fw fa-phone"></i>
							<span><?php echo $gtext['speed_dials'];/*Hızlı Aramalar*/?></span>
					</a><?php } ?>
                    </div>
                </div>
            </li><?php } 
		if($_SESSION['y_admin']==1){ ?>
			<li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseYon"
                    aria-expanded="true" aria-controls="collapseYon">
                    <i class="fas fa-fw fa-cog"></i>
                    <span><?php echo $gtext['settings'];/*Ayarlar*/?></span>
                </a>
                <div id="collapseYon" class="collapse" aria-labelledby="headingYon" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
						<!-- Portal Ayarları -->
                        <a class="collapse-item" href="/admin/Settings.php">
							<i class="fas fa-fw fa-cog"></i>
							<span><?php echo $gtext['s_psettings'];/*Portal Ayarları*/?></span>
						</a>
						<!-- yetkiler -->
                        <a class="collapse-item" href="/admin/Personels.php">
							<i class="fas fa-fw fa-user"></i>
							<span><?php echo $gtext['users'];/*Kullanıcılar*/?></span>
						</a>
                        <a class="collapse-item" href="/Corporate/Departments.php">
							<i class="fas fa-fw fa-building"></i>
							<span><?php echo $gtext['a_department']." ".$gtext['processes'];/*Birim İşlemleri*/?></span>
						</a>
                        <a class="collapse-item" href="/Corporate/ManagerList.php">
							<i class="fas fa-fw fa-user-tie"></i>
							<span><?php echo $gtext['a_managerlist'];/*Yönetici Listesi*/?></span>
						</a><?php	
					if(@$ini['usersource']=='LDAP'){ ?>
						<a class="collapse-item" href="/AD/Sync_AD.php">
							<i class="fas fa-fw fa-users"></i>
							<span><?php echo "*LDAP ".$gtext['pairing'];/*Eşleştirme*/?></span>
						</a>
					<?php }	?>
                    </div>
				</div>
			</li><?php
		}
} ?></ul>
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>