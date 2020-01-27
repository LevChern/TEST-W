<?php
function u2a1($s){return mb_convert_encoding($s,  "Windows-1251", "UTF-8");}	
function a2u1($s){return mb_convert_encoding($s,  "UTF-8", "Windows-1251");}	

$rus =  "_абвгдеёжзийклмнопрстуфхцчшщьыэюя";  
$zrus = "_АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЭЮЯ";
$lat = "_ a b v g d e e zhz i j k l m n o p r s t u f h tschshthiiy e yuya";
function translit8($str){// перевод рус в лат
  global $rus, $lat, $zrus;  $s = "";
  $str1 = u2a1($str);
  $zrus1 =  u2a1($zrus);		
  $rus1 =  u2a1($rus);		
  for ($i=0; $i<strlen($str1); $i++)  {
	$x = substr($str1,$i,1);
	$kr = strpos($zrus1,$x);
	if (empty($kr)) $kr = strpos($rus1,$x) ;
	if (!empty($kr)) $s .= trim(substr($lat,2*$kr,2));
	else $s .= $x;
  }
  return $s;     
}

function WriteLine($fn,$s){
	$fh = fopen($fn, "a"); // ќткрыть файл дл¤ добавлени¤ записи
	fputs($fh,$s."\n");
	fclose($fh); 
}
function WriteAll($fn,$s){
	$fh = fopen($fn, "w"); // ќткрыть файл дл¤ добавлени¤ записи
	fputs($fh,$s."\n");
	fclose($fh); 
}
function CreateDir($fd){
	if (!is_dir($fd)){
		mkdir($fd, 1); // ќткрыть файл дл¤ добавлени¤ записи
	}
}

$rus1 =  u2a1("абвгдеёжзийклмнопрстуфхцчшщьыэюя");  
$zrus1 = u2a1("АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЭЮЯ");
$dig   = "_5812__17__3600_5972_839464_____";

function fparol8($fi){// пароль по фомилии
  global $rus1, $zrus1, $dig;  $s = ""; $kc=0;
  $fio = u2a1($fi);
  $ds=0; $i=0;
  while ($i<strlen($fio))  {
	$c = substr($fio,$i,1); $c1 = a2u1($c);//	
	$kr = strpos($zrus1,$c);	// позиций  0 2 ... 
	if (empty($kr)) $kr = strpos($rus1,$c);
	$d = substr($dig,$kr,1); // 
	//echo "<BR>c=$c1 kr=$kr d=$d";
	if ($d=='_'){ $i++; continue;}
	$kc++; 
	if ($kc>4) break;
	$ds += $d;
	$s .= $d; 
	$i++;
  }
  $d1 = $ds % 10;
  $s = str_pad($s,4,'0');
  //echo " s=$s";
  return $d1.$s;     
}


?>