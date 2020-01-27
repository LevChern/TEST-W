<?php
	$nf= $_REQUEST["nf"];  // файл 
	$time = $_REQUEST["time"];  // график  1;2;3;5;6; 
	$mt = explode(";",$time);
	$mt1 = '{"graf":['.$mt[0];
	for ($i=1;$i<7;$i++){
		$mt1 .= (','.$mt[$i]);
	}
	$mt1 .= "]}";
	include "lib1_file.php";
	WriteAll($nf,$mt1);
	echo "График задан";//  в $nf";
/*
	$fgr = "$gr/$gr".".txt";
	$mgr = file($fgr);
	$stud = $mgr[$st-1]; 
	$ms = explode(" ",$stud);
	$fam = $ms[0];
	$nst = translit8($fam);
	CreateDir("$gr/$nst");
	WriteAll("$gr/$nst/graf".".json",$mt1);
	echo "График задан в $gr/$nst";
*/	
?>