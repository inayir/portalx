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
$form=[
	"form"=> "YFR-040",
	"tanimi"=> "İzin Talep Formu",
	"category"=> "K1-04",
	"key1"=>"_id",
	"datakeydoc"=>"personel",
	"datakey"=>"sicilno",
	"kontrol"=> "ik@aselsankonya.com.tr",
	"onay"=> "M",
	"ack"=> "Personelin izin taleplerini girmek için kullanacağı standart formdur.",
	"not"=> "İzin talebinde bulunan personel, bağlı bulunduğu tüm yöneticilerden hiyerarşik düzende onay imzalarını almak ve talep formunu Kurumsal Yönetim Direktörlüğüne / İnsan Kaynaklarına iletmekle yükümlüdür.",
	"formdate"=> "01.01.2024",
	"form_secure"=>"1",
	"orientation"=>"P",
	"aktif"=>"1",
	"fields"=> [
		"field_1"=> [
			"name"=>"adisoyadi",
			"label"=>"Adı Soyadı",
			"type"=> "p",
			"fromtable"=> "personel",
			"fromfield"=> "adisoyadi",
			"col"=> "1",
		],
		"field_2"=> [
			"name"=>"sicilno",
			"label"=>"Sicil Numarası",
			"type"=> "p",
			"fieldsend"=>"1",
			"fromtable"=> "personel",
			"fromfield"=> "sicilno",
			"col"=> "2",
		],
		"field_3"=> [
			"name"=>"unvan",
			"label"=>"Unvanı",
			"type"=> "p",
			"fromtable"=> "personel",
			"fromfield"=> "unvan",
			"col"=> "1",
		],
		"field_4"=> [
			"name"=>"birim",
			"label"=>"Birimi",
			"type"=> "p",
			"fromtable"=> "personel",
			"fromfield"=> "birim",
			"col"=> "2",
		],
		"field_5"=> [
			"name"=>"manager",
			"label"=>"Yöneticisi",
			"type"=> "p",
			"fromtable"=> "personel",
			"fromfield"=> "manager",
			"col"=> "1",
		],
		"field_6"=> [
			"name"=>"startdate",
			"label"=>"İzin Başlama Tarihi",
			"type"=> "date",
			"ftype"=> "date",
			"mask"=> "00.00.0000",
			"ack"=> "İzin Başlama Tarihi",
			"mandatory"=> "1",
			"col"=> "1",
		],
		"field_7"=> [
			"name"=>"startdatetime",
			"label"=>"İzin Başlama Saati",
			"type"=> "select",
			"ftype"=> "datetime",
			"mask"=> "00.00",
			"default_value"=> "7:30",
			"ack"=> "İzin Başlama Zamanı",
			"mandatory"=> "1",
			"col"=> "2",
			"options"=> [
				"s1"=>"7:30", "s1label"=> "7:30",
				"s2"=>"8:00", "s2label"=> "8:00",
				"s3"=>"8:30", "s3label"=> "8:30",
				"s4"=>"9:00", "s4label"=> "9:00",
				"s5"=>"9:30", "s5label"=> "9:30",
				"s6"=>"10:00", "s6label"=> "10:00",
				"s7"=>"10:30", "s7label"=> "10:30",
				"s8"=>"11:00", "s8label"=> "11:00",
				"s9"=>"11:30", "s9label"=> "11:30",
				"s10"=>"12:00", "s10label"=> "12:00",
				"s11"=>"13:00", "s11label"=> "13:00",
				"s12"=>"13:30",	"s12label"=> "13:30",
				"s13"=>"14:00",	"s13label"=> "14:00",
				"s14"=>"14:30",	"s14label"=> "14:30",
				"s15"=>"15:00",	"s15label"=> "15:00",
				"s16"=>"15:30",	"s16label"=> "15:30",
				"s17"=>"16:00",	"s17label"=> "16:00",
				"s18"=>"16:30",	"s18label"=> "16:30",
			],
		],
		"field_8"=> [
			"name"=>"enddate",
			"label"=>"İzin Bitiş Tarihi",
			"type"=> "date",
			"ftype"=> "date",
			"mask"=> "00.00.0000",
			"ack"=> "İzin Bitiş Tarihi",
			"mandatory"=> "1",
			"col"=> "1",
		],
		"field_9"=> [
			"name"=>"enddatetime",
			"label"=>"İzin Bitiş Saati",
			"type"=> "select",
			"ftype"=> "datetime",
			"mask"=> "00.00",
			"default_value"=> "17:00",
			"ack"=> "İzin Bitiş Zamanı",
			"mandatory"=> "1",
			"col"=> "2",
			"options"=> [
				"s1"=>"7:30", "s1label"=> "7:30",
				"s2"=>"8:00", "s2label"=> "8:00",
				"s3"=>"8:30", "s3label"=> "8:30",
				"s4"=>"9:00", "s4label"=> "9:00",
				"s5"=>"9:30", "s5label"=> "9:30",
				"s6"=>"10:00", "s6label"=> "10:00",
				"s7"=>"10:30", "s7label"=> "10:30",
				"s8"=>"11:00", "s8label"=> "11:00",
				"s9"=>"11:30", "s9label"=> "11:30",
				"s10"=>"12:00", "s10label"=> "12:00",
				"s11"=>"13:00", "s11label"=> "13:00",
				"s12"=>"13:30",	"s12label"=> "13:30",
				"s13"=>"14:00",	"s13label"=> "14:00",
				"s14"=>"14:30",	"s14label"=> "14:30",
				"s15"=>"15:00",	"s15label"=> "15:00",
				"s16"=>"15:30",	"s16label"=> "15:30",
				"s17"=>"16:00",	"s17label"=> "16:00",
				"s18"=>"16:30",	"s18label"=> "16:30",
				"s19"=>"17:00",	"s19label"=> "17:00",
			],
		],
		"field_10"=> [
			"name"=>"workdate",
			"label"=>"İşe Başlama Tarihi",
			"type"=> "date",
			"ftype"=> "date",
			"mask"=> "00.00.0000",
			"ack"=> "Çalışmaya Başlama  Tarihi",
			"mandatory"=> "1",
			"col"=> "1",
		],
		"field_11"=> [
			"name"=>"workdatetime",
			"label"=>"İşe Başlama Saati",
			"type"=> "select",
			"ftype"=> "datetime",
			"mask"=> "00.00",
			"default_value"=> "7:30",
			"ack"=> "Çalışmaya Başlama Zamanı",
			"mandatory"=> "1",
			"col"=> "2",
			"options"=> [
				"s1"=>"7:30", "s1label"=> "7:30",
				"s2"=>"8:00", "s2label"=> "8:00",
				"s3"=>"8:30", "s3label"=> "8:30",
				"s4"=>"9:00", "s4label"=> "9:00",
				"s5"=>"9:30", "s5label"=> "9:30",
				"s6"=>"10:00", "s6label"=> "10:00",
				"s7"=>"10:30", "s7label"=> "10:30",
				"s8"=>"11:00", "s8label"=> "11:00",
				"s9"=>"11:30", "s9label"=> "11:30",
				"s10"=>"12:00", "s10label"=> "12:00",
				"s11"=>"13:00", "s11label"=> "13:00",
				"s12"=>"13:30",	"s12label"=> "13:30",
				"s13"=>"14:00",	"s13label"=> "14:00",
				"s14"=>"14:30",	"s14label"=> "14:30",
				"s15"=>"15:00",	"s15label"=> "15:00",
				"s16"=>"15:30",	"s16label"=> "15:30",
				"s17"=>"16:00",	"s17label"=> "16:00",
				"s18"=>"16:30",	"s18label"=> "16:30",
			],
		],
		"field_12"=> [
			"name"=>"izsure",
			"label"=>"İzin Süresi",
			"type"=> "text",
			"ftype"=> "int",
			"mask"=> "00",
			"default_value"=> "1",
			"ack"=> "İzin Süresi",
			"mandatory"=> "1",
			"col"=> "1",
			"script"=>"$('#izsure').on('focus', function(){
				var son=Array();
				var date1=$('#startdate').val();
				var date2=$('#enddate').val();
				if (date1!=date2){ //Gun hesabı
					son=calcDate(date1,date2);
					$('#izsure').val(son['total_days']+1);
					$('#izsurec').val(son['text']);
				}else{ //saat hesabı		
					var saatfark=calcHour($('#startdatetime').val(),$('#enddatetime').val());
					if(saatfark>5) { saatfark-=1; }
					$('#izsure').val(saatfark);
					$('#izsurec').val('S');
				}
			});",
		],
		"field_13"=> [
			"name"=>"izsurec",
			"label"=>"İzin Süresi Saat/Gün",
			"type"=> "select",
			"ftype"=> "text",
			"default_value"=> "D",
			"ack"=> "İzin Süresi S/G",
			"mandatory"=> "1",
			"col"=> "2",
			"options"=> [
				"s1"=> "D", "s1label"=>"Gün",
				"s2"=> "S",	"s2label"=> "Saat",
			],
		],
		"field_14"=> [
			"name"=>"iztip",
			"label"=>"İzin Tipi",
			"type"=> "radio",
			"ftype"=> "text",
			"default_value"=> "Y",
			"ack"=> "İzin Tipi",
			"mandatory"=> "1",
			"col"=> "1",
			"options"=> [
				"s1"=>"M", "s1label"=> "Mazeret İzni",
				"s2"=>"Y", "s2label"=> "Yıllık İzin",
				"s3"=>"E", "s3label"=> "Evlilik İzni",
				"s4"=>"D", "s4label"=> "Doğum İzni",
				"s5"=>"O", "s5label"=> "Ölüm İzni",
				"s6"=>"S", "s6label"=> "Süt İzni",
				"s7"=>"L", "s7label"=> "Ödül İzni",
				"s8"=>"I", "s8label"=> "İdari İzin",
				"s9"=>"U", "s9label"=> "Ücretsiz İzin",
			],			
		],
		"field_15"=> [
			"name"=>"adres",
			"label"=>"İznin Geçirileceği Adres",
			"type"=> "textarea",
			"ftype"=> "blob",
			"ack"=> "İznin geçirileceği adres ikamet adresinden farklı ise doldurulması zorunludur.",
			"mandatory"=> "1",
			"col"=> "1",
		],
		"field_16"=> [
			"name"=>"aciklama",
			"label"=>"İzin Açıklaması",
			"type"=> "text",
			"ftype"=> "text",
			"ack"=> "Zorunludur.",
			"mandatory"=> "1",
			"col"=> "1",
		],
		"field_17"=> [
			"name"=>"cdate",
			"label"=>"İzin Talep Tarihi",
			"type"=> "datetime",
			"ftype"=> "datetime",
			"mask"=> "00.00.0000 00:00",
			"ack"=> "Formun Dolduruluş Tarihi",
			"mandatory"=> "1",
			"col"=> "1",
		],
		"field_18"=> [
			"name"=>"onaylayan",
			"label"=>"Onaylayan",
			"type"=> "text",
			"ftype"=> "text",
			"ack"=> "Onaylayan",
			"mandatory"=> "0",
			"onay"=>"1",
			"col"=> "1",
		],
		"field_19"=> [
			"name"=>"onaydate",
			"label"=>"Onay Tarihi",
			"type"=> "datetime",
			"ftype"=> "datetime",
			"mask"=> "00.00.0000 00:00",
			"ack"=> "Onay Tarihi",
			"mandatory"=> "0",
			"onay"=>"1",
			"col"=> "2",
		]
	]
];
//
$baglanti = new MongoDB\Driver\Manager("mongodb://localhost:27017"); 
//
$veri = new MongoDB\Driver\BulkWrite();
$veri->insert($form);
$sonuc = $baglanti->executeBulkWrite("DB01.Forms", $veri);
echo $sonuc->getInsertedCount() . ' kayıt eklendi.';

?>