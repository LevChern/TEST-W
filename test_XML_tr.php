<?php   header("Content-Type: text/html; charset=UTF-8"); 
// test_XML_tr.php  начать тренинг
	//$ftest  = $_REQUEST["ftest"]; 	// файл.xml 
	//$pps  = $_REQUEST["pps"]; 	// папка с данными
	//$ff =  $pps."/TEST-".$ftest;
	$gr  = $_REQUEST["gr"]; 	// 
	$fam  = $_REQUEST["fam"]; 	// 
	$kdisc  = $_REQUEST["trdisc"]; 	//  kod;наименования;тема;prep;
	echo "<br>kdisc=$kdisc";
	$mdisc = explode(";",$kdisc); 
	$disc = $mdisc[0]; 
	$name = $mdisc[1];
	$graf = $_REQUEST["graf"]; 	// 
	if (!empty($graf)){ // график по дисциплине name
	
		include "testGraf.php";
		
		return;
	}
	$nf = "GR-$gr/$fam/trening-$disc.json";
/*	
	$tema="1";
	if (is_file($nf)){  // [["1","1","2","17-06-25","23:10","12"]]
		$mm = file($nf); $smm = ""; foreach($mm as $s) $smm.=$s;
//echo $smm;
		$mj = json_decode($smm);
		foreach($mj as $dd){
			if ($dd[1]==$dd[2]){ // тема пройдена
				$tema=$dd[0]+1;	
			}
		}
	}else{
		
	}
*/	
	$tema = $_REQUEST["tema"]; 	// 
	$ff =  "GR-$gr/$fam/TEST-$disc-$tema";
	$prep = $_REQUEST["prep"]; 	// 
	$trnew = $_REQUEST["trnew"]; 	// 
	if (!empty($trnew)){ // тест по полученному json преподавателя
		include "testNew.php";
		return;
	}
	//echo "<br>ff=$ff";
?>
<?php
//test_XML_tr.php   
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
<?php 
	echo "ff='".$ff."'\n"; 
	echo "gr='".$gr."'\n";
	echo "fam='".$fam."'\n"; 
	echo "disc='".$disc."'\n"; 
	echo "tema='".$tema."'\n"; 
?>
prog = "lib_xml.php" 	// работа со КР 
qy=0;qa=0
time0=0; time1=0
$(document).ready( function(){  // вызов нужных функций 
	var date = new Date();
    time0 = 60*60*date.getHours()+60*date.getMinutes()+date.getSeconds();

	hp = 50; 
	//alert('test_XML')
	$('#tests').load(prog,{"nfile":ff+".xml","size":hp,"wpt":150},
		function(data){
			qa = document.getElementById("qall").value	
		}
	)	// test
	
});
function fsingle(n,n1){
	//$("res_"+n).html("n="+n)
	var mv=document.getElementsByName("s_"+n) // [0].value
	var i1=-1
	for(i=0; i<mv.length; i++){
		if (mv[i].checked) {i1=i; break}
	}
	//alert("i1="+i1+" n1="+n1)
	var res = "?" // (i1==n1)?"да":"нет"
	if (i1==n1)qy++
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
	var res = "?"//(s==s0)?"да":"нет"
	if (s==s0)qy++
	$("#res_"+n).html(res)
}
function fclose(){ // s='11000'
	var qq = document.getElementById("qall").value
	//alert(qyes+" из "+qq)
	var date = new Date();
    time1 = 60*60*date.getHours()+60*date.getMinutes()+date.getSeconds();
	var time = (time1-time0)  
	//alert(time0+" "+time1)
	$.get("testResult.php?gr="+gr+"&fam="+fam+"&disc="+disc+"&tema="+tema+"&qy="+qy+"&qa="+qq+"&time="+time,
		function(data){
			$("#result").html("Правильных ответов "+qy+" из "+qa+". Потрачено секунд "+time)
		}
	)
	
}
</script>
</head>
<body>
<form>
    <input type='button' class='b2' value='Вернуться назад' onclick = 'window.close();window.history.back()'>
	<input type='button' value='Завершить' onclick = 'fclose()' class='b2'>
	<span id="result"></span>
</form>

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
