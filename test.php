<?php header("Content-Type: text/html; charset=UTF-8");
	/* преобразование в json
	$fx = "TEST-chernyshov/TEST-Wpr-2.xml";
		include "lib_xsl.php";
		$sj = trans($fx,"test2json.xsl");
	$fj = "res.json";
		$hj = fopen($fj,"w");fputs($hj,$sj); fclose($hj);
		echo "Создан $fj<br>";
	*/	
?>		
<script>
m1 = ['пн','вт','ср']
m2 = [1,2,3,4,5,6,7,8,9,10,11,12]
function finit(){
	s1 = document.getElementById('sel1')
	s1.options.length = 3
	for (i=0;i<m1.length; i++){ 
		s1.options[i].value = m1[i] 
		s1.options[i].text = m1[i] 
	} 
}
function fsel(){
	k = s1.selectedIndex; //alert(k) 
	s2 = document.getElementById('sel2')
	s2.options.length = 4
	for (i=0;i<4; i++){ 
		s2.options[i].value = m2[4*k+i] 
		s2.options[i].text = m2[4*k+i] 
	} 
}

function f1(){alert('1')}
</script>
<body onload='finit()'>
<select id = 'sel1' size='3' onchange='fsel()'><select>
<select id = 'sel2' size='4'> <select>

<input type='button' Onclick='f1()' value='calc'> 

