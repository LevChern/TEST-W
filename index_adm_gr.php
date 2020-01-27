<?PHP header("Content-Type: text/html; charset=utf-8");?>
<!DOCTYPE html>  
<HTML>  <!-- -->
<HEAD><META charset=UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<script src="jquery-latest.min.js"></script>
<script>
btz = ""
function finit(){
	return
	btz = window.localStorage.getItem('dz1-btz') 
	fbtz.BTZ.value=btz
	ftests()
}
function fsetBTZ(){
	btz = fbtz.BTZ.value // $("#BTZ").attr("value")
	//alert(bz)
	window.localStorage.setItem('dz1-btz', btz);
	ftests()
}
function ftests(){
	//alert("btz="+btz)
	$('#tests').load("test_stat.php",{"btz":btz})
}
function fred(){ // Редактировать
	//alert('ferd')
	window.location = "index_db.php?btz="+btz
	//$.get("index_db.php")
}
function fdemo(){
	var dem = document.getElementById('demo').checked  // true/false
	//alert("dem="+dem)
	//$("#dem").load("setdemo.php",{"demo":dem})
	$.post("setdemo.php",{"demo":dem})
}
</script>
</HEAD>
<body onload='finit()'>
<table>
<tr><td valign='top'>
<input type='button' value='Закрыть окно' onclick = 'window.close();window.location="index.php"' class='b2'>
<br>
<?php
	$mdir = scandir(".");
	$k = 0;
	for ($i=0; $i<count($mdir); $i++){ // Список групп
		if (substr($mdir[$i],0,2)=='GR'){
			$mgr[$k++] = substr($mdir[$i],3);
		}
	}

$matr = array("фио","должность","fio","psw");   // ,"ставка","степень","звание"
$mtd = array("Фамилия Имя Отчество","должность","логин","пароль");  //,"ставка","степень","звание"
$mww = array(300,100,100,100);
$sattr = "";  $mtitle = ""; $msize = "";
for($i=0; $i<count($matr); $i++){
	$sattr .= ($i>0?";":"").$matr[$i]; // атрибуты
	$stitl .= ($i>0?";":"").$mtd[$i];  // заголовки
	$ssize .= ($i>0?";":"").$mww[$i];  // размеры
}
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
<td valign='top'>
<form action="adm_edit_gr.php" method="post" target="_blank">
	<input class='b2' type='submit' value='Группы'/>
	<br><SELECT name='gr1' size='10' onchange='frm1.gr.value=frm1.gr1.value' style='width:150px;'>  
	<?php  // 
	   $kmax = count($mgr);
	   $kmax = 6;
	   for ($k=0; $k<$kmax; $k++){
		 $x = $mgr[$k];  // номер логин 
		 print "<OPTION value=$x> $x";
	   }
	?>
	</SELECT>
</form>	
<td valign='top'>
<form action="adm_edit_gr.php" method="post" target="_blank">
	<input name='gr2' size='10'/>
	<input class='b2' type='submit' value='Добавить'/>
</form>	

<td valign='top'>
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
</table>
