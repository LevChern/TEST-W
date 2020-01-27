<?php  header("Content-Type: text/html; charset=UTF-8");
	$gr = $_REQUEST["gr"];
	$dkod = $_REQUEST["dkod"];
	
	
?>
<html>
<head>
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<script src="jquery-latest.min.js"></script>
<body>
<input valign='top' type='button' value='Закрыть окно' onclick = 'window.close()' class='b2'>
<br>
<?php
	echo "<b>Группа $gr. Дисциплина $dkod<b>";
	echo "<table border = '1' cellspacing = '0' cellpadding='5' class='t0' style='background-color:#F0F0F0'>";
	echo "<tr><th>Фамилия<th>пройдено<br>тем<th>затрачено<br>попыток<th>затрачено<br>секунд";
	$mst = file("GR-$gr/GR-$gr.txt");
	include "lib1_file.php";
	foreach ($mst as $st){
		$sst = explode(" ",$st); $fam=$sst[0];
		$Lfam = translit8($fam);
		$nf = "GR-$gr/$Lfam/trening-$dkod.json";
		$temap="0";	$ks=0; $sek=0;
		if (is_file($nf)){
			$mm = file($nf); $smm = ""; foreach($mm as $s) $smm.=$s;
			$mj = json_decode($smm);
			foreach($mj->history as $dt){ 
				// {"history":[["1","1",  "2","17-08-10","22:25","15"],
				$d3 = substr($dt[3],3);   //              тема прав всего    
				$ks++; $sek += (1*$dt[5]);
				if ($dt[1]==$dt[2]){ // пройденная тема
					$temap = 1*$dt[0];	
				}
			}	
		}
		echo "<tr><td>$fam<td>$temap<td>$ks<td>$sek";
	}
	echo "</table>";
?>	

	