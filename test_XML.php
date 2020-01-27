<?php   header("Content-Type: text/html; charset=UTF-8"); 
	$ftest  = $_REQUEST["ftest"]; 	// файл.xml для редактирования
	$pps  = $_REQUEST["pps"]; 	// папка с данными
	$ff =  $pps."/TEST-".$ftest;
	//echo "ff=$ff";

?>
<!DOCTYPE html>    
<HTML><head>
<META http-equiv=Content-Type content="text/html; charset="windows-1251">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<style>
  select { width: 100px; /* Ширина списка в пикселах */ } 
</style>   
<script src="jquery-latest.min.js"></script>
<script>  
<?php echo "ff='".$ff."'\n" ?>
prog = "lib_xml.php" 	// работа со КР 
$(document).ready( function(){  // вызов нужных функций 
	hp = 50; 
	//alert('test_XML')
	$('#tests').load(prog,{"nfile":ff+".xml","size":hp,"wpt":150})	// test
});
function fsingle(n,n1){
	//$("res_"+n).html("n="+n)
	var mv=document.getElementsByName("s_"+n) // [0].value
	var i1=-1
	for(i=0; i<mv.length; i++){
		if (mv[i].checked) {i1=i; break}
	}
	//alert("i1="+i1+" n1="+n1)
	var res = (i1==n1)?"да":"нет"
	$("#res_"+n).html(res)
}
function fmultiple(n,s0){ // s='11000'
	var mv=document.getElementsByName("m_"+n) // [0].value
	var s=""  // 00101
	for(i=0; i<mv.length; i++){
		//alert(mv[i].checked)
		s += (mv[i].checked)?"1":"0"
	}
	//alert("s="+s0+" s="+s)
	var res = (s==s0)?"да":"нет"
	$("#res_"+n).html(res)
}
</script>
</head>
<body>
<form><input type='button' value='Закрыть окно' onclick = 'window.close()' class='b2'></form>
<form name='frm1' action="lib_sprav.php">
<table border cellspacing=0>
	<tr><td>Тест <? echo $ftest; ?> 
	<!--
		<input type='button' onclick='fwrite(1)' value='записать/обновить тест' class='b3' >  
		<input type='button' onclick='fwrite(2)' value='записать как XML' class='b3' >  
	-->	
		 <span id="msg"></span>
	<tr>
		<td><div id="tests"></div> <!-- <textarea name='test'--> 
</table>
</form>	
</body>
