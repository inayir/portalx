function calcDate(date1, date2){
	var calcDate_data = {}; 
	// memorize input to detect invalid input
	calcDate_data.input1 = date1;
	calcDate_data.input2 = date2;
	// initiate date object
	var dt_date1 = new Date(date1);
	var dt_date2 = new Date(date2);
	// get the time stamp
	date1 = dt_date1.getTime();
	date2 = dt_date2.getTime();
	var calc;
	// check which time stamp is greater
	if (date1 > date2){	calc = new Date(date1 - date2) ; } else { calc = new Date(date2 - date1) ; }
	// retrieve the date, month and year
	var calc_format_tmp = calc.getDate() + '-' + (calc.getMonth()+1)+ '-'+calc.getFullYear();
	var days_passed = parseInt(Math.abs(calc.getDate()-1));
	var days 	= "D"; 
	// convert to days and sum together
	//var total_days = (years_passed * 365) + (months_passed * 30.417) + days_passed;
	calcDate_data.last_result = {
		"total_days" : Math.round(days_passed),
		"text" :  days
	};
	// message returned when an invalid date was input
	if (! calcDate_data.last_result.text || calcDate_data.input1==null || calcDate_data.input2==null) {
		calcDate_data.last_result.text = "Bir hatalı giriş var.";
	}
	// return the result
	return calcDate_data.last_result;
}
function calcHour(h1, h2){
	var ilk=h1.split(':');
	var son=h2.split(':'); 
	var saatfark=son[0]-ilk[0]; 
	if(ilk[1]==30){ saatfark=saatfark-(0.5); }
	if(son[1]==30){ saatfark=saatfark+(0.5); }	
	return saatfark;
}
function toDayLocal(){
	const _date = new Date().toLocaleDateString();
	return _date;
}