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
function datem($dat){ //date to mongodate
	return new \MongoDB\BSON\UTCDateTime(strtotime($dat)*1000);
}
function mdatetodate($dat){
	if($dat!=""){
		$t=strpos($dat,'T'); 
		if($t==''){ return substr($dat,0); }
		else{ return substr($dat,0,$t); }
	}else{ return false; }
}
function mdatetimetodate($dat){
	global $ini;
	if($dat!=""){
		$t=strpos($dat,'T'); 
		$d=substr($dat,0,$t); 
		$dl=date($ini['date_local'], strtotime($d));
		$tm=substr($dat,$t+1);
		return $dl." ".$tm;
	}else{ return false; }
}
function percount($dp, $ou){
	/*Gets department personel count*/
	global $db; $par='department';
	if($dp=='C'){ $par='company'; }
	$xsay=0;
	@$xcollection = $db->personel; 
	$xcursor = $xcollection->find(
		[
			'$and'=>[[$par=>$ou],['state'=>['$ne'=>'C']],['title'=>['$ne'=>'Phone']]]
		],
		[
			'limit' => 0,
			'projection' => [
				'state'=>1,
			]
		]
	);
	if(isset($xcursor)){	 
		foreach ($xcursor as $xsatir) {
			$yer="";
			if($xsatir->state=='A'){ $xsay++; }
		}
	}
	return $xsay;
}
function perlist($dp, $ou){
	/*Gets department personel count*/
	global $db; $par='department';
	if($dp=='C'){ $par='company'; }
	$xsay=0; $list="";
	@$xcollection = $db->personel; 
	$xcursor = $xcollection->find(
		[
			'$and'=>[[$par=>$ou],['state'=>['$ne'=>'C']]]
		],
		[
			'limit' => 0,
			'projection' => [
				'state'=>1,
				'displayname'=>1,
			],
			'sort'=>[
				'displayname'=>1,
			],
		]
	);
	if(isset($xcursor)){	 
		foreach ($xcursor as $xsatir) {
			$yer="";
			if($xsatir->state=='A'){ 
				$list.=$xsatir->displayname."; ";
			};
		}
	}
	return $list;
}
function setstate($state){
	switch($state){ 
		case "A" 	: $state=1; break; 
		case "1" 	: $state=1; break; 
		case "on" 	: $state=1; break; 
		case "P"	: $state=0; break; 
		case "0" 	: $state=0; break; 
		case "off" 	: $state=0; break; 
	} 
	return $state;
} 
function xarray_sort(){
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}
function getNextSequence($db, $field){
    $result = $db->counters->findOneAndUpdate(
        ['_id' => 'hdseq'],
        ['$inc' => [$field => 1]],
        [
            'upsert' => true,
            'returnDocument' => MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER
        ]
    );

    return $result[$field];
}
?>