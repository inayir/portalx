<?php
/*Formu ekledik...*/
/* Not=> LIKE => ["name"=> /.*aranan.*/ //]
/* 
p			görünen bilgi
text		input
date		datetime
textarea	varchar/blob
select		text/datetime vb.
radio		value/true-false
checkbox	value(s)
file	dosya yükleme (multi seçimli de olabilir)
p tipinde veri gösterilir ama form kaydında gerekli bir alan ise fieldsend=>"1" 
orientation : yazdırmada yön: P Portrait/Dikey L: Landscape/Yatay

Not: Aynı field değiştirilmesin isteniyor hem de formdan verisinin alınması isteniyorsa iki ayrı field olarak girilir. Birisi p olarak gösterilir, diğeri gizli text olur.
---------------------------------------------------
form=>
	name=> kodu
	tanimi=> adi
	table=> verilerin kaydedileceği dosya
	kontrol=> hesap (bir hesaba yönlendirilir, mailine bilgi atılır.)
	onay=> M Müdür(Manager), D Direktör(Director)
	datakeydoc=>yeni formda anahtar bilginin getirileceği tablo(Mongo) 
	datakey=> veri dosyada farklı fielddan gelecekse girilir. Değilse formdata field adı geçerlidir.
*/
$tar=date("Y-m-d H:i:s", strtotime("now"));
$form=[
	"test"=> "Sweep",
	"tanimi"=> "Bağlantı testi",
	"zaman"=> $tar,
];
//
$baglanti = new MongoDB\Driver\Manager("mongodb://localhost:27017"); 
//
$veri = new MongoDB\Driver\BulkWrite();
$veri->insert($form);
$sonuc = $baglanti->executeBulkWrite("DB01.Test", $veri);
echo $sonuc->getInsertedCount() . ' kayıt eklendi.';

?>