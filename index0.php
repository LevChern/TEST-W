<?PHP header("Content-Type: text/html; charset=utf-8");?>
<!DOCTYPE html>  
<HTML>  <!--  Система LAB_DB   http://www.mysql.ru/docs/   -->
<HEAD><META charset=UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
</HEAD>
<script>
function fgr(){
	frm_prep.gr.value = frm1.grup.value
	frm_stud.gr.value = frm1.grup.value
	//alert (frm1.grup.value)
}
function getLog(){
	var x1 = window.localStorage.getItem('dz1-grup') 
	var x2 = window.localStorage.getItem('dz1-fio') 
	var x3 = window.localStorage.getItem('dz1-parol') 
	document.frm1.grup.value = x1
	document.frm1.fio.value = x2
	document.frm1.parol.value = x3
	frm_prep.gr.value=x1
	frm_stud.gr.value=x1
	
	var x = window.localStorage.getItem('dz1-vidw') 
	var a = document.getElementById('vidw').options // [2].selected=true
	var k, n = a.length
	for (var i=0; i<n; i++){
		y = a[i].value; 
		//k = y.indexOf('_'); y = y.substring(0,k)
		if (x == y) {
			a[i].selected=true; 
			break
		}
	}
}
function setLog(){
	var x1 = document.frm1.grup.value
	var x2 = document.frm1.fio.value
	var x3 = document.frm1.parol.value
	window.localStorage.setItem('dz1-grup', x1);
	window.localStorage.setItem('dz1-fio', x2);
	window.localStorage.setItem('dz1-parol', x3);
	var x4 = document.frm1.vid.value
	window.localStorage.setItem('dz1-vidw', x4);
}
</script>

</HEAD>
<BODY onload='getLog()'> 
<p class='p0'>Система тестирования ФУ</p>
<table  class='t0' width = '850'>
<tr>
<td class="td0">
	<form  action="lab-registr.php" method="post" name='frm1'>
	<table border=0 cellpadding=5 cellspacing=0 >
	  <tr>
		<td valign='top'>
		<table border = 0 >
			<tr><td>Группа:			<td><input  name="grup" size=10 onchange='fgr()'>
			<tr><td>Фамилия(login):	<td><input  name="fio" size=10>
			<tr><td>Пароль:			<td><input type="password" name="parol" size=10>
		</table>
		</td>
	</table>
	</form>
	<i>Студент вводит группу, фамилию, пароль
	<br>Преподаватель - группу, логин, пароль
	</i>
<td  class="td0" valign='top'>
	<form name='frm_adm' action="index_adm.php" method="post" target="_blank"> 
		<input class='b2' type="submit" value="Админ" name="reg_adm">
	</form>	
	<form name='frm_prep' action="index_prep.php" method="post">
		<input class='b2' type="submit" value="Препод" name="reg_prep">
		<input type="hidden"  name="gr">
	</form>	
	<form name='frm_stud' action="index_stud.php" method="post">
		<input class='b2' type="submit" value="Студент" name="reg_stud">
		<input type="hidden" name="gr">
		<input type="hidden" value="TASK" name="test">
	</form>	

<td  class="td0"  valign='top'>
	Приложение по проведению тестирования:
	<UL>
	<LI>
	<LI>
	<LI>
	<LI>
	<LI>
	</UL>
</table>



</BODY></HTML>