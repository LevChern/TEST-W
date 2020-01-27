<?php   header("Content-Type: text/html; charset=UTF-8"); 
// testNewphp  test_XML_tr.php ...  include "testNew.php";   кнопка тренинг+
// тест строится их json препода
	//$gr  = $_REQUEST["gr"]; 	// 
	//$fam  = $_REQUEST["fam"]; 	// 
	//$disc  = $_REQUEST["trdisc"]; 	// 
	//echo "<br>disc=$disc";
	$f = "TEST-$prep/TEST-$disc-$tema.xml";
//	echo $nf;
	// перемешать и вырнуть
/*
	$nf = "TEST-$prep/TEST-$disc-$tema.json";
	$mm = file($nf); $smm = ""; foreach($mm as $s) $smm.=$s;
	$mj = json_decode($smm);
	$mnew = array();
	foreach($mj->disc as $dd){
		if ($dd[0]==$disc){$dd[1]=$num;}
		$mnew[] = $dd;
	}
*/	
function mix($mk, $n){ // перемешивание строк файла
	$xy = sizeof($mk);
	for ($i = 0; $i<$n; $i++) {  // перемешивание
		$k1 = rand(0,$xy-1);$k2 = rand(0,$xy-1);
		$qq = $mk[$k1];	$mk[$k1] =  $mk[$k2];	$mk[$k2] = $qq;
	}
	return $mk;
}

	$nitem = "assessmentItem";
	$xml = simplexml_load_file($f);
	$mq = array(); // массив заданий
	$nq = 0; $m1 = array(); 
	foreach ($xml->$nitem as $p) { // <assessmentItem identifier="choice">
		$m1[] = $nq;  
		$typ = $p->responseDeclaration["cardinality"]; // multiple|single
		$ch = $p->itemBody->choiceInteraction;
		$prompt = $ch->prompt;  // вопрос
		if ($typ=="single"){
			$val = trim($p->responseDeclaration->correctResponse->value);  // ChoiceB
			$i1 = -1; $i=0;
			$mr = array();
			foreach($ch->simpleChoice as $res){ // варианты ответов
				$xx = trim($res["identifier"]);
				$mr[] = $res;
				if($xx == $val){$i1 = $i;} // i1 - правильный ответ (0,1,...)
				$i++;
			}
			$mq[] = array("prompt"=>$prompt, "vi"=>$i1, "mreq"=>$mr);
		}else{ // multiple
			$mv = $p->responseDeclaration->correctResponse; //->value
			$kk = count($mv->value);
			$sv="-";  // -ChoiseA|ChoiseC|
			foreach($mv->value as $vv){ // правильные ответы
				$sv .= (trim($vv)."|"); // echo "<br>vv=$vv"; 	
			}
			$mr = array();
			foreach($ch->simpleChoice as $res){ // варианты ответоы
				$xx = trim($res["identifier"]);  //ChoiseA  ChoiseB
				$mr[] = $res;
			}
			$mq[] = array("prompt"=>$prompt, "vi"=>$sv, "mreq"=>$mr);
		}
		$nq++;
	}
	
	$m1 = mix($m1,1000); // перешанные 0..$nq-1
	$mq1 = array(); // перемешанные задания
	
	$ii=0;
	foreach ($mq as $qq){  // перемешать задания
		$k = $m1[$ii];
		//echo "<br>".$k.$qq["prompt"];
		$mq1[$k] = $qq;
		$ii++;	
	}	

	$nq=1; 
	for($i=0; $i<$ii; $i++){ 
		$qq = $mq1[$i]; 
		//echo "<br>".$nq.")".$qq["prompt"];
		$tz = $qq["vi"];
		$bmult = substr($tz,0,1)=="-"; // несколько правильных 	
		$k1 = count($qq["mreq"]);
		$m2 = array(); for($k=0; $k<$k1; $k++)$m2[]=$k;
		$m2 = mix($m2,1000);
		$mr1 = array(); // перемешанные ответы
		$jj=0; $zz="";
		foreach ($qq["mreq"] as $rr){   // перемешать ответы
			$k = $m2[$jj];
			$mr1[$k] = $rr;
			if (substr($rr,0,1)=="*"){
				$zz .= ("$k|");
			}
			$jj++;	
		}
		$zz = substr($zz,0,strlen($zz)-1);
		if ($bmult){
			$qq["vi"] = "-".$zz;	
		}else{ // один правильный 
			$qq["vi"] = $zz;	
		}
		$mq1[$i]["vi"] = $zz; 
		
		//echo "<br>".$qq["vi"];
		$mr2 = array();
		for($j=0; $j<$jj; $j++){
			//echo "<br>".$mr1[$j];
			$mr2[] = $mr1[$j];	
		}
		$mq1[$i]["mreq"] = $mr2;
		$nq++;
	} 	
	
	//echo "<br>--------------------";
	//print_r($mq1);
	//echo "<br>--------------------";
	$br = "";  //<br>
	echo $br.'{"test": {"assessmentItem": ['."\n"; // преобразовать в json
	for($i=0; $i<$ii; $i++){ 
		$qq = $mq1[$i]; 
		$tz = $qq["vi"];   // 0|2 1 
		echo $br.'{"responseDeclaration": {'."\n".'"correctResponse": {';
		$mz = explode("|",$tz);
		if (count($mz)>1){ // несколько
			$sz = '"value":[';
			$iz=0;
			foreach($mz as $z) { //	"value":["ChoiceA",	"ChoiceB"]
				$Choice = substr("ABCDEFGHIJK",$z,1);
				$zp = $iz==0?"":",";
				$sz .= ($zp.'"Choice'.$Choice.'"');
				$iz++;
			}
			$sz .= "]";
			echo "$br$sz";
		}else{ // один правильный 
			$Choice = substr("ABCDEFGHIJK",$tz,1);
			echo $br.'   "value":"Choice'.$Choice.'"';   //"value":"ChoiceB"
		}
		echo "$br  },";
		$prompt = $qq["prompt"];
		echo $br.'   "_identifier": "RESPONSE",';
		$tr = (count($mz)>1)?"multiple":"single";
		echo $br.'"_cardinality": "'.$tr.'"';
		echo $br.'},';
		echo $br.'"itemBody": {';
		echo $br.'"choiceInteraction": {';
		echo $br.'"prompt": "'.$prompt.'",';
		echo $br.'"simpleChoice": [';
	//	{		"_identifier": "ChoiceA", "__text": "стабилизация"	},
		$k1 = count($qq["mreq"])-1;  // 0 1 2
		$ir=0;
		foreach ($qq["mreq"] as $rr){   // перемешать ответы
			$Choice = substr("ABCDEFGHIJK",$ir,1);
			$zap = $ir<$k1?",":"";
			echo $br.'{"_identifier": "Choice'.$Choice.'", "__text": "'.$rr.'"}'.$zap;
			$ir++;
		}
		echo $br.']';
		$zap1 = ($i<$ii-1)?",":"";
		echo '} } }'.$zap1;
	}
	echo "] } }";

	// 
/*		
	$m1 = array(1,2,3,4,5,6);
	$m1 = mix($m1,1000);
	foreach($m1 as $x) echo $x;
	

	$mout = array("disc"=>$mnew);
	$ss = json_encode($mout);
*/	
	
	
?>
