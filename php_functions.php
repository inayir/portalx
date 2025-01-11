<?php
/*
	PHP functions
*/
function logger($filename, $logx){
	global $docroot;
	$log=date("Y-m-d H:i:s", strtotime("now")).";".$logx;
	$log.="\n";
	$dosya=$docroot."/logs/".$filename.".log"; 
	touch($dosya); 
	$dosya = fopen($dosya, 'a'); 
	fwrite($dosya, $log); 
	fclose($dosya); 
}
function tirnakayarla($d){
	$d=str_replace('\r', '\\r',$d);
	$d=str_replace('\n', '\\n',$d);
	return $d;
}
function str_ayikla($str){
	$str = stripslashes('\\', '\\\\', $str);
	$str = str_replace('//', '////', $str);
	$str = str_replace("'", "\'", $str);
	$str = str_replace('"', '\"', $str);
	return $str;
}
function str_return($str,$spaces){
	if($spaces==0){ $str = str_replace(' ', '', $str); } //0:none, '':all
	$search = array('Ç','ç','Ğ','ğ','ı','İ','Ö','ö','Ş','ş','Ü','ü');
	$replace = array('C','c','G','g','i','I','O','o','S','s','U','u');
	return str_replace($search,$replace,$str);
}
function strtouppertr($str){
	$search = array('ç','ğ','ı','i','ö','ş','ü');
	$replace = array('Ç','Ğ','I','İ','Ö','Ş','Ü');
	$s=str_replace($search,$replace, $str);
	$s=strtoupper($s);
	return $s;
}
function datem($dat){
	return new \MongoDB\BSON\UTCDateTime(strtotime($dat)*1000);
}
?>
