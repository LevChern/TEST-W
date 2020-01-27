<?php  header("Content-Type: text/html; charset=UTF-8");
// Вызов из index_prep панель Тесты(тренинг):
// доступные темы $.get("test_put.php?gr="+gr+"&disc="+md[0]+"&num="+num+"&prep="+prep);
// очистить			$.get("test_put.php?gr="+gr+"&disc="+md[0]+"&num="+0);

// выбор дисциплины	$.get("test_put.php?do=date&gr="+gr+"&disc="+md[0],
// открыть доступ	$.get("test_put.php?do=open&date="+fdate+"&gr="+gr+"&disc="+md[0]);
// закрыть доступ	$.get("test_put.php?do=close&date="+fdate+"&gr="+gr+"&disc="+md[0]);
//	TEST-prep-treinig.xml  // список дисциплин преподавателя (формируется если нет в index_prep)
//	<дисциплина kod='ob' name='Основы бизнеса' tem='2'/>
//"GR-$gr/trening.json";    // список доступных дисциплин и розданных тем
// {"disc":[ ["prog",0,"haritonova","Основы бизнеса"],...]}  - сколько роздано
// меняется при раздаче, при открытии/закрытии 
	$gr	= $_REQUEST["gr"];		// группа
	$pgr  ="GR-$gr";			// папка группы
	$disc = $_REQUEST["disc"];	// код дисциплины
	$do = $_REQUEST["do"]; 		// open|close
	$h1 = fopen("debug","w");	
	fputs($h1, "gr=$gr\n");	
	if (!empty($do)){
		$d = $_REQUEST["date"];	// дата открытия
		$nf = "GR-$gr/trening-$disc.txt";       // дата открытия
		$nof = "GR-$gr/trening-$disc-close.txt";// дата закрытия
		if ($do=="open"){ // открыть доступ - создать файл "GR-$gr/trening-$disc.txt";
						//                  - удалить trening-$disc-close    
						// добавить дисциплину в trening.json	
						// {"disc":[["ob","3","haritonova","4"]]}						
			$kt = $_REQUEST["kt"]; // число тем
			$nd = $_REQUEST["nd"]; // название дисциплины
			$prep = $_REQUEST["prep"]; // препод
			
			if (is_file($nof)) unlink($nof);
			$h=fopen($nf,"w"); fputs($h,$d); fclose($h);
			$ntr = "GR-$gr/trening.json";// доступные дисциплины
			//disc":[["ob","3","haritonova","название"],...
			$mm = file($ntr); $smm = ""; foreach($mm as $s) $smm.=$s;
			$mj = json_decode($smm);
			$h = fopen($ntr,"w"); // 
			$mnew = array();
			foreach($mj->disc as $dd){
				$mnew[] = $dd;
			}
			$mnew[] = array($disc,$kt,$prep,$nd);
			$mout = array("disc"=>$mnew);
			$ss = json_encode($mout);
			fputs($h,$ss);
			fclose($h);

		}else if ($do=="close"){ // закрыть доступ - удалить "GR-$gr/trening-$disc.txt";
								//                 - создать trening-$disc-close          	
								// удалить дисциплину из trening.json		
			if (is_file($nf)) unlink($nf);
			$h=fopen($nof,"w"); fputs($h,$d); fclose($h);
			$ntr = "GR-$gr/trening.json";// доступные дисциплины
			//disc":[["ob","3","haritonova","название"],...
			$mm = file($ntr); $smm = ""; foreach($mm as $s) $smm.=$s;
			$mj = json_decode($smm);
			$h = fopen($ntr,"w"); // 
			$mnew = array();
			foreach($mj->disc as $dd){
				if ($dd[0]!=$disc)	$mnew[] = $dd;
			}
			$mout = array("disc"=>$mnew);
			$ss = json_encode($mout);
			fputs($h,$ss);
			fclose($h);

		}else if ($do=="date"){ // вернуть дата открытия;закрытия
			//$.get("puttest.php?do=date&gr="+gr+"&disc="+md[0]
			//$h1=fopen("debug","w");	fputs($h1, $nf);//	fclose($h1);
			$d0 = ""; $d1 = "";
			if (is_file($nf)){
				$md = file($nf); $d0 = trim($md[0]);
			}
			if (is_file($nof)){
				$md = file($nof); $d1 = trim($md[0]);
			}
			echo "$d0;$d1";
		}else if ($do=="clear"){ // очистить - удалить истории  по тренингу дисциплины
			$dir = opendir($pgr);
			fputs($h1, "pgr=$pgr");	
			$kf=0;
			while($file = readdir($dir)) { // папки студетов
				if (is_dir("$pgr/$file") && $file != '.' && $file != '..') {
					//echo "<br>$file :"; 
					$mdir = scandir("$pgr/$file");
					foreach ($mdir as $ft){  // TEST-disc-n.json | . xml
						//echo "$file"; // trening-disc.json
						$mn = explode("-",$ft);
						if (count($mn)==2 && $mn[0]=="trening" && $mn[1]==($disc.".json")){
							$kf++;	
							unlink("$pgr/$file/$ft");
					//		fputs($h1, "$file");	
							//echo " $pgr/$file/$ft";
						}
					}	
				}
			}
			closedir($dir);
			echo "Удалено из $pgr/* файлов $kf";
		}	
		fclose($h1);
		return;
	} // "do" not empty 	

	$num  = $_REQUEST["num"]; 	// что только что роздано
	if ($num==0)return; 
	$prep = $_REQUEST["prep"];
	$nf   = "GR-$gr/trening.json";    // список розданных тем
	// {"disc":[ ["prog",0,4,"haritonova","Основы бизнеса"],...]}  - сколько роздано
	fputs($h1, "prep=$prep num=$num\n");	
	fclose($h1);	
	// обновить trening.json 
	if (!is_file($nf)){ // загружена первая тема первой дисциплины
		$h=fopen($nf,"w"); // 
		$ss = json_encode($mout);
		fputs($h,$ss);
		fclose($h);
	}else{ // загружена тема
		$mm = file($nf); $smm = ""; foreach($mm as $s) $smm.=$s;
		$mj = json_decode($smm);
	// {"disc":[ ["prog",0,4,"haritonova","Основы бизнеса"],...]}  - сколько роздано
		//print_r($mj);
		$h=fopen($nf,"w"); // 
		$mnew=array();
		foreach($mj->disc as $dd){ // поиск по коду в trening.json и обновления номера темы
			if ($dd[0]==$disc){$dd[1]=$num;}
			//fputs($h1, $disc."==".$dd[0]."-".$dd[1]."\n");
			$mnew[] = $dd;
		}
		$mout = array("disc"=>$mnew);
		$ss = json_encode($mout);
		fputs($h,$ss);
		fclose($h);
		echo $num;
	}
	
?>