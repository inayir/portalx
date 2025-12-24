<!DOCTYPE html>
<html lang="tr">
<?php
/*
Insert: Formu getirir ve kullanıma sunar. Update: önce form verisi getirilir.
*/
error_reporting(0);
include('../set_mng.php');
include($docroot."/config/config.php");
include($docroot."/sess.php");
$simdi=date($ini['date_local']." H:i", strtotime("now"));
$form=$_GET['f']; //girilmiş bir form getirilecektir. Örnek:: "YFR-040";
if($form==''){ echo "Belirsiz Form!"; exit; }
@$onay=$_GET['o']; //operasyon o: onay u: update 
@$key1=$_GET['key1']; //key1. genel olarak _id kullanılacaktır. form ve _id ile getirilir.
@$kayitgetir=$key1; 
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
if($cursor->form_secure=='1'){
	if(@$user==""){ header('Location: /login.php'); }
}
$onay=$cursor->onay;
$tumfields=$cursor->fields;
$tf = json_decode(json_encode ( $tumfields ) , true);
$tfsay=count($tf);

if($kayitgetir!=""){ //FormData Kaydı getirilir........................FormData ($cursor->table iptal edildi.).
	/*$fdatadoc=$ini['MongoDB'].'.FormData'; //.$cursor->table;
	if($cursor->key1=="_id"){ 
		$fdatafilter['_id']=new MongoDB\BSON\ObjectID($key1);
	}else{ $fdatafilter[$cursor->key1]=$key1; }  //form ve _id
	$fdataoptions = []; 
	$fdatasonuc=get_mongodata($fdatadoc, $fdatafilter, $fdataoptions);
	foreach ($fdatasonuc as $fdatasatir){ }  //*/
	$fd='FormData'; //std dosyalama
	if($cursor1->formdata==''){ $col2=$db->FormData; }else{ $doc=$cursor->formdata; $col2=$db->$doc; }
	$id=new MongoDB\BSON\ObjectID($kayitgetir);
	$cursor1 = $col2->findOne(
		[
			'_id'=>$id
		],
		[
			'limit' => 1,
			'projection' => [
			]
		]
	);
	if($onay=='M'&&$cursor1->onaydate!=""){ //onay gerektiriyorsa ve onaylanmamışsa formu dolduran ve onay yetkisi olan açabilir.
		$onay='o.';   
	} //o. onaylandı demek.
	//$fds = json_decode(json_encode ( $fdatasatir ) , true); //var_dump($fds);
}
function get_rec($fromtbl, $fromfield, $dat, $returnfield){
	//personel->username='inayir'=> displayname: İbrahiöm NAYİR
	//personel->username='inayir'=> manager: Ilker PISIRICI
	global $db;
	$cols=$db->$fromtbl;
	$cursor2=$cols->findOne(
		[
			$fromfield=>$dat
		],
		[
			'limit' => 1,
			'projection' => [
			]
		]
	);
	if(count($cursor2)>0){ return $cursor2->$returnfield; } else { return 0; }
}
/*/var_dump($cursor->fields);
$fromtable=$cursor->fields->field_1->fromtable;	
$fromfield=$cursor->fields->field_1->fromfield; //getirilecek field : displayname
$fromkey  =$cursor->fields->field_1->fromkey; 	 //sorgu keyi : username
$fromdata =$_SESSION['user'];  // :'inayir'; 		
echo "data getirilir:".$fromfield."=".$fromdata."->".$field.": ".get_rec($fromtable, $fromfield, $fromkey, $fromdata); //*/

//$cursor->onay D veya M ise Kişinin yöneticisi getirilir.//*/
if($kayitgetir!=""&&$onay!=""){ //girilmiş kaydın onayı için manager getirilir...
	$fromtable=$cursor->fields->field_5->fromtable;	
	$fromfield=$cursor->fields->field_5->fromfield; //getirilecek field : displayname
	$field=$cursor->fields->field_5->name;
	$fromkey  =$cursor->fields->field_5->fromkey; 	 
	if($fromkey=='currentuser'){ $fromdata =$_SESSION['user']; } else{ $fromdata=''; } // :'inayir';
	echo "data getirilir:".$fromfield."=".$fromdata."->".$field.": "; 
	$manager=get_rec($fromtable, $fromfield, $fromdata, $field); 
	echo $manager; exit;
	//if(@$yetki_onay!=1){ $onay=''; }  //yetki_onay 1 ise onaylayabilir.
}//*/
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $cursor->form." ".$cursor->tanimi; ?></title>
    <!-- Custom fonts for this template-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/vendor/datepicker/datepicker.css" rel="stylesheet">
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
                        <h1 class="h3 mb-0 text-gray-800"><?php echo $cursor->form." ".$cursor->tanimi; if($onay=='o'){ echo "-Onay"; } if($onay=='o.'){ echo " (Onaylı)"; } ?></h1>
                    </div>
                    <small><?php echo $cursor->ack; ?></small>
					<div id="ret"></div>
                    <!-- Content Row -->
                    <div class="row">
					<div class="col-xl-10 col-lg-12">
					<form id="form1" method="POST" action="set_formdata.php">
					<input type="hidden" name="form" id="form" value="<?php echo $cursor->form; ?>"/>
					<input type="hidden" name="key1" id="key1" value="<?php echo $key1; ?>"/>
					<table class="table table-striped">
					<tbody>
<?php 
$scol=1; $script=""; $fscript=""; $ftfilter=[];
//var_dump($tf);
for($f=1;$f<=$tfsay;$f++){ 
	$fs='field_'.$f;  //echo $tf[$fs]['type']; echo "<br>";
	$gelen_data="";	
	@$tipx='';  //onay için kayıtlar getirildiğinde alanların kapalı getirilmesi için type p ye döndürülür ama asıl type budur.
	@$tip=$tf[$fs]->type; 
	if($onay=='o'||$onay=='o.'){ //kayıt onaylanmak için getirilmişse...
		if($tf[$fs]['type']=='date'||$tf[$fs]['type']=='radio'||$tf[$fs]['type']=='checkbox'||$tf[$fs]['type']=='select'){ 
			$tipx=$cursor->fields->$fs->type; 
		}
		$tip="p"; //$kayitgetir=getirilecek kayıt gelmiştir.; 
		if($kayitgetir==""){ echo "Kayıt no gereklidir!"; exit; }
	}
	//----------------------------------------------------------------------------
	if($kayitgetir!=""){ //form kayıt verisi getiriliyor. FormData veya başka table dan.
		$gelecekfield=$tf[$fs]['name']; 
		$gelen_data=$cursor->$gelecekfield; 
	}
	//fromtable ftype -------------------------------------------------------------
	if($kayitgetir==""&&$tf[$fs]['fromtable']!=''){ //fields_field_n e fromtable verisi getiriliyor.
		/*/if($datakey==""){ $datakey=$key2; } //ikinci bir parametre mesela sicilno. Form ilk çağrıldığında böyle olabilir.
		$fromtable=$cursor->fields->$fs->fromtable;
		$fromfield=$cursor->fields->$fs->fromfield;
		$fromtblpar=$cursor->fields->$fs->fromtblpar;
		$gelen_data=get_rec($fromtable, $fromfield, $name, 'displayname'); 		 
		//*/	
	}//*/
	//----------------------------------------------------------------------------
	$def=$tf[$fs]['default_value'];
	if($gelen_data==""&&$def!=""){ $gelen_data=$def; }
	//----------------------------------------------------------------------------
  if($tf[$fs]['fdatab']=='H'){ ?>
		<input type="hidden" name="<?php echo $tf[$fs]['name']; ?>" id="<?php echo $tf[$fs]['name']; ?>" value="<?php echo $gelen_data; ?>"/><?php
  }else{  //normal satırlar............
	if($scol==1){ echo "
				<tr>"; } 
	$f1=$f+1; if($f1<$tfsay){ $fs1='field_'.$f1; $scol=$tf[$fs]['col']; }else{ $scol=1; }
				echo "
					<td";
					if($tf[$fs]['ack']!=""){ echo " title='".$cursor->fields->$fs->ack."'"; }
					echo ">";				
				echo $tf[$fs]['label']; 
				if($cursor->fields->$fs->mandatory=="1"){ echo "(*)"; }
				echo "</td>
					<td"; if($scol==1){ echo ' colspan="3"';} echo ">"; 
				$onayli=0; 
				if($tf[$fs]['onay']!=''){
					$onayli=$gelen_data;  //onay gerektiren durumlarda bu alan girilmeyecek.
					if($onay=='o'){ //onaylanmış ama tekrar onay istenmesi durumunda geçerli veri ile alan getirilir.
						$onayli=0;  
					}
					$tip=$tf[$fs]['type'];  //echo "onaydate:".$gelen_data;
				}
				if($onayli==0){	
				echo "<div class='col-md'>"; 
						//p sadece gösterilir, input hidden olur.
						if($tip=='p'&&$tipx==''){ 
							echo "<p><b>".$gelen_data."</b></p>";
							echo "<input type='hidden' name='".$cursor->fields->$fs->name."' id='".$cursor->fields->$fs->name."' value='".$gelen_data."'/>";
						}
						if($tip=='p'&&$tipx=='date'&&$gelen_data!=""){ 
							echo "<p><b>".date($ini['date_local'], strtotime($gelen_data))."</b></p>";
						}
						//input text------------
						if($tip=='text'){ echo "<input type='text' class='form-control mb-3' name='".$cursor->fields->$fs->name."' id='".$cursor->fields->$fs->name."' value='".$gelen_data."'/>";
								//Kontroller
							if($tip=='text'&&$cursor->fields->$fs->mandatory=='1'){
								$script.="if($('#".$cursor->fields->$fs->name."').val()==''){ alert('Boş geçilemez.'); return false; }\n";
							}
						}
						//input date  ----------------------------------
						if($tip=='date'){ 
							if($gelen_data=='') { $gelen_data=date("Y-m-d", strtotime("now")); } 
							echo "<input type='date' class='form-control mb-3' data-date-format='dd.mm.yyyy' name='".$cursor->fields->$fs->name."' id='".$cursor->fields->$fs->name."' value='".$gelen_data."'/>";
							//Kontroller
							if($tip=='date'&&$cursor->fields->$fs->mandatory=='1'){
								$script.="if($('#".$cursor->fields->$fs->name."').val()==''){ alert('Tarihi doğru giriniz:".$cursor->fields->$fs->label."'); return false; }\n";
							}
						}
						//input datetime  ----------------------------------
						if($tip=='datetime'){ 
							if($gelen_data=='') { $gelen_data=date("Y-m-d", strtotime("now")); } 
							echo "<input type='date' class='form-control mb-3' data-date-format='dd.mm.yyyy H:i' name='".$cursor->fields->$fs->name."' id='".$cursor->fields->$fs->name."' value='".$gelen_data."'/>";
							//Kontroller
							if($tip=='date'&&$cursor->fields->$fs->mandatory=='1'){
								$script.="if($('#".$cursor->fields->$fs->name."').val()==''){ alert('Tarihi doğru giriniz:".$cursor->fields->$fs->label."'); return false; }\n";
							}
						}
						//textarea ---------------------------------
						if($tip=='textarea'){ echo "<textarea class='form-control mb-3' name='".$cursor->fields->$fs->name."' id='".$cursor->fields->$fs->name."' value='".$gelen_data."'>".$def."</textarea>";
							//Kontroller
							if($cursor->fields->$fs->mandatory=='1'){
								$script.="if($('#".$cursor->fields->$fs->name."').val()==''){ alert('Boş geçilemez.'); return false; }\n";
							}
						}
						//input radio -----------------------------------------
						if($tip=='radio'||$tipx=='radio'){ //döngü ile tüm radyo seçenekleri alınır. options
							$ops=$cursor->fields->$fs->options;
							$op = json_decode(json_encode ( $ops ) , true);
							$opsay=count($op)/2;
							for($o=1; $o<=$opsay; $o++){
								$so='s'.$o; $solabel='s'.$o.'label';
								if($tipx==''){  ?>	
							<div class="form-check m-1"><input class="form-check-input" type="radio" value="<?php echo $cursor->fields->$fs->options->$so; ?>" name="<?php echo $cursor->fields->$fs->name; ?>" id="<?php echo $cursor->fields->$fs->name.'_'.$o; ?>" <?php if($def==$cursor->fields->$fs->options->$so){ echo "checked"; } ?>><label class="form-check-label" for="<?php echo $cursor->fields->$fs->name.'_'.$o; ?>"><?php echo $cursor->fields->$fs->options->$solabel; ?></label></div><?php
								}elseif($gelen_data==$cursor->fields->$fs->options->$so){
									echo "<p><b>".$cursor->fields->$fs->options->$solabel."</b></p>";
								} 
							}
							if($cursor->fields->$fs->script!=""){ $fscript.=$cursor->fields->$fs->script; }
						} 
						//checkbox ------------------------
						if($tip=='checkbox'||$tipx=='checkbox'){ //döngü ile tüm radyo seçenekleri alınır. options 
								$ops=$cursor->fields->$fs->options;
								$op = json_decode(json_encode ( $ops ) , true);
								$opsay=count($op)/2;
								//default_value içinde birden fazla değer olacağı için buna göre ilerlemeli. test edilecek... ?
								$opdef = json_decode(json_encode ( $def ) , true);
								for($o=1;$o<=$opsay;$o++){
									$so='s'.$o; $solabel='s'.$o.'label'; 
									if($tipx==''){ ?>
							<div class="form-check m-1">
								<input class="form-check-input" type="checkbox" value="<?php echo $cursor->fields->$fs->options->$so; ?>" name="<?php echo $cursor->fields->$fs->name; ?>" id="<?php echo $cursor->fields->$fs->name.'_'.$o; ?>" <?php if(in_array($cursor->fields->$fs->options->$so, $opdef)){ echo "checked"; }?>>
								<label class="form-check-label" for="<?php echo $cursor->fields->$fs->name.'_'.$o; ?>"><?php echo $cursor->fields->$fs->options->$solabel; ?></label>
							</div><?php 
									}elseif($gelen_data==$cursor->fields->$fs->options->$so){
										echo "<p><b>".$cursor->fields->$fs->options->$solabel."</b></p>";
									} 
								}							
							if($cursor->fields->$fs->script!=""){ $fscript.=$cursor->fields->$fs->script;}
						}
						//SELECT ----------------------------------
						if($tip=='select'||$tipx=='select'){ 
							$ops=$cursor->fields->$fs->options;
							$op = json_decode(json_encode ( $ops ) , true);
							$opsay=count($op)/2; 
							if($tipx==''){ ?><SELECT class="form-control" name="<?php echo $cursor->fields->$fs->name; ?>" id="<?php echo $cursor->fields->$fs->name; ?>"><?php }
							for($o=1;$o<=$opsay;$o++){ 
								$so='s'.$o; $solabel='s'.$o.'label'; 
								if($tipx==''){									 
									echo '<option value="'.$cursor->fields->$fs->options->$so.'"'; 
									if($def==$cursor->fields->$fs->options->$so){ echo ' selected'; }
									echo '>'.$cursor->fields->$fs->options->$solabel.'</option>'; 
								//}elseif($gelen_data==$cursor->fields->$fs->options->$so){
								}elseif($gelen_data==$cursor->fields->$fs->options->$so){
									echo "<p><b>".$cursor->fields->$fs->options->$solabel."</b></p>";
								}
							} if($tipx==''){ ?></SELECT><?php }
							if($cursor->fields->$fs->script!=""){ $fscript.=$cursor->fields->$fs->script; }
						} ?>							
						</div>
					<?php 
					if($cursor->fields->$fs->script!=""){ $fscript.=$cursor->fields->$fs->script; }
				} else {
					if($gelen_data!=""){ 
						echo "<p><b>";					
						if($tip=='date'){ echo date($ini['date_local'], strtotime($gelen_data)); }
						else{ echo $gelen_data; }
						echo "</b></p>";
					}
				} ?></td><?php if($scol=='1'){ echo "
				</tr>"; }?>
<?php
  }
} ?>
					<tr>
						<td colspan="4" class="text-center">					
							<button class="btn btn-primary" type="submit" id="send">Gönder</button>
							<button class="btn btn-secondary" type="button" id="cancel"><?php echo $gtext['cancel']; ?></button>
							<button class="btn btn-default" type="button" id="print" disabled >Yazdır</button>
						</td>
					</tr>
					</tbody>
					</table>
					</form>
                        <p><?php echo "Not:".$cursor->not;?></p>
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

    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/datepicker/datepicker.js"></script>
    <script src="/js/sb-admin-2.js"></script>
    <script src="/vendor/form-master/dist/jquery.form.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="form_functions.js"></script>
    <script src="/js/js_variables.js"></script>
<script>
$(document).ready(function () {
	$('#send').on("click", function(){ //gönder	console.log('sended:');
		var opt={
			target	: '#ret',
			type	: 'POST',
			url 	: './set_formdata.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
<?php echo $script; ?>
				confirm('Emin Misiniz?');				
			},
			success: function(data){ 	
				console.log('Önizleme :'+data);
				if(data!=''){ alert(data); location.reload(); }
				else { alert('Bir hata oluştu!'); }
			}
		}
		$('#form1').ajaxForm(opt); //*/
	});
	$('#print').on("click", function(){ 
		//yazdırma
	});
	<?php echo $fscript; ?>
});
</script>
</body>

</html>