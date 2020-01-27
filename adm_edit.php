<?php   header("Content-Type: text/html; charset=UTF-8");
// adm_edit.php
// список преподавателей 
//$mtd = array("№","Фамилия Имя Отчество","должность","ставка","степень","звание");
//$mww = array(25,300,100,50,70,100);
//$matr = array("фио","должность","ставка","степень","звание"); // на 1 меньше
$pps  = $_REQUEST["pps"]; 		//  папка
$fxml0  = $_REQUEST["fxml"]; 	// файл  test_os_vv  test_дисциплина_тема
include "lib1_file.php";
$fxml = translit8($fxml0);
$nitem  = $_REQUEST["nitem"]; //  название элемента строки
$sattr 	= $_REQUEST["sattr"];  // атрибуты
$stitl 	= $_REQUEST["stitl"];  // имена полей
$ssize 	= $_REQUEST["ssize"];  // размеры полей
$ws 	= $_REQUEST["scr"];  // 20 для скроллинга
$rows 	= $_REQUEST["rows"];  // fold/fxml
$kdis 	= $_REQUEST["kdis"];  // число элементов с disabled

//$mprep 	= $_REQUEST["mprep"];
$fxml0 = $fxml;
if (empty($_REQUEST["rows"])){
	$rows = 0;  // <input>
} else $rows = $_REQUEST["rows"];
$fxml	= $fxml.".xml";
$matr = explode(";",$sattr);
$mtd = explode(";",$stitl);
$mww = explode(";",$ssize);
//echo "fold=$fold fxml0=$fxml0";
$kod="";
$wn=25;  // ширина колонки номера
$wt=66;
$ktr=0;  // число строк
?>
<script src="jquery-latest.min.js"></script>
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<style type='text/css'>
	td {vertical-align:top}
	table {border-collapse: separate; }/* border-spacing: 7px 11px; }*/
</style>
<style>
	<?php 
	$i=2; 
	echo ".col1{width:".$wn."px;}";
	foreach ($mww as $w){
		echo ".col".$i."{width:".$w."px;}";
		$wt += $w;
		$i++;
	}
	echo "#dd {overflow:auto; height:400px; border:1px solid #777; width:".$wt."px; }";
	?>	
	.tw{background-color:#F0F0F0;}
	.div0{width:1145px; background-color:#F0F0F0;}
    .b0 { border-radius: 8px; background: #F0DADA; width:25px }
</style>
<script>
<?php 
	echo "kdis=$kdis\n";
	echo "rows=$rows\n";
	echo "pps='".$pps."'\n";
	echo "fxml='".$fxml."'\n";
	$wt1 = $wt-$ws; echo "wt1=$wt1";
	echo "\nmww = new Array(".$mww[0];
	$i = 0;
	foreach ($mww as $s){
		if($i>0) echo ",$s";
		$i++;
	}	
	echo ")\n";
?>
function fcancel(par){  // отменить
	$("#addi").html("")
	$("#add2").html("")
}
function fwrite(par){  // записать 
	work = par==0?"add":"change"
	var s = $('form').serialize();
	//alert(s)
	s1=""
	for(var i=0; i<mww.length;i++){
		var val = document.getElementById("v"+i).value // пробелы на _
		val = val.replace(/ /g,"_") 
		s1+=("&v"+i+"="+val)
	}
	$("#add2").load("adm_edit-3.php?"+work+"=1&"+s+s1,
		function(){
			//alert("333")
			//window.location.reload(false)
			window.closer()
		}
	)
}
function fdel(){
	var nn = frm1.num.value // номер записи
	//alert(nn)
	var s = $('form').serialize();
	$("#add2").load("adm_edit-3.php?del=1&"+s,
		function(){window.location.reload(true)}
	)
}	
function fadd(){  // добавить
	var s="<table border class='tw' cellspacing='0' cellpadding='0' style='width:"+wt1+"px'>"
	var k = ktr+1
	var tt = rows==0?"input":"textarea rows="+rows
	s+="<tr><td style='width:25px' ><input id='vn' style='width:24px' disabled value='"+k+"'>";  // номер п/п
	for (var i=0; i<mww.length; i++){
		var xw = mww[i]
		var xw1 = mww[i]-1
		//if (i==0){
		//	val = kod; dis="disabled"
		//}else { val=""; dis=""}
		val=""; dis=""
		if (tt=="input")
			s+="<td style='width:"+xw+"px'><"+tt+" id='v"+i+"' style='width:"+xw1+"px' "+dis+" value='"+val+"'/>"
		else {
			s+="<td style='width:"+xw+"px'><"+tt+" id='v"+i+"' style='width:"+xw1+"px' "+dis+">"+val+"</textarea></td>"
		}
	}
	s+="</table>";
	//alert(s)
	$("#addi").html(s)
	$("#add2").html("<input type='button' value='Записать' onclick='fwrite(0)'>"+
					"<input type='button' value='Отменить' onclick='fcancel(0)'>")
}

function fch(){ // изменить
	var nn = frm1.num.value // номер записи
	if (nn.length==0){alert('не задан номер');return}
	var tr = $("#table1").children().children().eq(nn-1)
	var tt = rows==0?"input":"textarea rows="+rows
	var s="<table border class='tw' cellspacing='0' cellpadding='0' style='width:"+wt1+"px'>"
	s+="<tr><td style='width:25px'><input id='vn' style='width:23px' disabled value='"+nn+"'/>";
	for (var i=0; i<mww.length; i++){
		var tv = tr.children().eq(i+1).html()
		var xw = mww[i]
		var xw1 = mww[i]-1
		if (i<kdis){
			dis="disabled"
		}else { dis=""}
		//alert(i+" "+dis+" tv="+tv)
		if (tt=="input")
			s+="<td style='width:"+xw+"px'><"+tt+" id='v"+i+"' style='width:"+xw1+"px' "+dis+" value='"+tv+"'/>"
		else {
			//tv = tr.children().eq(i+1).html()
			s+="<td style='width:"+xw+"px'><"+tt+" id='v"+i+"' style='width:"+xw1+"px' "+dis+">"+tv+"</textarea></td>"
		}
	}
	s+="</table>";
	//alert(s)
	$("#addi").html(s)
	$("#add2").html("<input type='button' value='Записать' onclick='fwrite(1)'>"+
					"<input type='button' value='Отменить' onclick='fcancel(1)'>")
}
bfio = 1
function fio(){ // экспорт-импорт  $ff =  $pps."/".$fxml;
	var ff = pps+"/"+ fxml
	//alert(bfio)
	if (bfio==1){
	$("#io").html(
		"<form  action='adm_edit-234.php'  method='POST' enctype='multipart/form-data' >"
		+"<a href='"+ff+"' download>Скачать XML</a>"
		+"Файл "+ fxml
		+"<input type=hidden name=MAX_FILE_SIZE value=1000000 />"
		+"<input type='file' name='myfile' size=80/>" 
		+"<input type='hidden' name='nfile' value='"+fxml+"'/>" 
		+"<input type='hidden'  name='type' value='xml'/>"
		+"<input type='hidden' name='pps' value='"+pps+"'/>"
		+"<input class='b2' type='submit' value='Загрузить XML-file'/>"
		+"</form>"
	)
	}else{
		$("#io").html("<form></form>")	
	}
	bfio = 1-bfio
}
</script>
<body>

<?php
function ftitle(){ // построение заголовка 
	global $mtd,$wt,$ws;
	$wt1 = $wt-$ws;
	echo "<table border class='tw' cellspacing='0' cellpadding='0' >";  // style='width:".$wt1."px'
	echo "<tr align='center'><td class='col1'>№";
	$i=2; 
	foreach ($mtd as $s){
		echo "<td class='col".$i."'>$s</td>";
		$i++;
	}
	echo "</table>";
}

function show($p, $attr, $w){ // ячейка таблицы
	$y = $p[$attr];
	echo "<td class='col".$w."'>".$y;
}

function rows($xpps){ // строки таблицы  
	global $matr, $nitem,$kod;
	$i=0; $kod="";
//<преподаватель email="IAleksandrova@fa.ru" каф="пм" fio="aleksandrova_ia" 
//фио="Александрова Ирина Александровна" работа="основное место работы" срок="44377" 
//$xpps = simplexml_load_file($ff);
	$pp = $nitem;
	foreach ($xpps->$pp as $p) {
		$i++;
		$j=1;
		echo "<tr align='center'><td class='col".$j."'>$i";
		foreach($matr as $s){ // по атрибутам
			show($p,$s,$j+1); // показать строку
			$j++;
			$kod = $p["код"];
		}
		if (!empty($mdf))foreach ($mdf as $dd) show ($p,$dd); // дополн. поля
	}	
	return $i;
}		

function atr($n, $p){  // (" название='".$p["название"]."'");  
	$xx = $p[$n];
	if (empty($xx)) return "";
	else return " ".$n."='".$xx."'";
}
//------------------------------------------------------------------
	$ff =  $pps."/".$fxml;
	if (!is_file($ff)){
		$h = fopen($ff,"w");
		fwrite($h,'<?xml version="1.0" encoding="utf-8" standalone="yes"?>');
		fwrite($h,'<данные>');
		fwrite($h,'</данные>');
		fclose($h);
		echo "<input class='b2' type='button' value='Закрыть окно' onclick = 'window.close()'><br>";

		echo("Создан файл $ff"); 
		return; //copy("test0.xml",$pps."/".$fold."/".$fxml);   // "$fold/$fxml"
	}
	$xml = simplexml_load_file($ff);
	if (!$xml) {
		echo "<BR>Ошибка загрузки $ff\n";
		foreach(libxml_get_errors() as $error) {
			echo "\t", $error->message;
		}
		return;
	}
	$pp = $nitem; // a2u($nitem);
	$ktr = sizeof($xml->$pp); // число записей
?>
<table>
<tr><td>
	<table><tr>
	<td><input class='b2' type='button' value='Закрыть окно' onclick = 'window.close()'>
	<td><input class='b1' type='button' value='Э/И' onclick = 'fio()'> 
	<td><span id="io"></span>
	</table>
<!--		
	<form  action="adm_edit-234.php"  method="POST" enctype='multipart/form-data' >
		<a href="<?php echo $ff;?>" download>Скачать XML</a>
		Файл <?php echo $fxml;?>
		<input type=hidden name=MAX_FILE_SIZE value=1000000 />
		<input type='file' name='myfile' size=80/> 
		<input type='hidden' name='nfile' value='<?php echo $fxml; ?>'/> 
		<input type='hidden'  name='type' value='xml'/>
		<input type='hidden' name='pps' value='<?php echo "inprog-ump/".$pps; ?>'/>
		<input class='b2' type='submit' value='Загрузить XML-file'/>
	</form>
-->	
<tr><td>

	<form name='frm1' action='adm_edit-1.php' method="POST" target="_blank">
		№ <input name='num' type='number' style='width:45px' min='1' max='<?php echo $ktr;?>' value='1'>
		<input class='b100' type='button' value='Изменить'  onclick='fch()'/>
<?php 

	echo "<input class='b100' type='button' value='Добавить'   onclick='fadd()'/>";
	echo "<input class='b100' type='button' name='del' value='Удалить' onclick='fdel()'>"; 
?>
		<span id='add2'></span>  <!-- Записать   -->
		<a href='<?php echo $ff;?>'> <?php echo $ffn;?> </a>
		<input type='hidden' name='xml' value='<?php echo $ff; ?>'>
		<input type='hidden' name='attr' value='<?php echo $sattr;?>'>
		<input type='hidden' name='titl' value='<?php echo $stitl;?>'>
		<input type='hidden' name='size' value='<?php echo $ssize;?>'>	
		<input type='hidden' name='pps' value='<?php echo $pps; ?>'>
		<input type='hidden' name='nitem' value='<?php echo $nitem; ?>'>
		<input type='hidden' name='kpub' value='<?php echo $kpub; ?>'>
		<input type='hidden' name='nfile' value='<?php echo $ffn; ?>'>
	</form>
<td>
</table>

<table>
<tr><td valign='top'>
	<?php ftitle();  // заголовок ?>  
	<div id='addi'></div>
	<div id='dd'>
		<table id='table1' border class='tw' cellspacing='0' cellpadding='0' style='background-color:#F0F0F0'>
			<?php $ktr = rows($xml); ?>
		</table>
	</div>
</table>	
<script>
<?php 
	echo "ktr=$ktr\n"; // число записей 
	echo "// $kod\n";
	if (empty($kod)){
		$mf = explode("_",$fold);
		$kod = substr($mf[0],0,1).$mf[1]."-00";
	}
	$mkod = explode("-",$kod); // cln_02  -> cln_03
	$kod1 = ("1".$mkod[1])+1;   // 102 -> 103
	$kod2 = $mkod[0]."-".substr($kod1,1);
	echo "kod='".$kod2."'\n";
?>
</script>	
