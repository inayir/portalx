<?php
$docroot=$_SERVER['DOCUMENT_ROOT'];
include($docroot.'/set_mng.php');
include($docroot."/sess.php");
require($docroot."/ldap.php");
if($user==""){
	header('Location: \login.php');
}
$username=$_GET['u']; 
$ksay=0; 
$data=Array(); 
/*if($ini['usersource']=='LDAP'){ 
	//get ous
	$liste=Array("ou", "description");
	$filter="ou=*";
	$sr=ldap_list($conn, $ini['base_dn'], $filter, $liste); 
	$info=ldap_get_entries($conn, $sr); 
	
	for ($ii=0; $ii < $info["count"]; $ii++){
		$data[$ii]['ou']=$info[$ii]["ou"][0];
		$data[$ii]['description']=$info[$ii]["description"][0];
		$ksay++;
	}
}else{ //*/
	//birim bilgileri getirilir-getting department infos
	@$collection = $db->departments;
	try{
		@$cursor = $collection->find(
			[
				'$and'=>[['dp' => 'C'],['status'=>'A']]
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
				$satir=[];
				$satir['ou']=$formsatir->ou;
				$satir['company']=$formsatir->company;
				$satir['description']=$formsatir->description;
				$fsatir[]=$satir;
				$ksay++;
			} //*/	
		}
	}catch(Exception $e){
		
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

    <title><?php echo $gtext['a_move'];?></title>

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
	<script src="../js/portal_functions.js"></script>
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $gtext['user']." ".$gtext['a_move'];/*Kullanıcı Birim Değiştir*/?></h1>
                        <!--a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a-->
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<form id="myForm" action="./M_user_move.php" method="POST">
						<table border=1px align=center >
						<tr height="30">
							<td colspan="2" align="center"><b><?php echo $ini['domain']." ".$gtext['user']." ".$gtext['record'];/*Kullanıcı Kaydı*/?></b></td>
						</tr>
						<tr>
							<td width="150"><?php echo $gtext['username'];/*Username*/?>: </td>
							<td>
								<div  class="m-1">
									<input class=".dis" type="text" name="username" id="username" value="<?php echo $username; ?>" size="22" <?php if($username!=""){ echo "readonly"; } ?>/>
									<input type="hidden" name="o_username" id="o_username" value="<?php echo $username; ?>"/>
									<input type="hidden" name="distinguishedname" id="distinguishedname" value=""/><?php if($username==""){ ?>
									<button type="button" id="usrkont"><?php echo $gtext['short_control'];/*Kont*/?></button><?php } ?>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['name'];/*Adı*/?>: </td>
							<td>
								<div  class="m-1">
									<input class=".dis" type="text" name="givenname" id="givenname" value="" size="30"/>
									<input type="hidden" name="o_givenname" id="o_givenname" value=""/>
									<input type="hidden" name="displayname" id="displayname" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['surname'];/*Soyadı*/?>: </td>
							<td>
								<div  class="m-1">
									<input class=".dis" type="text" name="sn" id="sn" value="" size="30"/>
									<input type="hidden" name="o_sn" id="o_sn" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td>E-Mail: </td>
							<td>
								<div  class="m-1">
									<input class=".dis" type="text" name="mail" id="mail" value="" size="30" <?php if($username!=""){ echo "readonly"; } ?>/>
									<input type="hidden" name="o_mail" id="o_mail" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['pernumber'];/*Sicil*/?>: </td>
							<td>
								<div  class="m-1">
									<input class=".dis" type="text" name="description" id="description" value="" size="30"/>
									<input type="hidden" name="o_description" id="o_description" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['pertitle'];/*Unvanı*/?>: </td>
							<td>
								<div  class="m-1">
									<input class=".dis" type="text" name="title" id="title" value="" size="30"/>
									<input type="hidden" name="o_title" id="o_title" value=""/>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['percompany'];/*Company*/?>: </td>
							<td>
								<div  class="m-1">
									<input type="hidden" name="o_company" id="o_company" />
									<div id="o_companydesc"></div>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['a_department'];/*Department*/?>: </td>
							<td>
								<div  class="m-1">
									<input type="hidden" name="o_department" id="o_department" width="100" />
									<div id="o_departmentdesc"></div>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['manager'];/*Yönetici*/?>: </td>
							<td>
								<div  class="m-1">
									<div id="dmanager"></div>
									<input type="hidden" name="o_manager" id="o_manager" value=""/>
								</div>
							</td>
						</tr>
						<tr>
						<td colspan="2" class="text-center fw-bold"><?php echo $gtext['new']." ".$gtext['movetodepartment'];/*Yeni Birim Seçiniz*/?>:</td>
						</tr>
						<tr>
							<td><?php echo $gtext['new']." ".$gtext['percompany'];/*New Company*/?>: </td>
							<td>
								<div  class="m-1">
									<SELECT type="text" name="company" id="company"><?php echo "\n";
									for ($i=0; $i < $ksay; $i++){
										$a=$fsatir[$i]["ou"];
										if($a[0]!="_"&&$fsatir[$i]["description"]!=""){ 
											echo "<option value='".$a."'>";
											echo $fsatir[$i]["description"]."</option>\n"; 
										}
									} ?>
									</SELECT>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['new']." ".$gtext['a_department'];/*New Department*/?>: </td>
							<td>
								<div  class="m-1">
									<SELECT name="department" id="department" width="100"/>
									</SELECT>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['new']." ".$gtext['manager'];/*New Manager*/?>: </td>
							<td>
								<div  class="m-1">
									<div id="newdmanager"></div>
									<input type="hidden" name="manager" id="manager" value=""/>
									<input type="hidden" name="managerdn" id="managerdn" value=""/>
								</div>
							</td>
						</tr>
						
						<tr>
							<td colspan="2" align="center">
								<input type="submit" id="record" value="<?php echo $gtext['a_move'];/*Birim Değiştir*/?>" />
								<input type="button" id="clear" value="<?php echo $gtext['close'];/*Temizle*/?>" width="50" onclick="javascript:self.close();"/>
							</td>
						</tr>
						<tr>
							<td colspan="2"><small><div id="rp"></div></small></td>
						</tr>
						</table>
						<div>* Mecburi alan.</div>
                        </form>
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
var disabledOU="<?php echo $ini['disabledOU']; ?>";
var u="<?php echo $username; ?>";
$('#useraccountcontrol').bootstrapToggle('on');
var objdep=[]; 
$(document).ready(function() {
	var options={
		type:	'POST',
		url : './M_user_move.php',
		contentType: 'application/x-www-form-urlencoded;charset=utf-8',
		beforeSubmit : function(){
			if($('#department').val()==$('#o_department').val()){ alert('<?php echo $gtext['a_department'].' '.$gtext['a_mustchange'];?>!'); return false; }
			return confirm('<?php echo $gtext['q_rusure'];/*Emin misiniz?*/?>');	
		},
		success: function(data){ //console.log(data);  
			$('#record').attr("disabled", true); 
			if(data=='login'){ alert('Please Login!'); location.href('../login.php'); }
			var msg='<?php echo $gtext['a_OK'];/*OK*/?> ';
			if(data.indexOf('!')>-1){ msg='<?php echo $gtext['u_error'];/*Error*/?>'; }
			alert(msg); 
			var dat=data.replace(/\n/g,'<br>');
			$('#rp').html(dat); //olmadı
		}
	}
	$('#myForm').ajaxForm(options);
	$('#givenname, #sn').on("blur", function(){ 
		if(u==''&&$('#givenname').val()!=''&&$('#sn').val()!=''){	
			var usr=crea_name($('#givenname').val(), '<?php echo $ini['givenname_length']; ?>', $('#sn').val(), '<?php echo $ini['sn_length']; ?>', '<?php echo $ini['asayrac'];?>',1);
			if($('#username').val()==''){
				$('#username').val(usr);
				$('#usrkont').click();	
			}
		}	
	});
	$('#username').on("blur", function(){ $('#usrkont').click(); });
	var kontt=0; var dep='';
	function sleep(ms) {
		return new Promise(resolve => setTimeout(resolve, ms));
	}
	function bilgilerigetir(username){
		var yol="get_user_infos.php"; dep='';
		var keys=['samaccountname','givenname','sn','displayname','mail','description','title','company','department','distinguishedname','manager','useraccountcontrol'];
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
					$('#'+k).val(v); 
					$('#o_'+k).val(v);
					if(keys.find(k=>k)) { 
						switch(k){
						case "samaccountname":
							$('#'+k).val(v); $('#o_'+k).val(v);
							if(u!=''){ $('#username').html(v); }else{ $('#username').val(v); }
							$('#o_username').val(v);
							break;
						case "distinguishedname":
							$('#'+k).val(v); $('#o_'+k).val(v);
							var va=v.indexOf(disabledOU); 
							if(va<0){ $('#record').val('Değiştir'); }
							break;
						case "company":
							$('#o_'+k).val(v);
							getoudesc(v,'');
							break;
						case "department":
							$('#o_'+k).val(v);
							dep=v; 
							getoudesc(v,'ou');
							break;
						case "manager":
							getoudesc(v,'managedby'); 
							break;
						default:
							$('#'+k).val(v); 
							$('#o_'+k).val(v);
						}
					}				
				}); 
				$('#rp').html('');//*/
			},
			error: function(response){ alert('Hata!'); }
		});
		return kontt;
	}
	if(u!=''){ 
		bilgilerigetir(u);
	}
	function getoudesc(ou, dp){ 
		$.ajax({
			url: "OUdesc.php",
			type: "POST",
			datatype: 'json',
			data: { ou: ou},
			success: function(response){  //console.log(response);
				var obj=JSON.parse(response);
				$.each(obj, function(i, value, manager){
					if(dp==''){ $('#o_companydesc').html(obj[i].value); }
					else{ 
						$('#o_departmentdesc').html(obj[i].value); 
						$('#o_manager').val(obj[i].managedby); 
						$('#dmanager').html(obj[i].manager); 
					}
				});
			}
		});
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
				objdep=JSON.parse(response); 
				$.each(objdep, function(i, key, value, manager, dmanager){
					if(objdep[i].key!=dep){
						var s='<option value="'+objdep[i].key+'"';
						if(i==0){ s+=' selected '; }
						s+='>'+objdep[i].value+'</option>'; 
						$select.append(s); 
					}
				});
				var val=$('#department').val(); 
				$.each(objdep, function(i, key, manager, dmanager){ 
					if(objdep[i].key==val){
						$('#manager').val(objdep[i].dmanager);
						var mdn=objdep[i].manager; if(mdn==''){ mdn=Array(); }
						$('#managerdn').val(mdn); //dn
						$('#newdmanager').html(objdep[i].dmanager); 
					}
				}); 
			}
		});
		$('#department').change();
	});
	$('#department').on("change", function(){ 
		var val=$('#department').val(); //console.log(val);
		$.each(objdep, function(i, key, manager, dmanager){ 
			if(objdep[i].key==val){
				$('#manager').val(objdep[i].dmanager);
				var mdn=objdep[i].manager; if(mdn==''){ mdn=Array(); }
				$('#managerdn').val(mdn); //dn
				$('#newdmanager').html(objdep[i].dmanager); 
			}
		});
	});
	$('#clear').on('click', function(){
		location.reload();
	});
	$('#company').change();  
	$('.dis').attr('disabled', true);
$('form').find(':input').change(function(){ $('#record').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#record').prop("disabled", true ); });
});
</script>
</body>

</html>