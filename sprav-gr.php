<?php header("Content-Type: text/html; charset=UTF-8"); ?>
<!DOCTYPE html>    
<HTML><head>
<META http-equiv=Content-Type content="text/html; charset="UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<script src="jquery-latest.min.js"></script>
<script>  
prog = "lib1_sprav.php" 	// работа со справочником групп 

function prep_show(){  // 
	$("#grups").html()
	$('#grups').load(prog,{"nfile":ft,"name":"grups","size":hp,"wp":200})
}
function ftr(x){
	if(x.length==0)return x
	var k = x.indexOf("_")
	if (k>0) return x.substring(0,k)
	else return x
}

$(document).ready( function(){  // вызов нужных функций скрипта (== nagruzka.size)
	$("#grups").click(function(event){	// выбор преподавателя
		fio =  frm1.grups.value; // ФИО
		var vv = $("#grups option:selected").text();
		frm1.val.value = vv
		ngr = "GR-"+vv
		$('#studs').load(prog,{"nfile":ngr,"name":"studs","size":hp,"wpt":50})	// список студентов
	})
	hp = 20; 
	ft = ""		// загрузка групп 
	$('#grups').load(prog,{"nfile":ft,"name":"grups","size":hp,"wp":100})  	// список групп
	$('#studs').load(prog,{"nfile":ft,"name":"studs","size":hp,"wpt":50})	// список студентов
});

function fwrite(){ // добавить запись 
	var gr = frm1.val.value	// группа
	if (gr!=""){
		//$.get(prog+"?rfio="+gr+"&pref=GR-");	// создать папку 
		$('#msg').load(prog+"?rfio="+gr+"&pref=GR-");
		alert("Группа "+gr+" добавлена")
		prep_show()
	}else{
		alert("не все поля заполнены")
	}	
}

function fwrite1(){ // добавить/обновить список группы
	var gr = frm1.val.value	// группа
	if (gr!=""){
		$.get(prog+"?name=newstuds&rfio="+gr+"&pref=GR-");	// 
		alert("Список "+gr+" обновлен")
		list_show()
	}else{
		alert("не все поля заполнены")
	}	
}

function fdelete(fio){ // удалить запись
	var gr = frm1.val.value	// группа
	if (gr!=""){
		$.get(prog+"?dfio="+gr+"&pref=GR-",   // удалить папку
			function(data){
				alert("Группа "+gr+" удалена "+data)
				prep_show()
			}
		)	
	}	
}
</script>
</head>
<body>
<form><input type='button' value='Закрыть окно' onclick = 'window.close()'  class='b2'></form>
<!-- a href="javascript:self.close()">закрыть окно</a --> 
<form name='frm1' action="lib1_sprav.php">
<table border cellspacing=0 style='background-color:#F0F0F0'>
	<tr><td colspan=3>Группы
		<input type='button'  value='добавить' onclick='fwrite()'   class='b2'>
		<input type='button'  value='удалить' onclick='fdelete()'  class='b2'>
		<input type='submit'  value='записать/обновить список' name='wlist' class='b3' >
		<tr><td>
		<table><tr>
			<td><div id="grups"></div>
			<td><div id="studs"></div>
			<tr><td  colspan=2><div id="msg"></div>
		</table>	
</table>
</form>	
</body>