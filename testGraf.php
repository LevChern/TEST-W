<?php   //header("Content-Type: text/html; charset=UTF-8"); 
// testGraf.php  test_XML_tr.php ...  include "testGraf.php";
	//$gr  = $_REQUEST["gr"]; 	// 
	//$fam  = $_REQUEST["fam"]; 	// 
	//$disc  = $_REQUEST["trdisc"]; 	// 
	//echo "<br>disc=$disc";
	$nf = "GR-$gr/$fam/graf-$disc.json";
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
<?php  // $nf = GR-ggg/famirt/fff.json
	$mnf = explode("/",$nf); $dir = $mnf[0]."/".$mnf[1];
	if (!is_dir($dir)) mkdir($dir);
	echo "nf='".$nf."'\n";   // файл
	echo "gr='".$gr."'\n";
	echo "fam='".$fam."'\n"; 
	echo "disc='".$disc."'\n";  // код дисциплины
?>
function setgraf(){ //  Сохранить
	var time = document.getElementById("d1").value   // frms.$('#d1').attr("value")
	time += (";"+document.getElementById("d2").value)   
	time += (";"+document.getElementById("d3").value)   
	time += (";"+document.getElementById("d4").value)   
	time += (";"+document.getElementById("d5").value)   
	time += (";"+document.getElementById("d6").value)   
	time += (";"+document.getElementById("d7").value)   
	//alert('time= '+ time)
	//var mf = ms[1].split(' ')   // Фмилия 
	$("#setgr").load("testGrafSave.php?nf="+nf+"&time="+time,  // +"&fam="+mf[0]
		function(){
			$('#d1').attr('disabled',true);
			$('#d2').attr('disabled',true);
			$('#d3').attr('disabled',true);
			$('#d4').attr('disabled',true);
			$('#d5').attr('disabled',true);
			$('#d6').attr('disabled',true);
			$('#d7').attr('disabled',true);
		}
	)
}
</script>
</head>
<body>
<form><input type='button' value='Закрыть окно' onclick = 'window.close()' class='b2'>
</form>
<b>График тренинга по дисциплине "<?php echo $name;?>"</b>
<table class='tw'  cellspacing='0'>
	<tr><td>
		<input type="button" onclick='setgraf()' value="Сохранить" class='b2'>
	<tr><td><span id='setgr'></span>	
	<tr><td valign='top'>
		<table border='1' cellspacing='0' cellpadding='3'>
		<tr><th>День<br>недели<th>Время<br>(мин)
<?php		
	$mdn = array("пн","вт","ср","чт","пт","сб","вс");
	if (is_file($nf)){  // {"graf":[0,15,15,15,15,10,10]}
		$mm = file($nf); $smm = ""; foreach($mm as $s) $smm.=$s;
		$mj = json_decode($smm);
		$j=0;
		foreach($mj->graf as $dd){
			$dn = $mdn[$j++];
			echo "<tr><td>$dn<td><input type='number' min='0' max='60' step='5' value='".$dd."' id='d".$j."' size='1'>";
		}
	}else{
		$j=0; $dd=15;
		foreach ($mdn as $dn){
			$j++;
			echo "<tr><td>$dn<td><input type='number' min='0' max='60' step='5' value='".$dd."' id='d".$j."' size='1'>";
		}
	}	
?>		
</table>
</body>
