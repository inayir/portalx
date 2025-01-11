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
if($ini['usersource']=='LDAP'){ 
	//get ous
	$liste=Array("ou", "description");
	$filter="ou=*";
	$sr=ldap_list($conn, $ini['base_dn'], $filter, $liste); 
	$info=ldap_get_entries($conn, $sr); 
	
	for ($ii=0; $ii < $info["count"]; $ii++){
		$data[$ii]['ou']=$info[$ii]["ou"][0];
		$data[$ii]['description']=$info[$ii]["description"][0];
		$ksay++;
	}//*/
}else{ 
	@$collection = $db->departments;
	try{
		@$cursor = $collection->find(
			[
				'dp' => ['$eq'=>'C']
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
				$data[]=$satir;
				$ksay++;
			} //*/	
		}
	}catch(Exception $e){
		
	}	
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
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
	<script src="/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.min.js"></script>
    <script src="ad_functions.js"></script>
	<script src="/js/portal_functions.js"></script>
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
									<SELECT type="text" name="company" id="company">
	<?php					for ($i=0; $i < $ksay; $i++){
								$a=$data[$i]["ou"];
								if($a[0]!="_"&&$data[$i]["description"]!=""){ 
									echo "<option value='".$a."'>";
									echo $data[$i]["description"]."</option>\n"; 
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
										<OPTION value="."></OPTION>
									</SELECT>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $gtext['new']." ".$gtext['manager'];/*New Manager*/?>: </td>
							<td>
								<div  class="m-1">
									<div id="newdmanager"></div>
									<input type="hidden" name="manager" id="manager" value="...."/>
								</div>
							</td>
						</tr>
						
						<tr>
							<td colspan="2" align="center">
								<input type="submit" id="record" value="<?php echo $gtext['a_move'];/*Birim Değiştir*/?>" />
								<input type="button" id="clear" value="<?php echo $gtext['close'];/*Temizle*/?>" width="50" onclick="javascript:self.close();"/>
							</td>
						</tr>
						</table>
						<div>* Mecburi alan.</div>
						<div id="rp"></div>
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
$(document).ready(function() {
	var options={
		type:	'POST',
		url : './M_user_move.php',
		contentType: 'application/x-www-form-urlencoded;charset=utf-8',
		beforeSubmit : function(){
			if($('#department').val()==$('#o_department').val()){ alert('<?php echo $gtext['a_department'].' '.$gtext['a_mustchange'];?>!'); return false; }
			var q=confirm('<?php echo $gtext['q_rusure'];/*Emin misiniz?*/?>');	//$('#rp').html('');
		},
		success: function(data){ //console.log(data); //$('#rp').html(data);  
			$('#record').attr("disabled", true); 
			if(data=='login'){ alert('Please Login!'); location.href('../login.php'); }
			var msg='<?php echo $gtext['a_OK'];/*OK*/?> ';
			if(data.indexOf('!')>-1){ msg='<?php echo $gtext['u_error'];/*Error*/?>'; }
			alert(msg+'\n'+data);
			$('#rp').html(data.replace('\n','<br>')); //olmadı
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
	$('#clear').on("click", function(){
		$('#record').attr("disabled", false);
		$('#company').change();
		$('#department').change();
		$('#rp').html("");
	});
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
					if(keys.find(k=>k)) { 
						$('#'+k).val(v); 
						$('#o_'+k).val(v);
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
						}
						if(k=='company'){ 
							$('#o_companydesc').html(getoudesc(v,''));
						}
						if(k=='department'){ 
							dep=v; 
							$('#o_departmentdesc').html(getoudesc(v,'ou'));
						}
						if(k=='manager'){ 
							$('#dmanager').html(v);  
							$('#newdmanager').html(v); 
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
					if(i==0){ s+=' selected '; }
					s+='>'+obj[i].value+'</option>'; 
					$select.append(s); 
					if(dep==''&&i==0){ 
						$('#manager').val(obj[i].manager); 
						$('#newdmanager').html(obj[i].manager); 
					}
				});
			}
		});
		$('#department').change();
	});
	function getoudesc(ou, dp){ 
		$.ajax({
			url: "OUdesc.php",
			type: "POST",
			datatype: 'json',
			data: { ou: ou},
			success: function(response){  //console.log(response);
				var obj=JSON.parse(response);
				$.each(obj, function(i, value, manager){
					if(dp==''){ $('#o_companydesc').html(obj[0].value); }
					else{ 
						$('#o_departmentdesc').html(obj[0].value); 
						$('#dmanager').html(obj[i].manager); 
						$('#o_manager').val(obj[i].manager); 
					}
				});
			}
		});
	}
	$('#department').on("change", function(){
		depch();
	});
	function depch(){
		$.ajax({
			url: "OUlist.php",
			type: "POST",
			datatype: 'json',
			data: { ou: $('#department').val(), dp:'ou' },
			success: function(response){  //console.log(response);
				var objm=JSON.parse(response); 
				$.each(objm, function(i, key, manager, dmanager){
					$('#manager').val(objm[i].manager);
					$('#newdmanager').html(objm[i].dmanager); 
				});
			}
		});
	}
	$('#clear').on('click', function(){
		location.reload();
	});
	$('#company').change(); depch(); 
	$('.dis').attr('disabled', true);
$('form').find(':input').change(function(){ $('#record').prop("disabled", false ); });
$('#cancel').on('click', function(){ $('#record').prop("disabled", true ); });
});
</script>
</body>

</html>