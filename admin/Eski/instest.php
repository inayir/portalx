<?php
/*php.ini ye yazma testi*/
echo "***";
$php_ini_file=php_ini_loaded_file();
$contents = file($php_ini_file);
$aranan=[];
$aranan[]='extension=ldap';
$aranan[]='extension=php_mongodb.dll';
$aranan[]='[PHP_COM_DOTNET]';
$aranan[]='extension=php_com_dotnet.dll';
//
$a=0;
for($i=0;$i<count($contents);$i++){
	$satir=$contents[$i];
	if(strpos($satir, $aranan[$a])){ 
		if(strpos($satir, ";")>=0){ $satir=str_replace(";", "", $satir); }
		$a++;
	}
	$ycontent[]=$satir;
}
if($a<count($aranan)){
	for($i=$a;$i<(count($aranan)-$a)+2;$i++){
		//echo $aranan[$i]."<br>";
		$ycontent[]=$aranan[$i]."\n";		
	}
}
file_put_contents("phpx.ini",implode("",$ycontent));
var_dump($ycontent);
echo "<br>---";
?>