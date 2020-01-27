<?php  header("Content-Type: text/html; charset=UTF-8");
/*  	 "index_stud.php"   
 Вход студента $gr определена в index_login
 отображается список группы 
 Поля Фамилия, Пароль (значение сохраняются) и 
 кнопка Тест-тренинг (только если не режим Тест-контроль)
 по кновке вызывается ftrening  интерфейс строится в tesn_get.php
*/
?>

<html>
<head>
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<?php
$gr = $_REQUEST["gr"];
include "lib1_file.php";
$gr = translit8($gr);
$nf = "GR-$gr/trening_gr.json";
if (!is_file("GR-$gr/control.json")){ // тренинг
	if (!is_file($nf)){
		echo "<b>Нет дисциплин для тренинга</b>";
		return;
	}
}
?>
<script src="jquery-latest.min.js"></script>
<script>
<?php
	echo "gr = 'GR-".$gr."'\n";   // "/GR-".$gr.".txt'
if (!is_file("GR-$gr/control.json")){ // тренинг
	// открыт ли доступ на тренинг какой-либо дисциплины?
	$nf = "GR-$gr/trening_gr.json";
	// {"disc":[ ["WPr",0,2,"chernyshov","Web-программирование"]
	$mm = file($nf); $smm = ""; foreach($mm as $s) $smm.=$s;
	$mj = json_decode($smm);
	//print_r($mj);
	$mdisc=""; // дисциплины, открытые для группы
	//??mdisc = 'ob;1;3;Основы бизнеса|WPr;1;2;Web-программирование'
	$i=0;
	foreach($mj->disc as $dd){
		$disc = $dd[0]; $tema=$dd[1];  $prep = $dd[3];  $name = $dd[4]; 
		//if (is_file("GR-$gr/trening-$disc.txt")){
			$ras = $i==0?"":"|";
			$mdisc .= "$ras$disc;$name;$tema;$prep";
			$i++;
		//}
	}
	echo "mdisc = '".$mdisc."'\n";   // "kod;name;ntema;prep|..."
	$mdd1 = explode("|",$mdisc);
	$mdd2 = explode(";",$mdd1[0]);
	echo "prep = '".$mdd2[3]."'\n";  
	echo "control = false\n";  
	echo "person = 'true'\n";  
	
}else{ // контроль
	// из index_login $mj  = {"disc":"tiim","min":15,"psw":"1","ball0":5,"ball1":3,"person":"false"}
	//$psw = $mj->psw; // групповой пароль 
	//$person = $mj->person; // "true" | "false"
	echo "person = '".$mj->person."'\n";   // false true
	echo "control = true\n";  
}	
	
	
?>
stat=0
var fam0="" // n.Фамилия Имя
var fam=""
var nstud=0
function fstud(){  // выбор студента
	//frms.login1.value = document.getElementById("studs").value
	fam0 = document.getElementById("nstuds").value
	mf = fam0.split(".")
	nstud = mf[0]
	document.getElementById("fam0").value = fam0
	$("#trening").html("")
	$("#history").html("")
	$("#session").html("")
	$("#theader").html("")
	$("#contr").html("")
	if (!control)
		document.getElementById("trparol").value=""
}

prep = ""  // преподаватель
pre = "test3"
function setSLog(psw){
	if (person=="true" && control)
		window.localStorage.setItem(pre+'-tparol', psw);
	else 
		window.localStorage.setItem(pre+'-trparol', psw);
}
function sInit(){ // восстановление фио и пароля
	fam0 = window.localStorage.getItem(pre+'-fam0')
	if (fam0.length==0){
		fam0 = document.getElementById("fam0").value
	}else{
		document.getElementById("fam0").value 	=fam0	
	}
	var mfam = fam0.split(".") // м.б.  1.Иванов или без номера
	nstud=0	// номер по-порядку
	if (mfam.length>1){
		fam = mfam[1];
		nstud = mfam[0]
	} 
	mfam1 = fam.split(" ") // Мванов Иван
	if (mfam1.length>1){
		fam = mfam1[0];
	}
	//alert(person+" "+control)
	if (control){ // тест-контроль
		if (person=="true")document.getElementById("tparol").value =	window.localStorage.getItem(pre+'-tparol');
	}else{
		document.getElementById("trparol").value =	window.localStorage.getItem(pre+'-trparol');
	} 
}
//-----------------------------------------------
function fcontrol1(data){  // режим Кнопка Тест-контроль
	var b = data.substr(0,1)=="?" // непр.пароль
	if (b) {
		data = data.substr(1)
		$("#contr").html(data) // 
	}else{ // пароль правильный
		$.get("test_res.php?gr="+gr+"&nstud="+nstud+"&res=1",
			function(data){
				//alert(data)
				var b1 = data.substr(0,1)=="?" // результат есть
				if (b1){
					//alert(data)
					data = data.substr(1)
					$("#contr").html("<font color='red'>"+data+"</font>") // 
				}else{
					//var mfam = fam.split(" "); fam = mfam[0];
					document.getElementById("famst").value = fam
					document.getElementById("nst").value = nstud
					//alert(gr+" "+fam+" "+psw+" "+nstud)
		//alert(nstud + fam)
					frms.submit()  // test_init.php
				}
			}	
		)		
	}	
}

function fcontrol(){  // режим Кнопка Тест-контроль
    //alert(fam0+person)
	var psw = (person=="true")?document.getElementById("tparol").value:"1"
	window.localStorage.setItem(pre+'-fam0', fam0);
	fam = fam0
	setSLog(psw)
	//alert(fam+' '+psw)
	if (person=="true"){
		$.get("test_psw.php?gr="+gr+"&fam="+fam+"&psw="+psw,
			fcontrol1
		)
	}else{
		fcontrol1("not person")
	}
}
//------------------------------------------
var temap=0  // пройденные темы  1..temap

function ftrening(){  // режим тренинга Кнопка Тест-тренинг
	var psw = document.getElementById("trparol").value
	//alert(fam+fam0+psw)
 
	window.localStorage.setItem(pre+'-fam0', fam0);
	var mfam = fam0.split(" "); fam = mfam[0]; num=0
	mfam = fam.split("."); 
	if (mfam.length>1) {fam = mfam[1]; num = mfam[0]}
//alert("test_get.php?gr="+gr+"&fam="+fam+"&psw="+psw+"&mdisc="+mdisc+"&num="+num)	
	//alert(fam+" "+psw+" "+num+" "+mdisc)
	// mdisc = WPr;Web-программирование;2;chernyshov|fil;Философия;2;chernyshov|ob;Основы бизнеса;2;haritonova
	var b = false
	$.get("test_get.php?gr="+gr+"&fam="+fam+"&psw="+psw+"&mdisc="+mdisc+"&num="+num,
		function(data){  // d0;d1
			//alert("data="+data)
			b = data.substr(0,1)=="?"
			if (b) data = data.substr(1)
				
			$("#trening").html(data) // интерфейс тренинг + график
			
			if (!b){
				setSLog(psw)
//$("#theader").html("<table width='100%'><tr><th align='center'> История<th align='center'> График")
				$("#studs").html("")
				$("#psw").html("")
				finfo()	
			}
		}	
	)
}
function finfo(){  // изменение дисциплины для тренинга
	var disc = document.getElementById("hdisc").value
	//var fam = document.getElementById("fam").value
	//var mfam = fam.split(" "); fam = mfam[0];
	//mfam = fam.split("."); if (mfam.length>1) fam = mfam[1];
	//alert(gr+" "+fam+" "+disc)
	//disc = 'tiim;Теория и история менеджемента;3;prep'
	var md = disc.split(';')
	//document.getElementById("ntema").value = md[2]
	//$("#ntema").attr("max",md[2]).attr("value",md[2])
	prep = md[3]
	$("#tmsg").html("")
	$.get("testHistory.php?gr="+gr+"&fam="+fam+"&disc="+disc,
		function(data){  // d0;d1
			$("#history").html(data)
			temap = $("#temap").html(); //alert(temap) 
			var temap1 = 1*temap+1 
			//alert(temap) 
			$("#ntema").attr("max",temap1)
			$("#ntema").attr("value",temap1)
		}	
	)
	$.get("testGrafInfo.php?gr="+gr+"&fam="+fam+"&disc="+disc,
		function(data){  // 
			$("#session").html(data)
		}	
	)
}

function fget_json(){  // вызов по кнопке тренинг
	//var fam = document.getElementById("fam").value
	//var mfam = fam.split(" "); fam = mfam[0];
	//mfam = fam.split("."); if (mfam.length>1) fam = mfam[1];
	var disc = document.getElementById("hdisc").value
	var md = disc.split(';'); disc=md[0];
	var tema = document.getElementById("ntema").value
	//alert(tema+' '+temap)
	if (tema<1 || tema>1*temap+1){
		$("#tmsg").html("Некорр. номер темы")	
	}else{
		$("#tmsg").html("")
		// строит интерфейс с вызовом  fsingle  fmultiple  fclose
		window.location.href = "get_json.php?prep="+prep+"&disc="+disc+"&tema="+tema+"&gr="+gr+"&fam="+fam
	}
}
</script>
</HEAD>
<BODY onload='sInit()'> 
<input type='button' class='b2' value='Вернуться назад' onclick = 'window.close();window.history.back()'>

<!--input valign='top' type='button' value='Закрыть окно' onclick = 'window.location="index.php"' class='b2'-->
<br>
	<table border = '1' cellspacing = '0' cellpadding='10' style='background-color:#F0F0F0'>
	<tr><td valign='top'>
<?php  // список студентов группы 
	$ns = "GR-".$gr."/GR-".$gr.".txt";
	$np = "GR-".$gr."/control.json"; 
	// файл {"disc":"tiim","min":15,"psw":"1","ball0":5,"ball1":3,"person":"false"}
	//echo "md=$mdisc";
	$bt = is_file($np);
	if ($bt){ // доступ к Тест-контролю
//------------------------------------------------------	Тест-контроль	
		//print " Тест-контроль"; 
		$sd = file("GR-".$gr."/testcontrol.txt");
		$dis = $bt?"":"disabled";
		$trdis = empty($mdisc)?"disabled":"";
		//echo "<div id='studs'>";
		echo "<b>Группа</b> $gr<br>";
		$mst = file($ns);
		$kmax = count($mst);
		print "<SELECT id='nstuds' size='".$kmax."' onchange='fstud()'>";
		$i1=1;
		foreach ($mst as $x){ // список группы
			//$x = $mst[$i];  // фио[;mail]
			$mx = explode(";",$x); 
			$x = trim($mx[0]);  // ф и о	
			$mx1 = explode(" ",$x); 
			$x1 = translit8($mx1[0]);  // famyli
			print "<OPTION value='".$i1.".".$x."'>$i1.$x";
			$i1++;	
		}
		print "</SELECT>";
		//echo "</div>";
?>			
	<td valign='top'>
		<form name="frms"  action="test_init.php" method="post">
			<input  type = 'hidden' name="gr" value="<?php echo $gr;?>"> 
			<input  type = 'hidden' name="nstud" id='nst'> 
			<input  type = 'hidden' name="fam" id='famst'> 
			<input  type = 'hidden' name="login" > 
			Фамилия: <input name="login1" id="fam0" size="30" disabled>
			<div id="psw"></div>
			<hr>
				Дисциплина '<?php echo trim($sd[0]);?>'<br><br>
				<?php if ($person=="true"){
					echo "Пароль: <input name='psw' type='password' id='tparol' size='3'>";					
				}
				?>
				<input onclick='fcontrol()' type="button" value="Тест-контроль" name="trening"  class='b2'>
			<hr>
			<div id='contr'></div> 
		</form>	

	</table>
<?php		
		return; 
//-----------------------------------------------------------------	Тест-тренинг	
	}else if (!empty($mdisc)){ // // доступ к Тест-тренингу 
	//95978	Борисов	borisov 68080	Иванов	ivanov 45298	Петров
			$dis = $bt?"":"disabled";
			$trdis = empty($mdisc)?"disabled":"";
			echo "<div id='studs'>";
			echo "<b>Группа</b> $gr<br>";
			$mst = file($ns);
			$kmax = count($mst);
			print "<SELECT id='nstuds' size='".$kmax."' onchange='fstud()'>";
			$i1=1;
			foreach ($mst as $x){ // список группы
				//$x = $mst[$i];  // фио[;mail]
				$mx = explode(";",$x); 
				$x = trim($mx[0]);  // ф и о	
				$mx1 = explode(" ",$x); 
				$x1 = translit8($mx1[0]);  // famyli
				print "<OPTION value='".$i1.".".$x."'>$i1.$x";
				$i1++;	
			}
			print "</SELECT>";
			echo "</div>";
	}else{
		print " Доступ не открыт."; return; 
	}	
?>
	<td valign='top'>
		<form name="frms"  action="test_init.php" method="post">
			<input  type = 'hidden' name="login" size='2' > 
			<input  type = 'hidden' name="gr" value="<?php echo $gr;?>"> 
			Фамилия: <input name="login1" id="fam0" size="30" disabled>
				<br><div id='controling'></div>
		</form>	
		
		<div id="psw">
		<hr>
			Пароль: <input type="password" id="trparol" size="3">
			<input onclick='ftrening()' type="button" value="Тест-тренинг" name="trening" <?php echo $trdis;?> class='b2'>
		</div>
		
		<form name='frmtren' action='test_XML_tr.php' target='_blank'>
			<input type='hidden' name='gr' value='<?php echo $gr; ?>'>
			<!--  Дисциплина finfo()=информация   action=начать -->
			<hr>
			
			<div id='trening'></div> 
<!-- построено из ftreninig test_get.php 
		echo "<input name='fam' type='hidden' value='".$Lfam."'>";
		echo "<input name='prep' type='hidden' value='".$prep."'>";
		echo "<select id='hdisc' onchange='finfo()' name='trdisc' style='width:300px' size=$kmd>";
		echo "Тема <input id='ntema' style='width:50px' name='tema' min=1 max=$ntema type='number' value=$ntema>";
		echo "<input  type='submit'  value='тренинг' class='b2'>";
		echo "<input  type='submit' name='trnew' value='тренинг+' class='b2'>";
		echo "<input  type='hidden' name='prep' value='".$prep."'>";
		echo "<input  type='submit' name='graf' value='график' class='b2'>";
-->
			<table class='tw' width='100%' cellspacing='0' border='0'>
				<tr><div id='theader'></div>
				<!--th  class='t0'>График сеансов<th class='t0'> История тренинга -->
				<tr><td valign='top' align='center'>
					<span id='history'></span>
				<td  valign='top' align='right'>
					<span id='session'></span>
			</table>	
		</form>	 <!--frmtren-->
	</table>
<!-- input type="submit" value="Изменить пароль" name="chparol" -->

</body></html>