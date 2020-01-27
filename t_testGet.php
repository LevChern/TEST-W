<?php   header("Content-Type: text/html; charset=UTF-8"); 
// testNewphp  test_XML_tr.php ...  include "testNew.php";   кнопка тренинг+
// тест строится их json препода
?>
<script src="jquery-latest.min.js"></script>
<script>
function f1(){
	prep  = 'haritonova'; 	// 
	disc  = 'tiim'; 	// 
	tema  = 1; 	// 
	$("#test").load("testGet.php?prep="+prep+"&disc="+disc+"&tema="+tema)
}	
</script>
<body onload='f1()'>
test=
<div id='test'></div>
