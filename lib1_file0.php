<?php           // 
$rus =  "_абвгдеЄжзийклмнопрстуфхцчшщьыэю€";
$zrus = "_јЅ¬√ƒ≈®∆«»… ЋћЌќѕ–—“”‘’÷„Ўў№џЁёя";
$lat = "_ a b v g d e e zhz i j k l m n o p r s t u f h tschshthiiy e yuya";
function translit($str){
  global $rus, $lat, $zrus;  $s = "";
  for ($i=0; $i<strlen($str); $i++)  {
	$x = substr($str,$i,1);
	$kr = strpos($zrus,$x);
	if (empty($kr)) $kr = strpos($rus,$x) ;
	if (!empty($kr)) $s .= trim(substr($lat,2*$kr,2));
	else $s .= $x;     // все остальные символы остаютс€ 
  }
  return $s;     
}

function ReadLline($fn){
	if (is_readable($fn) ) {
		$fh = fopen($fn, "r"); // ќткрыть файл
		$line = fgets($fh,4096);
		fclose($fh); 
	} else {
		print "$fn is not readable!";
	}
	return $m0;
}

function WriteLine($fn,$s){
	$fh = fopen($fn, "a"); // ќткрыть файл дл€ добавлени€ записи
	fputs($fh,$s."\n");
	fclose($fh); 
}

function CreateDir($fd){
	if (!is_dir($fd)){
		$kr = fopen($fd, 1); // ќткрыть файл дл€ добавлени€ записи
	}
}

function WriteAll($fn,$s){
	$fh = fopen($fn, "w"); // ќткрыть файл дл€ добавлени€ записи
	fputs($fh, $s);
	fclose($fh); 
}


?>