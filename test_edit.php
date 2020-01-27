<?php   header("Content-Type: text/html; charset=UTF-8"); 
	$ftest  = $_REQUEST["ftest"]; 	// файл.xml для редактирования
	$pps  = $_REQUEST["pps"]; 	// папка с данными
	$ff =  $pps."/".$ftest;
	//echo "ff=$ff";
	if (false){ // !is_file($ff)){ // создать файл
		$h = fopen($ff,"w");
		fputs($h,'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>');
		fputs($h,"\n<темы>");
		fputs($h,"\n</темы>");
		fclose($h);
	}	
?>
<!DOCTYPE html>    
<HTML><head>
<META http-equiv=Content-Type content="text/html; charset="UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<style>
  select { width: 100px; /* Ширина списка в пикселах */ } 
</style>   
<script src="jquery-latest.min.js"></script>
<script>  
<?php echo "ff='".$ff."'\n" ?>
prog = "lib_sprav.php" 	// работа со КР 
$(document).ready( function(){  // вызов нужных функций 
	hp = 50; 
	$('#tests').load(prog,{"nfile":ff+".txt","size":hp,"wpt":150})	// test
});
function fwrite(typ){  //записать как XML (json)
	var test = document.getElementById('test').value
	//alert(test)
	$('#msg').load(prog,{"nfile":ff+".txt","val":test,"wtask":typ})	// 
}
</script>
</head>
<body>
<form><input type='button' value='Закрыть окно' onclick = 'window.close()' class='b2'></form>
<form name='frm1' action="lib_sprav.php">
<table border cellspacing=0>
	<tr><td>Тест <? echo $ftest; ?> 
		<input type='button' onclick='fwrite(1)' value='записать/обновить тест' class='b3' >  <!-- -->
		<input type='button' onclick='fwrite(2)' value='записать как XML' class='b3' >  <!-- -->
		 <span id="msg"></span>
	<tr>
		<td><div id="tests"></div> <!-- <textarea name='test'--> 
</table>
</form>	
</body>
