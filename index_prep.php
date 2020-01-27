<?PHP //header("Content-Type: text/html; charset=utf-8");
/* 
test_jput.php - раздать тесты
список дисциплин control.xml <дисциплина kod="prog" name="Программирование" />
	 "index_prep.php"   include  в  index_login.php
user=prep&gr=pi4-2&login=cln&parol=11	 
*/
?>
<html>
<head>
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<?php
	//$gr = $_REQUEST["gr"];
	//if (empty($gr)){
		$gr = $_REQUEST["gr1"];
		if (empty($gr)){
			echo "<BR>Группа не задана<BR>";	
			return;
		}
		//$gr = $_REQUEST["gr1"];
	//}	
	$fam = $_REQUEST["fam"];
	$Lfam = $_REQUEST["Lfam"];
	include "lib1_file.php";
	CreateDir("TEST-$Lfam");
	//$ftr = "GR-$gr/trening.json";
	// возможно используются тесты лектора?
	$fd = "ADM/_disc.xml";
	if (is_file($fd)){ // без uplan2.xml
		//<дисциплина кратк="Wpr" название="Web-программирование" преподаватель="Чернышов Л.Н." лекц="16" //сем="32" тем="6"/>
		//<дисциплина кратк="DB" название="Базы данных" преподаватель="Лукин В.Н." лекц="10" сем="40" тем="3"/>
		//echo "fd=$fd<br>";
		//echo "fam=$fam<br>";
		//echo "Lfam=$Lfam<br>";
		
		$xml = simplexml_load_file($fd); // 
		foreach ($xml->дисциплина as $p) { //
			$dcod = $p["кратк"]; $name = $p["название"];
			$prep = $p["преподаватель"];
			$mp = explode(" ",$prep); $pfam=$mp[0]; 
			//echo "pfam=$pfam<br>";
			
			if ($pfam==$fam){
				$quest["$dcod"] = $name;
				$qLec["$dcod"] = $prep;
			}
		}
		$kd = 0;
		if (is_file("TEST-$Lfam/trening.xml")){  // "$TEST-$Lfam/trening.xml";
			$xmltr = simplexml_load_file("TEST-$Lfam/trening.xml"); // тесты для тренинга преподавателя
			foreach ($xmltr->дисциплина as $p) {
				$kd++;
			}	
		}
		if ($kd==0){	// <дисциплина kod='WPr' name='' tem='6' />
			$h = fopen("TEST-$Lfam/trening.xml","w");
			fputs($h, "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>\n");
			fputs($h, "<дисциплины>\n");
	//<дисциплина kod='WPr' name='Web-программирование' tem='4'/>
			foreach($quest as $kod=>$nn){
				$Lec = $qLec[$kod];
				$sL = empty($Lec)?"":"лектор='".$Lec."'";
				fputs($h,"<дисциплина kod='".$kod."' name='".$nn."' ".$sL." tem='0'/>\n");
			}
			fputs($h, "</дисциплины>");
			//if (empty($quest)){
			//	echo "<BR>Нет тестов<BR>";	return;
			//}
		}
		
	}else{
		$fuplan = "Data1/uplan2.xml";
		if (!is_file("TEST-$Lfam/trening.xml")){  // "$TEST-$Lfam/trening.xml";
			if (is_file($fuplan)){  // 
		//  <дисциплина код="Б.1.1.3.12" кафедра="ЭТ" название="Программирование" 
		//преподаватель="Лукин В.Н." лектор="Чернышов Л.Н." сем1="1" лекции1="18" семинары1="36" СРС1="54" сем2="2" лекции2="18" семинары2="18" СРС2="72" 
		// краткое="ЭТ"/>
				$xml = simplexml_load_file($fuplan); // 
				foreach ($xml->дисциплина as $p) { //
					$dcod = $p["краткое"]; $name = $p["название"];
					$prep = $p["преподаватель"]; $pLec = $p["лектор"];
					$mp = explode(" ",$prep); $pfam=$mp[0]; 
					if ($pfam==$fam){
						//echo "<br>$dcod $prep $pLec";
						$quest["$dcod"] = $name;
						$qLec["$dcod"] = $pLec;
					}
				}	
			}else{
				echo "Нет файла $fuplan"; return;
			}	
		}
		$h = fopen("TEST-$Lfam/trening.xml","w");
		fputs($h, "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>\n");
		fputs($h, "<дисциплины>\n");
//<дисциплина kod='WPr' name='Web-программирование' tem='4'/>
		if (!empty($quest)){
			foreach($quest as $kod=>$nn){
				$Lec = $qLec[$kod];
				$sL = empty($Lec)?"":"лектор='".$Lec."'";
				fputs($h,"<дисциплина kod='".$kod."' name='".$nn."' ".$sL." tem='0'/>\n");
			}
		}
		fputs($h, "</дисциплины>");
		if (empty($quest)){
			echo "<BR>Нет тестов<BR>";	return;
		}
	}  

	$xmltr = simplexml_load_file("TEST-$Lfam/trening.xml"); // тесты для тренинга преподавателя
	//echo "<br>TEST-$Lfam/trening.xml";
	foreach ($xmltr->дисциплина as $p) { //
		//<дисциплина kod='ob' name='Основы бизнеса' [лектор=''] tem='3'/>
		$x1 = $p["kod"]; $x2 = $p["name"]; $x3 = $p["tem"];
		$nj = "TEST-$Lfam/$x1-btz.json";
		$quest["$x1"] = "new Array()";
		if (is_file($nj)){ // расклад по темам
			$mm = file($nj); $smm = ""; foreach($mm as $s) $smm.=$s;
			$mj = json_decode($smm); // {"btz":"ob","quest":[5,14,7]}
			$quest["$x1"] = $mj->quest; // quest;
		}
	}	
	//foreach($quest as $x=>$y){$s="";foreach($y as $z)$s.=$z; echo "<br>$x=>$s";}
	$mdir = scandir("TEST-$Lfam");
	$k = 0; 
	$stxt = ""; 
	foreach ($mdir as $dir){
		$kl = strlen($dir);
		if ( substr($dir,0,4)=='TEST' && substr($dir,$kl-4,4)=='.txt') {
			$xx = substr($dir,5);  // имя файла f1.txt   $x1=5
			$x1 = strlen($xx); //
			if (substr($xx,$x1-4,4)==".txt") $xx = substr($xx,0,$x1-4);
			$mkr[$k++] = $xx;
			$stxt .= (";".$xx);
		}
	}
	//return;
?>
<script src="jquery-latest.min.js"></script>
<script>
<?php
	$fnc = "GR-$gr/control.json"; 
	$sm = "";   // открыт доступ
	$disc = "";	$psw='1'; $maxtime = 15; $ball0 = 5;	$ball1 = 3; $person = "false";
	if (is_file($fnc)){  // доступ открыт?
		$mm = file($fnc); foreach($mm as $s) $sm.=$s;
		$mj = json_decode($smm); 
		// {"disc":"tiim","min":15,"psw":"1","ball0":5,"ball1":3,"person":"false"}
		//$disc = $mj->disc; // quest;
		$disc = $mj->disc;
		$maxtime = $mj->min;
		$psw = $mj->psw;
		$ball0 = $mj->ball0;
		$ball1 = $mj->ball1;
		$person = $mj->person;
		echo "control=$sm\n";
	}else{
		echo "control={}\n";
	}
	//echo "disc='".$disc."'\n";
	$ndisc = ""; // Роздано
	if (is_file("GR-$gr/testcontrol.txt")){
		$mtxt = file("GR-$gr/testcontrol.txt"); 
		$ndisc=trim($mtxt[0]);
		echo "name0='".$ndisc."'\n";
	}else echo "name0=''"."\n";

	echo "mq={}\n";
	
	foreach($quest as $k=>$v){
		$sv="new Array()";
		if ($v!="new Array()") {
			$sv = "new Array("; $ii=0;
			foreach($v as $q){ 
				$sv .= (($ii==0?"":",").$q); $ii++;
			}
			$sv .= ")";
		} 
		echo "mq['".$k."']=".$sv."\n";
	}
	
	echo "prep='".$Lfam."'\n";
	echo "gr='".$gr."'\n";
	echo "stxt='".$stxt."'\n";
	
	if (empty($gr)){echo "Не задана группа"; return;}
	$fgrup 	=   "GR-$gr";
	$dh = opendir($fgrup);
	$nmax = 0; $nres=0;
	while ($file = readdir($dh)) {
		if (substr($file,0,8)=="test-res") {
			$nres++;
		}else if (substr($file,0,5)=="test-"){
			$nmax++; 
		}	
	}
//	echo "<b>Группа $gr</b> (результатов $nres из $nmax)";
	echo "nres='".$nres."'\n";
	echo "nmax='".$nmax."'\n";

	
?>
btz = ""
function finit(){
	//return;
	var md = document.getElementById("Tema").options
	var kd = md.length
	btz = window.localStorage.getItem('dz1-prbtz')
	var sc = JSON.stringify(control)
	if(sc.length>2){ // был открыт доступ
		//{"disc":"ob","min":11,"psw":"33","ball0":3,"ball1":1,"person":"true"}
		//$("#result").html(control.disc)
		$("#min").attr("value",control.min)	//var min = document.getElementById("min").value // 
		$("#psw").attr("value",control.psw)	//var psw = document.getElementById("psw").value
		$("#ball0").attr("value",control.ball0)	//var ball0 = document.getElementById("ball0").value
		$("#ball1").attr("value",control.ball1)	//var ball1 = document.getElementById("ball1").value
		if (control.person=="true") document.getElementById("person").checked = true
		for(var i=0; i<md.length;i++){
			if(control.disc==md[i].value.split(";")[0]){
				//alert(i)
				document.getElementById('Tema').options[i].selected=true
				ftest() // выбор дисциплины
				$("#dcopen").html(control.t0); //alert(control.t0)
				var data="Роздано в группе "+gr
				$("#result").html(data)
				document.getElementById("open").disabled=false
				//fopen()  // открыть доступ 
				document.getElementById("open").value="Доступ открыт"
				document.getElementById("open").disabled = true
				document.getElementById("close").value="Закрыть доступ"
				document.getElementById("close").disabled=false
				//var d = new Date();
				$("#dcclose").html("")
				document.getElementById("Tema").disabled=true
				document.getElementById("clear").disabled=true
				document.getElementById("jtest").disabled=true
			}
		}
		document.getElementById("Tema").disabled=true
	}else if (name0.length>0){ // Роздано  доступ не открыт
		//alert("name0 "+kd)
		if (kd==1){
			document.getElementById('Tema').options[0].selected=true
			ftest() // выбор дисциплины
		}else{
			for(var i=0; i<kd;i++){
				if(name0==md[i].value.split(";")[3]){
					document.getElementById('Tema').options[i].selected=true
					ftest() // выбор дисциплины
				}
			}
		}
	}else{ // не роздано 
		//alert("n0 "+kd)
		if (kd==1){
			document.getElementById('Tema').options[0].selected=true
			ftest() // выбор дисциплины
		}else{
			for(var i=0; i<kd;i++){
				if(name0==md[i].value.split(";")[3]){
					document.getElementById('Tema').options[i].selected=true
					ftest() // выбор дисциплины
				}
			}
		}
	}
	//frm1.BTZ.value=btz
	document.getElementById("res").disabled = false
	$("#gr_res").html("результатов "+nres+" из "+nmax)
}
/*----------------------------------------------------------
function fsetBTZ(){ // ???
	btz = frm1.BTZ.value // $("#BTZ").attr("value")
	window.localStorage.setItem('dz1-prbtz', btz);
	var ms = btz.split("-")
	if (ms[1]==prep){
		fbtz.btz.value=btz
	}	
}
*/
function fnq0(){
	var n0 = document.getElementById('nq0').value
	var n1 = document.getElementById('nq1').value
	document.getElementById('nq2').value = n0-n1
}
function fnq(n){ // изменить число вопросов по теме
	var n0 = document.getElementById('nq0').value
	var n1 = document.getElementById('nq'+n).value
	document.getElementById('nq'+(3-n)).value = n0-n1
}
var kt = 0 // число тем
var it = 0 // номер темы
var st = 0 // общее число вопросов
var dkod = "" // код дисциплины	
var sk = ""   // 4|5|6 - вопросов по темам
var name=""  // назв. выбранной дисциплины
function fsum(){ // изменение вопросов по теме
	var s = 0
	for (var i=0; i<kt;i++){
		var q = document.getElementById("t_"+i).value
		s += 1*q
	}
	document.getElementById("tall").value = s
	it = 0
	st = s
}
function fall(){ // изменение общего числа вопросов
	var ds = 1*document.getElementById("tall").value - st
	st = document.getElementById("tall").value
	document.getElementById("t_"+it).value = 1*document.getElementById("t_"+it).value + 1*ds
	it = (it+1); if (it>=kt)it=0
}
function fsave(){ // сохранить БТЗ
	var s=""
	for (var i=0; i<kt;i++){
		var q = document.getElementById("t_"+i).value
		s += (q + (i==kt-1?"":","))
	}
	//alert(prep+' '+dkod+" "+s)
	$.get("test_btz.php?prep="+prep+"&disc="+dkod+"&qq="+s,
		function(data){ // обновить mq[dkod]  mq['ob']=new Array(2,1,1)
			for (var i=0; i<kt;i++){
				mq[dkod][i] = document.getElementById("t_"+i).value
			}
		}
	);
}
function ftest(){ // выбор дисциплины 
	var disc = document.getElementById("Tema").value
	var md = disc.split(";");  // 
	//dkod;kdost;kt;name;v1|v2... для тренинга
	dkod = md[0]; 	name = md[3] 
	//alert(name0+" disc="+disc)  //  
	//  disc=prog;1;3;Программирование;2|2|1|
	if (name0.length>0){
		$("#result").html("Роздано по '"+name0+"'")
		//alert(name0+"=="+md[1])
		document.getElementById("open").disabled = (name0!=name)
		document.getElementById("clear").disabled = (name0!=name)
		document.getElementById("res").disabled = (name0!=name)
		document.getElementById("jtest").disabled = (name0!=name)
	}else{
		$("#result").html("")
		document.getElementById("open").disabled = true
		document.getElementById("jtest").disabled = false
		document.getElementById("res").disabled = true
	}
	$("#dcopen").html("")
	$("#dcclose").html("")
	document.getElementById("close").disabled = true

	kt = md[2] // число тем всего
	sk = md[4] // 4|5|6 - всего вопросов по темам
	var mk = sk.split("|")   
	var mt = new Array()
	if (mq[dkod].length==0){ // не было разбивки
		for (var i=0; i<kt;i++){
			mt[i] = mk[i] 
		}
	}else{
		kt1 = mq[dkod].length  // разбивка препода м.б. меньше (добавились темы)
		for (var i=0; i<kt;i++){
			mt[i] = (i>=kt1?0:mq[dkod][i]) 
		}
	}	
	//return
	var sh = "<br><table><tr>"
	sh += "<td><input type='button' class='b2' onclick = 'fsave()' value='Сохранить'>"
	sh += "<td>"
	var kk = 0, kk0=0; st=""
	for (var i=0; i<kt;i++){
		//alert(i+" "+mt[i])
		var pp = (i==kt-1)?"":"+"
		st += (mk[i]+pp)
		var pL = (i==kt-1)?"=":"+"
		kk += 1*mt[i]; kk0 += 1*mk[i]
		sh += "<td><input onchange='fsum()' type='number' style='width:35px' id='t_"+i+"' value="+mt[i]+" min=0 max="+mk[i]+">"+pL
	}
	sh += "<td><input onchange='fall()' type='number' style='width:40px' id='tall' min=0 max="+kk0+" value="+kk+"></table>"
	var mdisc = disc.split(";")
//alert(st+"="+kk0+" "+sh) 
	$("#contr").html(" Вопросов по темам "+st+"="+kk0+" "+sh)
	st = kk
//return
	ftren()  // для тренинга
}
function put_jtest(){ // раздать тесты для контроля по дисциплине dkod
	var sq="", kt= mq[dkod].length
	for (var i=0; i<kt; i++){
		sq += (mq[dkod][i] + (i==kt-1?"":","))
	}
	//alert("gr="+gr+"&disc="+dkod+"&sq="+sq+"&sk="+sk+"&prep="+prep)
	// см. get_json.php
	$.get("test_jput.php?gr="+gr+"&disc="+dkod+"&sq="+sq+"&sk="+sk+"&prep="+prep+"&name="+name,
		function(data){
			//alert(data)
			$("#result").html(data)
			document.getElementById("open").disabled=false
			document.getElementById("clear").disabled=false
		}
	);
}
function fopen(){ // открыть доступ тест-контроля
	var min = document.getElementById("min").value // 
	//var min = $("#min").attr("value")
	var psw = document.getElementById("psw").value
	var ball0 = document.getElementById("ball0").value
	var ball1 = document.getElementById("ball1").value
	var person = document.getElementById("person").checked
	var d = new Date();
	var fdate = d.getHours()+"."+d.getMinutes()
	$("#dcopen").html(fdate)
	var ss = "&min="+min+"&psw="+psw+"&ball0="+ball0+"&ball1="+ball1+"&person="+person+"&t0="+fdate
	//alert(ss)
	$.get("test_jput.php?open=yes&gr="+gr+"&disc="+dkod+ss,
		function(data){
			document.getElementById("open").value="Доступ открыт"
			document.getElementById("open").disabled = true
			document.getElementById("close").value="Закрыть доступ"
			document.getElementById("close").disabled=false
			$("#dcclose").html("")
			document.getElementById("Tema").disabled=true
			document.getElementById("clear").disabled=true
			document.getElementById("jtest").disabled=true
		}
	);
}
function fclose(){ // закрыть доступ  тест-контроля
	$.get("test_jput.php?close=yes&gr="+gr+"&disc="+dkod,
		function(data){
			document.getElementById("open").value="Открыть доступ"
			document.getElementById("close").value="Доступ закрыт"
			document.getElementById("open").disabled = false
			document.getElementById("close").disabled = true
			var d = new Date();
			var fdate = d.getHours()+"."+d.getMinutes()
			$("#dcopen").html("")
			$("#dcclose").html(fdate)
			document.getElementById("Tema").disabled=false
			document.getElementById("clear").disabled=false
			document.getElementById("jtest").disabled=false
		}
	);
}
function fclear(){ // удалить тесты-контроля
	$.get("test_jput.php?clear=yes&gr="+gr+"&disc="+dkod,
		function(data){
			$("#dopen").html("")
			$("#dclose").html("")
			document.getElementById("clear").disabled=true
			document.getElementById("open").disabled=true
			document.getElementById("res").disabled=true
			$("#result").html(data)
			name0=""
			nres = 0
			$("#gr_res").html("результатов "+nres+" из "+nmax)

		}
	);
}
//-------------------------------------------------- тренинг
function puttest(){ // доступные темы             
	//alert('pt')
	num = $("#tema1").attr('value')
	num = document.getElementById("tema1").value
	//alert(num+" "+dkod+" "+prep+" "+gr)
	$.get("sprav-prep-save.php?gr="+gr+"&dkod="+dkod+"&num="+num+"&fam="+prep,
		function(data){
			$("#tmsg").html(data)
		}
	);
}
function ftclear(){ // очистить             
	var kod = document.getElementById("Tema").value // prog;0;Программирование
	var md = kod.split(";")
	$.get("test_put.php?gr="+gr+"&disc="+md[0]+"&do=clear",
		function(data){
			$("#trmsg").html(data)
			/*document.getElementById("put").disabled=false
			document.getElementById("tema1").value = 1  // следующий
			//document.getElementById("tema2").value = ""	
			document.getElementById("tropen").disabled=true
			document.getElementById("trclear").disabled=true
			ftclose()
			*/
		}
	)	
}

function ftren(){ // выбор дисциплины для тренинга 
	var kod = document.getElementById("Tema").value  // "ob";0;4;"Основы бизнеса"
	//alert(kod)
	var md = kod.split(";")
	$("#tren").html(md[3])
	dkod = md[0]
	var tmax = md[2]  // число тем дисциплины
	var num = md[1] // число доступных тем
	if (num==tmax){
		document.getElementById("tema1").disabled = true // доступны все
	}	
	document.getElementById("put").disabled = (num==tmax) // раздать
	$("#tema1").attr("value",num).attr("max",tmax)
	$("#trmsg").html("")
	$("#tmsg").html("")
	// записано, сколько роздано (начало - 0)
	// {"disc":[ ["prog",0,4,"haritonova","Основы бизнеса"],...]}  - сколько роздано
	//$.getJSON('GR-'+gr+'/trening.json', function(data){
	var val1=""
	$.get("test_put.php?do=date&gr="+gr+"&disc="+dkod,
		function(data){  // d0;d1    вернуть даты из trening-kod.txt   trening-kod-close.txt
			//alert("data="+data) дата открытия;дата закрытия;num
			var dd = data.split(";")
			//alert(dd)
			document.getElementById("trclear").disabled = dd[0].length>0

			document.getElementById("dopen").value = dd[0]
			document.getElementById("dclose").value = dd[1]
			document.getElementById("tropen").disabled = dd[0].length>0
			document.getElementById("trclose").disabled = dd[0].length==0
			document.getElementById("put").disabled = dd[0].length>0
			document.getElementById("tema1").disabled=dd[0].length>0

			document.getElementById("tropen").value=dd[0].length>0?"Доступ открыт":"Открыть доступ"
			document.getElementById("trclose").value=dd[1].length>0?"Доступ закрыт":"Закрыть доступ"
			$("#trresult").attr("disabled",false)
		}	
	);
}

function ftopen(){ // открыть доступ для тренинга
	var d = new Date();
	var m = d.getMonth()+1
	var fdate = d.getDate()+"."+m
	document.getElementById("dopen").value = fdate
	document.getElementById("dclose").value = ""
	document.getElementById("tropen").disabled = true
	document.getElementById("tropen").value = "Доступ открыт"
	document.getElementById("trclose").value = "Закрыть доступ"
	document.getElementById("trclose").disabled = false
	document.getElementById("trclear").disabled = true
	var kod = document.getElementById("Disc").value  // prog;2;4;Программирование
	alert("kod="+kod)
	var md = kod.split(";")
	//var psw = document.getElementById("psw").value  // 
	alert("date="+fdate+"&gr="+gr+"&disc="+md[0]+"&kt="+md[1]+"&nd="+md[2]+"&prep="+prep)
	$.get("test_put.php?do=open&date="+fdate+"&gr="+gr+"&disc="+md[0]+"&kt="+md[1]+"&nd="+md[3]+"&prep="+prep,
		function(data){
			document.getElementById("put").disabled=true
			document.getElementById("tema1").disabled=true
		}
	);
//alert(fdate)
}
function ftclose(){ // закрыть доступ для тренинга
	var d = new Date();
	var m = d.getMonth()+1
	var fdate = d.getDate()+"."+m
	document.getElementById("dclose").value = fdate
	document.getElementById("dopen").value = ""
	document.getElementById("trclose").value = "Доступ закрыт"
	document.getElementById("tropen").value = "Открыть доступ"
	document.getElementById("tropen").disabled = false
	document.getElementById("trclose").disabled = true
	
	document.getElementById("trclear").disabled = false
	var kod = document.getElementById("Tema").value  // prog;4;Программирование
	var md = kod.split(";")
	$.get("test_put.php?do=close&date="+fdate+"&gr="+gr+"&disc="+md[0],
		function(data){
			document.getElementById("put").disabled=false
			document.getElementById("tema1").disabled=false
		}
	);
	//alert(fdate)
}
function ftresult(){
	//alert("dko")
	$("#tr_dkod").attr('value',dkod)
	return
	$("#frmresult").submit(function( event ) {
		alert( "Handler for .submit() called." );
		event.preventDefault();
	});
}
</script>
</HEAD>
<BODY onload='finit()'> 

<table  cellpadding='5'> 
<tr><td><td>
<b>Преподаватель</b> &nbsp; <?php echo $fam;?>&nbsp; &nbsp; 
<?php
	echo "<b>Группа $gr</b> (<span id='gr_res'></span>)";  // результатов $nres из $nmax
?>
<tr><td valign='top'>

<td> 
<table border = '1' cellspacing = '0' cellpadding='5' class="t0" style='background-color:#F0F0F0'>
<tr>
<td valign='top' rowspan='3'>
	<br>
	<form>
		<input valign='top' type='button' value='Закрыть окно' onclick = 'window.location="index.php"' class='b2'>
	</form>
	<form name="frmadmink" action="sprav-gr2.php" method="post" target="_blank">
		<input type="submit" value="Мои группы" name="grups"  class='b2'>
		<input type="hidden" value="<?php echo $fam;?>" name="fam">
		<input type="hidden" value="<?php echo $Lfam;?>" name="Lfam">
	</form>
	<form name="frmpsw" action="sprav-gr1.php" method="post" target="_blank">
		<input type="submit" value="Пароли группы" name="psw"  class='b2'>
		<input type="hidden" value="<?php echo $gr;?>" name="grup">
	</form>
	<form name="frmr" action="sprav-prep.php" method="post" target="_blank">
		<input type="submit" value="Дисциплины" name="tr"  class='b2'>
		<input type="hidden" value="<?php echo $fam;?>" name="fam">
		<input type="hidden" value="<?php echo $Lfam;?>" name="Lfam">
		<input type="hidden" value="<?php echo $gr;?>" name="gr">
	</form>	
	<form name="frmr" action="sprav-tema.php" method="post" target="_blank">
		<input type="submit" value="Темы дисциплин" name="tr"  class='b2'>
		<input type="hidden" value="<?php echo $fam;?>" name="fam">
		<input type="hidden" value="<?php echo $Lfam;?>" name="Lfam">
	</form>	

<td valign='top'>
    <b>Тесты (контроль):</b><BR>
	<?php // список дисциплин  <дисциплина kod="prog" name="Программирование" tem="4"/>

function fLect($xmltr,$dkod,$prep){
	$Lec = ""; // $prep - лектор по дисциплине $dkod
	foreach ($xmltr->дисциплина as $p) { //  "TEST-$Lfam/trening.xml"
		//<дисциплина kod='ob' name='Основы бизнеса' [лектор=''] tem='3'/>
		$x11 = $p["kod"]; $xL = $p["лектор"];
		$xL1 = translit8($xL);
		if ($x11==$dkod && $xL1==$prep){
			$Lec = $xL1; break;
		}
//<дисциплина kod='ob' name='Основы бизнеса' [лектор=''] tem='3'/>
	}
	return $Lec;
}	
	
	
	if (!is_file("GR-$gr/trening_gr.json")){
		echo "<b><font color='red'>Дисциплины не назначены</font><b>";
	}else{
		$mm = file("GR-$gr/trening_gr.json"); 
		$smm = ""; foreach($mm as $s) $smm.=$s;
		$md = json_decode($smm); // {"disc":[
								 // ["ob",2,4,"lukin","Основы бизнеса" ...],
								 // ["prog",2,4,"chernyshov","программирование  -- лектор!	
		$kd = 0;				 // 	2 доступно, 4 - всего
		foreach ($md->disc as $disc){
			$Lect="";
			if ($disc[3]==$Lfam) $kd++;  // м.б.лектор!
			else { // предмет м.б. лектора
				$Lect = fLect($xmltr,$disc[0],$disc[3]); 
				if (!empty($Lect)) $kd++;
			}
			//echo "<br>".$Lfam." ".$disc[0]." ".$disc[3]." ".$Lect;
		}	
		//echo "<br>kd=$kd";
		echo "<SELECT size=$kd onchange='ftest()' id='Tema' style='width:480px'>";
		foreach ($md->disc as $disc){
			//	["WPr",0,2,"chernyshov","Web-программирование"]
			//              lukin    
			$x0 = $disc[0];  $Lect=""; $prep = "";
			$x1 = $disc[1];  $x2 = $disc[2]; $x4 = $disc[4];
			if ($disc[3]==$Lfam){
				$prep = $Lfam;
				$qq = "";  //    дост             всего
			}else{ 
				$Lect = fLect($xmltr,$x0,$disc[3]); 
				if (!empty($Lect)) $prep = $Lect;;
			}	
			//echo "<br>$Lfam/$x0 $Lect prep=$prep";	
			
			if (!empty($prep) && is_file("TEST-$prep/$x0".".xml")){ // разбивка вопросов по темам
				$xt = simplexml_load_file("TEST-$prep/$x0".".xml"); 
				$j=0;
				foreach ($xt->тема as $q) { //
					$qq .= (($j==0?"":"|"). $q["вопросов"]);
					$j++;
				}
				
				echo "<OPTION value='".$x0.";".$x1.";".$x2.";".$x4.";".$qq."'> $x4 (тем $x1"."/$x2)";
				//echo "<br>OPTION value='".$x0.";".$x1.";".$x2.";".$x4.";".$qq."' $x4 (тем $x1"."/$x2)";
			}
		// для тренинга
		//	$tdost = $disc[1]; $tall = $disc[2]; $name=$disc[4];
		//	echo "<OPTION value='".$disc[0].";".$tdost.";".$tall.";".$name."'> $name (тем $tall)";
		}	
		echo "</SELECT>"; 
	}
	?>
<tr><td valign='top'>
	<table cellpadding='2' >
		<tr><td colspan='4'><div id='contr'></div>
			<hr>
		<tr><td>
			<input type="button" onclick='put_jtest()' value="Раздать тесты" id="jtest" disabled class='b2'>
			<td colspan='3'><font color='red'><span id="result"></span></font>
		<tr><td>
			<input type="button" onclick='fopen()' disabled value="Открыть доступ" id="open" class='b2'>
			<td align='center'><font color='red'><span id="dcopen"></span></font>
			<td>пароль: <input id="psw" value='<?php echo $psw;?>' style='width:40px' >
			<td align='right'>балл за ответ: <input id="ball0" type='number' min='1' max='10' value='<?php echo $ball0;?>'
				style='width:40px'>
		<tr>
			<td>
			<td align='right'>минут:<input id="min" value='<?php echo $maxtime;?>' style='width:40px'>
			<td><input type="checkbox" id="person"> личный
		    <td align='right'>за част.ответ: <input id="ball1" type='number' min='1' max='10' value='<?php echo $ball1;?>'
							style='width:40px'>
		<tr>
			<td><input type="button" onclick='fclose()' disabled value="Доступ закрыт" id="close" class='b2'>
			<td align='center'><font color='red'><span id="dcclose"></span></font>
		<tr><td>
			<form name="frm-contr" action="test_result.php" method="post" target='_blank'>
				<input type="hidden" value="<?php echo $gr;?>" name="grup">
				<input type="submit" value="Результаты" name="result" id='res' class='b2'>
				<td valign='top'><input type="checkbox" name="full"> полные
			</form>
			<td valign='top'>
			<td valign='top'><input type="button" onclick='fclear()' disabled value="Очистить" id="clear" class='b2'>
			<!-- input type="submit" value="Перенумеровать" name="renum" class='b2' -->
	</table>
		
<tr><td valign='top'>
		<b>Тесты (тренинг):</b><span id="tren"></span>
		<hr>
		<table  cellpadding='3' width='100%'>
			<tr><td>
				<input type="button" onclick='puttest()' value="Доступные темы" id="put" disabled  class='b2'>
				<td><td>1-<input type='number' disabled min='1' max='' id="tema1"  value='' style='width:35px'>
				<!--td align='right'>Розданы темы<td><input id="tema2" disabled value='' style='width:40px'-->
				<td colspan='3'><font color='red'><span id="tmsg"></span><font>
			<tr><td>
				<input onclick = 'ftopen()' type="button" value="Открыть доступ" id="tropen" disabled class='b2'>
				<td>дaта:<td><input id="dopen" size='3' disabled value='<?php echo $dopen;?>'>
				<td>дней:<td width='30%'><input name="dmin" size='3' value='<?php echo $maxdays;?>'>
			<tr><td>
				<input onclick = 'ftclose()' type="button" value="Закрыть доступ" id="trclose" class='b2' disabled >
				<td>дaта:<td><input id="dclose" size='3' disabled value='<?php echo $dclose;?>'>
			<tr><td>
				<input onclick="ftclear()" type="button" value="Очистить истории" id="trclear" class='b2' disabled >
				<td colspan='5'><font color='red'><span id='trmsg'></span></font> 
			<tr><td colspan='6'><hr>
				<form action='tr_result.php' target='_blank' id="frmresult">
					<input disabled onclick="ftresult()" type="submit" value="Результаты"  class='b2' id="trresult">	
					<input type='hidden' value='<?php echo $gr;?>' name='gr'>
					<input type='hidden' id='tr_dkod' value='' name='dkod'>
					
				</form>
		</table>
</table>
</table>

</body></html>