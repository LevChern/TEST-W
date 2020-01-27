<?PHP //header("Content-Type: text/html; charset=utf-8");
?>
<?php
	$gr = $_REQUEST["gr"];
	if (empty($gr)){
		$gr = $_REQUEST["gr1"];
		if (empty($gr)){
			echo "<BR>Группа не задана<BR>";	
			return;
		}
		//$gr = $_REQUEST["gr1"];
	}	
	$fam = $_REQUEST["fam"];
	$Lfam = $_REQUEST["Lfam"];
	echo "<BR>Группа $gr fam=$fam";
?>	
