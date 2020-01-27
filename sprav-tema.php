<?php header("Content-Type: text/html; charset=UTF-8"); 
	$fam = $_REQUEST['fam'];   //  
	$Lfam = $_REQUEST['Lfam'];   //  
?>
<html><head>
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<script src="jquery-latest.min.js"></script>
<script>
<?php
	echo "fam='".$Lfam."'\n";
?>
function finit(){}
function ftema(disc){ // выбор набора
	//alert(disc+" "+fam)
	window.open("adm_edit_up.php?fxml="+disc+".xml"
		+"&sattr=номер;ftest(номер);ftestXML(номер);вопросов;тема"
		+"&stitl=номер;тесты_"+disc+";проверка_XML;вопросов;тема"
		+"&ssize=50;80;80;60;600"
		+"&pps="+fam
		+"&nitem=тема"
		+"&scr=40"
		+"&option=2"
	, "_blank")
}
</script>
</HEAD>
<BODY onload='finit()'> 
<input valign='top' type='button' value='Закрыть окно' onclick = 'window.close()' class='b2'>
<br>
Преподаватель<b> &nbsp; <?php echo $fam;?> </b>

<table border='1' cellspacing='0' cellpadding='5' style='background-color:#F0F0F0'>
<tr><th>№<th>код<th>кратк<th>дисциплина
<?php
/* sprav-tema.php вызов из index_prep кнопка "Темы дисциплин"
Выборка дисциплин препода
<уч_план код="09.09.03" направление="Прикладная информатика" год="2014" профиль="Прикладная информатика в экономике">
  <дисциплина код="Б.1.1.3.7" кафедра="ОМ" название="Теория и история менеджемента" преподаватель="Харитонова Е.Н." сем1="1" лекции1="22" семинары1="18" СРС1="54" сем2="2" лекции2="18" семинары2="18" СРС2="54" краткое="ТиИМ"/>
  <дисциплина код="Б.1.1.1.5" кафедра="ДАН" краткое="WPr" название="Web-программирование" преподаватель="Чернышов Л.Н." сем1="6" лекции1="18" семинары1="18" СРС1="72"/>
  <дисциплина спец="ПИ-2014" код="Б.1.1.1.6" название="Философия" преподаватель="Чернышов Л.Н." кафедра="Философия" экзамены="2" зачеты="1" ЗЕ="4" ЗЕ-экз="3" Всего="144" ауд="68" лекции="32" семинары="36" СРС="76" СРС-сем="40" СРС-экз="36" сем1="1" ЗЕ1="4" час-нед1="2" лекции1="16" семинары1="18" СРС1="20" сем2="2" час-нед2="2" лекции2="16" семинары2="18" СРС2="56" краткое="Фил"/>

*/	include "lib1_file.php";
	$xml = simplexml_load_file("Data1/uplan2.xml"); // 
	$i=1;
	foreach ($xml->дисциплина as $p) { //
		$dkod = $p["код"]; $nam = $p["краткое"]; $name = $p["название"];
		$prep = $p["преподаватель"];
		$Lec = $p["лектор"];
		$mprep = explode(" ",$prep);
		if ($mprep[0] == $fam && empty($Lec)){
			$kod = translit8($nam);
			echo "<tr><td>$i<td>$dkod";
			echo "<td><input type='button' value='".$nam."' class='b1' onclick='ftema(\"".$kod."\")'><td>$name";
			$i++;
		}
	}	

?>
</table>
	
</body>