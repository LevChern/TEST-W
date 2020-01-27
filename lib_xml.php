<?php // inprog
// 25.12					$res1 = str_replace("_lt;","&lt;",$res);

header("Content-Type: text/html; charset=UTF-8");
include "lib1_file.php";
//	$('#tests').load(prog,{"nfile":ft,"size":hp,"wpt":700})	// test
$f 		= $_REQUEST["nfile"];  	// 
$size 	= $_REQUEST["size"];
$wpt 	= $_REQUEST["wpt"]; 	// ширина в символах textarea
$test   = $_REQUEST["val"];    
//<script src="jquery-latest.min.js"></script>
?>
<style>
	table{background-color:#F0F0F0;}
</style>
<script>

</script>
<?php
// ------------------------------------ 
//echo "wtask=$wtask test=$test f=$f";
/*		
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
			<simpleChoice> stay with your luggage at all times.</simpleChoice>
		</choiceInteraction>
	</itemBody>
</assessmentItem>
<assessmentItem	identifier="choiceMultiple">
	<responseDeclaration identifier="RESPONSE" cardinality="multiple">
		<correctResponse>
			<value>H</value>		<value>O</value>
		</correctResponse>
		<mapping lowerBound="0" upperBound="2" defaultValue="-2">
			<mapEntry mapKey="H" mappedValue="1"/>	<mapEntry mapKey="O" mappedValue="1"/>

		</mapping>
	</responseDeclaration>
	<itemBody>
		<choiceInteraction responseIdentifier="RESPONSE" shuffle="true" maxChoices="0">
			<prompt>Which of the following elements are used to form water?</prompt>
			<simpleChoice identifier="H" fixed="false">Hydrogen</simpleChoice>
			<simpleChoice identifier="He" fixed="false">Helium</simpleChoice>

		</choiceInteraction>
	</itemBody>
</assessmentItem>
*/

//$('#msg').load(prog,{"nfile":ff+".txt","val":test,"wtask":1})	
// отобразвить XML
	$sh = $size+2; 
	echo "<table border cellspacing='0' width='700'>";
	if (is_file($f)){
		$nitem = "assessmentItem";
		$xml = simplexml_load_file($f);
		$nq = 1;
		foreach ($xml->$nitem as $p) { // <assessmentItem identifier="choice">
			//$q = "q".$nq;  
			echo "<tr><td>$nq) Задание ".$p["number"]." ".$p["author"];
			
			$typ = $p->responseDeclaration["cardinality"]; // multiple|single
			$ch = $p->itemBody->choiceInteraction;
			$prompt = $ch->prompt;
			echo "<br>$prompt";
			echo "<br><form name='frm_".$nq."'>";
			if ($typ=="single"){
				$val = trim($p->responseDeclaration->correctResponse->value);
				$i1 = -1; $i=0;
				foreach($ch->simpleChoice as $res){
					$xx = trim($res["identifier"]);
					if($xx == $val){$i1 = $i;}
					echo "<input type='radio' name='s_".$nq."'>$res<br>";
					$i++;
				}
				echo "<input type='hidden' value='".$i1."' name='res'>";
				echo "<input type='button' value='ответ' onclick='fsingle(".$nq.",".$i1.")'>";
				echo " результат:<span id='res_".$nq."' size='4' disavled></span>";
				echo "<br>";
			}else{ // multiple
				$mv = $p->responseDeclaration->correctResponse; //->value
				$kk = count($mv->value);
				$sv="-";  // 01010   ChoiseA|ChoiseC|
				foreach($mv->value as $vv){ // правильные ответы
					$sv .= (trim($vv)."|"); // echo "<br>vv=$vv"; 	
				}
				echo "<input type='hidden' value='".$sv."' name='res'>";
				$s = "";
				foreach($ch->simpleChoice as $res){ // варианты ответоы
					//$res1 = html_entity_decode($res); 
					$res1 = str_replace("_lt;","&lt;",$res);
					$res1 = str_replace("_gt;","&gt;",$res1);
					$xx = trim($res["identifier"]);  //ChoiseA  ChoiseB
					$k = strpos($sv,"$xx|");
					$s .= ($k>0)?"1":"0";	
					echo "<input type='checkbox' name='m_".$nq."'>$res1<br>";
				}
				$s = '"'.$s.'"';
				echo "<input type='button' value='ответ' onclick='fmultiple(".$nq.",".$s.")'>";
				//echo " результат:<span id='res_".$nq."'>qq</span>";
				echo  " результат:<span id='res_".$nq."' size='4' disavled></span>";
				echo "<br>";
			}
			echo "</form>"; 	
			$nq++;
		}
		$nq--;
		echo "<hr>Всего вопросов <input disabled id='qall' value='".$nq."' size='3'>"; 	
	}else{
		echo "Нет файла $f";
	}
	echo "</table>";

?>
