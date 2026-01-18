<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Etiket yazdır</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="/vendor/googleapis/Nunito.css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
	<!--JQuery-->
    <script src="/vendor/jquery/jquery.js"></script>
    <!-- Bootstrap core JavaScript-->
	<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.css" rel="stylesheet">
<style>
@page{ margin:0; }
@media print { size: 35mm 25mm; }
</style>
</head>
<body id="page-top"><!-- Page Wrapper -->
    <div id="wrapper">
	<article class="border printTable" id="printTable">
	<section>
	<table>
	<tr>
		<td><span>Kod:</span><span class="f_code">100001</span></td>
		<td rowspan="2"><div class="p-2" id="divkarekod" width="20"></div></td>
	</tr>
	<tr>
		<td><span>S/N:</span><span class="f_serial">MVC1201232</span></td>
	</tr>
	</table>
	</section>
	</article>
	<button onClick="printContent('printTable');">Yazdır</button>
	</div>
<script>
function printContent(id){
	/*Source: https://stackoverflow.com/questions/11634153/how-to-add-a-print-button-to-a-web-page */
	var str=document.getElementById(id).innerHTML
	newwin=window.open('','printwin');
	newwin.document.write('<HTML moznomarginboxes mozdisallowselectionprint>\n<HEAD>\n');
	newwin.document.write('<TITLE>.</TITLE>\n');
	newwin.document.write('<script>\n');
	newwin.document.write('function chkstate(){\n');
	newwin.document.write('if(document.readyState=="complete"){\n');
	newwin.document.write('window.close()\n');
	newwin.document.write('}\n');
	newwin.document.write('else{\n');
	newwin.document.write('setTimeout("chkstate()",2000)\n');
	newwin.document.write('}\n');
	newwin.document.write('}\n');
	newwin.document.write('function print_win(){\n');
	newwin.document.write('window.print();\n');
	newwin.document.write('chkstate();\n');
	newwin.document.write('}\n');
	newwin.document.write('<\/script>\n');
	newwin.document.write('	<style>\n');
	newwin.document.write('@page{ width: 35mm; height: 25mm; margin:0.5mm; border: 0.1mm solid; border-radius:1mm; }\n');
	newwin.document.write('@media print { size: 35mm 25mm; }\n');
	newwin.document.write('</style>\n');
	newwin.document.write('</HEAD>\n');
	newwin.document.write('<BODY onload="print_win()">\n');
	newwin.document.write(str);
	newwin.document.write('</BODY>\n');
	newwin.document.write('</HTML>\n');
	newwin.document.close();
}
</script>
</body>
</html>