<?php // 
header("Content-Type: text/html; charset=UTF-8");
include "lib1_file.php";
//	$('#tests').load(prog,{"nfile":ft,"size":hp,"wpt":700})	// test
$f 		= $_REQUEST["nfile"];  	// 
$size 	= $_REQUEST["size"];
$wpt 	= $_REQUEST["wpt"]; 	// ширина в символах textarea
$wtask  = $_REQUEST["wtask"];   // записать/обновить 1 - txt 2 - XML
$test   = $_REQUEST["val"];     // тест как текст (textarea)
// ------------------------------------ 
//echo "wtask=$wtask test=$test f=$f";
if (!empty($wtask) && !empty($test)){ // обновить тесты

	if ($wtask==2){ // запись в XML
/*		  '*'  оставлены???! 
Задание 121. Харитонова Е.Н.
Главной отличительной чертой мобилизационной модели развития экономики СССР в середине XX века является:

*чрезвычайность
*временность
постоянство
стабильность
экстенсивность
https://ru.wikipedia.org/wiki/QTI
http://www.imsglobal.org/question/qtiv2p2/imsqti_v2p2_impl.html
<assessmentItem identifier="choice">
	<responseDeclaration identifier="RESPONSE" cardinality="single">
		<correctResponse>
			<value>ChoiceA</value>
		</correctResponse>
	</responseDeclaration>
	<itemBody>
		<choiceInteraction responseIdentifier="RESPONSE" shuffle="false" maxChoices="1">
			<prompt>What does it say?</prompt>
			<simpleChoice identifier="ChoiceA">You must stay with your luggage at all times.</simpleChoice>
			<simpleChoice identifier="ChoiceB">Do not let someone else look after your luggage.</simpleChoice>
			<simpleChoice identifier="ChoiceC">Remember your luggage when you leave.</simpleChoice>
		</choiceInteraction>
	</itemBody>
</assessmentItem>
<assessmentItem	identifier="choiceMultiple">
	<responseDeclaration identifier="RESPONSE" cardinality="multiple">
		<correctResponse>
			<value>H</value>
			<value>O</value>
		</correctResponse>
		<mapping lowerBound="0" upperBound="2" defaultValue="-2">
			<mapEntry mapKey="H" mappedValue="1"/>
			<mapEntry mapKey="O" mappedValue="1"/>
			<mapEntry mapKey="Cl" mappedValue="-1"/>
		</mapping>
	</responseDeclaration>
	<itemBody>
		<choiceInteraction responseIdentifier="RESPONSE" shuffle="true" maxChoices="0">
			<prompt>Which of the following elements are used to form water?</prompt>
			<simpleChoice identifier="H" fixed="false">Hydrogen</simpleChoice>
			<simpleChoice identifier="He" fixed="false">Helium</simpleChoice>
			<simpleChoice identifier="C" fixed="false">Carbon</simpleChoice>
			<simpleChoice identifier="O" fixed="false">Oxygen</simpleChoice>
			<simpleChoice identifier="N" fixed="false">Nitrogen</simpleChoice>
			<simpleChoice identifier="Cl" fixed="false">Chlorine</simpleChoice>
		</choiceInteraction>
	</itemBody>
</assessmentItem>

Задание 122. Харитонова Е.Н.		
*/
function htrim($s){ // вместо trim заменить знаки < и т.п.
	//return htmlentities(trim($s));
	$s1 = trim($s);
	$s2 = htmlspecialchars($s1);
	$s2 = str_replace("&lt;","_lt;",$s2);
	$s2 = str_replace("&gt;","_gt;",$s2);
	$s2 = str_replace("&quot;",'_quot;',$s2);
	return $s2;
}


		$mf = explode(".",$f); $fx = $mf[0].".xml";
		if (!is_file($f)){
			echo "Нет файла $f"; return;
		}
		//echo "f=$f fx=$fx";
		$ms = file($f);
		$h = fopen($fx,"w"); 
		fputs($h,'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>');
		fputs($h,"\n<tests tema='".$mf[0]."'>");
		$k = 0;
		$s1 = trim($ms[$k++]); 	
		//echo "<br>$s1";
		$kk = 0;
		while ($k<count($ms) && $kk<100){
			$kk++;
			if (substr($s1,0,14)=="Задание"){
				fputs($h,"\n$s1");
				$mx = explode(" ",$s1);
				$num = explode(".",$mx[1]);  // номер задания
				$author = "";
				if (count($mx)>3) $author = "author='".$mx[2]." ".$mx[3]."'";
				
				$s = htrim($ms[$k++]); $prompt = "";  // вопрос м.б. в несколльких строках
          		while ($k<count($ms) && strlen($s)>0){
					$prompt .= ($s." ");
					$s = htrim($ms[$k++]);     
				} // $s пусто 
				
				$i=0; $q=0; 
				$s1 = "s1"; // варианты ответов (со *!) в массив 
          		while ($k<count($ms) && strlen($s1)>0){
					$s1 = htrim($ms[$k++]);
					$b = substr($s1,0,1)=='*';
					$mq[$i] = $b;  if($b) $q++;
					$mt[$i] = $s1;
					$i++;
				} // s1=$ms[$k] = пусто, $k - следуюшая строка
				$typ = ($q>1)?"choiceMultiple":"choice";
				// формирование задания
				$ss = "\n<assessmentItem number='".$num[0]."' ".$author.">";	
				fputs($h,$ss);
				$card = ($q>1)?"multiple":"single";
				fputs($h,"\n <responseDeclaration identifier='RESPONSE' cardinality='".$card."'>");
				fputs($h,"\n   <correctResponse>");
				for ($j=0; $j<$i-1; $j++){
					if ($mq[$j]){
						$Choice = substr("ABCDEFGHIJK",$j,1);
						$ss = "\n      <value>Choice".$Choice."</value>";
						fputs($h,$ss);
					}	
				}
				fputs($h,"\n   </correctResponse>");
				fputs($h,"\n </responseDeclaration>"); 
				fputs($h,"\n <itemBody>");
				fputs($h,"\n   <choiceInteraction>");
				fputs($h,"\n      <prompt>".$prompt."</prompt>");
				for ($j=0; $j<$i-1; $j++){
					$Choice = substr("ABCDEFGHIJK",$j,1);
					$ss = "\n      <simpleChoice identifier='Choice".$Choice."'>";
					$ch = $mt[$j];
					if (substr($ch,0,1)=="*")$ch = substr($ch,1);
					$ss .= ($ch."</simpleChoice>");
					fputs($h,$ss);
				}
				fputs($h,"\n   </choiceInteraction>");
				fputs($h,"\n </itemBody>");
				$ss = "\n</assessmentItem>";	
				fputs($h,$ss);
				// пропуск пустых строк
				$s1 = htrim($ms[$k++]); 	
				while ($k<count($ms) && strlen($s1)==0){
					$s1 = htrim($ms[$k++]); 	
				}	
			}		
		}
		fputs($h,"\n</tests>");
		$f = $fx;	// $mf[0].".xml";
		fclose($h);
		
		// преобразование в json
		include "lib_xsl.php";
		
		$sj = trans($fx,"test2json.xsl");
		
		$fj = $mf[0].".json";
		$hj = fopen($fj,"w");fputs($hj,$sj); fclose($hj);
		echo "Создан $fj<br>";
		
// обновить
// <темы><тема номер="1" тема="Тема 1" вопросов="5"/>
		
	}else{ // запись в текст с содержимым:
//Задание 121. Харитонова Е.Н.
//Главной отличительной чертой мобилизационной модели развития экономики СССР в середине XX века является:

//*чрезвычайность
//*временность
//постоянство
//стабильность
//экстенсивность
		$h = fopen($f,"w"); 
		fputs($h,$test);
		fclose($h);
	}
	
	echo "обновлен $f";
	return;
}
//$('#msg').load(prog,{"nfile":ff+".txt","val":test,"wtask":1})	
	$sh = $size+2; 
	echo "<textarea rows='".$sh."' cols='".$wpt."' id='test'>";
	if (is_file($f)){
		$m = file($f); 		  // массив строк
		foreach ($m as $x) {
			echo $x;
		}
	}
	echo "</textarea>";

?>
