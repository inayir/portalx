<script>
$(document).ready(function () {
	$('#send').on("click", function(){ //gönder	console.log('sended:');
		var opt={
			target	: '#ret',
			type	: 'POST',
			url 	: './set_formdata.php',
			contentType: 'application/x-www-form-urlencoded;charset=utf-8',
			beforeSubmit : function(){
if($('#startdate').val()==''){ alert('Tarihi doğru giriniz:İzin Başlama Tarihi'); return false; }
if($('#enddate').val()==''){ alert('Tarihi doğru giriniz:İzin Bitiş Tarihi'); return false; }
if($('#workdate').val()==''){ alert('Tarihi doğru giriniz:İşe Başlama Tarihi'); return false; }
if($('#izsure').val()==''){ alert('Boş geçilemez.'); return false; }
if($('#adres').val()==''){ alert('Boş geçilemez.'); return false; }
if($('#aciklama').val()==''){ alert('Boş geçilemez.'); return false; }
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
	$('#izsure').on('focus', function(){
	  var son=Array();
	  var date1=$('#startdate').val();
	  var date2=$('#enddate').val();
	  if (date1!=date2){ //Gun hesabı
	   son=calcDate(date1,date2);
	   $('#izsure').val(son['total_days']+1);
	   $('#izsurec').val(son['text']);
	  }else{ //saat hesabı		
	   var saatfark=calcHour($('#startdatetime').val(),$('#enddatetime').val());
	   if(saatfark>5) { saatfark-=oglearasi; }
	   $('#izsure').val(saatfark);
	   $('#izsurec').val('S');
	  }
	});		
});
</script>