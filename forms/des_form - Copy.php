<!DOCTYPE html>
<html lang="tr">
<?php
/*
Form tasarımı sayfası
f: varolan Formu getirir ve tasarım değişikliği yaptırır. f boşsa Yeni Form olur.
*/
include('../set_mng.php');
error_reporting(0);
include($docroot."/sess.php");
$bugun=date("Y-m-d", strtotime("now"));
@$form=$_GET['f']; //update girilmiş bir form getirilecektir. Örnek:: "YFR-040"; Boşsa yeni formdur.
@$kopyala=$_GET['c']; //form kopyalanmak istenirse... C
$tfsay=0;
if($form==''){ 
	$drm="Yeni";
	//$form="FR-001";
	$formdat['form']="FR-001";
	$formdat['description']="New Form"; 
	$formdat['category']="K-1"; 
	$formdat['datakey']=""; 
	$formdat['datakeydoc']=""; 
	$formdat['kontrol']=""; 
	$formdat['onay']="N"; 
	$formdat['ack']="Usage or acknowledgements.";
	$formdat['formdate']=$bugun;
	$formdat['formsecure']="1";
	$formdat['orientation']="P";
	$formdat['state']="0";
	$formdat['fields']=['field_1'=>[
				"name"=>"displayname",
				"type"=> "p",
				"label"=>"Name Surname",
				"ack"=> "",
				"mandatory"=> "0",
				"default_value"=> "",
				"fromtable"=> "personel",
				"fromfield"=> "displayname",
				"ftype"=> "text",
				"fdatab"=> "N",
				"mask"=> "00.00.0000",
				"col"=> "1"
				]
			];
 }else{
	//Form kaydı getirilir........................
	$coll=$db->Forms;
	$cursor = $coll->findOne(
		[
			'form'=>$form
		],
		[
			'limit' => 1,
			'projection' => [
			]
		]
	);
	//foreach ($cursor as $formsatir){ 
		$formdat['form']=$cursor->form;
		$formdat['description']=$cursor->description;
		$formdat['category']=$cursor->category;
		$formdat['datakey']=$cursor->datakey;
		$formdat['datakeydoc']=$cursor->datakeydoc;
		$formdat['kontrol']=$cursor->kontrol;
		$formdat['onay']=$cursor->onay;
		$formdat['ack']=$cursor->ack;
		$formdat['formdate']=$cursor->formdate;
		$formdat['formsecure']=$cursor->formsecure;
		$formdat['orientation']=$cursor->orientation;
		$formdat['state']=$cursor->state;
	//} 
	if($kopyala=='C'){ $cursor->description = $cursor->description." Copy"; } //Form kopyalanıyorsa
	/*if($cursor->formsecure=='1'){
		//if(@$user==""){ header('Location: /login.php'); }
	}//*/
	//$key1=$cursor->_id;
	$tumfields=$cursor->fields;
	$tf = json_decode(json_encode ( $tumfields ) , true);
	if($tf!=''){ $tfsay=count($tf); }
	//$tfsay=6;
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $gtext['s_formdesign'];/*." ".$form; /*Form Tasarımı*/?></title>
    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/vendor/datepicker/datepicker.css" rel="stylesheet">

<?php include($docroot."/set_page.php"); ?>
<style>
textarea {
  resize: both;
  overflow: auto;
}
</style>
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Form Tasarımı<?php echo " ".$form; ?></h1>
                    </div>
					<div id="ret"></div>
					<form name="form1" method="POST" action="set_form.php">
					<input type="hidden" name="key1" id="key1" value="<?php echo $key1; ?>"/>
                    <!-- Content Row -->
                    <div class="row">
					<div class="col-xl-4 col-lg-4">
					<h6><?php echo $gtext['parameters']; ?></h6>
					<TABLE id="params" class="table table-striped">
					<TR>
						<td><?php echo $gtext['category'];?>:</td><td><input type="text" name="category" id="category" value="<?php echo $formdat['category']; ?>"/>
						</td>
					</TR>
					<TR>
						<td><?php echo $gtext['code'];?>:</td><td><?php $formdrm='text'; if($form!=''){ echo $form; $formdrm='hidden'; } ?>
						<input type="<?php echo $formdrm; ?>" name="form" id="form" value="<?php echo $form; ?>"/>
						</td>
					</TR>
					<TR>
						<td><?php echo $gtext['description'];?>:</td><td><input type="text" name="description" id="description" value="<?php echo $formdat['description']; ?>"/>
						</td>
					</TR>
					<TR>
						<td><?php echo $gtext['ack'];?>:</td>
						<td><input type="text" name="ack" id="ack" value="<?php echo $formdat['ack']; ?>"/></td>
					</TR>
					<TR>
						<td><small>(Başka dosyadan gelecek)Data Dosyası</small>:</td>
						<td>
							<input type="text" name="datakeydoc" id="datakeydoc" value="<?php echo $formdat['datakeydoc']; ?>"/>
						</td>
					</TR>
					<TR>
						<td>Data Anahtar Alanı:</td>
						<td>
							<input type="text" name="datakey" id="datakey" value="<?php echo $formdat['datakey']; ?>"/>
						</td>
					</TR>
					<TR>
						<td>Güvenlilik:</td><td>
							<select name="formsecure" id="formsecure">
								<option value="1" <?php if($formdat['formsecure']==1){ echo "selected"; } ?>>Güvenlik Gerektirir</option>
								<option value="0" <?php if($formdat['formsecure']==0||$form==''){ echo "selected"; } ?>>Gerektirmez</option>
							</select>
						</td>
					</TR>
					<TR>
						<td>Onay:</td>
						<td>
							<select name="onay" id="onay">
								<option value="M" <?php if($formdat['onay']=="M"){ echo "selected";} ?>>Müdür</option>
								<option value="D" <?php if($formdat['onay']=="D"){ echo "selected";} ?>>Direktör</option>
								<option value="O" <?php if($formdat['onay']=="O"){ echo "selected";} ?>>Diğer</option>
								<option value="N" <?php if($formdat['onay']=="N"){ echo "selected";} ?>>Onay Gerektirmez</option>
							</select>
						</td>
					</TR>
					<TR>
						<td>Kontrol:</td><td>
						<input type="text" name="kontrol" id="kontrol" value="<?php echo $formdat['kontrol']; ?>"/></td>
					</TR>
					<TR>
						<td>Kağıt Yönü:</td>
						<td>
							<select name="orientation" id="orientation" title='Page Orientation'>
								<option value="P" <?php if($formdat['orientation']=="P"){ echo "selected"; } ?>>Dikey</option>
								<option value="L" <?php if($formdat['orientation']=="L"){ echo "selected"; } ?>>Yatay</option>
							</select>
						</td>
					</TR>
					<TR>
						<td>Oluşturma Tarihi:</td>
						<td>
							<input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d", strtotime($formdat['formdate'])); ?>"/>
						</td>
					</TR>
					<TR>
						<td><?php echo $gtext['state'];?>:</td>
						<td>
							<div class='form-check m-1'><input class='form-check-input' type='radio' value='A' name='state' <?php $state=$formdat['state']; if($state=="A"||$form==""){ echo "checked"; } ?>><?php echo $gtext['active'];?></option></div>
							<div class='form-check m-1'><input class='form-check-input' type='radio' value='P' name='state'<?php if($formdat['state']=="P"||$formdat['state']==""){ echo " checked"; } ?>><?php echo $gtext['passive'];?></option></div>
						</td>
					</TR>
					<TR>
						<td><?php echo $gtext['note'];?>:<br>(Bu not formun altında görünür.)</td>
						<td>
							<textarea name="not" id="not"><?php echo $formdat['not']; ?></textarea>
						</td>
					</TR>
					<TR>
						<TD colspan="4" class="text-center">
							<button class="btn btn-primary" type="submit" id="send"><?php echo $gtext['save'];?></button>
							<button class="btn btn-secondary" type="button" id="cancel"><?php echo $gtext['cancel']; ?></button>
						</TD>
					</TR>
					</TABLE>
					<small>(*) Bu alanlara kucuk harflerle giriş yapınız, '- ve _' dışında özel karakter ve boşluk karakteri kullanmayınız.</small>
					<br><small>(**) Bu alanlar anahtar alan olduğu için girilmiş veri varken kaldırmayınız veya değiştirmeyiniz. Aksi takdirde eski verilere ulaşılabilmekle birlikte, eski verilerin de hesaba konulduğu raporlarda hatalar çıkabilir.</small>
					</div>
					<div id="res"></div>
					
					<div class="col-xl-6 col-lg-6">
					
					<h6>Form Alanları</h6>
					<div class="div">						
						<TABLE id="fields" class="table table-striped">
						<TR>
							<TH colspan="4" style='border:1px; border-style:solid;'>
								<div class="text-right w-100" style='border:1px; border-style:dotted;'>
									<select class="form-input-sm" id="alanlar">
									<option value="0">En sona</option><?php 
									for($fx=1;$fx<=$tfsay;$fx++){ $fsx='field_'.$fx; 
									echo "<option value='".$cursor->fields->$fsx->name."'>".$cursor->fields->$fsx->name."</option>"; 
									}?>
									</select><small>veya seçilen alandan sonra</small>
									<button class="btn btn-secondary m-1" type="button" id="alanekle">Alan Ekle</button>
								</div>
							</TH>
						</TR>
						<TBODY>
					<?php 	//fieldlar sağdaki alana getirilir...
$scol=1; $script=""; $fscript=""; $ftfilter=[]; $def="";
for($f=1;$f<=$tfsay;$f++){ 
	$fs='field_'.$f;
	@$tip=$cursor->fields->$fs->type; 
	//----------------------------------------------------------------------------
	echo "<TR>"; 
	$f1=$f+1; if($f1<$tfsay){ $fs1='field_'.$f1; $scol=$cursor->fields->$fs1->col; }else{ $scol=1; }
				echo "
			<TD style='border:1px; border-style:solid;'>
				<table id='det_".$fs."' width='100%'>
					<tr>
						<td>Alan*:";
				if($cursor->fields->$fs->name==$cursor->datakey){ echo "<b>(*)(**)</b>"; }
				echo "</td>";
				echo "<td><input type='text' name='".$fs."_name' id='".$fs."_name' value='".$cursor->fields->$fs->name."'/>";
				echo "</td>
					</tr>
					<tr>
						<td>Giriş/Gösterim Tipi:</td>"; 
				echo "<td><select name='".$fs."_type' id='".$fs."_type'><option value='p'";    if($cursor->fields->$fs->type=="p"){ echo " selected";} echo ">Sadece Gösterim</option><option value='text'"; if($cursor->fields->$fs->type=="text"){ echo " selected";} echo ">Yazı Girişi</option><option value='date'"; if($cursor->fields->$fs->type=="date"){ echo " selected";} echo ">Tarih Girişi</option><option value='datetime'"; if($cursor->fields->$fs->type=="datetime"){ echo " selected";} echo ">Tarih/Saat Girişi</option><option value='select'"; if($cursor->fields->$fs->type=="select"){ echo " selected";} echo ">Açılır Liste</option><option value='radio'";  if($cursor->fields->$fs->type=="radio"){ echo " selected";} echo ">Radyo Butonu</option><option value='checkbox'"; if($cursor->fields->$fs->type=="checkbox"){ echo " selected";} echo ">Seçim Kutusu</option><option value='textarea'"; if($cursor->fields->$fs->type=="textarea"){ echo " selected";} echo ">Geniş Yazı Alanı</option></select>";	
				if($cursor->fields->$fs->name==$cursor->datakey){ 
					echo "<span style='display:";
					if($cursor->fields->$fs->type=='p'){ "inline"; }else{ echo "none";}
					echo "'>&nbsp(***)Bu alanın verisi kaydedilir.</span>"; 
				}
				echo "</td>
					</tr>
					<tr>
						<td width='30%' id='lfield_".$f."' title='".$f."'>Etiket*:</td>"; 
				echo "<td><input type='text' name='".$fs."_label' id='".$fs."_label' value='".$cursor->fields->$fs->label."'/>"; 
				echo "</td>
					</tr>
					<tr>
						<td>Açıklama:</td>";
				echo "<td><input type='text' name='".$fs."_ack' id='".$fs."_ack' value='".$cursor->fields->$fs->ack."'/>";
				echo "</td>
					</tr>
					<tr>
						<td>Zorunlu alan:</td>"; 
				echo "<td><div class='form-check m-1'><input class='form-check-input' type='radio' value='1' name='".$fs."_mandatory'";
				if($cursor->fields->$fs->mandatory=="1"){ echo " checked"; }
				echo ">Zorunlu</option></div>";
				echo "<div class='form-check m-1'><input class='form-check-input' type='radio' value='0' name='".$fs.".mandatory'";
				if($cursor->fields->$fs->mandatory=="0"||$cursor->fields->$fs->mandatory==""){ echo " checked"; }
				echo ">Zorunlu Değil</option></div>
						</td>
					</tr><tr>
						<td>Öntanımlı Değer:</td>";
				echo "<td><input type='text' name='".$fs."_default_value' id='".$fs."_default_value' value='".$cursor->fields->$fs->default_value."'/>";
				echo "</td>
					</tr>
					<tr>
						<td>Sütun:</td>"; 
				echo "<td><div class='form-check m-1'><input class='form-check-input' type='radio' value='1' name='".$fs."_col'";
				if($cursor->fields->$fs->col=="1"){ echo " checked"; }
				echo ">1.Sütun veya tek sütun</option></div>";
				echo "<div class='form-check m-1'><input class='form-check-input' type='radio' value='0' name='".$fs."_col'";
				if($cursor->fields->$fs->col=="2"){ echo " checked"; }
				echo ">2.Sütun</option></div>
						</td>
					</tr>";
				/*bunlar eklenecek....				
				"fieldsend"=>"1", type dan sonrasına
				"fromtable"=> "personel",
				"fromfield"=> "displayname",
				"ftype"=> "text",
				"fdatab"=> "N",
				"mask"=> "00.00.0000",
				*/
				$opsay=0;
				if(isset($cursor->fields->$fs->options)){
					$ops=$cursor->fields->$fs->options;
					$op = json_decode(json_encode ( $ops ) , true);
					//var_dump($op);
					$opsay=count($op)/2;
				}
				if($opsay>0&&($tip=='radio'||$tip=='checkbox'||$tip=='select')){ 
					echo "
					<tr>
						<td colspan='3'>"; //seçenekler varsa..
					echo "<table id='field_options'>
							<tr>
								<th colspan='4' class='text-center bg-gradient-light'>Seçenekler</th>
							</tr>
							<tr>
								<th>Seç</th>
								<th>Etiket</th>
								<th>Değer</th>
								<th><button class='btn btn-warning' type='button' id='".$fs."_opadd'>".$gtext['insert']."</button></th>
							</tr>";
					$def=$cursor->fields->$fs->default_value;
					for($o=1;$o<=$opsay;$o++){ 
						$so='s'.$o; $solabel='s'.$o.'label'; 			 
						echo "<tr><td style='text-align:center;'><input class='form-check-input' type='radio' value='".$fs."_".$so."' name='o_".$fs."_s' id='o_".$fs."_s' ";
						if($def==$cursor->fields->$fs->options->$so){ echo "checked"; } 
						echo "></td>"; 
						echo "<td><input type='text' name='".$fs."_s".$o."label' id='".$fs."_s".$o."label' value='".$cursor->fields->$fs->options->$solabel."' /></td>"; 
						echo "<td><input type='text' name='".$fs."_s".$o."' id='".$fs."_s".$o."' value='".$cursor->fields->$fs->options->$so."' /></td>"; 
						echo "<td><button class='btn btn-info' type='button' id='opsil'>Çıkar</button></td>"; 
						echo "</tr>"; 
					}
					echo "
						</table>
						</td>
					</tr>";
				}//*/
				if($cursor->fields->$fs->script!=""){ 
					echo "
					<tr>
						<TD colspan='3'>
							<textarea class='form-control' style='min-width: 100%' rows='10' name='".$fs."_script' id='".$fs."_script'>".$cursor->fields->$fs->script."</textarea>
							<p><small>*Not: İşlev Javascript ve JQuery dilleri ile yazılabilir. Toplam 20 satırı geçemez.<br>İşlevi iptal etmek için yazı alanını tamamen siliniz.</small></p>
						</td>
					</tr>"; 
				}
						 ?>							
					</table>
			</TD>
			<TD style='border:1px; border-style:solid;'>
						<button class="btn btn-danger w-100" type="button" id="alansil" onClick="javascript:alanisil('<?php echo $f; ?>');">Sil</button><?php if($cursor->fields->$fs->script==""){ echo "<br>";?>
						<button class="btn btn-info w-100" type="button" onClick="javascript:scekle('<?php echo $f; ?>');">İşlev</button><?php  } ?>
			</TD>
		  </TR><?php echo "
					"; } ?>
						</TBODY>
						</TABLE>
					</div>
				</form>
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
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/datepicker/datepicker.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/js/sb-admin-2.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>
    <script src="form_functions.js"></script>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<script>
$(document).ready(function () {
	$('#send').on("click", function(){ 
		$('#res').html('');
		var opt={
			target	: '#ret',
			type	: 'POST',
			url 	: './set_form.php',
			beforeSubmit : function(){ <?php echo $script; ?>
				confirm('Emin Misiniz?');				
			},
			success: function(data){ //console.log('Önizleme :'+data);
				$('#res').html(data);
				if(data!=''){ alert(data); /*location.reload();//*/ }
				else { alert('Bir hata oluştu!'); }
			}
		}
		$('form1').ajaxForm(opt); //*/
	});
	<?php echo $fscript; ?>
});
	$('#alanekle').on('click', function(){
		var yer=$('#fields tbody tr').length; //console.log('rc:'+yer);
		var alanlarsind=$('#alanlar option:selected').index(); //alanlar index=0 en sona eklenir.
		if(alanlarsind==0){ 
			if(yer>=8){ yer=(yer)/8;}  
			yer=yer+1; 
		}else{ 
			yer=alanlarsind+2;
			//bu sıradaki ve sonrakilerin alan adları değiştirilmeli, sonra yenisi açılmalı.	
		} 
		var fs='field_'+yer; 
		var icerik="<TR><TD></TD><TD><table id='det_"+fs+"' width='100%'>";
		icerik+="<tr><td>Alan *:</td><td><input type='text' name='"+fs+"_name' id='"+fs+"_name' value='"+fs+"'/>"+fs+"</td></tr>"; 
		icerik+="<tr><td>Giriş/Gösterim Tipi:</td><td><select onchange='gtip("+yer+");' name='"+fs+"_type' id='"+fs+"_type'>";
		icerik+="<option value='text' selected >Yazı Girişi</option>";
		icerik+="<option value='date'>Tarih Girişi</option>";
		icerik+="<option value='datetime'>Tarih/Saat Girişi</option>";
		icerik+="<option value='select'>Açılır Liste</option>";
		icerik+="<option value='radio'>Radyo Butonu</option>";
		icerik+="<option value='checkbox'>Seçim Kutusu</option>";
		icerik+="<option value='textarea'>Geniş Yazı Alanı</option>";
		icerik+="<option value='p'>Sadece Gösterim</option>";
		icerik+="</select><span id='namespan' style='display:none'>&nbsp(***)Bu alanın verisi kaydedilir.</span></td></tr>";
		icerik+="<tr><td width='30%' id='lfield_"+fs+"' title='"+fs+"'>Etiket*:</td>"; 
		icerik+="<td><input type='text' name='"+fs+"_label' id='"+fs+"_label' value=''/>"; 
		icerik+="</td></tr>";
		icerik+="<tr><td>Açıklama:</td><td><input type='text' name='"+fs+"_ack' id='"+fs+"_ack' value=''/>";
		icerik+="</td></tr>";
		icerik+="<tr><td>Zorunlu alan:</td><td><div class='form-check m-1'><input class='form-check-input' type='radio' value='1' name='"+fs+"_mandatory' checked >Zorunlu</option></div><div class='form-check m-1'><input class='form-check-input' type='radio' value='0' name='"+fs+"_mandatory'>Zorunlu Değil</option></div></td></tr>";
		icerik+="<tr><td>Öntanımlı Değer:</td><td><input type='text' name='"+fs+"_default_value' id='"+fs+"_default_value' value=''/></td></tr>";
		icerik+="<tr><td>Sütun:</td><td><div class='form-check m-1'><input class='form-check-input' type='radio' value='1' name='"+fs+"_col' checked >1.Sütun veya tek sütun</option></div><div class='form-check m-1'><input class='form-check-input' type='radio' value='0' name='"+fs+"_col'>2.Sütun</option></div></td></tr>";
		icerik+="</table></TD><TD></TD><TD><button class='btn btn-danger' type='button' id='alansil' onClick='javascript:alanisil("+yer+");'>Sil</button></TD></TR>";
		var opti="<option value='field_"+(yer)+"'>Yeni Alan"+(yer)+"</option>";
		if(alanlarsind==0){ $('#fields tbody').append(icerik); console.log('append'); }
		else {  $('#fields tbody tr').eq(yer).after(icerik); console.log('after'); }
		//alanlara da eklenir.
		var opti="<option value='field_"+yer+"'>Yeni Alan "+yer+"</option>";
		$('#alanlar').append(opti);
		console.log('icerik:'+icerik);
		
	});
	function gtip(yer){ 
		var eltype=$('#field_'+yer+'_type').val(); //console.log(eltype);
		if(eltype=='p'){ }
		if(eltype=='select'||eltype=='radio'){	
			var opsay=$('#det_field_'+yer+'_options thead tr').length; 
			if(opsay<1){ 
				var opbar="<tr><td colspan='3'><table id='field_"+yer+"_options'><tr><th colspan='4' class='text-center bg-gradient-light'>Seçenekler</th></tr><tr><th>Seç</th><th>Etiket</th><th>Değer</th><th><button class='btn btn-warning' type='button' id='field_"+yer+"_opadd' onclick='opadd("+yer+");'>Ekle</button></th></tr></table></td></tr>";
				//console.log('opsay:'+opsay+' '+opbar);
				$('#det_field_'+yer).append(opbar);
			}					
			
		}
	}
	function opadd(yer){  //add row to field_1_options table 
		var o=$('#field_'+yer+'_options tbody tr').length-1;
		var so='field_'+yer+'_s'+o, solabel=so+'label'; 
		var ops="<tr><td style='text-align:right;'><input class='form-check-input' type='radio' name='o_field_"+yer+"_s' id='o_field_"+yer+"_s' value='"+so+"'";
		if(o==1){ ops+=" checked "; }
		ops+= "></td>"; 
		ops+= "<td><input type='text' name='"+solabel+"' id='"+solabel+"' value='' /></td>"; 
		ops+= "<td><input type='text' name='"+so+"' id='"+so+"' value='' /></td>"; 
		ops+= "<td><button class='btn btn-info' type='button' id='opsil' onclick='opsil("+yer+");'>Çıkar</button></td>"; 
		ops+= "</tr>"; //*/
				
		ops+="</td></tr>";
		$('#field_'+yer+'_options').append(ops);//*/
		
		//console.log('eklenen opsiyon:'+solabel+' '+so);
	}
	function opsil(f){
		alert('will be...');
	}	
	function alanisil(f){
		alert('Alan Silinecek...'+f);
		$('#fields tr').eq((f+1)).remove();
		//alanlardan da silinir.
		$('#alanlar option').eq((f+1)).remove();
	}
	function scekle(f){
		alert('Islev eklenecek...'+f);
	}
</script>
</body>

</html>