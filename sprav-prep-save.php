<?php header("Content-Type: text/html; charset=UTF-8"); 
/* sprav-prep.php вызов из index_prep кнопка "Список тестов(тр)"
	$.get("sprav-prep-save.php?gr="+gr+"&dkod="+dkod+"&num="+num+"&fam="+prep,
	обновить списки  trening_gr.xml
*/
	$fam = $_REQUEST['fam'];   //  
	$gr = $_REQUEST['gr'];   //  
	$snew = $_REQUEST['snew'];   //  
	$dkod = $_REQUEST['dkod'];   //  
	$num = $_REQUEST['num'];   //  
	$ffg = "GR-$gr/trening_gr.json"; // дисциплины группы
	$mdisc = array(); // массив дисциплин
	if (!empty($num)) { // обновить число доступных тем
		$mm = file($ffg); $smm = ""; foreach($mm as $s) $smm.=$s;
		$md = json_decode($smm); // {"disc":[["ob",2,4,"haritonova","Основы бизнеса"],
		foreach ($md->disc as $disc){
			$ntem = ($disc[0]==$dkod)?$num:$disc[1];
			$mdisc[] = array($disc[0],$ntem,$disc[2],$disc[3],$disc[4]);	
		}
		$h = fopen($ffg,"w");
		fputs($h,'{"disc":['."\n");
		$i=0;
		foreach ($mdisc as $mm) { // 
			$z = ($i==0)?"":",";
			fputs($h,$z.'["'.$mm[0].'",'.$mm[1].','.$mm[2].',"'.$mm[3].'","'.$mm[4].'"]'."\n");
			$i++;
		}
		fputs($h,"]}");
		fclose($h);
		echo "Доступно тем $num";
		return;
	}
	//echo $ffg; return;
	$bb = is_file($ffg);
	if ($bb){ // если есть - выборка других преподов 
		$mm = file($ffg); $smm = ""; foreach($mm as $s) $smm.=$s;
		$md = json_decode($smm); // {"disc":[["ob",2,4,"haritonova","Основы бизнеса"],
		foreach ($md->disc as $disc){
			if ($disc[3]!=$fam){
				$mdisc[] = array($disc[0],$disc[1],$disc[2],$disc[3],$disc[4]);	
			}                  //          дост.    всего     fam    name 
		}
	}
	// дописываем новые 
	$ms = explode("|",$snew);  // ob;0;4;Основы бизнеса|tiim;0;4;Теория и история менеджемента
	$h = fopen($ffg,"w");
	fputs($h,'{"disc":['."\n");
	$i=0;
	foreach ($ms as $m) { // 
		$z = ($i==0)?"":",";
		$mm = explode(";",$m);
		fputs($h,$z.'["'.$mm[0].'",'.$mm[1].','.$mm[2].',"'.$fam.'","'.$mm[3].'"]'."\n");
		$i++; 
	}	
	if ($bb){ // других преподов
		foreach ($mdisc as $mm) { // 
			$z = ($i==0)?"":",";
			fputs($h,$z.'["'.$mm[0].'",'.$mm[1].','.$mm[2].',"'.$mm[3].'","'.$mm[4].'"]'."\n");
			$i++;
		}
	}
	fputs($h,"]}");
	fclose($h);
	echo ($bb?"Обновлен":"Создан")." файл GR-$gr/trening_gr.json";
?>
