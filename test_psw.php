<?php header("Content-Type: text/html; charset=UTF-8");	
	$gr	= $_REQUEST["gr"];		// группа
	$fam = $_REQUEST["fam"];	// 
	$psw = $_REQUEST["psw"]; 	//

	include "lib1_file.php";
	$Lfam = translit8(trim($fam)); // фамилия студента
	$fpsw = fparol8($fam);  	 // пароль
	if ($fpsw!=$psw){ 
		echo "?<font color='red'>Неправильный пароль $fpsw!=$psw $fam $Lfam</font>"; 
	}else{
		echo "yes";
	}	
	return;
?>	