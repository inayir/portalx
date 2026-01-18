<?php 
/*
Finding files in the directory...
*/
$docroot=$_SERVER['DOCUMENT_ROOT'];
$fromdir = $docroot.'\config\ext';
$extension_dir=ini_get('extension_dir')."\\rrr\\";
//finding files...
$files = scandir($fromdir);
$msg="";
for($i=0;$i<count($files);$i++){
	if($files[$i]!="."&&$files[$i]!=".."){ 
		$msg.="<div>";
		$file = $fromdir.'\\'.$files[$i];
		$newfile = $extension_dir.'\\'.$files[$i];
		$msg.=$file." -> ".$newfile.": ";
		if(!file_exists($newfile)){
			if (!copy($file, $newfile)) {
				$msg.="Failed to copy ";
			}else{
				$msg.="Copied";
			}
		}
		$msg.="</div>";//*/
	}
}
echo $msg;
?>