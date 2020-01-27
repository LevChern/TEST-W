<!DOCTYPE html>  
<HTML>
<HEAD><META charset=UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<style>
	.user {font-size:120%}	
</style>
<script src="jquery-latest.min.js"></script>
<script>
pre = "test3"
bg=0   
fam=""; Lfam=""; grup=""
function fstud(){
	$("#psw").html("")
	frm1.gr.value=frm1.gr1.value
	window.localStorage.setItem(pre+'-gr', document.frm1.gr.value);
}
function getLog(){
	bg=0   
	var user = window.localStorage.getItem(pre+'-user') 
	$('input[name="user"]').eq(user).prop('checked',true)
	document.frm1.gr.value = window.localStorage.getItem(pre+'-gr') 
	grup = document.frm1.gr.value
	//document.frm1.fio.value = window.localStorage.getItem(pre+'-fio') 
	document.frm1.login.value = window.localStorage.getItem(pre+'-login') 
	document.frm1.parol.value = window.localStorage.getItem(pre+'-parol') 
	//alert(user)
	ident(user)
}
function fref(s){
	//http://www.chernyshov.com/TEST3/  
	// user=prep&gr1=pi4-2&login=cln&parol=11&fam=Чернышов&Lfam=chernyshov
	//alert(s+"&fam="+fam+"&Lfam="+Lfam)
	//document.location.href = "index_prep.php?"+s+"&fam="+fam+"&Lfam="+Lfam;
	var log = document.frm1.login.value
	document.location.href = "index_prep.php?"+s+"&fam="+log+"&Lfam="+Lfam;
}
function setLog(){ // войти
	var s = $('form').serialize();
	//alert('bg='+bg+" "+s+" "+fam+" "+Lfam)
	if (bg==1){ // логин прошел

		fref(s)  //  

		return false
	}
	//alert(s)  // user=prep&login=cln&parol=11
	$.get('index_login.php?'+s, function(data){
		//alert(data)
		if (data.substr(0,3)=="yes"){
			var user=0;
			for (var i=0;i<3;i++){
				if ($('input[name="user"]').eq(i).prop('checked')){
					user = i; break
				}
			}
			//alert(user)
			window.localStorage.setItem(pre+'-user', user);
			window.localStorage.setItem(pre+'-gr', document.frm1.gr.value);
			//window.localStorage.setItem(pre+'-fio', document.frm1.fio.value);
			window.localStorage.setItem(pre+'-login', document.frm1.login.value);
			window.localStorage.setItem(pre+'-parol', document.frm1.parol.value);
			if (user==0){ 
				window.location.href="index_adm.php"
				//$.post('index_adm.php')
			}else if(user==1){ // преподаватель		
				ff = data.substr(3);
				//alert(ff)  Фамилия;familia;sgrup
				var mff = ff.split(";");
				fam = mff[0]; Lfam=mff[1]
				if (mff[2].length>0){
					//alert(grup)
					//alert(ff)
					sgr="<SELECT name='gr1' size='9' onchange='fstud()' style='width:150px;font-size:100%'>"  
					mgr = mff[2].split("|")		
					for(var j=0;j<mgr.length;j++){
						sgr += ("<OPTION value='"+mgr[j]+"'>"+mgr[j])
					}  
					sgr += "</SELECT>"
					$("#dgrup").html(sgr)
					bg=1
				}else{ // группы препода не заданы
					frm1.gr1.disabled=0 //  открыть выбор группы
					bg=1
					//window.location.href="index_prep.php?"+s+"&fam="+mff[0]+"&Lfam="+mff[1]
				}
				var kg = document.frm1.gr1.options.length
				if (grup.length>0){ // 
					//alert(kg)
					for (var i=0;i<kg;i++){
						if 	(document.frm1.gr1.options[i].value==grup){
							document.frm1.gr1.options[i].selected = true	
							break
						}					
					}
				}else{
					if (kg==1){
						document.frm1.gr1.options[0].selected = true	
						document.frm1.gr.value = document.frm1.gr1.options[0].value
					}
				}
				
				
				//$.post('index_prep.php')
				$("#psw").html("<font color='red'>"+fam+"</font>")
				$("#entry").attr("value","Группа")
			}else if(user==2){ // студент			
				window.location.href="index_stud.php?"+s
				//$.post('index_stud.php')
			
			}	
		}else{
			$("#psw").html("<font color='red'>"+data+"</font>")
		}
	})
}
function ident(tuser){
	bg=0   
	$("#psw").html("")  // frm1.fio.disabled=1;
	if (tuser==0){ // админ
		frm1.gr.disabled=1;frm1.login.disabled=1
		frm1.gr1.disabled=1;
	}else if (tuser==1){ // препод
		frm1.gr.disabled=1;frm1.login.disabled=0
		frm1.gr1.disabled=1
	}else if (tuser==2){
		frm1.gr.disabled=0;frm1.login.disabled=1
		frm1.gr1.disabled=0
	} 
}	

var ncur=1;
mfu= new Array(); mnum= new Array();
<?php 
if (is_dir("FU")){
	$mdir = scandir("./FU");
	$k = 0;
	foreach ($mdir as $img){ // Список изображений
		$ext = substr($img,strlen($img)-4,4);
		$pre = substr($img,0,2);
		$num = substr($img,3,2);
		if ($pre=='fu' && $ext =='.jpg' || $pre=='gu' && $ext =='.gif' ) {
			echo "mfu[".$k."] = new Image()\n";
			echo "mnum[".$k."] = ".$num."\n";
			echo "mfu[".$k."].src = 'FU/".$img."'\n";
			$k++;
		}
	}
}	
//mfu[0]=new Image();   // картинка 1
//mfu[0].src="DOCS/fu_01.jpg"
?>
 
function frw(){
	if (ncur < mfu.length) {
		ncur++;
		document.images['r'].src=mfu[ncur-1].src;
		$("#num").html(mnum[ncur-1])
	}
}
function bck(){
	if (ncur > 1) {
		ncur--;
		document.images['r'].src=mfu[ncur-1].src;
		$("#num").html(mnum[ncur-1])
	}
}

</script>
</HEAD>
<BODY onload='getLog()'> 
<?php
	$md = getdate();
	$mdir = scandir(".");
	$k = 0;
	for ($i=0; $i<count($mdir); $i++){ // Список групп
		if (substr($mdir[$i],0,2)=='GR'){
			$mgr[$k++] = substr($mdir[$i],3);
		}
	}
	$kmax = count($mgr);
	//echo  $kmax;
	echo "<p class='p0' style='font-size:120%'>Система онлайн-тестирования TEST-W"; 
	if (is_dir("FU")){
		echo "<font color='red'>ФУ</font></p>";
	}
?>

<form name="frm1"  action="index_login.php" method="post">  <!---->
<table class="t0">
<tr>
<td class="td0" rowspan='2'>
<?php
if (is_dir("FU")){
?>	
<!-- листание 
<table cellspacing="4" cellpadding="0" border="0">
 <tr><td align='center'> <img src="FU/fu_11.jpg" name="r"  height='250'> </td>
 <tr><td align='center'> <b>Я <font color='red'>Ф</font>илин-<font color='red'>У</font>мник (<span id='num'>11</span>). А ты кто?</b>
<tr><td>
   <input type="button" value="Назад" onClick="bck()" class='b1'>  
   <input type="checkbox" name='like'>А ты мне нравишься  
   
   <input type="button" value="Вперед" onClick="frw()" class='b1'>
  </td>
 </tr>
</table>
-->
<?php	
}
?>
<td class="td0" colspan='3'>
	<input type="radio" name="user" value="admin" onclick='ident(0)'><span class='user'>Администратор</span>
	<input type="radio" name="user" value="prep" onclick='ident(1)'><span class='user'>Преподаватель</span>
	<input type="radio" name="user" value="stud" checked="checked" onclick='ident(2)'><span class='user'>Студент</span>

	<tr><td class="td0" >
	<b>Группы:</b>
	<br>
	<div id="dgrup">
	<SELECT name='gr1' size='9' onchange='fstud()' style='width:150px;font-size:100%'>  
	<?php  // 
		for ($k=0; $k<$kmax; $k++){
			$x = $mgr[$k];  // 
			print "<OPTION value=$x> $x";
		}
	?>
	</SELECT>
	</div>
<td class="td0">
	 <table cellpadding = '5' >
		<tr><td>Логин:<td><input name="login" size="10">
		<tr><td>Пароль:<td><input type="password" name="parol" size=10>
		<tr><td>Группа:<td><input name="gr" size="10">
		<tr><td><input id='entry' class='b1' type='button' onclick='setLog()' value="Войти" name="registr">
			<td valign='top'>
			<div id="psw"></div>
	 </table>  <!--onclick='ident(frm1.user.value)'-->
	 <hr>
	<a href='DOCS/man-adm.php'>Руководство администратора</a>
	<br><a href='DOCS/man-pps.php'>Руководство преподавателя</a>
	<br><a href='DOCS/man-stud.php'>Руководство студента</a>
<td  class='td0' align='top'>
	<!--b>Возможности системы:</b-->
	<UL>
<!--	
	<LI>доступ с мобильных устройств
	<UL><LI><a href='DOCS/man_ios.pdf'>пользователь iOS</a>
	<LI><a href='DOCS/man_and.pdf'>пользователь Android</a>
	<LI><a href='DOCS/man_wos.pdf'>пользователь Windows Phone</a>
	</UL>
-->	
	<LI>вопросы с вариантами ответов
	<LI>гибкая настройка критериев оценок
	<LI>генерация вопросов по справочникам
	<LI>просмотр и анализ результатов
	<LI>контроль доступа и времени
	<LI>статистика результатов
	<LI>режим тренинга по расписанию
	<LI>проведение аттестаций
	</UL>
</table>
</form>

</BODY></HTML>