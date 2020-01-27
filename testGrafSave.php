<?php // testGrafSave  вызов из testGraf
	$nf   = $_REQUEST["nf"];    // файл 
	$time = $_REQUEST["time"];  // график  1;2;3;5;6; 
	$mt = explode(";",$time);
	$mt1 = '{"graf":['.$mt[0];
	for ($i=1;$i<7;$i++){
		$mt1 .= (','.$mt[$i]);
	}
	$mt1 .= "]}";
	include "lib1_file.php";
	WriteAll($nf,$mt1);
	echo "<font coclor='red'>График изменен</font>";//  в $nf";
?>