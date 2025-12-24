<?php
include('../set_mng.php');
error_reporting(0);
include($docroot."/sess.php");
require($docroot."/ldap.php");
if($user==""){
	header('Location: \login.php');
}
@$username=$_GET['u']; 
$ksay=0; 
$csatir=Array(); 
/*if($ini['usersource']=='LDAP'){ 
	//get ous
	$liste=Array("ou", "description");
	$filter="ou=*";
	$sr=ldap_list($conn, $ini['base_dn'], $filter, $liste); 
	$info=ldap_get_entries($conn, $sr); 	
	for ($ii=0; $ii < $info["count"]; $ii++){
		$csatir[$ii]['ou']=$info[$ii]["ou"][0];
		$csatir[$ii]['description']=$info[$ii]["description"][0];
		$ksay++;
	}
}else{ //*/
	@$collection = $db->departments;
	@$cursor = $collection->find(
		[
			'$and'=>[['dp' => ['$eq'=>'C']],['state'=>'A']]
		],
		[
			'limit' => 0,
			'projection' => [
				'ou' => 1,
				'company' => 1,
				'description' => 1,
			],
			'sort'=>['description'=>1],
		]
	);
	if(isset($cursor)){	
		foreach ($cursor as $formsatir) {
			//echo $formsatir->ou." ".$formsatir->description."<br>";
			$satir=[];
			$satir['ou']=$formsatir->ou;
			$satir['company']=$formsatir->company;
			$satir['description']=$formsatir->description;
			$csatir[]=$satir;
			$ksay++;
		} //*/	
	}
//}
?>
<!DOCTYPE html>
<html lang="<?php echo $dil;?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $gtext['user'];?></title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
	<link href="../vendor/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">    
	<!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
	<script src="/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.js"></script>
    <script src="ad_functions.js"></script>
	<script src="/js/portal_functions.js"></script>
<?php include($docroot."/set_page.php"); ?>

</head>
<body id="page-top">
<script>
var dis='';
</script>
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['user']." ".$gtext['insert']."/".$gtext['change'];/*Kullanıcı Ekle/Değiştir*/?></h1>
                        <!--a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a-->
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                    <div class="table-responsive">
					  <form id="myForm" action="user_save.php" method="POST">
						<table class="table-striped w-100">
						<tr class="" style="height: 40px;">
							<td colspan="4" class="text-center"><b><?php echo $ini['domain']." ".$gtext['user']." ".$gtext['record'];/*Kullanıcı Kaydı*/?></b></td>
						</tr>
						<tr>
							<td class="w-25 text-right m-1"><?php echo $gtext['username'];/*Username*/?>(*): </td>
							<td class="w-25">
								<div class="m-1 input-group">
									<input class="form-control" type="text" name="username" id="username" value="<?php echo $username; ?>" size="22" <?php if($username!=""){ echo "readonly"; } ?>/>
									<input type="hidden" name="o_username" id="o_username" value="<?php echo $username; ?>"/>
									<input type="hidden" name="distinguishedname" id="distinguishedname" value=""/><?php if($username==""){ ?>
									<button class="form-control" type="button" id="usrkont"><?php echo $gtext['short_control'];/*Kont*/?></button><?php } ?>
								</div>
							</td>
							<td class="w-25 text-right m-1"><?php echo $gtext['s_personeltype'];/*Personel Tipi*/?>: </td>
							<td class="w-25">
								<div class="m-1">
									<SELECT class="form-control input-sm" name="ptype" id="ptype">
									<OPTION value="P" selected><?php echo $gtext['s_personel'];/*Personel*/?></OPTION>
									<OPTION value="C"><?php echo $gtext['s_consultant'];/*Danışman*/?></OPTION>
									<OPTION value="I"><?php echo $gtext['s_intern'];/*Stajyer*/?></OPTION>
									<OPTION value="O"><?php echo $gtext['s_other'];/*Diğer*/?></OPTION>
									</SELECT>
								</div>
							</td>
						</tr>
						<tr>
							<td class="w-25 text-right m-1"><?php echo $gtext['name'];/*Adı*/?>(*): </td>
							<td class="w-25">
								<div class="m-1">
									<input class="form-control" type="text" name="givenname" id="givenname" value="" size="30"/>
									<input type="hidden" name="o_givenname" id="o_givenname" value=""/>
								</div>
							</td>
							<td class="w-25 text-right m-1"><?php echo $gtext['surname'];/*Soyadı*/?>(*): </td>
							<td>
								<div  class="m-1">
									<input class="form-control" type="text" name="sn" id="sn" value="" size="30"/>
									<input type="hidden" name="o_sn" id="o_sn" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="w-25 text-right m-1">E-Mail: </td>
							<td>
								<div  class="m-1">
									<input class="form-control" type="text" name="mail" id="mail" value="" size="30" <?php if($username!=""){ echo "readonly"; } ?>/>
									<input type="hidden" name="o_mail" id="o_mail" value=""/>
								</div>
							</td>
							<td class="w-25 text-right m-1"><?php echo $gtext['pernumber'];/*Sicil*/?>:</td>
							<td>
								<div  class="m-1">
									<input class="form-control" type="text" name="description" id="description" value="" size="30"/>
									<input type="hidden" name="o_description" id="o_description" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="w-25 text-right m-1"><?php echo $gtext['pertitle'];/*Unvanı*/?>: </td>
							<td>
								<div  class="m-1">
									<input class="form-control" type="text" name="title" id="title" value="" size="30"/>
									<input type="hidden" name="o_title" id="o_title" value=""/>
								</div>
							</td>
							<td class="w-25 text-right m-1"><?php echo $gtext['manager'];/*Yönetici*/?>: </td>
							<td>
								<div class="m-1">
									<div id="dmanager"></div>
									<input type="hidden" name="manager" id="manager" value="...."/>
									<input type="hidden" name="o_manager" id="o_manager" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="w-25 text-right m-1"><?php echo $gtext['percompany'];/*Company*/?>(*): </td>
							<td class="w-25">
								<div class="m-1">
									<SELECT class="form-control" type="text" name="company" id="company" <?php if($username!=""){echo 'disabled="disabled"';  } ?>>
	<?php					for ($i=0; $i < $ksay; $i++){
								$a=$csatir[$i]["ou"];
								if($a[0]!="_"&&$csatir[$i]["description"]!=""){ 
									echo "<option value='".$a."'>";
									echo $csatir[$i]["description"]."</option>\n"; 
								}
							} ?>	
									</SELECT>
									<input type="hidden" name="o_company" id="o_company" value=""/>
								</div>
							</td>
							<td class="w-25 text-right m-1"><?php echo $gtext['a_department'];/*Department*/?>(*): </td>
							<td class="w-25">
								<div class="m-1">
									<SELECT class="form-control" name="department" id="department" width="100" <?php if($username!=""){echo 'disabled="disabled"';  } ?>/>
										<OPTION value=".">.</OPTION>
									</SELECT>								
									<input type="hidden" name="o_department" id="o_department" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="w-25 text-right m-1"><?php echo $gtext['telephonenumber'];/*Dahili Telefon*/?>: </td>
							<td class="w-25">
								<div class="m-1">
									<input class="form-control" type="text" name="telephonenumber" id="telephonenumber" value="" size="30"/>
									<input type="hidden" name="o_telephonenumber" id="o_telephonenumber" value=""/>
									<input class="form-control" type="text" name="otherTelephone" id="otherTelephone" value="" size="30" style="display:none" />
								</div>
							</td>
							<td class="w-25 text-right m-1"><?php echo $gtext['mobile'];/*Telefon (GSM)*/?>(*): </td>
							<td class="w-25">
								<div class="m-1">
									<input class="form-control" type="text" name="mobile" id="mobile" value="" size="30"/>
									<input type="hidden" name="o_mobile" id="o_mobile" value=""/>
									<input class="form-control" type="text" name="otherMobile" id="otherMobile" value="" size="30" style="display:none" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="w-25 text-right m-1"><?php echo $gtext['physicaldeliveryofficename'];/*Ofis*/?>: </td>
							<td class="w-25">
								<div class="m-1">
									<input class="form-control" type="text" name="physicaldeliveryofficename" id="physicaldeliveryofficename" value="" size="30"/>
									<input type="hidden" name="o_physicaldeliveryofficename" id="o_physicaldeliveryofficename" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="w-25 text-right m-1"><?php echo $gtext['streetaddress'];/*Adres*/?>: </td>
							<td class="w-25">
								<div class="m-1">
									<textarea class="form-control h-100" name="streetaddress" id="streetaddress"></textarea>
									<textarea style="display:none;" name="o_streetaddress" id="o_streetaddress"></textarea>
								</div>
							</td>
							<td class="w-50 ml-1" colspan="2">
							  <div class="input-group text-right ml-1">	
								<div class="w-50"><?php echo $gtext['district'];/*İlçe*/?>:&nbsp;</div>
								<div class="w-50">
									<input class="form-control" type="text" name="district" id="district"/>
									<input type="hidden" name="o_district" id="o_district"/>
								</div>
							  </div>
							  <div class="input-group text-right ml-1">
								<div class="w-50"><?php echo $gtext['st'];/*Şehir*/?>:&nbsp;</div>
								<div class="w-50">
									<input class="form-control" type="text" name="st" id="st"/>
									<input type="hidden" name="o_st" id="o_st"/>
								</div>
							  </div>
							  <div class="input-group text-right ml-1">								
								<div class="w-50"><?php echo $gtext['country'];/*Ülke*/?>:&nbsp;</div>					
								<div class="w-50">
									<input class="form-control" type="text" name="co" id="co"/>
									<input type="hidden" name="o_co" id="o_co"/>
								</div>
							  </div>
							</td>
						</tr>
						<tr>
							<td class="w-25 text-right m-1"><?php echo $gtext['sdate'];/*Starting Date*/?>(*): </td>
							<td class="w-25">								
								<div class="m-1">
									<input class="form-control" type="date" name="sdate" id="sdate" value="<?php echo date("Y-m-d", strtotime("now")); ?>"/>
									<input type="hidden" name="o_sdate" id="o_sdate" value=""/>
								</div>
							</td>
							<td class="w-25 text-right m-1"><?php echo $gtext['resigndate'];/*Leave Date*/?>: </td>
							<td class="w-25">								
								<div class="m-1">
									<input class="form-control" type="date" name="resigndate" id="resigndate" value=""/>
									<input type="hidden" name="o_resigndate" id="o_resigndate" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="w-25 text-right m-1"><?php echo $gtext['pass'];/*Şifre*/?>: </td>
							<td class="w-25">
								<div class="m-1 input-group">
									<input class="form-control" type="text" name="password" id="password" value="" size="22"/>
									<button class="form-control" type="button" id="stdpss" title="Standard Password">Std</button>
									<button class="form-control" type="button" id="createpss" title="Create new Password"><?php echo $gtext['create'];/*Oluştur*/?></button>
								</div>
							</td>
							<td class="w-25 text-right m-1"><?php echo $gtext['state'];/*Durumu*/?>: <span id='uac'></span></td>
							<td class="w-25">
								<div class="m-1">
									<input type="checkbox" name="useraccountcontrol" id="useraccountcontrol" data-bs-toggle="toggle" data-on="<?php echo $gtext['active'];/*Aktif*/?>" data-off="<?php echo $gtext['passive'];/*Kapalı*/?>"/>
								</div>
							</td>
						</tr>
						<tr>
							<td class="w-25 text-right m-1"><?php echo $gtext['note'];/*Not*/?>: </td>
							<td colspan="3"><textarea class="form-control w-50" name="note" id="note"></textarea></td>
						</tr>
						<tr>
							<td colspan="4" align="center">
								<input type="submit" id="record" value="<?php echo $gtext['save'];/*Kaydet*/?>" width=50 disabled />
								<input type="button" id="close" value="<?php echo $gtext['close'];/*Kapat*/?>" width=50/>
							</td>
						</tr>
						</table>
					  <div>* <?php echo $gtext['mandatory_field'];/*Mecburi alan*/?></div>
					  <div id="rp"></div>
                      </form>
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

<script>
var domain="<?php echo $ini['domain']; ?>";
var disabledOU="<?php echo $ini['disabledOU']; ?>";
var u="<?php echo $username; ?>";

$('#useraccountcontrol').bootstrapToggle('on');
$(document).ready(function() {
	var options={
		target: '#rp',
		type:	'POST',
		url : './set_user.php',
		contentType: 'application/x-www-form-urlencoded;charset=utf-8',
		beforeSubmit : function(){
			if($('#givenname').val()==''||$('#sn').val()==''){ alert('Fields can NOT blank!'); return false; }
			$('#rp').html('');
			if(confirm('<?php echo $gtext['q_rusure'];?>')){
				$('#record').prop("disabled", true); 
			}else{
				return false;
			}
			jQuery.noConflict();
		},
		success: function(data){
			if(data.indexOf('!!')>-1){ alert(data); window.opener.location.reload(); }else{ alert('<?php echo $gtext['a_OK'];/*"OK";*/?>');}
		}
	}
	$('#myForm').ajaxForm(options);
	$('#givenname, #sn').on("blur", function(){ 
		if(u==''&&$('#givenname').val()!=''&&$('#sn').val()!=''){	
			var usr=crea_name($('#givenname').val(),'<?php echo $ini['givenname_length'];?>',$('#sn').val(),'<?php echo $ini['sn_length'];?>','<?php echo $ini['asayrac'];?>',1);
			if($('#username').val()==''){
				$('#username').val(usr);
				$('#usrkont').click();	//control
			}
		}	
	});
	$('#username').on("blur", function(){ $('#usrkont').click(); });
	$('#close').on("click", function(){
		window.close();
	});
	var kontt=0; var dep='';
	$('#usrkont').on("click", function(){ 
		$('#mail').val('');
		if($('#givenname').val()==''||$('#sn').val()==''||$('#username').val()==''){ 
			alert('<?php echo $gtext['u_fieldmustnotblank'];/*boş olamaz.*/?>!');  
			return false; 
		}
		$.ajax({
			type: 'POST',
			url: 'user_kont.php',
			data: { u: $('#username').val() },
			beforeSubmit : function(){ 
				
			},
			success: function (data){ //
			console.log(data);
				if(data==''){ console.log('notcontrolled!'); }
				if(data=='+'||data=='L+'){ 
					alert('<?php echo $gtext['free'];/*Uygun*/?>');
					$('#mail').val($('#username').val()+'@'+domain);
				}else{ //veriler yerine konur 
					if(data=='-'){ return confirm('<?php echo $gtext['u_fieldmustnotblank'];/*boş olamaz*/?>');  }
					if(data=='L-'){ return confirm('<?php echo "Kayıt bulunamadı!"; ?>');  }
					if(data.indexOf('!!')>-1){ alert('Please login!'); location.reload(); }
					if(kontt==0&&(data=='U'||data=='U')){ /*used*/
						if(confirm('<?php echo $gtext['u_usernameused'].",".$gtext['q_userinfos'];/*used*/?>')){
							bilgilerigetir($('#username').val()); 
						}else{
							$('#username').val('').focus();
						}
					}
				}
			}
		});
	});
	function sleep(ms) {
		return new Promise(resolve => setTimeout(resolve, ms));
	}
	function bilgilerigetir(username){
		var yol="get_user_infos.php"; dep='';
		var keys=['samaccountname','givenname','sn','mail','description','title','mobile','otherMobile','company','department','distinguishedname','telephonenumber','otherTelephone','physicaldeliveryofficename','manager','useraccountcontrol','ptype','note','streetaddress','district','st','co','sdate','resigndate'];
		$.ajax({
			url: yol,
			type: "POST",
			datatype: 'json',
			async: false,
			data: { u: username, keys: keys },
			success: function(response){  //console.log(response);				
				var obj=JSON.parse(response);
				kontt=obj.length; 
				$.each(obj, function(i, key, value){ 
					var k=obj[i].key, v=obj[i].value; 
					if(keys.find(k=>k)) { 
						if(k!='useraccountcontrol'){ 
							$('#'+k).val(v); 
							$('#o_'+k).val(v);
							//console.log(k+' :'+$('#'+k).val()+' o:'+$('#o_'+k).val());
						}
						if(k=='samaccountname'){ 
							if(u!=''){ $('#username').html(v); }else{ $('#username').val(v); }
							$('#o_username').val(v);
						}
						if(k=='mail'){ 
							if(u!=''){$('#mail').html(v);}else{ $('#mail').val(v);}
							$('#o_mail').val(v);
						}
						if(k=='distinguishedname'){
							var va=v.indexOf(disabledOU); 
							if(va<0){ $('#record').val('Değiştir'); }
							else{ $('#record').prop("disabled", true); /*İşlem yapılmaz*/ }
						}
						if(k=='useraccountcontrol'){ 
							$('#useraccountcontrol').prop("checked",false); 
							if(v==512||v==544||v==66048){ 
								$('#useraccountcontrol').prop("checked",true);
							}
						}
						if(k=='department'){ 
							dep=v; 
							$('#o_department').val(v);
						}
						if(k=='company'){ 
							$('#o_company').val(v);
						}
						if(k=='manager'){ 
							var x=v.indexOf(',OU');
							if(x>=0){ v=v.substring(3,x); }
							$('#dmanager').html(v); 
						}
						if(k=='givenname'){
							var y=v.indexOf('<?php echo $ini['disabledname'];?>');
							if(y>=0){ dis=1; }
						}
						if(k=='streetaddress'){
							$('#'+k).html(v);
						}
						if(k=='telephonenumber'){
							$('#'+k).html(v);
                            if(v==''){ $('#telephonenumber').css('display', 'none'); $('#otherTelephone').css('display', 'inline'); }
						}
						if(k=='mobile'){
							$('#'+k).html(v);
                            if(v==''){ $('#mobile').css('display', 'none'); $('#otherMobile').css('display', 'inline'); }
						}
					}				
				}); 
				$('#rp').html('');
			},
			error: function(response){ alert('Hata!'); }
		});
		return kontt;
	}
	if(u!=''){ 
		bilgilerigetir(u);
	}
	$('#company').on("change", function(){ 
		$.ajax({
			url: "OUlist.php",
			type: "POST",
			datatype: 'json',
			data: { ou: $('#company').val() },
			success: function(response){  //console.log(response);
				var $select=$('#department');
				$select.find('option').remove();
				var obj=JSON.parse(response);
				$.each(obj, function(i, key, value, manager){
					var s='<option value="'+obj[i].key+'"';
					if(obj[i].key==dep){ s+=' selected'; }
					s+='>'+obj[i].value+'</option>'; 
					$select.append(s);
					if(dep==''&&i==0){ 
						$('#dmanager').html(obj[i].dmanager); 
						$('#manager').val(obj[i].manager); 
						$('#o_manager').val(obj[i].manager); 
					}
				});
			}
		});
	});
	$('#department').on("change", function(){
		$.ajax({
			url: "OUlist.php",
			type: "POST",
			datatype: 'json',
			data: { ou: $('#department').val(), dp:'ou' },
			success: function(response){  //console.log(response);
				var objm=JSON.parse(response); 
				$.each(objm, function(i, key, manager, dmanager){
					$('#manager').val(''); $('#dmanager').html('');
					$('#manager').val(objm[i].manager);
					$('#dmanager').html(objm[i].dmanager); 
				});
			}
		});
	});
	$('#stdpss').on('click', function(){
		$('#password').val('<?php echo $ini['stdpass'];?>');
	});
	$('#createpss').on('click', function(){
		$.ajax({
			url: "crpass.php",
			type: "POST",
			datatype: 'json',
			success: function(response){  //
			console.log(response);
				var objm=JSON.parse(response); 
				$.each(objm, function(i, key, pss){
					$('#password').val(objm[i].pss);
				});
			}
		});
	});
	$('#ptype').on('change', function(){
		if($('#ptype').val()=='I'){ 
			$('#title').val('<?php echo $gtext['s_intern']; ?>'); 
		}
	});
	$('#close').on('click', function(){
		location.reload();
	});
	$('#company').change();  
$('form').find(':input').change(function(){ 
	if(dis==''||dis<0){ $('#record').prop("disabled", false ); } 
	else{ alert('<?php echo $gtext['u_nochange'];?>'); }
});
$('#sn').on('keyup', function(){
	$(this).val($(this).val().toUpperCase());
});
$('#cancel').on('click', function(){ $('#record').prop("disabled", true ); });
});
</script>
</body>

</html>