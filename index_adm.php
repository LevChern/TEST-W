<?php header("Content-Type: text/html; charset=UTF-8");
// обновление только uplan
?>
<HEAD>
<script src="jquery-latest.min.js"></script>
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<style>
   .but {background: #F0DADA }
</style>
<script>
var th0 = {}
var tr0 = {}
var data0 = {"3_3":"rrr"}
folder="Data1"
tabl0=""
tabl = "data1";t0=""
temp = ""; pre="t2"
<?php
	$w0 = 50; // ширина 1-й коолонки
	$fold="Data1";
	if (is_dir($fold)){
		$dh = opendir($fold);
		$s = "\"<select id='seld' onchange='ft()' style='width:".$w0."px'>";
		while ($file = readdir($dh)){  // кроме *-th.json  *-tr.json 
			$mm = explode('.',$file);
			if (strpos($file,"-th.json")>0) continue; 
			if (strpos($file,"-tr.json")>0) continue; 
			if (sizeof($mm)>1 && $mm[1]=="json"){
				$s .= "<option value='".$mm[0]."'>$mm[0]";	
				$tabl=$mm[0];
			}
		} 
		$s .= "</select>\"\n";
		closedir($dh);
	}else{
		$s = "'Данные\n'";
	}
	//echo "s=$s\n";
	echo "w0=$w0\n";
	echo "tabl='".$tabl."'\n";
?>

t1="</table>"
function felem(obj, i, k){ // заголовок столбца или строки
	var elem = obj.s
	if (obj.b) elem = "<input class='but' type='button' onclick='ft(\""+i+"$"+k+"\")' value='"+elem+"'"+
			" style='width:"+obj.w+"px'>"
	return elem
}

var	h=""
var mh=[]; var kh=0;

function ftG(sub,ind,nL, k1){ // раскрыть заголовки 1-го + 2-го+... по горизонтали
	//						ind 0  0_0  0_0_0 ... 
	var mL = ind.split('_'); var hh;
	var br="<br>"; for (var n=1;n<nL;n++)br+="<br>"
	var j1 = mL[nL]
	var ind1=mL[0]; for(var jj=1;jj<nL;jj++) ind1+=("_"+mL[jj])
	var indh = (mL[0]=="0")?"1":(1*mL[0]+1) 
	for(var jj=1;jj<nL;jj++){hh = 1*mL[jj]+1; indh+=("_"+hh)}	
	for (var j=0; j<sub.length; j++){
		var elem = felem1(sub[j], ind1,j, "",k1) //sub[j].s+" "+sub[j].n 
		jh=1*j+1;mh[kh++] = indh+"_"+jh
		h += ("<th valign='top' style='width:"+sub[j].w+"px'>"+br+elem+"</th>")
		var sn = sub.n   // 1_1_k,   k=j+1
		if (sub[j].b && j==j1 && nL<mL.length){
			//alert('subj='+sub[j].b+' '+(j==j1))
			ftG(sub[j].sub,ind, nL+1, k1)
		}
	}
}
function fname(sr, sh){ // имя  
	var res = sr+"$"+sh
	return res
}
function fval(sr, sh){ // значение ячейки si-строка, sk-колонка
	var res = fname(sr,sh)
	var res1 = data0[res]  // тема(fкратк)  "тема(ОБ,Чернышов Л.Н.)"
	if (typeof res1 == "undefined"){
		res1 = ""//"<font size=-1>"+res+"</font>"	
	}else{
		//alert(res1)
	}
	var ms = res1.split('(')
	if (ms.length>1){
		var disc = ms[1].substr(0,ms[1].length-1)  // ОБ,Чернышов Л.Н.
		var ms1 = disc.split(',')  // 
		disc = ms1[0]
		var ms2 = ms1[1].split(' ')  //
		var fam = ms2[0]	
		res1 = "<input type='button' class='b1' value='"+disc+"' onclick='ftema(\""+disc+"\",\""+fam+"\")'>"
	}
	return res1
}

var mk1 =[];  var kh1=0; // раскрытые названия строк
var mi1=[];	  var kr1=0	 // -- колонок
var k1t = "-1"

function ft(i1_k1){ // раскрыть горизонтальный пункт i1, вертикальный k1 
	var ind="-1";	var indk="-1"
	var i1=-1; var k1=-1	 // только 0-й уровень
	k1t = "-1"
	kh1=0; kr1=0
	if (i1_k1){  // -1$k  
		mik = i1_k1.split("$"); 
		ind = mik[0];  indk = mik[1]; 
		k1t = indk
		mi1 = mik[0].split("_")
		kr1 = mi1.length
		i1 =  mi1[0]; 
		mk1 =  mik[1].split("_"); 
		k1 = mk1[0]
		kh1 =  mk1.length; 
		if(k1==-1)kh1=0
	}
	//alert("i1_k1 "+i1_k1+" k1="+k1+" kh1="+kh1)	//i1 - заголовок колонки, k1 - строки (c 0)
	h=""
	kh=0; 
	for(var kk=0; kk<kh1; kk++)h += "<th>" 
	for (var i=0; i<th0.length; i++){ // заголовок 0-го уровня
		var elem = felem(th0[i],i,k1t)
		mh[kh++] = 1*i+1
		h += ("<th valign='top' style='width:"+th0[i].w+"px'>"+elem+"</th>")
		if (i==i1){ // раскрыть колонки ind = 0 1
			ftG(th0[i].sub,ind, 1, k1t) // рекурсивно  сформирован mh
		}
	}
	
	var jmax = tr0.length
	for (var j=0; j<jmax; j++){ // строки 0-го уровня
		var elem = felem(tr0[j],ind,j)
		//alert('j='+j+' ind='+ind+' k1='+k1+ ' elem='+elem)
		j1 = j+1
		mi1[kr1++] = j1
		h += "<tr>"
		h += ("<td align='center' style='width:"+w0+"px'>"+elem+"</td>")
		for(var kk=0; kk<kh1-1; kk++)h += "<td>"
		if (k1>=0) h += "<td>" 
		for (var ih=0;ih<kh;ih++){
			var elem = fval(tr0[j].n, mh[ih])
			var name = fname(tr0[j].n,mh[ih])
			h += ("<td><div id='"+name+"'>"+elem+ "</div></td>")
		} 
		if (j==k1){ // дополнительные строки  0, 1
			ftG2(tr0[j].sub,ind, 1, indk)
			// рекурсивно раскрыть ячейки 1-го + 2-го+... по горизонтали
		}
	}
	$("#rep").html(t0+h+t1)
}

function felem1(obj, i0,i, k0,k){ // заголовок столбца или строки
	var elem = obj.s;
	var ii=i, kk=k
	if (obj.b) {
		if (i0.length>0) ii = i0+"_"+i; 
		if (k0.length>0) kk = k0+"_"+k; 
		var par = ii+"$"+kk
		elem = "<input class='but' type='button' onclick='ft(\""+par+"\")' value='"+elem+"'"+
			" style='width:"+obj.w+"px'>"
	}		
	return elem
}
function ftG2(sub,ind, nL, indk){ // раскрыть ячейки 1-го + 2-го+... по горизонтали
	//var mL = indk.split('_')		  // ind - заголовки колонок, indk -строки	
	var k1 = mk1[nL]
	var indkk=mk1[0];  var td="<td>"
	for (var k=0; k<nL-1; k++){
		indkk += ("_"+mk1[k])
		td += "<td>"
	} 
	for (var k=0; k<sub.length; k++){
		var elem = felem1(sub[k],"",ind, indkk,k)   // заголовок или кнопка
		h += "<tr>" + td 
		h += ("<td align='center' style='width:"+w0+"px'>"+elem+"</td>")
		for(var kk=0; kk<kh1-nL; kk++)h += "<td>"
		for (var ih=0;ih<kh;ih++){ // по колонкам
			elem = fval(sub[k].n, mh[ih])
			var name = fname(sub[k].n+"$"+mh[ih])
			h += ("<td><div id='"+name+"'>"+elem+ "</div></td>")
		}
		if (sub[k].b && k==k1 && nL<mk1.length){ // строки следующих уровней]
			//alert("ftG2")
			ftG2(sub[k].sub,ind, nL+1, indk)
		}	
	}
}
function setSel(val,sel){ // установить позицию в select
	var opt = document.getElementById(sel).options
	var b=false; var v=""
	if (opt.length==0)return v
	if (val.length==0){ //??
		opt[0].selected=true; v=opt[0].value
		return v
	}
	for (var i=0;i<opt.length;i++){
		var o=opt[i];
		if(o.value==val) {
			o.selected=true; b=true; v=val; break
        }
    }
	if (!b) {opt[0].selected=true; v=opt[0].value}
	return v
}
function getLog(){
	tabl = window.localStorage.getItem(pre+'-tabl') 
	//alert("tabl="+tabl) //
	tabl = ""+tabl
	if (tabl=="null") tabl="uplan"
	tabl = setSel(tabl,'seld')
}
function setLog(){
	window.localStorage.setItem(pre+'-tabl', tabl);
}
var step=0

function finit(){ //alert(folder+"/"+tabl)
	getLog()
	step++
	if (tabl.length==0) return
	$.getJSON(folder+"/"+tabl+".json",function(data,ss){
		th0 = data.th
		tr0 = data.tr
		data0 = data.dataV
		head = data.head
		var head0=""
		if (typeof head != "undefined"){
			head0=head+"<br>"
		}	
		t0=head0+"<table  border='1' cellspacing='0' cellpadding='5' style='background-color:#F0F0F0;'>"+
	"<tr><th valign='top'><input id='data' type='button' onclick='ft()' value='"+tabl+"'"+
		" style='width:"+w0+"px'>"
		ft()
	})
}
function fseldata(){ // выбор набора
	tabl = document.getElementById('seld').value
	setLog()
	$("#out").html('')
	finit()
}	
function ftema(disc,fam){ // выбор набора
	//alert(disc+" "+fam)
	window.open("adm_edit_up.php?fxml="+disc+".xml"
		+"&sattr=номер;ftest(номер);ftestXML(номер);вопросов;тема"
		+"&stitl=номер;тесты_"+disc+";проверка_XML;вопросов;тема"
		+"&ssize=50;80;80;60;600"
		+"&pps="+fam
		+"&nitem=тема"
		+"&scr=40"
		+"&option=2"
	, "_blank")
}

</script>
<body onload='finit()'>

<table>
	<tr><td valign='top'>
		<input valign='top' type='button' value='Закрыть окно' onclick = 'window.location="index.php"' class='b2'>

	<td valign='top'>
	<?php
		$matr1 = array("кратк","название","преподаватель","лекц","сем","тем");   //
		$mtd1 = array("кратк","название","преподаватель","лекц","сем","тем");  //,
		$mww1 = array(100,400,200,50,50,50);
		$sattr1 = "";  $mtitle1 = ""; $msize1 = "";
		for($i=0; $i<count($matr1); $i++){
			$sattr1 .= ($i>0?";":"").$matr1[$i]; // атрибуты
			$stitl1 .= ($i>0?";":"").$mtd1[$i];  // заголовки
			$ssize1 .= ($i>0?";":"").$mww1[$i];  // размеры
		}
	?>
	<form action="adm_edit.php" method="post" target="_blank">
		<input class='b2' type='submit' value='Дисциплины'/>
		<input type='hidden' name='sattr' value='<?php echo $sattr1;?>'>
		<input type='hidden' name='stitl' value='<?php echo $stitl1;?>'>
		<input type='hidden' name='ssize' value='<?php echo $ssize1;?>'>
		<input type='hidden' name='scr' value='20'>	
		<input type='hidden' name='rows' value='0'>	  <!-- textarea для ввода-->
		<input type='hidden' name='pps' value='ADM'>	
		<input type='hidden' name='fxml' value='_disc'>	
		<input type='hidden' name='nitem' value='дисциплина'>	
		<input type='hidden' name='kdis' value='0'>	
	</form>


	<td valign='top'>
	<?php
		$matr = array("фио","должность","fio","psw");   // ,"ставка","степень","звание"
		$mtd = array("Фамилия Имя Отчество","должность","логин","пароль");  //,"ставка","степень","звание"
		$mww = array(300,100,100,100);
		$sattr = "";  $mtitle = ""; $msize = "";
		for($i=0; $i<count($matr); $i++){
			$sattr .= ($i>0?";":"").$matr[$i]; // атрибуты
			$stitl .= ($i>0?";":"").$mtd[$i];  // заголовки
			$ssize .= ($i>0?";":"").$mww[$i];  // размеры
		}
	?>
	<form action="adm_edit.php" method="post" target="_blank">
		<input class='b2' type='submit' value='Преподаватели'/>
		<input type='hidden' name='sattr' value='<?php echo $sattr;?>'>
		<input type='hidden' name='stitl' value='<?php echo $stitl;?>'>
		<input type='hidden' name='ssize' value='<?php echo $ssize;?>'>
		<input type='hidden' name='scr' value='20'>	
		<input type='hidden' name='rows' value='0'>	  <!-- textarea для ввода-->
		<input type='hidden' name='pps' value='ADM'>	
		<input type='hidden' name='fxml' value='_pps_kaf_all'>	
		<input type='hidden' name='nitem' value='преподаватель'>	
		<input type='hidden' name='kdis' value='0'>	
	</form>
	<td valign='top'>
	<form action="adm_prep.php" method="post" target="_blank">
		<input class='b2' type='submit' value='Обновить ППС'/>
		<input type='hidden' name='fx' value='ADM/_pps_kaf_all'/>
		<input type='hidden' name='fs' value='ADM/preps'/>
		<input type='hidden' name='fout' value='kr-pps.txt'/>
	</form>
	<td valign='top'>
	<form action="index_adm_gr.php" method="post" target="_blank">
		<input class='b2' type='submit' value='Группы-ППС'/>
	</form>
</table>
<?php
	$ffd = "ADM/_disc.xml";
	//echo $ffd;
//	function show($p, $attr, $w){ // ячейка таблицы
//		$y = $p[$attr];
//		echo "<td class='col".$w."'>".$y;
//	}
	if (is_file($ffd)){
//	if (false){
		echo "учебный план: <br>";
		$xml = simplexml_load_file($ffd);
		if ($xml) {
			echo "<table border class='tw' cellspacing='0' cellpadding='5' style='background-color:#F0F0F0'>";
			echo "<tr><th>кратк<th>название<th>преподаватель<th>лекц<th>сем<th>тем";
			foreach($xml->дисциплина as $s){ // по атрибутам
				$mf = explode(" ",$s["преподаватель"]);
				echo "<tr><td><input type='button' class='b1' value='".$s["кратк"]."'";
				echo "onclick=ftema(\"".$s["кратк"]."\",\"".$mf[0]."\")>";
				echo "<td>".$s["название"];
				echo "<td>".$s["преподаватель"];
				echo "<td>".$s["лекц"];
				echo "<td>".$s["сем"];
				echo "<td>".$s["тем"];
			}	
			echo "</table>";	
		}else{
			foreach(libxml_get_errors() as $error) {
				echo "\t", $error->message;
			}
		}
		return;	
	}
?>
<table><tr><td valign='top'>
<form action="adm_edit.php" method="post" target="_blank">
	<input class='b2' type='submit' value='Изменить'/>
	<input type='hidden' name='sattr' value='код;кафедра;краткое;название;преподаватель;лектор;сем1;лекции1;семинары1;СРС1;сем2;лекции2;семинары2;СРС2'>
	<input type='hidden' name='stitl' value='код;кафедра;темы;название;преподаватель;лектор;сем 1;лек.;сем.;СРС;сем 2;лек.;сем.;СРС'>
	<input type='hidden' name='ssize' value='75;100;60;350;100;100;50;40;40;40;50;40;40;40'>
	<input type='hidden' name='scr' value='20'>	
	<input type='hidden' name='pps' value='Data1'>	
	<input type='hidden' name='fxml' value='uplan2'>	
	<input type='hidden' name='nitem' value='дисциплина'>	
</form>
<td  valign='top'>
<?php
	$fold="Data1";
	$w0 = 100;
?>
	учебный план:
<?php
	$fold="Data1";
	$w0 = 100;
	if (is_dir($fold)){
		$dh = opendir($fold);
		$s = "<select id='seld' onchange='fseldata()' style='width:".$w0."px'>";
		while ($file = readdir($dh)){
			$mm = explode('.',$file);
			if (sizeof($mm)>1 && $mm[1]=="json")
				$s .= "<option value='".$mm[0]."'>$mm[0]";	
		} 
		$s .= "</select>";
		closedir($dh);
	}else{
		$s = "Данных в $fold нет";
	}
	echo $s; 
?>
	<td valign='top'>
	<form action="adm_prep.php" method="post" target="_blank">
		<input class='b2' type='submit' value='Обновить'/>
		<input type='hidden' name='fx' value='Data1/uplan2'/>
		<input type='hidden' name='fs' value='Data1/uptest_json2'/>
		<input type='hidden' name='fout' value='Data1/uplan2.json'/>
	</form>

</table>

<div id="out"></div>
<div id="rep"></div>
</BODY></HTML>

