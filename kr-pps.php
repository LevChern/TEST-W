<?PHP header("Content-Type: text/html; charset=utf-8");?>
<html>
<head>
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<style> 
  select { width: 300px; /* Ширина списка в пикселах */  } 
</style> 
</head>
<body>
<input type='button' value='Закрыть окно' onclick = 'window.close()' class='b2'>
<?php     
//<?php header("Content-Type: text/html; charset=Windows-1251"); 
//"Раздать тесты" name="variant"  class='b1'>
//"Окрыть доступ" name="open" class='b1'>
//<input name="psw" size='3'>
//"Закрыть доступ" name="close" class='b1'>
//"Результаты" name="result" class='b1'>
//"Очистить" name="clear" class='b1'>
function ndisc($kd){ // название дисциплины по коду  ttt-1
//   <дисциплина код="Б.1.1.1.5" кафедра="ДАН" краткое="WPr" название="Web-программирование" преподаватель="Чернышов Л.Н." сем1="6" лекции1="18" семинары1="18" СРС1="72"/>
	$mkd = explode("-",$kd); $tema=$mks[1];
	$fkrx = "Data1/up_2.xml";
	$name="undefinded";
	//echo "<BR>$fkrx $kd?";
	$xml = simplexml_load_file($fkrx);
	$nitem = "дисциплина";
	$atr = "краткое";
	$nd = "название";
	foreach ($xml->$nitem as $p) { // поиск строки
		$pr = $p[$atr];
		$Lpr = translit8($pr); // 
		//echo "<BR>$pr $Lpr";
		if ($mkd[0] == $Lpr) {$name = $p[$nd]; break;}
	}
	return $name;
}

function setopen(){ // спсисо групп с открытым доступом
	$mdir = scandir(".");
	$h = fopen("grups.json","w");  // 
//[
// {"GR-pi1-1":[{"tiim-1":"Теория и история менеджемента"}]},
// {"GR-pi40":[{"prog-1":"Программирование"},{"tiim-1":"Теория и история менеджемента"},{"WPr-1":"Web-программирование"}]}/
//]
	fputs($h,"[");
	$k1 = 0;
	for ($i=0; $i<count($mdir); $i++){ // Список групп
		if (substr($mdir[$i],0,2)=='GR'){
			$gg = trim($mdir[$i]);  // GR-3pi1"
			$dh = opendir($gg);
			$kk=0; $ss = "";
			while ($file = readdir($dh)){
				if (substr($file,0,9)=="password-"){
					$disc = substr($file,9);
					$ndis = ndisc($disc);
					$s0 = $kk>0?",":"";
					if ($ndis!="undefinded"){
						echo "<BR>$k1 $gg $disc $ndis";
						$ss .= ($s0.'{"'.$disc.'":"'.$ndis.'"}');
						$kk++;
					}
				}	
			}
			closedir($dh);
			if ($kk>0){
				$s1 = $k1>0?",":"";
				fputs($h,$s1.'{"'.$gg.'":['.$ss.']}');					
				$k1++;
			}
		}
	}
	fputs($h,"]");
	fclose($h);
}

function ntrim($s){ // убрать номера  XX. или X.
	$s = trim($s);
	$k = strpos($s,' '); // 11. В состав оперативной памяти входят:
	if ($k<4 && substr($s,k-1)=="."){
		$s = substr($s,$k);
	}	
	return trim($s);
}

function mix($f, $n){ // перемешивание строк файла
	$mk = file($f);
	$xy = sizeof($mk);
	for ($i = 0; $i<$n; $i++) {  // перемешивание
		$k1 = rand(0,$xy-1);$k2 = rand(0,$xy-1);
		$qq = $mk[$k1];	$mk[$k1] =  $mk[$k2];	$mk[$k2] = $qq;
	}
	return $mk;
}
function mix1($ms,$k,$n){ // перемешивание массива $n раз, начиная с $k
	foreach ($ms as $x) $mk[] = $x;
	$nn = sizeof($mk);
	for ($i = 0; $i<$n; $i++) {  // перемешивание
		$k1 = rand($k,$nn-1);$k2 = rand($k,$nn-1);
		$qq = $mk[$k1];	$mk[$k1] =  $mk[$k2];	$mk[$k2] = $qq;
	}
	return $mk;
}
function mix2($ms,$k,$n,$nn){ // перемешивание массива $n раз, начиная с $k
	for ($i = 0; $i<$n; $i++) {  // перемешивание
		$k1 = rand($k,$nn-1);$k2 = rand($k,$nn-1);
		$qq = $ms[$k1];	$ms[$k1] =  $ms[$k2];	$ms[$k2] = $qq;
	}
	return $ms;
}

include "lib1_file.php";
$KR 	= $_POST['KR'];   	 // тест
$grup  	= $_POST['grup'];  	 // 
$prep  	= $_POST['prep'];  	 // пароль
$fam  	= $_POST['fam'];  	 //  рус.фам
$psw  	= $_POST['psw'];  	 // 
$min  	= $_POST['min'];  	 // время на тестирование
$nq  	= $_POST['nq'];  	 // число вопросов

$variant= $_POST['variant'];  // Раздать тесты
$pvariant= $_POST['pvariant'];  // Раздать для печати
$show	= $_POST['show'];  // Показать ответы
$open  	= $_POST['open'];   // Окрыть доступ
$close  = $_POST['close'];   // Закрыть доступ
$clear  = $_POST['clear'];   // Очистить
$result = $_POST['result'];   // Результаты
$full	 = $_POST['full'];   // полные Результаты
$renum	 = $_POST['renum'];   // перенумеровать
$person	 = $_POST['person'];   // личный пароль
$trening = $_POST['trening'];   // режим тренингаличный
$dmin  	= $_POST['dmin'];  	 // дней тренинга
$ball0  	= $_POST['ball0'];  	 // балл за полный ответ
$ball1  	= $_POST['ball1'];  	 // за часттичный ответ


$Lfam = translit8($fam); // папка с вариантами
$fkr 	= 	"TEST-$Lfam/TEST-$KR.txt";
$fkrx 	= 	"TEST-$Lfam/TEST-$KR.xml";
$fgrup 	=   "GR-$grup";
$fpsw 	=   "$fgrup/password";
$fmin 	=   "$fgrup/maxtime";
$fperson	=   "$fgrup/person";
$ftrening	=   "$fgrup/trening";
$fballs		=   "$fgrup/balls";

//echo "$fkr  $fgrup $fpsw show=$show";

if (!empty($renum)){  //  // перенумеровать
	if (!is_file($fkr)){ echo "Нет файла $fkr"; return;}
	$mk = file($fkr);  // тест
	$fkr1 	= 	"TEST-$Lfam/TEST-$KR.txt";
	$h = fopen($fkr1,"w");
	$xx = trim($mk[0]); // заголовок
	fputs($h,$xx."\n");
	fputs($h,"\n");
	
	$ss = ""; $i=2;
	$xx = trim($mk[$i]);  // условие
	fputs($h,$xx."\n");
	$i++;
	while (!empty($xx)){
		$xx = trim($mk[$i++]); 
		fputs($h,$xx."\n");
	}	
	$kk = count($mk);
	$ii=0;
	while ($i<$kk){ // вопросы 
		$xx = ntrim($mk[$i++]);
		$kt =  strpos($xx,'.'); //  123. 435
		if (!empty($kt)){ 
			//echo "kt=$kt"; return;
			if($kt<4) $xx = trim(substr($xx,$kt+1)); 
		}
		$ii++; 
		fputs($h,$ii.". ".$xx."\n");
		while (!empty($xx) && $i<=$kk){ // ответы
			$xx = ntrim($mk[$i++]);
			fputs($h, $xx."\n");
		}

	}	
	fclose($h);
	echo "<br>Задания перенумерованы. Число заданий $ii";
	return;
}
if (!empty($pvariant)){  // сформировать тесты для печати
	$fgr = "$fgrup/GR-$grup.txt";
	if (!is_file($fgr)){ echo "Нет файла группы $fgr"; return;}
	$ms = file($fgr);
	$i1 = 0;
//ТЕСТ по теме "СУБД в интернет-приложениях"
//Условия проведения теста:;1. Тип вопроса «Один, два или три из многих».;2. Каждый правильный ответ – 5 баллов.;
// 51. Тэги, одновременно открывающие и закрывающие|*<hr />|*<br />|</p>|</table>
	foreach ($ms as $st) {  // для каждого студента перемешанные вопросы
		$i1++;
		$ftask = "GR-$grup/test$i1.txt";
		if (is_file($ftask)){
			$mft = file($ftask); 
			$fprint = "GR-$grup/p_test$i1.txt";
			$zprint = "GR-$grup/z_test$i1.txt"; // со *
			$fh = fopen($fprint, "w"); // 
			$fz = fopen($zprint, "w"); // 
			$kk = 0;
			foreach ($mft as $qq) { // строки тестовых заданий
				$kk++;
				if ($kk<3){
					fputs($fh,$qq."\n");
					fputs($fz,$qq."\n");
				}else{
					$mqq = explode("|",$qq); $ii=0;
					foreach ($mqq as $sq){
						$ii++; $sz="  ";
						if (substr($sq,0,1)=='*') {$sq = substr($sq,1); $sz="* ";}
						$ssq = ($ii==1)?"":"  ";
						$ssz = ($ii==1)?"":$sz;
						fputs($fh,$ssq.$sq."\n");
						fputs($fz,$ssz.$sq."\n");
					}
				}
			}
			fclose($fh);
			fclose($fz);
		}	
	}
	echo "Сформировано файлов p_test*.txt (c_test*.txt )  $i1";
	return;
}
if (!empty($variant)){  // раздать тесты
	if (empty($KR)){ echo "Не выбран тест"; return;}
	$fgr = "$fgrup/GR-$grup.txt";
	if (!is_file($fgr)){ echo "Нет файла группы $fgr"; return;}
	$ms = file($fgr);  // список группы
/*	
	if (!empty($trening)){
		if (!is_file($fkrx)){ echo "Нет файла $fkrx"; return;}
		echo "$fkrx<BR>";
		echo "Режим тренинга для группы $grup на $dmin дней <BR>";
	<assessmentItem number='121'  identifier="choice">
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

		$xml = simplexml_load_file($fkrx);
		$nitem = "assessmentItem";
		$mq = array(); $mnum = array();
		$k=0;
		foreach ($xml->$nitem as $p) { // поиск строки
			$pr = $p->itemBody->choiceInteraction->prompt;
			$mq[] = $pr;
			$mnum[] = $p["number"];
			$ma[$k]= array(); // варианты ответов вопросы $k
			foreach ($p->itemBody->choiceInteraction->simpleChoice as $qa ){
				$ma[$k][] = $qa;
			}
			echo "<br>".$mnum[$k].".".$mq[$k];
			//foreach ($ma[$k] as $qa) echo "<br>--$qa";		
			$k++;
		}
		// для каждого студента перемешиваем вопросы (с перемешанными ответами)
$test0 = "ТЕСТ по теме ".fkrx; // Программирование Программирование для облаков"
$test1 = "Условия проведения теста: Ответить на все вопросы";
//113. Значение |type="resetbutton"|type="clear"|type="submit"|type="clearbutton"|*type="reset"
		$mgr = file("$fgrup/GR-$grup.txt");
		$ks=0;
		foreach ($mgr as $st){ // по студентам
			$i1 = $ks+1;
			$fst = "test$i1".".txt";
			$h = fopen("GR-$grup/$fst","w");
			fputs($h,$test0."\n".$test1); // ТЕСТ ... Условия
			$k = 0;
			foreach ($mq as $qq){  // вопросы
				$s = $mnum[$k].".".$qq;   // nn.Вопрос
				$mqs1 = mix2($mqs,0,10,$j-1); // перемешать ответы  перемешивание массива $n раз, начиная с $k
				$mqq = array();
				foreach ($ma[$k] as $qa) $mqq[]=$qa 
				foreach ($ma[$k] as $qa){ // варианты ответов
					$s .= ("|".trim($qa));
				}
				$k++;
				fputs($h,"\n".$s);
			}	
			fclose($h);
			$ks++;
			echo "<br>Сформированы тесты в $fst";
		}
		return;
	}
*/	
	if (!is_file($fkr)){ echo "Нет файла $fkr"; return;}
	echo "$fkr<BR>";
	$mk = file($fkr);  // строки тестовых заданий
/*	
ТЕСТ по теме 3 (управление памятью)

Условия проведения теста:
1. Тип вопроса «Один или два из многих».
2. Каждый правильный ответ – 5 баллов.

1. В состав оперативной памяти входят:
A. Регистры процессора
B. Кэш процессора
C. *Оперативные запоминающие устройства
*/
	$mout[] = trim($mk[0]); // заголовок
	$ss = ""; 
	$i=2;
	$xx = trim($mk[$i]);  // условие
	$i++;
	while (!empty($xx)){
		$ss .= ($xx.";");
		$xx = trim($mk[$i++]); 
	}	
	$ball0=5; $ball1=3;
	if (is_file("GR-$grup/balls")){
		$mb = file("GR-$grup/balls");
		$mb1 = explode(";",trim($mb[0]));
		$ball0=$mb1[0]; $ball1=$mb1[1];
	}  else {echo "<br>Нет файла $grup/balls"; }
	$ss = str_replace('$ball0',$ball0,$ss);	
	$ss = str_replace('$ball1',$ball1,$ss);	
	$mout[] = $ss; // условие
	$kk = count($mk);
	while ($i<$kk){ // вопросы 
		$xx = ntrim($mk[$i]); $ss="";  // вопрос
		$xx = htmlspecialchars_decode($xx, ENT_QUOTES);
		$xx = str_replace("'","&#39;",$xx);
		$xx = str_replace("\\","&#92;",$xx);	
		$xx = str_replace("|","&#124;",$xx);	
		$xx0 = $xx;
		$i++; $j=0;
		while (!empty($xx) && $i<=$kk){ // ответы
			$xx = ntrim($mk[$i++]);
			$xx = htmlspecialchars_decode($xx, ENT_QUOTES);
			$xx = str_replace("'","&#39;",$xx);
			$xx = str_replace("\\","&#92;",$xx);	
			$xx = str_replace("|","&#124;",$xx);	
			$mqs[$j] = $xx;
			$j++;
		}
		$mqs1 = mix2($mqs,0,10,$j-1); // перемешать ответы
		$ss = $xx0;
 		for ($jj=0; $jj<$j-1; $jj++){
			$ss .= ("|".$mqs1[$jj]);
		}	
		$mout[] = $ss; // условие
	}	
	$mout1 = mix1($mout,2,300);
	//foreach ($mout1 as $qq)echo "<BR>$qq";  // вопросы с 2-го
	
	$i1 = 1; // номер студента
	$iq = 2;
	foreach ($ms as $st) {  // для каждого студента перемешанные вопросы
		global $nq; // число вопросов
		$ftask = "GR-$grup/test$i1.txt";
		$fh = fopen($ftask, "w"); // 
		fputs($fh,trim($mout1[0])."\n");
		fputs($fh,trim($mout1[1])."\n");
		$k = 0;
		for ($k=0; $k<$nq; $k++) {
			$qq = $mout1[$iq];
			fputs($fh,trim($qq)."\n");
			$iq++; if ($iq>count($mout1)) $iq = 2;
		}
		fclose($fh); 
		$i1++;
	}
	if (!empty($trening)){ // В папку студента записать json-тесты
		$i1 = 1;
		$ma = array("0","A","B","C","D","E","F","G","H","I");
		foreach ($ms as $st) {  // для каждого студента перемешанные вопросы
			$mst1 = explode(" ",$st);  
			$ftask = "GR-$grup/test$i1.txt";
			$mtask = file($ftask);
			$nn = count($mtask);
			$Lstud = translit8(trim($mst1[0])); // папка с вариантами
			//echo "<br>$Lstud $ftask";
			$ndir = "GR-$grup/$Lstud";
			if (!is_dir($ndir)){
				mkdir($ndir);  
			}
			$fx = "GR-$grup/$Lstud/$KR".".json";
			//echo "<br>$fx $nn";
			$h = fopen($fx,"w"); 
			fputs($h,'{"tests":{"assessmentItem": [');
			$kt=0;	
//Тема 1 История менеджмента
//Условия проведения теста:;a)Тип вопроса «Один, два или более из многих».;b)Каждый правильный ответ – 5 балла, частичный - 3.;
//Экономические реформы Хрущева начались в СССР в … году.|1981|1961|1960|*1964|1965
			foreach ($mtask as $task){ // 
				$task = trim($task)	;
				$kt++;
				if ($kt<3) continue; // || empty($task)
				//echo "<br>$task";
				$mt = explode("|",trim($task));
				$s0 = $kt>3?",":"";
				fputs($h,"\n\t".$s0.'{"responseDeclaration":');	
				fputs($h,"\n\t".'{"correctResponse":');	
				$ss = "\n\t\t{".'"value": [';
				$j=0;
				for ($i=1;$i<count($mt);$i++){
					if (substr($mt[$i],0,1)=="*"){
						$s0 = $j>0?",":"";
						$ss .= ($s0.'"Choice'.$ma[$i].'"');
						$j++;
					}	
				}	
				$ss .=  ']},';
				fputs($h,$ss);
				fputs($h,"\n\t\t".'"_identifier":"RESPONSE",'); 
				fputs($h,"\n\t\t".'"_cardinality": "multiple"');
				fputs($h,"\n\t},");
				fputs($h,"\n".'"itemBody":');
				fputs($h,"\n\t{".'"choiceInteraction":');
				fputs($h,"\n\t\t{".'"prompt": "'.$mt[0].'",');
				fputs($h,"\n\t\t".'"simpleChoice": [');
				for ($i=1;$i<count($mt);$i++){
					$s0 = $i>1?",":"";
					fputs($h,"\n\t\t\t".$s0.'{"_identifier": "Choice'.$ma[$i].'", "__text": "'.$mt[$i].'" }');
				}	
				fputs($h,"\n\t\t]");
				fputs($h,"\n\t\t}");
				fputs($h,"\n\t}");
				fputs($h,"\n}");
			}
			fputs($h,"\n]}}");
			fclose($h);
			$i1++;
		}
	
/*
{"tests":{"assessmentItem": [
	  
	{"responseDeclaration":	
		{"correctResponse":
			{"value": [ "ChoiceA",  "ChoiceB"  ]},
			"_identifier":"RESPONSE", 
			"_cardinality": "multiple"
		},
	"itemBody": 
		{"choiceInteraction": 
			{"prompt": "Главной отличительной чертой мобилизационной модели развития экономики СССР в середине XX века является: ",
			"simpleChoice": [
				{"_identifier": "ChoiceA", "__text": "*чрезвычайность" }
				,{"_identifier": "ChoiceB", "__text": "*временность"   }
				,{"_identifier": "ChoiceC", "__text": "постоянство"    }
				,{"_identifier": "ChoiceD", "__text": "стабильность"   }
				,{"_identifier": "ChoiceE", "__text": "экстенсивность" }
			]
			}	
		}
    }
	
]}}
*/
	
	
	}
?>
<form action = 'kr-pps-1.php'>
<!--input type='submit' value='Сохранить' name= 'save'-->
<input type='hidden' value='<?php echo $grup;?>' name= 'grup'>
<br>Преподаватель <?php echo $fam;?>
<br>Тест для группы <?php echo $grup;?>
<br>
<textarea name="tasks" rows='30' cols='200'>
<?php	
	$i = -1;
	foreach($mout as $xx) {  // исходный вопросы
		$xx = trim($xx); 
		if (empty($show)){
			$mx = explode("|",$xx);
			echo $mx[0]."\n";
		} else {	
			echo $xx."\n";
		}	
		$i++;
	}
?>
</textarea>
</form>

<?php
	
}
	if (!empty($open)){ // открыть доступ
		if (empty($psw)) { echo "Не задан пароль"; return;}
		if (is_file($fpsw))	unlink($fpsw);
		WriteLine($fpsw, $psw);
		if (is_file($fmin))	unlink($fmin);
		WriteLine($fmin, $min);
		if (is_file($fperson))	unlink($fperson);
		if ($person=="on"){
			WriteLine($fperson, $person);
		}
		if (is_file($ftrening))	unlink($ftrening);
		if ($trening=="on"){
			WriteLine($ftrening, $trening);
			if (is_file($fmin))	unlink($fmin);
			WriteLine($fmin, $dmin);
		}
		if (is_file($fballs))	unlink($fballs);
		WriteLine($fballs, "$ball0;$ball1");
		echo "Доступ группе $grup открыт. Пароль $psw";
		if ($trening && !empty($KR)){
			$fpsw1 = $fpsw."-".$KR;  //"$fgrup/password";
			if (is_file($fpsw1)) unlink($fpsw1);
			WriteLine($fpsw1, $psw);
		}
		setopen();
		ndisc("dd");
	}
	
	
	if (!empty($close)){// закрыть доступ
		if (file_exists($fpsw)){
			unlink($fpsw);
			echo "Доступ группе $grup закрыт";
		} else {
			echo "Нет файла $fpsw";
		}	
		if ($trening && !empty($KR)){
			$fpsw1 = $fpsw."-".$KR;  //"$fgrup/password";
			if (is_file($fpsw1)) unlink($fpsw1);
		}	
		setopen();
	}
	
	if (!empty($clear)){ //очистить
		$dh = opendir($fgrup);
		$nmax = 0; $nmax1 = 0;$nmax2 = 0;
		$res = array();
		while ($file = readdir($dh)) {
			$x = substr($file,0,4);  // test.txt + test-res.txt
			$x1 = substr($file,1,5);  // c_test....txt + z_test...-res.txt
			if ($x=="test"){
				unlink("$fgrup/$file");
				$nmax++; 
			}	
			if ($x1=="_test"){
				unlink("$fgrup/$file");
				$nmax1++; 
			}	
		}
		echo "Удалено из папки группы $grup файлов: $nmax+$nmax1";
		//echo "<BR>test*.txt $nmax";
		//echo "<BR>test-res*.txt $nmax1";
		//echo "<BR>ip*.txt $nmax2";
	}
	if (!empty($result)){
// -------------------------------------------------------------	Результаты
		echo "Результаты:<BR>";
		$fgr = "$fgrup/$fgrup.txt";
		echo $fgr;
		$mst = file($fgr); 
		if (empty($full)){
			echo "<table border='1' cellspacing='0' cellpadding='5' class='t0' style='background-color:#F0F0F0'>";
			echo "<tr><th>Студент<th>Прав.<BR>ответов<th>Част.прав.<BR>ответов<th>Баллы";
			$i1 = 1;
			foreach ($mst as $fam){
				echo "<tr><td>$fam";
				$f0 = "$fgrup/test-res$i1.txt";
				if (is_file($f0)){
					$m = file($f0);
					$mm = explode(";",$m[0]); // правильных ответов;частивно; sбаллов
					echo "<td align='right'>".$mm[0]."<td align='right'>".$mm[1]."<td align='right'>".$mm[2];
				}else{
					echo "<td><td><td>";
				}	
				$i1++;
			}
			echo "</table>";
		} else { // полные результаты
			$i1 = 1; $k1=0;
			echo "<textarea rows='20' cols='150'>";
			foreach ($mst as $fam){
				$ft = "$fgrup/test$i1.txt";   // вопросы
				$f0 = "$fgrup/test-res$i1.txt"; // ответы
				if (is_file($f0)){
					$k1++;
					$m0 = file($ft);
					$m = file($f0);  // 0100;110;1100;1000;0100;010;1000;1001;010;0000;
					$m1 = explode(";",$m[1]); // ответы
					$m2 = explode(";",$m[2]); // правильные ответы
					$i = 1; $sn="";  // номера неправильных ответов
					foreach ($m1 as $x){
						if ($i==sizeof($m2)) break;
						if ($x!=$m2[$i-1]){
							$sn .= (";".$i);
						}	
						$i++;
					}	
					echo "-------------------------------\n";
					echo $i1.". ".trim($fam)."\n";
					//echo trim($m[1])."\n"; ответы 1100;1000;100;1100;010;010;1000;0010;1000;0010;
					//echo trim($m[2])."\n"; правильные ответв
					$m3 = explode(";",$m[1]);
					echo "Ошибки в вопросах: ",substr($sn,1)."\n";   // ;1;5;6;7;8;9;10
					$i=-1;
					$sn1 = "";
					foreach ($m0 as $x){ // x==1. Вопрос (номер исходный!)
						if ($i>0){
							$k = strpos((";".$sn.";"),(";".$i.";"));
							if ($k){
								$mxx = explode(".",$x);
								$sn1 .= (";".$mxx[0]);
								//$x = htmlspecialchars($x); 
								echo $i.". ".trim($x)."\n";
								echo "Ответ:". $m3[$i-1];
								echo "\n";
							}	
						}
						$i++;
					}	
					$mstat[] =  substr($sn1,1);
					//echo $sn1."\n";  // ;8;2;1;6;7;9;10 реальные номерв
				}	
				$i1++;
			}
			if ($k1==0)return;
			// 
			echo "</textarea>";
			echo  "<BR>Статистика ошибок<BR>";	
			echo "<table border='1' cellspacing='0' cellpadding='5'><tr><th>Номер вопроса";
			for ($j=1; $j<$i; $j++) {
				echo "<th>$j";	
				$stat[$j] = 0;
			}
			foreach ($mstat as $s){
				$mms = explode(";",$s); 
				foreach ($mms as $kk){
					$stat[$kk]++;	
				}	
			}	
			echo "<tr><td>Число ошибок";	
			for ($j=1; $j<$i; $j++) {
				echo "<td>".$stat[$j];	
				$stat[$j] = 0;
			}
			echo "</table>";
		}	
	}
?>	
<br>
