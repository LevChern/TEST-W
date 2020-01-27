<?php    header("Content-Type: text/html; charset=UTF-8"); 
//	$.get("testResult.php?gr="+gr+"&fam="+fam+"&disc="+disc+"&qy="+qyes+"&qa="+qq)
//[  ["prog",0],  ["tiim",2],  ["WPr",4]]  - сколько роздано
	$gr	= $_REQUEST["gr"];		// группа
	$fam = $_REQUEST["fam"];	// фамили¤
	include "lib1_file.php";
	$Lfam = translit8(trim($fam));
	$kdisc = $_REQUEST["disc"];	// код дисциплины
	$mdisc = explode(";",$kdisc); $disc = $mdisc[0]; $name = $mdisc[1];
	$nf = "$gr/$Lfam/graf-$disc.json";
	// {"graf":[5,15,5,5,0,15,15]
	if (is_file($nf)){
		$mm = file($nf); $smm = ""; foreach($mm as $s) $smm.=$s;
		$mj = json_decode($smm);
		$mall = 0;
		foreach($mj->graf as $dt){
			$mall += $dt;
		}
		//print_r($mj);
		echo "За неделю $mall мин<br>";
		echo "<table border cellspacing=0 cellpadding='7'>";
		echo "<tr><th>день<br>недели<th>время<br>(мин)";
		$md = array("пн","вт","ср","чт","пт","сб","вс");
		$i=0;
		//$s = $mj->graf;		print_r($s);
		foreach($mj->graf as $dt){
			$dd = $md[$i++];
			echo "<tr align='center'><td>$dd<td>$dt";
		}
		echo "</table>";
	}
?>