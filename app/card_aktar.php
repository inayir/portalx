<?php
include("baglan.php");
echo "Aktarım başladı.<br>";
$q="SELECT * FROM user_list_byuserid";
$r=mysqli_query($baglan, $q);
$say=0;
if($r){
	for($i=0; $prow=mysqli_fetch_assoc($r);$i++){
		$qc="UPDATE personel
		SET 
			card_no='".$prow['cardid']."'
		WHERE
			card_no is NULL AND sicilno='".$prow['sicil']."';";
			$rc=mysqli_query($baglan, $qc);
			if($rc){ echo $prow['sicil']." ".$prow['adisoyadi']."<br>"; $say++;  }
	}	
}
echo $say." Kayıt değiştirildi.";
?>