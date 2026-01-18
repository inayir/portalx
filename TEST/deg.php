<?php
$i=0;
$prefix="PC25";
		$p=$i+1; 
		$uz=strlen(floatval($p)); 
		for($puz=$p;$puz<3; $puz++){
			$p="0".$p;
		}
		echo $prefix.$p;
?>