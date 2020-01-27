<?php // тест-=тренинг
 header("Content-Type: text/html; charset=UTF-8");
//	index_stud.php --  $.get("test_get.php?gr="+gr+"&fam="+fam+"&psw="+psw+"&mdisc="+mdisc,
// Кнопка Тест-тренинг построение интерфейса со списком mdisc доступных тем
//	index_stud.php	<form name='frmtren' action='test_XML_tr.php' target='_blank'>
//	<input  value='тренинг'  onclick='fget_json()' type='button' name='trnew'  class='b2'>";
//	<input  value='график'   type='submit' name='graf'  class='b2'>";

	$gr	= $_REQUEST["gr"];		// группа
	$fam = $_REQUEST["fam"];	// 
	$psw = $_REQUEST["psw"]; 	//
	$num = $_REQUEST["num"]; 	// номер студента
	$mdisc = $_REQUEST["mdisc"]; //   $mdisc .= "$ras$disc;$name;$tema;$prep";
	//echo "$gr $fam $psw $num <br>$mdisc"; return;
 	if (empty($fam)){ 
		echo "?<font color='red'>Не задана фамилия</font>"; 
		return;
	}
	include "lib1_file.php";
	$Lfam = translit8(trim($fam)); // фамилия студента
	$fpsw = fparol8($fam);  	 // пароль
	if ($fpsw!=$psw && $num!=$psw){ 
		echo "?<font color='red'>Неправильный пароль</font>";   //  $fpsw!=$psw
		return;
	} // $psw=$fpsw
	
	if (!empty($mdisc)){ // тренинг
		//echo "<form name='frmtren' target='_blank'>";
		$md = explode("|",$mdisc);  // "kod;name;ntema;prep|..."
		$kmd = count($md);   
		echo "<table width='100%'><tr><td valign='top'>Дисциплина:";
		echo "<input name='fam' type='hidden' value='".$Lfam."'>";
		echo "<input name='prep' type='hidden' value='".$prep."'>";
		echo "<td><select id='hdisc' onchange='finfo()' name='trdisc' style='width:400px' size=$kmd>";
		$i=0;
		foreach ($md as $dd){ // по дисциплинам
			$i++; $sel = "";
			$mdd = explode(";",$dd);   // код;название;тема;prep
			if ($i==1){
				$sel = "selected";
				$ntema = $mdd[2]; // тем по первой дисциплине
				$prep = $mdd[3]; // преподаватель по первой дисциплине
			}
			echo "<option $sel value='".$dd."'>".$mdd[1];
		}
		echo "</select>"; // onclick='ftrstart()' type='button'
		
		echo "<tr><td>Тема:<td><input id='ntema' style='width:50px' name='tema' min=1 max=$ntema type='number' value=$ntema>";
		echo "<font color='red'><span id='tmsg'></span></font></table>";
		echo "</table>";

		echo "<table width='100%'>";
		echo "<tr><td align='center'>"; //<input  type='submit'  value='тренинг' class='b2'>";
		echo "<input  onclick='fget_json()' type='button' name='trnew' value='тренинг' class='b2'>";
		echo "<input  type='hidden' name='prep' value='".$prep."'>";
		echo "<td align='right'><input  type='submit' name='graf' value='график' class='b2'>";
		echo "</table>";
		//echo "</form>";
	}

	return;
	
?>