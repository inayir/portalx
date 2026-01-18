<?php
$arr=[];
$arr[]='Bilgisayar';
$arr[]='Ekran';
$arr[]='Printer';

if(is_array($arr)){ echo "Array:"; print_r($arr); }else{ echo "String:".$arr; }
?>