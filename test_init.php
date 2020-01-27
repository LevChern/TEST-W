<?php header("Content-Type: text/html; charset=UTF-8");
// 25.11			$var = str_replace("'",'"',$rr->__text);
//27.11		$var = str_replace("_quot;",'"',$var);

// test_init.php вызов из index_stud кнопка "тест-контроль"
	$gr 	= $_REQUEST['gr'];    // группа
	$nstud 	= $_REQUEST['nstud']; // 10 ...   - номер студент
	$fam 	= $_REQUEST['fam']; //   Иванов ...   - студент
	$bt 	= !empty($_REQUEST['bt']);
	$nf1 = "GR-$gr/test-res$nstud.txt";
	if ($bt){ // прямой вызов
		$bt = is_file($nf1); //echo "?Результат уже получен $nf1";
		
	}
	//$nfres1 = "GR-$gr/test-res$nstud.txt";    // ответ
	//echo $nfres1; 
	//if (is_file($nfres1)){
	//	echo "Результат уже есть"; return;
	//}

	//$mf =  explode(".",$nstud);
	//$stud = $mf[0];	
	//....$nfres1 = "GR-$gr/test-0res$stud.txt";    // первоначальный ответ
	//if (is_file($nfres1)){
		//echo "Третьего просмотра нет"; return;
	//}
	//echo "gr=$gr nstud=$nstud fam=$fam";
	//return;
?>
<!DOCTYPE html>    
<HTML><head>
<META http-equiv=Content-Type content="text/html; charset="UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<script src="jquery-latest.min.js"></script>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<script src="js/script.js"></script>
<script>
qq = Array()
<?php
	//$gr 	 = $_POST['gr'];    // группа
	//$nstud 	 = $_POST['nstud']; // 10.Иванов ...   - студент
	//if (empty($nstud)){ echo 'Не выбран студент'; return; }
	echo "gr='".$gr."'\n";
	echo "nstud='".trim($nstud)."'\n";
	if ($bt){
		echo "bres = '".$nf1."'\n";
	}else{	
		echo "bres = ''\n";
	}
		
	$nf = "GR-$gr/test-$nstud.json";
	$nfres = "GR-$gr/test-res$nstud.txt";
	if (!is_file($nf)){ // нет доступа 
		echo "</script>";
		echo "Нет доступа";
		return;
	}else{
		if (!is_file("GR-$gr/control.json")){
			echo "</script>";
			echo "Нет доступа";
			return;
		}
	}	
	if (is_file($nfres)){
		echo "</script>";
		$mr = file($nfres);
		echo "Результат уже есть: ".$mr[0];
		return;
	}

	$mq0 = file("GR-$gr/testcontrol.txt");
	echo "title='".trim($mq0[0])."'\n";  // название теста
	echo "fam='".$fam."'\n";  // фамилия
	$fnc = "GR-$gr/control.json";
	$mm = file($fnc); $sm = ""; foreach($mm as $s) $sm.=$s;
	$mj = json_decode($sm); 
	// {"disc":"tiim","min":15,"psw":"1","ball0":5,"ball1":3,"person":"false"}
	$maxt = $mj->min; // 
	echo "maxtime=".$maxt."\n";   // 
	$ball0 = $mj->ball0;
	echo "ball0=".$ball0."\n";   //   
	$ball1 = $mj->ball1;
	echo "ball1=".$ball1."\n";   //   
	echo "cond='Каждый правильный ответ – ".$ball0." баллов, частичный - ".$ball1."'\n";   // условия  t
	echo "nf = '".$nf."'\n";	// json с заданиями
	$mmt = file($nf); $smm = ""; foreach($mmt as $s) $smm.=$s;
	$mtj = json_decode($smm);
/*{"test":{"assessmentItem":[
//{"responseDeclaration":{"correctResponse":{"value":"ChoiceB"},
//						  "_identifier":"RESPONSE",
//						  "_cardinality":"single"},
	"itemBody":{"choiceInteraction":
			{"prompt":"adgwge",
			 "simpleChoice":[{"_identifier":"ChoiceA","__text":"*111"},
			 				{"_identifier":"ChoiceB","__text":"*222"},
							{"_identifier":"ChoiceC","__text":"333"}
							]
				}}}
,{"responseDeclaratio
*/
	echo "\n";
	$kk=0; // число вопросов
	foreach($mtj->test->assessmentItem as $qq){
		$resp = $qq->responseDeclaration;
		//echo "\n//".$resp->_cardinality;
		$body = $qq->itemBody->choiceInteraction;
		$var = str_replace("'",'"',$body->prompt);
		$var = str_replace("_lt;",'<',$var);
		$var = str_replace("_gt;",'>',$var);
		$var = str_replace("_quot;",'"',$var);
		$sq = "'".$var."'";
		$i=0;
		foreach($body->simpleChoice as $rr){ // варианты ответов
			$var = str_replace("'",'"',$rr->__text);
			$var = str_replace("_lt;",'<',$var);
			$var = str_replace("_gt;",'>',$var);
			$var = str_replace("_quot;",'"',$var);
			$sq .= (",'".$var."'");
			$i++;
		}
		
		for ($j=$i;$j<8;$j++) $sq .= ",''";  // до 8 вариантов ответов
		$mqq[] = $sq;
		$kk++;
//qq[4]=['Атрибуты HTML, задающие высоту и ширину слоя','width','top','height','start','',]
	}
	$i=0;  // 
	foreach ($mqq as $sq){
		echo "qq[$i]=[".$sq."]\n";
		$i++;
	}
	echo 'kres=0; res0="";  res="";'; 
/*	
//1;2;11
//0011;01000;11010;  ответы
//0111;01010;11010;  правильные
*/
?>
qv = new Array(0,1,2,3,4,5,6,7)
qr = new Array(0,1,2,3,4,5,6,7)
kk = 8  // число вариантов
k1 = -1; // 1 2
Array.prototype.shuffle = function(){  // перемешивание
	var i = this.length, j, t;
	while(i) {
		j = Math.floor( ( i-- ) * Math.random() );
		t = this[i];this[i] = this[j]; this[j] = t;
	}
	return this;
}

function fs(k){
	var s = "<input type='checkbox' name='b"+k+"'>"
//	s += "<input name='q"+k+"' disabled size='100' style='background-color:#FFFFFF'>"
//	s += "<textarea rows=2 cols='88' name='q"+k+"' disabled style='background-color:#FFFFFF;color: #000'>"
	return s
}
function ft(k){
//	var s = "<input type='checkbox' name='b"+k+"'>"
//	s += "<input name='q"+k+"' disabled size='100' style='background-color:#FFFFFF'>"
	var s = "<textarea rows=2 cols='88' name='q"+k+"' disabled style='background-color:#FFFFFF;color: #000'>"
	return s
}
function fst(k){
	$('#id'+k).html(fs(k)) // флажок
	$('#it'+k).html(ft(k)) // начало textarea
}
function fst0(k){ // очистить позицию k-го ответа
	$('#id'+k).html("")
	$('#it'+k).html("")
}
function f0(){ // выполняется при запуске
	//cond=""
	$('#id0').html(fam+"<HR><b>"+title+"</b><BR>(<i>"+cond+"</i>)")
	fst(1) // два варианта обязательны
	fst(2)
	t0 = new Date();
	st0 = t0.getHours() + ":" + t0.getMinutes()+ ":" + t0.getSeconds();
	tm0 = 60*60*t0.getHours()+60*t0.getMinutes()+ t0.getSeconds(); // 60*60*t0.getHours() + 
	//?if (res0.length>0) mres = res0.split(';')
	fnext()
}
function fres(b){
	//if (b)kss += ks2; ks2*=2	
	return b?1:0
}	
function fn(s){ // замена символов
	s = s.replace(/&lt;/g, "<")
	s = s.replace(/&apos;/g, "'")
	s = s.replace(/&quot;/g, '"')
	s = s.replace(/&#39;/g, "'")
	s = s.replace(/&#92;/g, "\\")
	s = s.replace(/&#124;/g, "|")
	return  s.replace(/&gt;/g, ">")
	//		$xx = str_replace("'","&#39;",$xx);
	//	$xx = str_replace("\\","&#92;",$xx);	
	//	$xx = str_replace("|","&#124;",$xx);
}
function fnext(){ // по кнопке "Следующий"
	t0 = new Date();
	var st = t0.getHours()+":"+t0.getMinutes()+":"+t0.getSeconds();
	var tm =  60*60*t0.getHours()+60*t0.getMinutes()+ t0.getSeconds(); // 60*60*t0.getHours() +
	var dt = tm-tm0; // прошло секунд    200 dm = 3   200 - 60*3 
	var ds = dt % 60; 
	var dm = (dt-ds)/60;
	var dt1 = 60*maxtime - dt
	var ds1 = dt1 % 60; var dm1 = (dt1-ds1)/60;
	var mss,mz,jj,j1;
	var ss0="";
	$('#id-time').html("Начало "+st0+ " текущее "+st + " прошло "+dm+":"+ds+"<b> осталось "+dm1+":"+ds1+"</b>" )
	//alert(k1)
	if (k1>=0){
		//ss = ""; // пред.тест.  Вариантов kk
		//kss = 0; ks2 = 1;
		qr.length = kk
		qr[qv[0]-1] = fres(frm1.b1.checked); 
		qr[qv[1]-1] = fres(frm1.b2.checked); 
		if (kk>2) qr[qv[2]-1] = fres(frm1.b3.checked); 
		if (kk>3) qr[qv[3]-1] = fres(frm1.b4.checked); 
		if (kk>4) qr[qv[4]-1] = fres(frm1.b5.checked); 
		if (kk>5) qr[qv[5]-1] = fres(frm1.b6.checked); 
		if (kk>6) qr[qv[6]-1] = fres(frm1.b7.checked); 
		if (kk>7) qr[qv[7]-1] = fres(frm1.b8.checked); 
		//ssq = qv.join("")
		ss = qr.join("")
		//alert(kk+" "+ssq + " " +ssr)
		//ss += fres(frm1.b1.checked); 
		//ss += fres(frm1.b2.checked); 
		ss0 = "00000000".substr(0,kk);
	}
	if (k1<0){
		k1++; 
	}else{// ss0 из одних 0 	
		if (ss!=ss0 && ss.length>0){ // пустые не пропускать!
			k1++; 
			res += (ss+";") // резульаты ответов 1100;11000; ... 
			$("#msg").html("")
		}else{
			$("#msg").html("Ничего не отмечено!")
		} 
	}	
	if (k1>=qq.length){ // последний вопрос. Отправка результатов
		frm1.nq.value = ""
		frm1.next.disabled = true
		frm1.nq.value = "Все"
		res = res.replace(/;;/g, ";")
		//alert("res="+res+"&nstud="+nstud+"&gr="+gr+"&kres="+kres)
		$('#result').load("test_res.php?res="+res+"&nstud="+nstud+"&gr="+gr+"&kres="+kres
			+"&ball0="+ball0+"&ball1="+ball1) // запись результата
		// первый раз kres=0 второй kres=1
		//alert(res)
	}else{	// replace(/automotive/g, "sea")
		//alert(k1) отображение вопроса и вариантов
		frm1.nq.value 	= k1+1
		frm1.qqq.value	= fn(qq[k1][0]); // вопрос
		frm1.b1.checked = false;
		frm1.b2.checked = false;
		kk = 8
		while ((kk>0) && (qq[k1][kk].length==0)){
			fst0(kk)  
			kk--
		} // kk - число вариантов
		
		// перемешать варианты 1..kk
		qv.length=kk   // qv = new Array(0,1,2,3,4,5,6,7)
		for(var j=0;j<kk;j++)qv[j]=j+1; qv.shuffle()
		
		frm1.q1.value = fn(qq[k1][qv[0]]);
		frm1.q2.value = fn(qq[k1][qv[1]]);
		if (kk>2){ fst(3); frm1.q3.value = fn(qq[k1][qv[2]])}
		if (kk>3){ fst(4); frm1.q4.value = fn(qq[k1][qv[3]])}
		if (kk>4){ fst(5); frm1.q5.value = fn(qq[k1][qv[4]])}
		if (kk>5){ fst(6); frm1.q6.value = fn(qq[k1][qv[5]])}
		if (kk>6){ fst(7); frm1.q7.value = fn(qq[k1][qv[6]])}
		if (kk>7){ fst(8); frm1.q8.value = fn(qq[k1][qv[7]])}
		
		if (res0.length>0){		
			mss = mres[k1]
			for (var j=0;j<mss.length;j++){
				mz = mss.substring(j,j+1);
				if (mz=='1'){ j1 = j+1
					eval("frm1.b"+j1+".checked = true")
				}
			}
		}
	}	
}
</SCRIPT>
<body onload='f0()'>
<input valign='top' type='button' value='Закрыть окно' onclick = 'window.location="index.php"' class='b2'>

Фамилия: <b><span id="id0"></span></b>
<table border = '1' cellspacing = '0' cellpadding='5' class="t0" style='background-color:#F0F0F0'>
	<tr>
		<td rowspan='2' valign='top'>
		 <div class="clocks">
            <canvas id="canvas" width="200" height="200"></canvas>
        </div>
		<br> 
<?php
	if(is_dir("FU")){
		echo "<img src='FU/gu_87.gif'  height='120'>"; 
	}
?>	
		<td>
		<div id="id-time"></div>
	<tr><td valign='top'>
	<form name='frm1'>		
	Вопрос № <input name='nq' disabled value='1' size='1' style='background-color:#FFFFFF;color:#000'>
	<font color='red'><span id='msg'></span></font><BR>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<textarea name='qqq' cols='88' rows='3' disabled style='background-color:#FFFFFF;color:#000'>
	</textarea>
	<table >
	<tr><td valign='top'><span id="id1"><td><span id="it1"></span>
	<tr><td valign='top'><span id="id2"><td><span id="it2"></span>
	<tr><td valign='top'><span id="id3"><td><span id="it3"></span>
	<tr><td valign='top'><span id="id4"><td><span id="it4"></span>
	<tr><td valign='top'><span id="id5"><td><span id="it5"></span>
	<tr><td valign='top'><span id="id6"><td><span id="it6"></span>
	<tr><td valign='top'><span id="id7"><td><span id="it7"></span>
	<tr><td valign='top'><span id="id8"><td><span id="it8"></span>
	</table>
	<BR><input name='next' type='button' onclick='fnext()' value='Следующий' class='b2'>
	<font color='red'><b><div id="result"></div></b></font>
	</form>
</table>
</body>