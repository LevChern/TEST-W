<?php // test_jput.php Вызов из index_prep панель Тесты(контроль):
// кнопки "раздать варианты"
// Открыть, закрыть и очистить
//$.get("test_jput.php?gr="+gr+"&disc="+dkod+"&sq="+sq+"&sk="+sk+&prep="+prep);
function mix0($ms,$n){ // перестановка в массиве $n раз
	foreach ($ms as $x) $mk[] = $x;
	$nn = sizeof($mk);
	for ($i = 0; $i<$n; $i++) {  // перемешивание
		$k1 = rand(0,$nn-1); 
		$k2 = rand(0,$nn-1);
		$qq = $mk[$k1];	$mk[$k1] =  $mk[$k2];	$mk[$k2] = $qq;
	}
	return $mk;
}// mix0
	$gr	= $_REQUEST["gr"];		// группа
	$disc = $_REQUEST["disc"];	// код дисциплины
	$open = $_REQUEST["open"];	// 
	$close = $_REQUEST["close"];	// 
	$clear = $_REQUEST["clear"];	// 
	$fnc = "GR-$gr/control.json";
	if (!empty($open)){ // открыть доступ к дисциплине
		$min = $_REQUEST["min"];	// 
		$ball0 = $_REQUEST["ball0"];	// 
		$ball1 = $_REQUEST["ball1"];	// 
		$psw = $_REQUEST["psw"];	// 
		$person = $_REQUEST["person"];	// 
		$t0 = $_REQUEST["t0"];	// 
		$mout = array("disc"=>$disc,"min"=>1*$min,"psw"=>$psw,
			"ball0"=>1*$ball0,"ball1"=>1*$ball1,"person"=>$person,"t0"=>$t0);
		$ss = json_encode($mout);
		$h = fopen($fnc,"w");
		fputs($h,$ss);
		fclose($h);	
		//echo "Доступ открыт";
		return;
	}
	if (!empty($close)){
		if(is_file($fnc)) unlink($fnc);
		//echo "Доступ закрыт";
		return;
	}
	if (!empty($clear)){
		$dh = opendir("GR-$gr");
		$nmax = 0; $nmax1 = 0;$nmax2 = 0;
		$res = array();
		while ($file = readdir($dh)) {
			$x = substr($file,0,5);  // test.txt + test-res.txt
			if ($x=="test-"){
				unlink("GR-$gr/$file");
				$nmax++; 
			}	
		}
		unlink("GR-$gr/testcontrol.txt");
		echo "Удалено из папки группы $gr файлов: $nmax";
		return;
	}

// раздать вопросы
	$name = $_REQUEST["name"];
	$sq = $_REQUEST["sq"]; 		// раздать вопросов по темам
	$sk = $_REQUEST["sk"]; 		// всего вопросов по темам
	$prep = $_REQUEST["prep"]; 		// препод
	//echo "<br>$gr $disc $sq $sk $prep";
	$mq = explode(",",$sq); 
	$kq = count($mq);  
	$mk = explode("|",$sk);
	$ktem = count($mk); // число тем

	$ngr = "GR-$gr/GR-$gr".".txt";   // группа 
	$mgr = file($ngr); $kgr = count($mgr);
	//echo "<br>kgr=$kgr";
	
	$ii=0; $k=0;	
	foreach ($mk as $kk){ // по темам
		$kq = $mq[$ii];   //  сколько раздать
		//echo "<br>tema $ii $kq";
		$m1 = range(0,$kk-1);
		$m2 = mix0($m1,200); // перемешанные вопросы в теме
		//$ss=""; foreach ($m2 as $kn)$ss.=("$kn,"); echo "<br>$ss";
		$j = 0;   //   0 1 2 3 4 
		for ($i=1; $i<=$kgr; $i++){ // по студентам 
			//echo "<br>";
			$iq = 0; 
			while ($iq < $kq ){ // раздать $kq вопросов
				$jq = $m2[$j];   // $j = 0 1 ... $kk-1 
				$ii1 = $ii+1;
				$mst[$i][$k+$iq] = "$ii1-$jq";   // 
				//echo " $j_($i)[$k+$iq]";
				$iq++;
				$j++;
				if ($j+$kq > $kk){  // 3+3>5
					//echo "<br>mix0";
					$m2 = mix0($m1,200); // перемешанные вопросы в теме
					$j = 0;
				}
			}
		}
		$ii++;
		$k += $kq;
	}

	for ($i=1; $i<=$kgr; $i++){ // по студентам 
		// echo "<br>$i)";
		$nst = "GR-$gr/test-$i".".json";   // тест для i-го студентам
		if (is_file($nst)) unlink($nst);
		$mh[$i] = fopen($nst,"a");	
		fputs($mh[$i], '{"test":{"assessmentItem":['."\n");	
		//foreach ($mst[$i] as $ss){ // 1-1 1-2 1-0 2-1 2-3 3-1 3-2 4-1
		//}	
		$mnum[$i]=0; // нумерация вопросов
	}
	$jj=0; 
	for ($nt=1; $nt<=$ktem; $nt++){ // по темам
		$npr = "TEST-$prep/TEST-$disc-$nt".".json";   // файл темы
/* прочитать и разобрать по заданиям
	{"test":{"assessmentItem":
	[
	{"responseDeclaration": 
		{"correctResponse": 
			{"value":["ChoiceA","ChoiceB"]},
			"_identifier": "RESPONSE",
			"_cardinality": "multiple"
		},
	"itemBody": 
			{"choiceInteraction": 
				{"prompt": "1-1 Главной отличительной чертой мобилизационной модели развития экономики СССР в середине XX века является: ",
				 "simpleChoice": 
					[
					{"_identifier": "ChoiceA","__text": "*чрезвычайность"},
					{"_identifier": "ChoiceB","__text": "*временность"},
					{"_identifier": "ChoiceC","__text": "постоянство"},
					{"_identifier": "ChoiceD","__text": "стабильность"},
					{"_identifier": "ChoiceE","__text": "экстенсивность"}					
					]
				}
			}
	},
*/		
		if (is_file($npr)){ 	//echo "<br>$npr";
			$mm = file($npr); $smm = ""; foreach($mm as $s) $smm.=$s;
			$mj = json_decode($smm);
			$i=0;
			foreach($mj->test->assessmentItem as $quest){
				$sq = json_encode($quest);
				$mquest[$i] = $sq;
				$i++;
				//echo "<br>$sq";
				//echo "<br>";
			}
			//echo "<br>npr=$npr i=$i";
		
			for ($i=1; $i<=$kgr; $i++){ // по студентам 
				foreach ($mst[$i] as $qq){
					$mm = explode("-",$qq); // 
					if ($mm[0]==$nt){
						// записать задание с номером $mm[1]
						$zz = ($mnum[$i]==0)?"":",";
						$mnum[$i]++;
						fputs($mh[$i], $zz.$mquest[$mm[1]]."\n");
						$jj++;	
					}
				}
			}
		}	
	}
	
	for ($i=1; $i<=$kgr; $i++){ // по студентам 
		fputs($mh[$i], ']}}');	
		fclose($mh[$i]);	
	}
	echo "Роздано в группе $gr (студентов $kgr)";
	$h = fopen("GR-$gr/testcontrol.txt","w"); fputs($h,$name);fclose($h);
?>