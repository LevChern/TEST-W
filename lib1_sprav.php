<?php // inprog
header("Content-Type: text/html; charset=UTF-8");
include "lib1_file.php";
$f 		= $_REQUEST["nfile"];  	// 
$name 	= $_REQUEST["name"];  	// 
$size 	= $_REQUEST["size"];
$edit 	= $_REQUEST["edit"]; 	// редакт. элемента
$wp 	= $_REQUEST["wp"]; 		// ширина в пикс.
$wpt 	= $_REQUEST["wpt"]; 	// ширина в символах textarea
$fadd 	= $_REQUEST["fadd"];    // полное значение (добавление $fadd)
$fdel 	= $_REQUEST["fdel"];	// удаление папки
$rfio 	= $_REQUEST["rfio"]; 	// создать папку дл§ фио $rfio
$dfio 	= $_REQUEST["dfio"]; 	// удалить папку дл§ фио $rfio
$dfile	= $_REQUEST["dfile"]; 	// удалить файл †Ц фио $rfio
$pref   = $_REQUEST["pref"]; 
$studs   = $_REQUEST["studs"]; 
$val   = $_REQUEST["val"]; 
$wlist   = $_REQUEST["wlist"]; 
$wtask  = $_REQUEST["wtask"]; 
$prep   = $_REQUEST["prep"]; 
// ------------------------------------ 
//echo "rfio=$rfio  $pref $f val=$val" ;
//echo "dfile=$dfile pref=$pref prep=$prep";
if (!empty($wlist) && !empty($val)){ // обновить список группы 
	if (!is_dir("GR-$val")){
		mkdir("GR-$val"); 
		echo "Cоздана папка GR-$val<BR>";
	}
	$f = "GR-$val/GR-$val.txt";
	$h = fopen($f,"w"); 
	fputs($h,$studs);
	fclose($h);
	echo "Список обновлен $f";
	return;
}

if (!empty($wtask) && !empty($val)){ // обновить тесты
	$f = "TEST-$prep/TEST-$val.txt";
	$h = fopen($f,"w"); 
	fputs($h,$studs);
	fclose($h);
	echo "БД обновлена $f";
	return;
}

if ($name=="grups" || $name=="krs"){ // группы / контрольные
	if ($name=="grups"){ $dir = ".";         $pre = "GR"; $kp=2; }
	else 		  	   { $dir = "TEST-".$f;  $pre = "TEST"; $kp=4; }
	$mdir = scandir($dir);  // папки 
	$k = 0;
	for ($i=0; $i<count($mdir); $i++){ // —писок групп/тестов  GR-Pi1
		if (substr($mdir[$i],0,$kp)==$pre){
			$xx = substr($mdir[$i],$kp+1);  // им¤ файла f1.txt   $x1=5
			$x1 = strlen($xx); //$x2 = substr($xx,$x1-4,4);
			if($pre == "GR"){
				$m[$k++] = $xx;
			}else{ // TEST только txt
				if (substr($xx,$x1-4,4)==".txt"){
					$xx = substr($xx,0,$x1-4);
					$m[$k++] = $xx;
				} 
			}
		}
	}
	//$nn = sizeof($m);     // число строк
	$sh = $size; $sw = "200"; $sk=25;
	//if ($nn==0) return;
	$ww = 8*10;
	echo "<input value='' name='val".$j."' style='width:".$ww."px;font-family:courier'>&nbsp;";
	echo "<br>";
	echo "<table><tr><td>";
	$sw = 100;
	if (!empty($wp)) $sw=$wp; else $s="<br>";
	if (!empty($id)) $sid=" id='".$id."'"; else $sid="";
	$s .= "<select".$sid." size='".$sh."' name='".$name."' style='width:".$sw."px;font-family:courier'>";
	foreach ($m as $mi) {
		$key = $mi;
		$val = $mi;
		$s .= ("<option value='" . $key . "'>" . $val);
    }
	echo $s."</select>";
	return;
}

if ($name=="studs"){ // студенты
	$sh = $size+2; 
	echo "<textarea rows='".$sh."' cols='".$wpt."' name='studs'>";
	if (!empty($f)){
		if (!empty($prep)) $dir = "TEST-$prep"; else {$dir = $f;} 
		$nf = "$dir/$f.txt";
		//echo $nf;
		if (is_file($nf)){
			$m = file($nf); 		  // массив строк
			foreach ($m as $x) {
				echo $x;
			}
		}	
	}
	echo "</textarea>";
	echo "</table><tr><td>";
	return;
}

if (!empty($rfio)){  // 
	$fd = translit8($rfio);
	$nd = $pref.$fd;
	$dr	=  $_SERVER['DOCUMENT_ROOT'];
	$nd = $dr."/TEST3/".$nd; //   
	if (!is_dir($nd)){
		$kr = mkdir($nd, 0777); // Чоздать папку
	}
	echo "kr=$kr  $nd";
	return;
}

if (!empty($dfio)){  // 
	$fd = translit8($dfio);
	$nd = $pref.$fd;
	$dr	=  $_SERVER['DOCUMENT_ROOT'];
	$nd = $dr."/TEST3/".$nd; //   
	if (is_dir($nd)){
		if ($objs = glob($nd."/*")) {
			foreach($objs as $obj) {
				unlink($obj);
			}
		}
		rmdir($nd); // удалить папку
	}
	echo "Удалена папка $nd"; 
	return;
}

if (!empty($dfile)){  // $.get(prog+"?dfile="+gr+"&prep="+prep+"&pref=KR-");
	//$fd = translit($dfio);
	$nf = "$pref-$prep/$pref-$dfile.txt";
	//$nf = "KR-ivanov/KR-1_tasks2.txt";
	//echo $nf;
	if (is_file($nf)){
		unlink($nf); // удалить файл
	}
	return;
}

$nn = 0;

if (is_file($f)){  // загрузка файла
	$kmax = 1000;  // максимальна§ ширина пол§
	$m = file($f); 		  // массив строк
	$nn = sizeof($m);     // число строк
	$m0 = explode(";",$m[0]);  $kp = sizeof($m0);  // элементов в строке
	for ($j=0;$j<$kp;$j++) $mw[$j] = 0;  // ширины элементов
	for ($i=0; $i<$nn; $i++) {
		$m0 = explode(";",$m[$i]);
		$kp = sizeof($m0);
		for ($j=0;$j<$kp;$j++){
			$ms[$i][$j] = $m0[$j];
			$k0 = strlen($m0[$j]);	
			if (($k0>$mw[$j])&& ($k0<$kmax)) $mw[$j] = $k0;
		}	
	}
}	

if (!empty($fadd)){ // добавить (без учета ключа)
	for ($i=0; $i<$nn; $i++) { // построчно не добавл§етс§
		if ($m[$i]==$fadd){ return; }
	}
	$h = fopen($f,"a+");
	if ($nn==0) fputs($h,$fadd);
	else fputs($h,"\n".$fadd);
	fclose($h);
	
} else if (!empty($fdel)){ // удалить
	$h = fopen($f,"w"); 
	$j=0; $b=0;
	for ($i=0; $i<$nn; $i++) {
		if (trim($m[$i])!=trim($fdel)){
			fputs($h, ($j>0?"\n":"").trim($m[$i]));
			$j++; 
		}else{
			if ($b>0) {
				fputs($h, (($j>0?"\n":"").trim($m[$i])));
				$j++; 
			}	
			$b++;
		}
	}
	fclose($h);

}else{ // отобразить
	$sh = $size; $sw = "800"; $sk=25;
	if ($nn==0) return;
	$sw=0;
	for ($j=0; $j<$kp; $j++){
		$ww = 8*$mw[$j];
		$sw += ($ww+8);
		if (empty($edit)){	
			echo "<input value='' name='val".$j."' style='width:".$ww."px;font-family:courier'>&nbsp;";
		}	
	}
	echo "<br>";
	$sw+=12;
	if (!empty($wp)) $sw=$wp; else $s="<br>";
	if (!empty($id)) $sid=" id='".$id."'"; else $sid="";
	$s .= "<select".$sid." size='".$sh."' name='".$name."' style='width:".$sw."px;font-family:courier'>";
	for ($i=0; $i<$nn; $i++) {
		$key =$m[$i];
		$val = "";
		for ($j=0; $j<$kp; $j++){
			$vij = trim($ms[$i][$j]);
			$val .= (str_pad($vij,$mw[$j],"_")."|");
		}	
		$s .= ("<option value='" . $key . "'>!" . $val);
    }
	echo $s."</select>";
}
?>
