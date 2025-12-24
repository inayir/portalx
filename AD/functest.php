<html>
<head>
<script src="/vendor/jquery/jquery.min.js"></script>
<script src="/AD/ad_functions.js"></script>
</head>
<body>
<input type="text" id="description" value=""/><br>
<input type="text" id="ou" value=""/><br>
<div id="rp"></div>
<script>
$('#description').on("blur", function(){ 
	var dep=dep_name($('#description').val(),50,0); console.log('->'+dep);
	if($('#ou').val()==''){
		$('#rp').html(dep);
	}
});
</script>
</body>
</html>