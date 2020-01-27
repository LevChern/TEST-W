<?php   header("Content-Type: text/html; charset=UTF-8"); 
// testNewphp  test_XML_tr.php ...  include "testNew.php";   кнопка тренинг+
// тест строится их json препода
	$prep  = $_REQUEST["prep"]; 	// 
	$disc  = $_REQUEST["disc"]; 	// 
	$tema  = $_REQUEST["tema"]; 	// 
	$nf = "TEST-$prep/TEST-$disc-$tema.json";
	//echo $nf;
	// перемешать и вырнуть
	$mm = file($nf); $smm = ""; foreach($mm as $s) $smm.=$s;
	echo $smm;
/*	
	$mj = json_decode($smm);
	$mnew = array();
	foreach($mj->disc as $dd){
		if ($dd[0]==$disc){$dd[1]=$num;}
		$mnew[] = $dd;
	}
	$mout = array("disc"=>$mnew);
	$ss = json_encode($mout);
*/	
?>