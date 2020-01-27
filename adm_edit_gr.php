<?php  header("Content-Type: text/html; charset=UTF-8");
// adm_edit-gr.php
// Вызов <form action="adm_edit_gr.php" method="post" target="_blank">
// <SELECT name='gr1'
	function delTree($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
		  (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}
	$gr = empty($_REQUEST["gr1"])?"":$_REQUEST["gr1"]; // редактировать
	$gr2 = empty($_REQUEST["gr2"])?"":$_REQUEST["gr2"]; // добавить 

	if (empty($gr) && empty($gr2) ){ 
		echo "<input type='button' value='Вернуться назад'". 
			" onclick = 'window.close();window.location=\"index_adm_gr.php\"' class='b2'>";
		echo "Группа не задана"; return; 
	}
  
	$do = empty($_REQUEST["do"])?"":$_REQUEST["do"]; // 
	if ($do=="add"){ // добавить группу| изменить
		$p = "GR-".$gr2;
		$bch = false;
		if (is_dir($p)){
			$bch = true;
		}else{	
			mkdir($p);
		}
		$fgr = $p."/".$p.".txt";
		$st = empty($_REQUEST["st"])?"":$_REQUEST["st"]; // 
		$h = fopen($fgr,"w");
		fwrite($h,$st);
		fclose($h);
		echo "Группа $gr2 ".($bch?"изменена":"добавлена");
		return;
	}
	
	if (empty($gr2)){	
		$pgr = "GR-".$gr;
		$fgr = $pgr."/".$pgr.".txt";
		$do = $_REQUEST["do"]; // 
		if($do=="del"){
			delTree($pgr);
			echo "Группа $gr удалена";
			return;
		}	
		$do = "";	
	} else{ // добавить
		$do = "add";	
		$gr = $gr2;
	}		
	//echo "Группа $gr";
	
?>
<!DOCTYPE html>  
<HTML>  <!-- -->
<HEAD><META charset=UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<script src="jquery-latest.min.js"></script>
<script>
<?php
	echo "gr = '".$gr."'\n";
	echo "do1 = '".$do."'\n";
?>
function finit(){
	return
}
function fdel(){ // удалить
	var gr = $("#gr").val()
	$.post("adm_edit_gr.php",{"do":"del","gr1":gr},function(data){
		$("#ta").val("")		//https://snipp.ru/jquery/textarea-jquery
		alert(data)
	})
	//alert(gr)	
}
function fsave(){
	var gr = $("#gr").val()
	var st = $("#ta").val()
	//alert(st)
	$.post("adm_edit_gr.php",{"do":"add","gr2":gr,"st":st},function(data){
		//$("#ta").val("")		
		alert(data)
	})
}

</script>
<body onload='finit()'>
<input type='button' value='Вернуться назад' onclick = 'window.close();window.location="index.php"' class='b2'>
<br> Группа <input value='<?php echo $gr; ?>' disabled size=5 id='gr'>
<?php
	if ($do!="add"){
		echo "<input type='button' value='Удалить' onclick = 'fdel()' class='b2'>";
	}
?>
<input type='button' value='Сохранить' onclick = 'fsave()' class='b2'>
<br><textarea rows=30 cols=60 id="ta">
<?php
	//echo "fgr=!$fgr!";
	if (is_file($fgr)){
		$mgr = file($fgr);
		foreach ($mgr as $s){
			echo trim($s)."\n";
		}	
	}else{
		echo "\n Нет группы ".$fgr;
	}		
?>

</textarea>
