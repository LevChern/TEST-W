<?php   header("Content-Type: text/html; charset=UTF-8");
//http://www.chernyshov.com/TEST3/adm_edit_up.php?fxml=WPr.xml&sattr=%D0%BD%D0%BE%D0%BC%D0%B5%D1%80;ftest(%D0%BD%D0%BE%D0%BC%D0%B5%D1%80);ftestXML(%D0%BD%D0%BE%D0%BC%D0%B5%D1%80);%D0%B2%D0%BE%D0%BF%D1%80%D0%BE%D1%81%D0%BE%D0%B2;%D1%82%D0%B5%D0%BC%D0%B0&stitl=%D0%BD%D0%BE%D0%BC%D0%B5%D1%80;%D1%82%D0%B5%D1%81%D1%82%D1%8B_WPr;%D0%BF%D1%80%D0%BE%D0%B2%D0%B5%D1%80%D0%BA%D0%B0_XML;%D0%B2%D0%BE%D0%BF%D1%80%D0%BE%D1%81%D0%BE%D0%B2;%D1%82%D0%B5%D0%BC%D0%B0&ssize=50;80;80;60;600&pps=%D0%A7%D0%B5%D1%80%D0%BD%D1%8B%D1%88%D0%BE%D0%B2&nitem=%D1%82%D0%B5%D0%BC%D0%B0&scr=40&option=2
//http://localhost/TEST3       /adm_edit_up.php?fxml=WPr.xml&sattr=%D0%BD%D0%BE%D0%BC%D0%B5%D1%80;ftest(%D0%BD%D0%BE%D0%BC%D0%B5%D1%80);ftestXML(%D0%BD%D0%BE%D0%BC%D0%B5%D1%80);%D0%B2%D0%BE%D0%BF%D1%80%D0%BE%D1%81%D0%BE%D0%B2;%D1%82%D0%B5%D0%BC%D0%B0&stitl=%D0%BD%D0%BE%D0%BC%D0%B5%D1%80;%D1%82%D0%B5%D1%81%D1%82%D1%8B_WPr;%D0%BF%D1%80%D0%BE%D0%B2%D0%B5%D1%80%D0%BA%D0%B0_XML;%D0%B2%D0%BE%D0%BF%D1%80%D0%BE%D1%81%D0%BE%D0%B2;%D1%82%D0%B5%D0%BC%D0%B0&ssize=50;80;80;60;600&pps=%D0%A7%D0%B5%D1%80%D0%BD%D1%8B%D1%88%D0%BE%D0%B2&nitem=%D1%82%D0%B5%D0%BC%D0%B0&scr=40&option=2
//$mtd = array("№","Фамилия Имя Отчество","должность","ставка","степень","звание");
//$mww = array(25,300,100,50,70,100);
//$matr = array("фио","должность","ставка","степень","звание"); // на 1 меньше
$option = 1; // режим 1 экпорт-импорт  2 - без
if (!empty($_REQUEST["option"]))
	$option	= $_REQUEST["option"];  // 20 для скроллинга

$short  = $_REQUEST["pps"]; 		//  папка
include "lib1_file.php";
$pps0  = $_REQUEST["pps"]; 		//  папка  Чернышов

$fxml0  = $_REQUEST["fxml"]; 	// файл.xml для редактирования
$fxml = translit8($fxml0);
$pps = "TEST-".translit8($pps0);
$mmf = explode(".",$fxml); $fname=$mmf[0];
//echo "option=$option fxml=$fxml  $fname<br>";
$nitem  = $_REQUEST["nitem"]; //  название элемента строки
$sattr 	= $_REQUEST["sattr"];  // атрибуты
$stitl 	= $_REQUEST["stitl"];  // имена полей
$ssize 	= $_REQUEST["ssize"];  // размеры полей
$ws = 0;
if (!empty($_REQUEST["scr"]))
	$ws 	= $_REQUEST["scr"];  // 20 для скроллинга
$rows 	= $_REQUEST["rows"];  // для textarea
$rows = 0;
if (!empty($_REQUEST["rows"]))
	$rows = $_REQUEST["rows"];

$matr = explode(";",$sattr);
$mtd = explode(";",$stitl);
$mww = explode(";",$ssize);
		
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
	echo "pps='".$pps."'\n";
	echo "rows=$rows\n";
	$wt1 = $wt-$ws; echo "wt1=$wt1";
	echo "\nmww = new Array(".$mww[0];
	$i = 0;
	foreach ($mww as $s){
		if($i>0) echo ",$s";
		$i++;
	}	
	echo ")\n";
	echo "\nmdis = new Array(0".strpos($matr[0],"(");  // атрибуты
	$i = 0;
	foreach ($matr as $s){
		if($i>0) echo ",0".strpos($s,"(");
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
	s1=""
	for(var i=0; i<mww.length;i++){
		var val = document.getElementById("v"+i).value // пробелы на _
		val = val.replace(/ /g,"_") 
		s1+=("&v"+i+"="+val)
	}
	//alert(s+s1)
	$("#add2").load("adm_edit-3.php?"+work+"=1&"+s+s1,
		function(){window.location.reload(false)}
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
		//}else { 
		val=""; dis=""; if (mdis[i]>0) dis='disabled'
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
		//if (i==0){
		//	dis="disabled"
		//}else { 
		dis=""; if (mdis[i]>0) {dis='disabled'; tv=''}
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
function ftest(disc){ // облаботка меню disc='fil-1'
	//alert(disc)
	window.open("test_edit.php?ftest=TEST-"+disc
		+"&pps="+pps
		, "_blank")
}
function ftestXML(disc){ // облаботка меню disc='fil-1'
	//alert(disc)
	window.open("test_XML.php?ftest="+disc
		+"&pps="+pps
		, "_blank")
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
		$mm = explode("_",$s); if (count($mm)>1) $s = $mm[0];
		echo "<td class='col".$i."'>$s</td>";
		$i++;
	}
	echo "</table>";
}

function show($p, $attr, $w, $b, $bv,$bf){ // ячейка таблицы $b - кнопка
	global $fname;
	$y = $p[$attr];
	if (!empty($b)){
		$y = $p[$b]; // значение номер
		$fun = "$bf(\"".$fname."-".$y."\")";
		echo "<td class='col".$w."'><input type='button' class='b1' value='".$bv."-".$y."' onclick='".$fun."'>";
	}else
	   echo "<td class='col".$w."'>".$y;
}

function rows($xpps){ // строки таблицы  
	global $matr, $mtd, $nitem,$kod;
	// +"&sattr=номер;тема;ftest(номер)"   
	//+"&stitl=номер;тема;тесты_"+disc        
	$i=0; $kod="";
	$pp = $nitem;
	foreach ($xpps->$pp as $p) {
		$i++;
		$j=1;
		echo "<tr align='center'><td class='col".$j."'>$i";
		foreach($matr as $s){ // по атрибутам
			$mm = explode("_",$mtd[$j-1]);   //  тесты(номер)
			$bval = "";
			$b = "";
			if (count($mm)>1){
				$bval = $mm[1];
				$ma = explode("(",$s);
				$b = substr($ma[1],0, strlen($ma[1])-1); // название атрибута
				$fun = $ma[0];
			}
			//  $b="номер" $bval = disc  $fun - test
			show($p,$s,$j+1,$b,$bval, $fun ); // показать строку
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
	libxml_use_internal_errors(true);
	$ff =  $pps."/".$fxml;
	//echo "ff=$ff";
	if (!is_dir($pps)){
		mkdir($pps);
	}	
	if (!is_file($ff)){ // создать файл
		$h = fopen($ff,"w");
		fputs($h,'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>');
		fputs($h,"\n<темы>");
		fputs($h,"\n</темы>");
		fclose($h);
	}	
	$xml = simplexml_load_file($ff);
	if (!$xml) {
		echo "<BR>Ошибка загрузки XML\n";
		foreach(libxml_get_errors() as $error) {
			echo "\t", $error->message;
		}
		return;
	}
	$pp = $nitem; // a2u($nitem);
	$ktr = sizeof($xml->$pp); // число записей

echo "<table>";
echo "<input class='b2' type='button' value='Закрыть окно' onclick = 'window.close()'>";
if ($option==1){
?>
<tr><td>
	<form  action="adm_edit-234.php"  method="POST" enctype='multipart/form-data' >
		<a href="<?php echo $ff;?>" download>Скачать XML</a>
		Файл <?php echo $fxml;?>
		<input type=hidden name=MAX_FILE_SIZE value=1000000 />
		<input type='file' name='myfile' size=80/> <!--// выбрать файл --> 
		<input type='hidden' name='nfile' value='<?php echo $fxml; ?>'/> <!--// выбрать файл --> 
		<input type='hidden'  name='type' value='xml'/> <!--// выбрать файл --> 
		<input type='hidden' name='pps' value='<?php echo "inprog-ump/".$pps; ?>'/>
		<input class='b2' type='submit' value='Загрузить XML-file'/>
	</form>
<?php
}else{
	$mf = explode(".",$fxml0);
	echo " <b> Темы по дисциплине $mf[0]</b>";
}
?>
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
		<table id='table1' border class='tw' cellspacing='0' cellpadding='0'>
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
