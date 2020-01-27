<?php header("Content-Type: text/html; charset=UTF-8"); 
// сохранить список групп Вызов из sprav-gr2
	$grs = $_REQUEST['grs'];   //  pi40|pi3...
	$Lfam = $_REQUEST['Lfam'];   //  
	$fn = "TEST-$Lfam/grups.txt";
	$h = fopen($fn,"w");
	$mg = explode("|",$grs);
	foreach($mg as $gr){
		fputs($h,"$gr\n");
	}
	fclose($h);
?>
