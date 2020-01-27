<?php header("Content-Type: text/html; charset=UTF-8"); 
// Вызов из index_prep   "Мои группы"
	$Lfam = $_REQUEST['Lfam'];   //  pi40
?>
<!DOCTYPE html>    
<HTML><head>
<META http-equiv=Content-Type content="text/html; charset="UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<script src="jquery-latest.min.js"></script>
<script>
<?php
	$sel = "<select id='mygr' onchange='fsel_mygr()' size='20' style='width:100px'>";
	echo 'sel="'.$sel.'"'."\n";
	echo 'Lfam="'.$Lfam.'"'."\n";

?>
gr = ""
function fsel_gr(){
	document.getElementById("add").disabled = false
	document.getElementById("del").disabled = true
	gr = document.getElementById("allgr").value // prog;0;Программирование
//alert(gr)
	//var gr = $("#allgr").attr
}
function fgr_add(){
	$("#result").html("")
	document.getElementById("save").disabled = false
	var md = document.getElementById("mygr")
	var n = md.options.length
		
	for(var i=0;i<n;i++){ 
		if (md.options[i].value == gr){
			gr=""; break
		}
	}	
	if (gr.length>0){
		md.options.length++
		md.options[i].value = gr
		md.options[i].text  = gr
	}
}
function fsel_mygr(){
	document.getElementById("add").disabled = true
	document.getElementById("del").disabled = false
	
}
function fgr_del(){
	$("#result").html("")
	document.getElementById("save").disabled = false
	var mgr = document.getElementById("mygr").value
	var md = document.getElementById("mygr")
	var n = md.options.length
	var s = sel
	for(var i=0;i<n;i++){ 
		var vv = md.options[i].value
		var tt = md.options[i].text
		if (vv!=mgr){
			s += "<option value='"+vv+"'>"+tt;	
				
		}
	}
	s += "</select>"
	$("#dgr").html(s)
}
function fgr_save(){
	var md = document.getElementById("mygr")
	var n = md.options.length
	var s = ""
	for(var i=0;i<n;i++){ 
		var vv = md.options[i].value
		s += (i==0?"":"|") + vv	
	}
	//alert(s)
	$.get("sprav-save.php?grs="+s+"&Lfam="+Lfam,
		function(data){
			$("#result").html("<font color='red'> Список групп сохранен</font>")
		}
	);
}

</script>
</head>
<body>
<form><input type='button' value='Закрыть окно' onclick = 'window.close()'  class='b2'></form>
<?php
	$fam = $_REQUEST['fam'];   //  pi40
	include "lib1_file.php";

	echo "Преподаватель <b>$fam</b>";
	echo "<span id='result'></span>";
	
	$fgr = "TEST-$Lfam/grups.txt";
	//$mst = file($fgr); 
	
	echo "<table border  cellspacing=0 cellpadding=3 style='background-color:#F0F0F0'>";
	echo "<tr><th>Все группы<th>Действие<th>Мои группы";
	echo "<tr><td valign='top'>";
	
	$dh = opendir(".");
	echo "<select id='allgr' onchange='fsel_gr()' size='20' style='width:100px'>";
	while ($file = readdir($dh)){  // 
		$x = substr($file,0,3);  // GR-
		if ($x=="GR-"){
			$gr = substr($file,3);
			echo "<option value='".$gr."'>$gr";	
			//echo "<BR>$file";
		}
	}
?>
	
	<td valign='top'>
		<input id='add' type='button' disabled value='Добавить ->' onclick = 'fgr_add()' class='b2'>
		<br><input id='del' type='button'  disabled value='<- Удалить' onclick = 'fgr_del()' class='b2'>
		<br><input id='save' type='button'  disabled value='Сохранить' onclick = 'fgr_save()' class='b2'>
	<td valign='top'>
	
	<div id='dgr'>
<?php
	echo $sel;
	if (is_file($fgr)){
		$mgr = file($fgr); 
		foreach ($mgr as $gr){   
			echo "<option value='".$gr."'>$gr";	
		}
	}	
?>
	</select>
	</table>

</body>