<?php header("Content-Type: text/html; charset=UTF-8");
//<html>
//<head>
//<META charset=UTF-8">
//<link rel="stylesheet" type="text/css" href="lab_db.css"/>
//</HEAD>
//<BODY> 
// интерфейс для загрузки информации   user=prep&login=cln&parol=11
	$user   = $_REQUEST['user'];  // stud | prep | admin
	$login 	= $_REQUEST['login'];
	$parol 	= $_REQUEST['parol'];
	$pps	="kr-pps.txt";
	$yes = false;
	$fam="";$Lfam="";
$ident=true;	
$sgrup="";
if ($ident){	
	if ($user=="stud") { // студент
		$gr 	= $_REQUEST['gr'];
		if (empty($gr)) {
			print " Не задана группа"; return; 
		}
		if (empty($parol)) {
			print " Не задан пароль"; return; 
		}
		$yes = true;		
		$fio 	= $_REQUEST['fio'];
		
		$np = "GR-$gr/control.json"; // файл с паролем
		$mj = "";
		if (is_file($np)){ // тест-контроль
			$mm = file($np); $sm = ""; foreach($mm as $s) $sm.=$s;
			$mj = json_decode($sm); 
		// {"disc":"tiim","min":15,"psw":"1","ball0":5,"ball1":3,"person":"false"}
			$psw = $mj->psw; // uest;
			$person = $mj->person; // uest;
			if ($parol == $psw) $yes = true;
			else {print "Некорр.пароль"; return; }
		} else {
			//тренинг
			//print "Доступ не открыт.\n"; return; 
		}
		
	}else if ($user=="prep") { // преподаватель 
		if (empty($login)) {
			print " Не задан логин!.\n"; return; 
		}
		if (is_file($pps)){
			$gr 	= $_REQUEST['gr'];
			$fam = "";
			//echo "<br>$login $parol";
			$mpsw = file($pps);
			foreach ($mpsw as $prep){
				//echo "<br>$prep";
				$mm = explode(";", trim($prep));    // Чернышов Л.Н.;cln;1
				$y1 = $mm[1]==$login;
				$y2 = $mm[2]==$parol;
				if ($mm[1]==$login && $mm[2]==$parol) {$fam=$mm[0]; break;}
			}
			if (!empty($fam)){
				$yes = true;
				include "lib1_file.php";
				$gr = translit8($gr);
				$mfam = explode(" ",$fam); $fam = $mfam[0];
				$Lfam = translit8($fam);
				if (is_file("TEST-$Lfam/grups.txt")){
					$mg = file("TEST-$Lfam/grups.txt");
					$i=0;
					foreach($mg as $gr){
						$sgrup .= (($i==0?"":"|").trim($gr));	
						$i++;
					} 
				}
			}else {
				print "Некорр. логин - пароль"; return; 
			}
		} else {
			print " Нет файла $pps"; return; 
		}
	} else {  // admin
		if ($parol=="314"){
			$yes = true;
		}else{
			print "Некорр. пароль"; return; 
		}
		$fam=="admin";$Lfam="admin";
	}
}   	
echo "yes$fam;$Lfam;$sgrup";
return;

if (!$yes) return; //------------------------------------------- авторизация не прошла

if 	($user=="admin") { 
	include "index_adm.php";
}else if 	($user=="prep") {
	//$fam = "Чернышов Лев";
	//$gr = "3ПИ1";
	include "index_prep.php";
}else if 	($user=="stud") {
	//$gr = "3ПИ1";
	include "index_stud.php";
}
return;
?>
</body></html>