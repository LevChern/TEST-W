<?php header("Content-Type: text/html; charset=UTF-8"); ?>
<!DOCTYPE html>    
<HTML><head>
<META http-equiv=Content-Type content="text/html; charset="UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<style>
   body { background: url("FU/images.jpg"); }
</style>

<script src="jquery-latest.min.js"></script>
<script>  
prog = "lib1_sprav.php" 	// работа со КР 
<?php
// Настройка локали
if (!headers_sent()) {
	header('Content-Type: text/html; charset=UTF-8');
}	
	$prep = $_REQUEST["prep"];
	$mp = explode(" ",$prep); $prep = $mp[0];
	$fam = $_REQUEST["fam"];
	echo "prep = '".$prep."'\n";
?>

function prep_show(){  // 
	$("#grups").html()
	$('#grups').load(prog,{"nfile":prep,"name":"krs","size":hp,"wp":200})  	// список тестов
}
function ftr(x){
	if(x.length==0)return x
	var k = x.indexOf("_")
	if (k>0) return x.substring(0,k)
	else return x
}
$(document).ready( function(){  // вызов нужных функций скрипта (== nagruzka.size)
	$("#grups").click(function(event){	// выбор преподавателя
		var vv = $("#grups option:selected").text();
		frm1.val.value = vv
		ngr = "TEST-"+vv
		hp = 20; 
		$('#studs').load(prog,{"nfile":ngr,"prep":prep,"name":"studs","size":hp,"wpt":100})	// список студентов
	})
	hp = 20; 
	ft = ""		// загрузка КР
	$('#grups').load(prog,{"nfile":prep,"name":"krs","size":hp,"wp":200})  	// список групп/тестов
	$('#studs').load(prog,{"nfile":ft,"name":"studs","size":hp,"wpt":100})	// список студентов/
});

function fdelete(){ // удалить KR
	var gr = frm1.val.value	// КР 
	if (gr!=""){
		$.get(prog+"?dfile="+gr+"&prep="+prep+"&pref=KR");	// удалить папку 
		alert("КР "+gr+" удалена!")
		prep_show()
	}	
}
</script>
</head>
<body>
<form><input type='button' value='Закрыть окно' onclick = 'window.close()' class='b2'></form>
<form name='frm1' action="lib1_sprav.php" method='POST'>
<table border cellspacing=0 style='background-color:#F0F0F0'>
	<tr><td colspan=3>Тесты <?php echo $fam; ?>
		<input type='hidden'  value='<?php echo $prep; ?>' name='prep'>
		<input type='button'  value='удалить' onclick='fdelete()'  class='b2' >
		<input type='submit'  value='записать/обновить тест' name='wtask' class='b3' >  <!-- -->
		<tr><td>
		<table><tr>
			<td><div id="grups"></div>
			<td><div id="studs"></div>
			<tr><td  colspan=2><div id="msg"></div>

		</table>	
</table>
</form>	
</body>