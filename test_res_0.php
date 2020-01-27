<?php header("Content-Type: text/html; charset=UTF-8");
$res = $_REQUEST["res"];
$stud = $_REQUEST["stud"];
$grup = $_REQUEST["grup"];
$kres = $_REQUEST["kres"]; // первый раз kres=0 второй kres=1
//echo "id=$stud ответы=$res";
$mres = explode(";",$res);
$nf = "$grup/test$stud.txt";
if (!is_file($nf)){
	echo "<BR>Нет файла $nf";
}	

$mq0 = file($nf);
for ($j=2; $j<sizeof($mq0);$j++) {
	$sq = $mq0[$j];
// 1. В состав оперативной памяти входят:|A. Регистры процессора|B. Кэш процессора|C. *Оперативные запоминающие устройства
	$mm = explode("|",$sq);
	$ss = ""; $sr="";	
	for ($i=0; $i<6; $i++){   // было 5
		$s = trim($mm[$i]);
		if ($i<sizeof($mm)) {
			if ($i>0) {
				if (substr($s,0,1)=="*") {
					$sr.="1"; $s = substr($s,1); 
				}else $sr.="0";
			}	
		}	
	}	
	// $sr  = 0010    100    001    1 - правильный ответ
	$mqq[]= $sr;  //."'".$sr."'".",$kss";		
}
//$mqq[]="'Вопрос 2:...','1','0','0','','100'";
//$mqq[]="'Вопрос 3:...','0','1','','','01'";
$i=0; $kr=0; $res0=""; 
$kr1=0; // частичные ответы   11100   11000    не указан один
foreach ($mqq as $sq){  
	//echo "<BR>$sq ".$mres[$i];
	if($sq==$mres[$i]) $kr++;
	else {
		//echo "<BR>$sq $mres[$i]";
		$jj = strlen($sq); $kerr=0; $b = true;
		for($j=0; $j<$jj; $j++){
			$c1 = substr($sq,$j,1);      // пр.ответ
			$c2 = substr($mres[$i],$j,1);
			if ($c1=='0'){
				if ($c2=='1') { $b=false; break; }
			} else {  // 1
			    if ($c2=='0') { $kerr++; } //echo "<BR>$kerr $j $c2 $c1";
			}
		}	
		if ($b && $kerr<2) $kr1++;
	}
	$res0 .= ($sq.";");
	$i++;
}	
$ball0=5; $ball1=3;
if (is_file("$grup/balls")){
	$mb = file("$grup/balls");
	$mb1 = explode(";",trim($mb[0]));
	$ball0=$mb1[0]; $ball1=$mb1[1];
} 
$bb = $ball0*$kr + $ball1*$kr1;
// запись результатов
$nf1 = "$grup/test-res$stud.txt";
if (is_file($nf1)){ // второй проход
//	echo "<BR>Повторная запись запрещена";
	$mmf = file($nf1);
	$mbb = explode(";",trim($mmf[0]));
	$bb0 = $mbb[2];
	if ($bb>$bb0){	
		$nf2 = "$grup/test-0res$stud.txt";
		rename($nf1,$nf2); 
		$fh = fopen($nf1, "w"); // Открыть файл 
		fputs($fh,"$kr;$kr1;$bb\n");  // число правильных ответов; баллов 
		fputs($fh,$res."\n");	// ответы
		fputs($fh,$res0);	// правильные ответы
		fclose($fh); 
		echo "<BR>Результат улучшен(было $bb0)<BR>ball = $bb (правильных $kr; частично $kr1)";
	}else{
		echo "Набрано баллов $bb, было $bb0. <BR>Результат оставлен прежним";
	}
}else{	
//	echo "<BR>Правильных ответов $kr из $i. Баллов $bb";
	echo "<BR>ball = $bb (правильных $kr; частично $kr1)";
	$fh = fopen($nf1, "w"); // Открыть файл 
	fputs($fh,"$kr;$kr1;$bb\n");  // число правильных ответов; баллов 
	fputs($fh,$res."\n");	// ответы
	fputs($fh,$res0);	// правильные ответы
	fclose($fh); 
}	


?>