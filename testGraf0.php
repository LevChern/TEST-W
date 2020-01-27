<?php   header("Content-Type: text/html; charset=UTF-8"); 
// test_Graf.php
	//$gr  = $_REQUEST["gr"]; 	// 
	//$fam  = $_REQUEST["fam"]; 	// 
	//$disc  = $_REQUEST["trdisc"]; 	// 
	//echo "<br>disc=$disc";
	$nf = "GR-$gr/$fam/graf-$disc.json";
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
	}
	//echo "<br>nf=$nf";
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
<?php 
	echo "nf='".$nf."'\n";   // файл
	echo "gr='".$gr."'\n";
	echo "fam='".$fam."'\n"; 
	echo "disc='".$disc."'\n"; 
?>
stat=0
$(document).ready( function(){  // вызов нужных функций 
//	$.getJSON('GR-'+gr+'/trening.json', function(data){
//		for(var i=0; i<data.disc.length; i++){ //   {"prog":1},
	$.getJSON(nf,function(data){
		document.getElementById("d1").value=data.graf[0]
		document.getElementById("d2").value=data.graf[1]
		document.getElementById("d3").value=data.graf[2]
		document.getElementById("d4").value=data.graf[3]
		document.getElementById("d5").value=data.graf[4]
		document.getElementById("d6").value=data.graf[5]
		document.getElementById("d7").value=data.graf[6]
		alert(nf+" "+data.graf[0]+data.graf[6])
		$("#d7").attr("value",777)
	})
})

function fsel(){  // выбор студента

}
function setgraf(){ // задать
	if(stat==0){
		$('#d1').removeAttr('disabled');
		$('#d2').removeAttr('disabled');
		$('#d3').removeAttr('disabled');
		$('#d4').removeAttr('disabled');
		$('#d5').removeAttr('disabled');
		$('#d6').removeAttr('disabled');
		$('#d7').removeAttr('disabled');
		$('#setgr').attr('value','Сохранить');
		stat=1
	} else{ // Сохранить
		var time = document.getElementById("d1").value   // frms.$('#d1').attr("value")
		time += (";"+document.getElementById("d2").value)   
		time += (";"+document.getElementById("d3").value)   
		time += (";"+document.getElementById("d4").value)   
		time += (";"+document.getElementById("d5").value)   
		time += (";"+document.getElementById("d6").value)   
		time += (";"+document.getElementById("d7").value)   
		//alert('time= '+ time)
		//var mf = ms[1].split(' ')   // Фмилия 
		$("#graf").load("graf_save.php?nf="+nf+"&time="+time,  // +"&fam="+mf[0]
			function(){
				$('#setgr').attr('value','Задать');
				$('#d1').attr('disabled',true);
				$('#d2').attr('disabled',true);
				$('#d3').attr('disabled',true);
				$('#d4').attr('disabled',true);
				$('#d5').attr('disabled',true);
				$('#d6').attr('disabled',true);
				$('#d7').attr('disabled',true);
				$('#setgr').attr('value','Задать');
				stat=0

			}
		)
	}   
}
</script>
</head>
<body onload='fsel()'>
<form><input type='button' value='Закрыть окно' onclick = 'window.close()' class='b2'>
</form>
<b>График тренинга по дисциплине "<?php echo $name;?>"</b>
<table class='tw'  cellspacing='0'>
	<tr><td>
		<input type="button" onclick='setgraf()' id='setgr' value="Задать" class='b2'>
	<tr><td valign='top'>
	<span id='session'>
		<div id="graf"></div>
		<table border='1' cellspacing='0' cellpadding='3'>
		<tr><th>День<br>недели<th>Время<br>(мин)
		<tr><td>пн<td><input type='number' min="0" max="60" step="5" value='15' id='d1' size='1' disabled>
		<tr><td>вт<td><input type='number' min="0" max="60" step="5" value='15' id='d2' size='1' disabled>
		<tr><td>ср<td><input type='number' min="0" max="60" step="5" value='15' id='d3' size='1' disabled>
		<tr><td>чт<td><input type='number' min="0" max="60" step="5" value='15' id='d4' size='1' disabled>
		<tr><td>пт<td><input type='number' min="0" max="60" step="5" value='15' id='d5' size='1' disabled>
		<tr><td>сб<td><input type='number' min="0" max="60" step="5" value='15' id='d6' size='1' disabled>
		<tr><td>вс<td><input type='number' min="0" max="60" step="5" value='3' id='d7' size='1' >
		</table>
	</span>
</table>
</table>
</body>
