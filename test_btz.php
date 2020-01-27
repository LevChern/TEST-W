<?php header("Content-Type: text/html; charset=UTF-8");
// вызов из index_prep  Сохранить распределение вопросов по дисциплине
$prep = $_REQUEST["prep"];
$disc = $_REQUEST["disc"];
$qq = $_REQUEST["qq"];  //  4,3,10
$nf = "TEST-$prep/$disc-btz.json";
$fh = fopen($nf,"w");
fputs($fh,'{"btz":"'.$disc.'","quest":['.$qq.']}');	// 
fclose($fh); 
?>