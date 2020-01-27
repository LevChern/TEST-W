<?php header("Content-Type: text/html; charset=UTF-8"); 
/* sprav-prep.php вызов из index_prep кнопка "Дисциплины"
TEST-$Lfam/trening.xml
<дисциплины><дисциплина kod='ob' name='Основы бизнеса' tem='4'/>
		Теория и история менеджемента
		Программирование
GR-$gr/trening_gr.json    сколько доступных теи для группы
{"disc":[
["ob",2,4,"haritonova","Основы бизнеса"],
["tiim",0,3,"haritonova","Теория и история менеджемента"]
]}
*/
// обновть списки  trening.xml
function ktem($Lfam,$kod){ // число тем по дисциплине $kod препода $Lfam
	$kt=0;
	//echo  "<br>$Lfam,$kod";
	$mdir = scandir("TEST-$Lfam");
	foreach ($mdir as $dir){
		$kl = strlen($dir);
		if (substr($dir,0,4)=='TEST' && substr($dir,$kl-4,4)=='.xml') {
			//echo "<br>$dir";
			$xx = explode("-",$dir);  // имя файла TEST-kod-n.txt   $x1=5
			$kd = $xx[1];
			if ($kd == $kod){
				$kt++;
			}
		}
	}	
	return $kt;	
}

	$fam = $_REQUEST['fam'];   //  
	$Lfam = $_REQUEST['Lfam'];   //  
	$gr = $_REQUEST['gr'];   //  
	echo "Преподаватель <b>$fam</b>";
//$ffd = "TEST-$Lfam/trening.xml"; // дисциплины преподавателя
// обновить	
	$mdt = array(); // число тем по дисциплине
	$mkod = array();
	$mdir = scandir("TEST-$Lfam"); // лектор?
	foreach ($mdir as $dir){
		$kl = strlen($dir);
		if (substr($dir,0,4)=='TEST' && substr($dir,$kl-4,4)=='.xml') {
			$xx = explode("-",$dir);  // имя файла TEST-kod-n.txt   $x1=5
			$kod = $xx[1];
			$mkod[] = $kod;
			if (empty($mdt[$kod])){
				$mdt[$kod] = 1;
			}else{
				$mdt[$kod]++;
			}
		}
	}	
	// могло не быть. Тесты лектора
	include "lib1_file.php";
	if (is_file("TEST-$Lfam/trening.xml")){ //TEST-$Lfam/trening.xml
//<дисциплины>
//<дисциплина kod='WPr' name='' tem='6' />
		$xml = simplexml_load_file("TEST-$Lfam/trening.xml");
		foreach ($xml->дисциплина as $p) { // 
			//<дисциплина kod='ob' name='Основы бизнеса' tem='3'/>
			$kodr = $p["kod"]; $x2 = $p["name"]; $x3 = $p["tem"]; $x4 = $p["лектор"];
			$kod = translit8($kodr);
			//echo "<br>$kod $x2 $x3"; 
			if (empty($mdt[$kod])){
				$mdt[$kod] = $x3;
				$mdL[$kod] = $x4;
			}
		}	
	}
	//foreach ($mdt as $kod=>$kt){
	//	echo "<br>$kod=>$kt";
	//}
	
	$mdn = array(); // названия дисциплин

	$ffp = "Data1/uplan2.xml";  // лектор="Чернышов Л.Н." 
	$xml = simplexml_load_file($ffp);
//<дисциплина код="Б.1.1.1.5" кафедра="ДАН" краткое="WPr" название="Web-программирование" 
// преподаватель="Чернышов Л.Н." лектор="ddd" сем1="6" лекции1="18" семинары1="18" СРС1="72"/>
	foreach ($xml->дисциплина as $p) { //
		$x1 = $p["краткое"]; 
		$kod = translit8($x1);  // код дисциплины
		$xp = $p["преподаватель"]; $mp = explode(" ",$xp); 
		$pf = $mp[0];  // фамилия преода
		if ($pf==$fam){
			$xp = $p["лектор"]; $mp = explode(" ",$xp); $pL = $mp[0];
			echo "<br>$kod $pL";
			if (!empty($mdt[$kod])){
				//echo " ".$mdt[$kod]." ".$pf.$pL;
				$mdn[$kod] = $p["название"]; 
				$mdL[$kod] = $pL; 	
				$mkod[] = $kod;				
			}else{
				echo "-- $pf $fam";
				$mdn[$kod] = $p["название"]; 
				$mdL[$kod] = $pL; 			
				$mdt[$kod] = ktem(translit8($pL),$kod); // ? 
				//echo "<br>$kod $pL". $mdt[$kod];
			}	
		}
	}
	//foreach ($mdL as $kod=>$L)echo "$kod=>$L";
	
	//print_r($mdn); print_r($mdL);
	$h = fopen("TEST-$Lfam/trening.xml","w");  // обновить дисциплины препода
	fputs($h,"<?xml version='1.0' encoding='UTF-8' standalone='yes'?>\n");
	fputs($h,"<дисциплины>\n");
//<дисциплина kod="prog" name="Программирование" [лектор=""] tem="4"/>
	foreach ($mdt as $kod=>$kt){
		$name = $mdn[$kod]; 
		$Lect = $mdL[$kod]; $sL = empty($Lect)?"":" лектор='".$Lect."'";
		fputs($h,"<дисциплина kod='".$kod."' name='".$name."' tem='".$kt."' ".$sL."/>\n");
	}
	fputs($h,"</дисциплины>");
	fclose($h);
//----------------------------------------------------	
	//$ffg = "GR-$gr/trening_gr.json"; // дисциплины группы
	$mdisc = array(); // массив дисциплин
	//echo "<br>$ffg";
	//print_r($mkod);
	if (is_file("GR-$gr/trening_gr.json")){ // если есть
		$mm = file("GR-$gr/trening_gr.json"); 
		$smm = ""; foreach($mm as $s) $smm.=$s;
		$md = json_decode($smm); // {"disc":["ob",0,4,"haritonova","Основы бизнеса" ...
		foreach ($md->disc as $disc){
			//echo "<br>$Lfam=".$disc[2];
			if ($disc[3]==$Lfam){
				if (in_array($disc[0], $mkod)){ // только те, что есть у препода
					//echo "<br>".$disc[0];
					$mdisc[] = array($disc[0],$disc[1],0, $disc[4],true);	
				}
			}		//                           дост.всего     
		}
	}
	//print_r($mdisc);
	// добавить полное число тем + дисц., к-х нет 
	//<дисциплины><дисциплина kod='ob' name='Основы бизнеса' tem='4'/>
	$xml = simplexml_load_file("TEST-$Lfam/trening.xml");  //
	$ii=0;
	foreach ($xml->дисциплина as $p) { // все дисциплины препода
		$kod = $p["kod"];
		$tem = $p["tem"]; 
		$name = $p["name"];
		$Lect = $p["лектор"];
		$b = false;
		//echo "<br>$kod $tem $Lect`";
		foreach($mdisc as $mdis){ // доступные
			if (($mdis[4]) && ($mdis[0]==$kod)){
				$b = true; break;
			}
		}
		if ($b){
			$mdisc[$ii][2]= $tem; 
			$mdisc[$ii][5]= $Lect; 
			$ii++;
			//$mdis[2] = $tem; echo "<br>$mdis[2] ";
		}else{ // добавить дисциплину
			//echo "<br>$kod,0,$tem,$name,false,$Lect";
			$mdisc[] = array($kod,0,$tem,$name,false,$Lect);	
		}
	}
?>
<!DOCTYPE html>    
<HTML><head>
<META http-equiv=Content-Type content="text/html; charset="UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<script src="jquery-latest.min.js"></script>
<script>
<?php
	echo "fam = '".$Lfam."'\n";   //  
	echo "gr = '".$gr."'\n";   //  
	echo "mkod = [";
	$i=0;
	foreach ($mdisc as $mdis){
		$zp = ($i==0)?"":",";
		$cc = ($mdis[4])?"true":"false";
		echo $zp."['".$mdis[0]."',".$mdis[1].",".$mdis[2].",'".$mdis[3]."',".$cc."]";
		$i++;
	}
	echo "]\n";
// mkod = {['ob',0,4,'Основы бизнеса',false],['tiim',0,4,'Теория и история менеджемента',false]}	
?>
function fsave(){
	// изменились ли флажки? false->true добавить true->false удалить  true->true оставить
	var b = false  // надо изменить
	var mn = new Array(); var j = 0
	for (var i=0; i<mkod.length;i++){
		b0 = document.getElementById("cb"+i).checked   // true|false
		b1 = mkod[i][4]
		//	alert(i+" "+b0+" "+b1)
		b = b || (b0!=b1) 
		if (b0){
			mn[j] = mkod[i]; j++	
		}
	}
	if (b){
		snew = ""
		for (j1=0; j1<j; j1++){
			snew += (j1==0?"":"|")+mn[j1][0]+";"+mn[j1][1]+";"+mn[j1][2]+";"+mn[j1][3]
		}
		//alert(snew)
		$.get("sprav-prep-save.php?fam="+fam+"&gr="+gr+"&snew="+snew,
			function(data){ // 
				$("#msg").html(data)
			}
		)
	}
}

</script>
</head>
<body>
<input type='button' value='Закрыть окно' onclick = 'window.close()'  class='b2'>
<input type='button' value='Сохранить' onclick = 'fsave()'  class='b2'>
<font color='red'><span id='msg'></span></font>
<br>

<?php
	echo "<BR><table class='tw' border cellspacing='0' cellpadding='5'>";
	echo "<tr><th>код<th>дост.<br>тем<th>всего<br>тем<th>название<th>лектор<th>для группы<br> $gr";
	$ii=0;
	foreach ($mdisc as $mdis){
		echo "<tr><td>$mdis[0]<td>$mdis[1]<td>$mdis[2]<td>$mdis[3]<td>$mdis[5]<td align='center'>";
		$cc = ($mdis[4]==1)?"checked":"";
		echo "<input type='checkbox' id='cb".$ii."' ".$cc.">";
		$ii++;
	}
	echo "</table>";

	
?>

</body>