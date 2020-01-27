<?php header("Content-Type: text/html; charset=UTF-8");
// test_res.php Вызов из test_init.php после ответа на все вопросы
//"res="+res+"&nstud="+nstud+"&grup="+grup+"&kres="+kres
$res 	= $_REQUEST["res"];   // 1100;0001...;  - ответы
$nstud 	= $_REQUEST["nstud"];
$gr		 = $_REQUEST["gr"];
if ($res=="1"){ // проверка повторного входа
	$nf1 = "$gr/test-res$nstud.txt";
	if (is_file($nf1)){
		echo "?Результат уже получен $nf1";
	}else echo "not res";
	return;	
}
$ball0	 = $_REQUEST["ball0"];
$ball1	 = $_REQUEST["ball1"];

$kres = $_REQUEST["kres"]; // первый раз kres=0 второй kres=1
//echo "id=$stud ответы=$res";
$mres = explode(";",$res);
$nf = "GR-$gr/test-$nstud.json";
if (!is_file($nf)){
	echo "<BR>Нет файла $nf";
}	

	$mmt = file($nf); $smm = ""; foreach($mmt as $s) $smm.=$s;
	$mtj = json_decode($smm);
/*{"test":{"assessmentItem":[
{"responseDeclaration":{"correctResponse":{"value":["ChoiceA","ChoiceB"]},
						"_identifier":"RESPOSE","_cardinality":"multiple"},
	"itemBody":{"choiceInteraction":
			{"prompt":"adgwge",
			 "simpleChoice":[{"_identifier":"ChoiceA","__text":"*111"},
			 				{"_identifier":"ChoiceB","__text":"*222"},
							{"_identifier":"ChoiceC","__text":"333"},
							{"_identifier":"ChoiceC","__text":"3331"},
							{"_identifier":"ChoiceC","__text":"3332"}
							]
				}}}
,{"responseDeclaration":{"correctResponse":{"value":"ChoiceB"},"_identifier":"RESPONSE","_cardinality":"single"},
	"itemBody":{"choiceInteraction":{"prompt":"Вопрос 2",
		"simpleChoice":[{"_identifier":"ChoiceA","__text":"1952"},
						{"_identifier":"ChoiceB","__text":"*1962"},
						{"_identifier":"ChoiceC","__text":"1972"},
						{"_identifier":"ChoiceD","__text":"1982"},
						{"_identifier":"ChoiceE","__text":"1992"}
						]
					}}}
*/
	$kk=0; // число вопросов
	$sqq="";
	foreach($mtj->test->assessmentItem as $qq){
		$resp = $qq->responseDeclaration;
		$tr = $resp->_cardinality;
		$body = $qq->itemBody->choiceInteraction;
		if ($tr=="single"){
			$var = $resp->correctResponse->value;  // ChoiceB
			$sq = "";  // 01000
			foreach($body->simpleChoice as $rr){ // варианты ответов
				$sq .= ($rr->_identifier == $var)?"1":"0";
			}
		}else{ // multiple
			$sqr = "";  // ChoiceA;ChoiceB;
			foreach ($resp->correctResponse->value as $qr){
				$sqr .= ("|".$qr);  
			}
			$ss = $sqr;
			$sq = ""; 
			foreach($body->simpleChoice as $rr){ // варианты ответов
				$var = $rr->_identifier;  
				$sq .= strpos($sqr,$var)?"1":"0";
			}
		}	
		$mqq[] = $sq;
		$sqq .= ($sq.";");
		$kk++;
	}
	//echo "kk=$kk $ss $sqq $sk";//return;

$kr1=0; // частичные ответы   11100   11000    не указан один
$i=0; $kr=0; $res0=""; 
foreach ($mqq as $sq){  
	//echo "<BR>$sq ".$mres[$i];
	if($sq==$mres[$i]) $kr++;  // правильный ответ
	else {
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
$bb = $ball0*$kr + $ball1*$kr1;
// запись результатов
$nf1 = "GR-$gr/test-res$nstud.txt";

if (false){ // is_file($nf1)){ // второй проход
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