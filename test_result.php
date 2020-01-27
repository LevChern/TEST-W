<?PHP header("Content-Type: text/html; charset=utf-8");
//	test_result.php  результаты  
?>
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
//include "lib1_file.php";
$grup  	= $_REQUEST['grup'];  	 // 
$fgrup 	=   "GR-$grup";
$full	 = $_POST['full'];   // полные Результаты
	echo "Результаты по группе <b>$grup</b><BR>";
	$fgr = "$fgrup/$fgrup.txt";
	if (is_file("$fgrup/testcontrol.txt")){
		$md = file("$fgrup/testcontrol.txt");
		echo "Дисциплина <b>'".trim($md[0])."'</b><br>";
			
	}
	//echo $fgr;
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
		foreach ($mst as $fam){  // по студентам
			//$ft = "$fgrup/test$i1.txt";   // вопросы
			$ft = "$fgrup/test-$i1.json";   // вопросы
			$f0 = "$fgrup/test-res$i1.txt"; // ответы
			if (is_file($f0)){
				$k1++;
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
				/*$m0 = file($ft);
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
				*/	
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
?>	
