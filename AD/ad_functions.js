function crea_name(gn, gnuzunluk, sn, snuzunluk, ayrac, lower){
	/*Kullanıcı isimlerini düzeltir*/
	var ad="";
	if(gnuzunluk==''){ gnuzunluk=99; }
	gnuzunluk=parseInt(gnuzunluk); 
	var gn2='';
	if(gn.indexOf(' ')>0){ 
		gn2=gn.substr((gn.indexOf(' ')+1),gnuzunluk); 
	}
	gn=gn.replace(/\s/g,'');
	if(gnuzunluk<99){ ad=gn.substr(0,gnuzunluk); }else { ad=gn; }
	//iki kelime ise her kelimenin ilk harfini al.
	ad=ad+gn2; 
	if(snuzunluk==''){ snuzunluk=99; }
	snuzunluk=parseInt(snuzunluk); 
	sn=sn.replace(" ", "");
	if(snuzunluk<99){ sn=sn.substr(0,snuzunluk); }
	//küçük harfe çevir, türkçe düzelt, al
	var usr=ad+ayrac+sn;
	var letters={"İ":"I","ı":"i","Ş":"S","ş":"s","Ğ":"G","ğ":"g","Ü":"U","ü":"u","Ö":"O","ö":"o","Ç":"C","ç":"c"};
	usr=usr.replace(/((^A-Z|^a-z|İ|ı|Ş|ş|Ğ|ğ|Ü|ü|Ö|ö|Ç|ç))/g, function(letter){ 
		return letters[letter];
	});
	if(lower==1){ usr=usr.toLowerCase(); }  //burada hata olabilir.
	return usr;
}
function dep_name(desc,uzunluk,lower){
	if(uzunluk==''){ uzunluk=99; }
	uzunluk=parseInt(uzunluk); 
	desc=desc.replace(/\s/g,'');
	if(uzunluk<99){ desc=desc.substr(0,uzunluk); } 
	//küçük harfe çevir, türkçe düzelt, al
	var letters={"İ":"I","ı":"i","Ş":"S","ş":"s","Ğ":"G","ğ":"g","Ü":"U","ü":"u","Ö":"O","ö":"o","Ç":"C","ç":"c"};
	if(lower==1){ desc=desc.toLowerCase(); }
	desc=desc.replace(/((^A-Z|^a-z|İ|I|ı|Ş|ş|Ğ|ğ|Ü|ü|Ö|ö|Ç|ç))/g, function(letter){ 
		return letters[letter];
	});
	return desc;//*/
	
}