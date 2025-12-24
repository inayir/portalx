        <ul class="navbar-nav bg-set sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/index.php">
                <div><img class="w-100" src="<?php echo @$ini['logo']; ?>" class="logo-dark" alt="logo" width="200"></div>
            </a>
             <div class="sidebar-brand-text text-center mx-2 text-white"><?php echo @$ini['title']; ?></div>
            <!-- Divider -->
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
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
					<i class="fas fa-thin fa-address-card"></i>
                    <span><?php echo $gtext['sb_guides'];/*Rehberler*/?></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
						<a class="dropdown-item" href="/Corporate/phone_book.php"><i class="fas fa-phone-square"></i> <?php echo $gtext['sb_phonebook'];/*Telefon Rehberi*/?></a>
					</li>
                    <li>
						<a class="dropdown-item" href="/Corporate/pservis.php"><i class="fas fa-fw fa-route"></i> <?php echo $gtext['shuttleroutes'];/*Servis Güzergahları*/?></a>
					</li>
                    <li>
						<a class="dropdown-item" href="/Corporate/ymenu.php"><i class="fas fa-fw fa-utensils"></i> <?php echo $gtext['sb_mealmenu'];/*Yemek Menüsü*/?></a>
					</li>
					<?php if(@$ini['Org_Sema']==1){ ?>
					<li>
						<a class="dropdown-item" href="/Corporate/org_sema.php"><i class="fas fa-fw fa-sitemap"></i> <?php echo $gtext['orgscheme'];/*Organizasyon Şeması*/?></a>
					</li><?php } ?>
				</ul>
            </li><?php if(@$ini['personel_pano']==1){ ?>
            <!-- Nav Item - Pano -->
            <li class="nav-item">
                <a class="nav-link" href="/pano.php"><i class="fas fa-fw fa-chalkboard"></i><span><?php echo $gtext['pano'];/*İletişim Panosu*/?></span></a>
            </li>
			<?php } ?>
			<?php if(@$_SESSION['y_admin']==1||@$ini['Fixtures']==1&&@$_SESSION['y_fixtures']==1){ ?>
            <!-- Nav Item - Fixture dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
					<i class="fas fa-solid fa-couch"></i>
                    <span><?php echo $gtext['fixtures'];//Demirbaşlar?></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
						<a class="dropdown-item" href="/FXT/"><i class="fas fa-solid fa-couch"></i> <?php echo $gtext['fixtures'];//Demirbaşlar?></a>
					</li>
                    <li>
						<a class="dropdown-item" href="/FXT/Fixture_actions.php"><i class="fas fa-list"></i> <?php echo $gtext['actions'];/*Hareketler*/?></a>
					</li>
                    <li>
						<a class="dropdown-item" href="/FXT/Fixture_assign.php"><i class="fas fa-pen"></i> <?php echo $gtext['fixtassigndoc'];/*Devir Tutanağı*/?></a>
					</li><?php if(@$_SESSION['y_admin']==1){ ?>
					<li><a class="dropdown-item" href="/FXT/Fixture_types.php">
							<i class="fas fa-fw fa-building"></i><span><?php echo $gtext['fixture']." ".$gtext['types'];/*Demirbaş Tipleri*/?></span>
						</a>
					</li><?php } ?>
				</ul>
            </li>
			<?php } ?>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                <?php echo $gtext['docs'];/*Belgeler*/?>
            </div>
            <!-- Nav Item - Pages Collapse Menu -->
		  <?php if(@$_SESSION['y_admin']==1||@$ini['Sertifikalar']==1||@$ini['Kalifikasyonlar']==1){ ?>
			<li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fas fa-fw fa-certificate"></i>
                    <span><?php echo $gtext['sb_qdocs'];/*Kalite Belgeleri*/?></span>
                </a>
                <ul class="dropdown-menu"><?php if(@$ini['Sertifikalar']==1){ ?>
                    <li>
						<a class="dropdown-item" href="/Corporate/b_all.php?tip=b_certs"><?php echo $gtext['certs'];/*01 Sertifikalar*/?></a>
					</li><?php } 
					if(@$ini['Kalifikasyonlar']==1){ ?>
                    <li>
						<a class="dropdown-item" href="/Corporate/b_all.php?tip=b_quals"><?php echo $gtext['quals'];/*02 Kalifikasyonlar*/?></a>
					</li><?php } ?>
                </ul>
            </li><?php }
		    if(@$ini['Formlar']==1){ ?>
			<!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="/forms/form_list.php">
                    <i class="fas fa-fw fa-folder"></i>
                    <span><?php echo $gtext['forms'];/*Yönetsel Formlar*/?></span>
                </a>
            </li><?php }  
//Yönetim
if(@$_SESSION['y_admin']==1||@$_SESSION['y_ayar01']==1||@$_SESSION['y_addinfoduyuru']==1||@$_SESSION['y_addinfohaber']==1||@$_SESSION['y_addinfoser']==1||@$_SESSION['y_addinfomenu']==1){ ?>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
			<div class="sidebar-heading">
                <?php echo $gtext['sb_siteman'];/*Site Yönetimi*/?>
            </div><?php
			if($_SESSION['y_ayar01']==1||$_SESSION['y_addinfoduyuru']==1||$_SESSION['y_addinfohaber']==1||$_SESSION['y_addinfoser']==1||$_SESSION['y_addinfomenu']==1||$_SESSION['y_admin']==1){ ?>		
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fas fa-fw fa-pen-fancy"></i>
                    <span><?php echo $gtext['sb_datafeed'];/*Bilgi Girişi*/?></span>
                </a>
                <ul class="dropdown-menu"><?php 
					if($_SESSION['y_addinfohaber']==1){ ?>
						<!-- y_addinfohaber -->
					<li><a class="dropdown-item" href="/Corporate/adm_dhm.php?dh=H">
							<i class="fas fa-fw fa-paper-plane"></i>
							<span><?php echo $gtext['news'];/*Haberler*/?></span>
						</a></li><?php 
					} 
					if($_SESSION['y_addinfoduyuru']==1){ ?>
						<!-- y_addinfoduyuru -->
						<li><a class="dropdown-item" href="/Corporate/adm_dhm.php?dh=D">
							<i class="fas fa-fw fa-bullhorn"></i>
							<span><?php echo $gtext['announcements'];/*Duyurular*/?></span>
						</a></li><?php 
					}
					if($_SESSION['y_bo']==1){ ?>
						<!-- y_bo Kurumsal-->
						<li><a class="dropdown-item" href="/Corporate/adm_org_sema.php">
							<i class="fas fa-fw fa-route"></i>
							<span><?php echo $gtext['orgschemes'];/*Organizasyon Şemaları*/?></span>
						</a></li><?php 
					} 
					if(@$_SESSION['y_rcall']==1){ ?>						
                        <li><a class="dropdown-item" href="/Corporate/Phone_add.php">
							<i class="fas fa-fw fa-phone"></i>
							<span><?php echo $gtext['speed_dials'];/*Hızlı Aramalar*/?></span>
						</a></li><?php } ?>
                </ul>
            </li><?php } 
		if($_SESSION['y_admin']==1){ ?>
			<li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fas fa-fw fa-cog"></i>
                    <span><?php echo $gtext['settings'];/*Ayarlar*/?> </span>
                </a>
                <ul class="dropdown-menu">
					<!-- Portal Ayarları -->
					<li><a class="dropdown-item" href="/admin/Settings.php">
						<i class="fas fa-fw fa-cog"></i>
						<span><?php echo $gtext['s_psettings'];/*Portal Ayarları*/?></span>
					</a></li>
					<li><a class="dropdown-item" href="/admin/Personels.php">
						<i class="fas fa-fw fa-user"></i>
						<span><?php echo $gtext['users'];/*Kullanıcılar*/?></span>
					</a></li>
					<li><a class="dropdown-item" href="/admin/Personel_actions.php">
						<i class="fas fa-fw fa-house-user"></i>
						<span><?php echo $gtext['user']." ".$gtext['actions'];/*Personel Hareketler*/?></span>
					</a></li>
					<li><a class="dropdown-item" href="/Corporate/Departments.php">
						<i class="fas fa-fw fa-building"></i>
						<span><?php echo $gtext['a_department']." ".$gtext['processes'];/*Birim İşlemleri*/?></span>
					</a></li>
					<li><a class="dropdown-item" href="/Corporate/ManagerList.php">
						<i class="fas fa-fw fa-user-tie"></i>
						<span><?php echo $gtext['a_managerlist'];/*Yönetici Listesi*/?></span>
					</a></li>
					<li><a class="dropdown-item" href="/Corporate/Phone_add.php">
						<i class="fas fa-fw fa-phone"></i>
						<span><?php echo $gtext['speed_dials'];/*Hızlı Aramalar*/?></span>
					</a></li>
                        <li><a class="dropdown-item" href="/Corporate/Places.php">
							<i class="fas fa-fw fa-place-of-worship"></i>
							<span><?php echo $gtext['places'];/*Yerler*/?></span>
						</a></li><?php	
					if(@$ini['usersource']=='LDAP'){ ?>
						<li><a class="dropdown-item" href="/AD/Sync_AD.php">
							<i class="fas fa-fw fa-users"></i>
							<span><?php echo "LDAP/AD ".$gtext['pairing'];/*Eşleştirme*/?></span>
						</a></li>
					<?php }	?>
				</ul>
			</li><?php
		}
} ?></ul>
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded border-0" id="sidebarToggle"><i class="fas fa-arrow-left"></i></button>
            </div>