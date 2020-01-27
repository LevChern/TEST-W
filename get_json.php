<?php  header("Content-Type: text/html; charset=UTF-8");
// get_json.pgp   inde_stud   function fget_json(){  // вызов по кнопке тренинг
?>
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<script src="jquery-latest.min.js"></script>
<script>
<?php  //+prep+"&disc="+disc+"&tema="+tema
	$prep  = $_REQUEST["prep"]; 	// 
	$disc  = $_REQUEST["disc"]; 	//  
	$tema  = $_REQUEST["tema"]; 	// 
	$gr    = $_REQUEST["gr"]; 	// 
	$mgr = explode("-",$gr); $gr=$mgr[1];
	$fam   = $_REQUEST["fam"]; 	// 
	include "lib1_file.php";
	$fam = translit8($fam);
	echo "prep='".$prep."'\n"; 
	echo "disc='".$disc."'\n"; 
	echo "tema='".$tema."'\n"; 
	echo "gr='".$gr."'\n"; 
	echo "fam='".$fam."'\n"; 
?>
qy=0;qa=0
time0=0; time1=0
function fn(s){ // замена символов
	return s
	//var s1 = s.replace(/&lt;/g, "<")
	//s = s.replace(/&apos;/g, "'")
	//s = s.replace(/&quot;/g, '"')
	//s = s.replace(/&#39;/g, "'")
	//s = s.replace(/&#92;/g, "\\")
	//s = s.replace(/&#124;/g, "|")
	alert(s+" "+s1)
	return  s1 // s.replace(/&gt;/g, ">")
}
function finit(){
	$.get("testGet.php?prep="+prep+"&disc="+disc+"&tema="+tema,
		function(data){  // json построен из XML!  Интерфейс для тестирования
		//alert(data)
			var st = "<table border cellspacing='0' width='700'  style='background-color:#F0F0F0'>"
			var tst = JSON.parse(data)
			var mtest = tst.test.assessmentItem
			qa = mtest.length
			for(var i=0; i<mtest.length; i++){ // по заданиям
				var i1 = i+1
				st += "<tr><td>Задание "+i1
				var tv = mtest[i].responseDeclaration._cardinality
				var pr = mtest[i].itemBody.choiceInteraction.prompt
				var mans = mtest[i].itemBody.choiceInteraction.simpleChoice
				st += ("<br>"+pr)
				st += ("<br><form name='frm_"+i1+"'>")
				var cr = mtest[i].responseDeclaration.correctResponse.value
				if (tv=='single'){
					ii = -1; 
					for(var j=0; j<mans.length; j++){
						var ct = mans[j]._identifier
						if (ct==cr) ii = j
						var ans = fn(mans[j].__text)
						//alert(ans)
						st += ("<input type='radio' name='s_"+i1+"'>"+ans+"<br>")
					}
					st += ("<input type='hidden' value='"+ii+"' name='res'>");
					st += ("<input type='button' id='b_"+i1+"' value='ответ' onclick='fsingle("+i1+","+ii+")'>");
					st += (" результат:<span id='res_"+i1+"' size='4' disavled></span>");
					//st += "<br>";
				}else{ // multiple
					sv = "-"
					for(var j=0; j<cr.length; j++){
						sv += (cr[j]+"|")	
					}	
					var s=""
					for(var j=0; j<mans.length; j++){
						var ct = mans[j]._identifier
						var k = sv.indexOf(ct)
						s += (k>0?"1":"0")
						var ans = fn(mans[j].__text)
						//alert(ans)
						st += ("<input type='checkbox' name='m_"+i1+"'>"+ans+"<br>")
					}
					s = '"'+s+'"';
					st += ("<input type='button'  id='b_"+i1+"' value='ответ' onclick='fmultiple("+i1+","+s+")'>")
					st += (" <span id='res_"+i1+"' size='4' disavled></span>")
					//st += "<br>";
				}
				st += "</form>";
			}
			st += "</table>";
			$("#test").html(st)

			var date = new Date();
			time0 = 60*60*date.getHours()+60*date.getMinutes()+date.getSeconds();
		}	
	)
} // window.location="index_stud.php"
function fsingle(n,n1){ // кнопка ответ  frm_"+n
	//$("res_"+n).html("n="+n)
	var mv=document.getElementsByName("s_"+n) // [0].value
	var i1=-1
	for(i=0; i<mv.length; i++){
		if (mv[i].checked) {i1=i; break}
	}
	//alert("i1="+i1+" n1="+n1)
	var res = "принят" // (i1==n1)?"да":"нет"
	if (i1==n1)qy++
	$("#res_"+n).html(res)
	$("#b_"+n).attr("disabled",true)	
}
function fmultiple(n,s0){ //  кнопка ответ  frm_"+n   s='11000'
	var mv=document.getElementsByName("m_"+n) // [0].value
	var s=""  // 00101
	for(i=0; i<mv.length; i++){
		//alert(mv[i].checked)
		s += (mv[i].checked)?"1":"0"
	}
	//alert("s="+s0+" s="+s)
	var res = "принят"//(s==s0)?"да":"нет"
	if (s==s0)qy++
	$("#res_"+n).html(res)
	$("#b_"+n).attr("disabled",true)
}
function fclose(){ // s='11000'
	//var qq = document.getElementById("qall").value
	//alert(qyes+" из "+qq)
	var date = new Date();
    time1 = 60*60*date.getHours()+60*date.getMinutes()+date.getSeconds();
	var time = (time1-time0)  
	//alert(time0+" "+time1)  gr  = GR-pi4-1
	//alert("gr="+gr+"&fam="+fam+"&disc="+disc+"&tema="+tema+"&qy="+qy+"&qa="+qa+"&time="+time)
	$.get("testResult.php?gr="+gr+"&fam="+fam+"&disc="+disc+"&tema="+tema+"&qy="+qy+"&qa="+qa+"&time="+time,
		function(data){
			$("#result").html("Правильных ответов "+qy+" из "+qa+". Потрачено секунд "+time)
		}
	)
}
function fend(){ // Завершить
	fclose()
	//alert("??")
	$("#test").html("")
	$("#bend").attr("value","Закрыть окно")
			  .attr("onclick",'window.location="index.php"')
  // 	'window.location="index.php"'	
}

</script>
<body onload='finit()'>
<input id='bend' type='button' value='Завершить' onclick = 'fend()' class='b2'>
<? echo "Дисциплина $disc. Тема $tema"; ?><br>
<span id='result'></span>
<div id="test"></div>
</body>