<?php header("Content-Type: text/html; charset=UTF-8"); 
// пароли студентов группы Вызов из index_prep
?>
<!DOCTYPE html>    
<HTML><head>
<META http-equiv=Content-Type content="text/html; charset="UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<script src="jquery-latest.min.js"></script>
</head>
<body>
<form><input type='button' value='Закрыть окно' onclick = 'window.close()'  class='b2'></form>
<?php
	$grup = $_REQUEST['grup'];   //  pi40
	include "lib1_file.php";

	echo "Группа <b>$grup</b>";
	$fgr = "GR-$grup/GR-$grup.txt";
	$mst = file($fgr); 
	echo "<table border  cellspacing=0 cellpadding=3 style='background-color:#F0F0F0'>";
	echo "<tr><th>psw<th>Фамилия<th>fam";
	foreach ($mst as $st){
		$mst = explode(" ",$st); $st = trim($mst[0]);	
		$Lfam = trim(translit8($st));
		$fpsw = fparol8($st);  	 // пароль
		echo "<tr><td>$fpsw<td>$st<td>$Lfam";
	}
	echo "</table>";
	return;
?>

</body>