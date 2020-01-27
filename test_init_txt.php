<?php header("Content-Type: text/html; charset=UTF-8");
	$gr 	 = $_POST['gr'];    // группа
	$nstud 	 = $_POST['nstud']; // 10.Иванов ...   - студент
	if (empty($nstud)){ echo 'Не выбран студент'; return; }
	$mf =  explode(".",$nstud);
	$stud = $mf[0];	
	$fgrup 	 = "GR-$gr";
	$nfres1 = "$fgrup/test-0res$stud.txt";    // первоначальный ответ
	if (is_file($nfres1)){
		echo "Третьего просмотра нет"; return;
	}
?>
<!DOCTYPE html>    
<HTML><head>
<META http-equiv=Content-Type content="text/html; charset="UTF-8">
<link rel="stylesheet" type="text/css" href="lab_db.css"/>
<style>
   body { background: url("FU/images.jpg"); }
</style>
<script src="jquery-latest.min.js"></script>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<script src="js/script.js"></script>
<script>
qq = Array()
<?php
	//$gr 	 = $_POST['gr'];    // группа
	//$nstud 	 = $_POST['nstud']; // 10.Иванов ...   - студент
	//if (empty($nstud)){ echo 'Не выбран студент'; return; }
	echo "grup='".trim($fgrup)."'\n";
	echo "nstud='".trim($nstud)."'\n";
	echo "stud=$stud\n";
	
$nf = "$fgrup/test$stud.txt";
$nfres = "$fgrup/test-res$stud.txt";
if (!file($nf)){
	return;
}
$mq0 = file($nf);
echo "title='".trim($mq0[0])."'\n";  // название теста
echo "cond='".trim($mq0[1])."'\n";   // условия  
$ntm = "$fgrup/maxtime";
$maxt = 90;
if (is_file($ntm)){
	$mtm = file($ntm);
	$maxt = trim($mtm[0]);
}
echo "maxtime='".$maxt."'\n";   // условия  
	//$md = getdate();
	//$time = $md["hours"].":".$md["minutes"];
	//echo "time='".$time."'\n";
$ii=0;
foreach ($mq0 as $sq){
// 1. В состав оперативной памяти входят:|A. Регистры процессора|B. Кэш процессора|C. *Оперативные запоминающие устройства
	$mm = explode("|",$sq);
	if (sizeof($mm)<2) continue;
	$ss = ""; $sr="";	
	$kss = 0; $ks2 = 1;
	for ($i=0; $i<6; $i++){  // было 5      
		$s = trim($mm[$i]);
		if ($i<sizeof($mm)) {
			if ($i>0) {
				if (substr($s,0,1)=="*") {
					$sr.="1"; $s = substr($s,1); 
					$kss += $ks2;
				}else $sr.="0";
				$ks2*=2;
			}	
			//$s = htmlspecialchars_decode($s, ENT_QUOTES);
			//$s = str_replace("\\","&#92;",$s);	
			//$s = str_replace("|","&#166;",$s);	
			$ss .= ("'".$s."',");
		}else{
			$ss .= ("'',");
		}	
	}	
	// $sr  = 0010    100    001    1 - правильный ответ
	//        0100=4  001=1  100=4
	$k = strpos($ss,".");   //  n. Вопрос    111.
	if ($k>0 && $k<4) $ss = "'".trim(substr($ss,$k+1));
	$mqq[]= $ss;  //."'".$sr."'".",$kss";		
}
//$mqq[]="'1. Вопрос 2:...','1','0','0','','100'";
//$mqq[]="'2. Вопрос 3:...','0','1','','','01'";
$i=0;
foreach ($mqq as $sq){
	echo "qq[$i]=[".$sq."]\n";
	$i++;
}	
//qq[0]=['Вопрос 1:...','1','0','1','0','1010']
//qq[1]=['Вопрос 2:...','1','0','0','','100']
if (is_file($nfres)){
	$mres = file($nfres);
	$res0 = trim($mres[1]);
	echo 'kres=1; res=""; res0="'.$res0.'";'; 
//1;2;11
//0011;01000;11010;  ответы
//0111;01010;11010;  правильные
}else{
	echo 'kres=0; res0="";  res="";'; 
}
?>
k1 = -1; // 1 2
function fs(k){
	var s = "<input type='checkbox' name='b"+k+"'>"
//	s += "<input name='q"+k+"' disabled size='100' style='background-color:#FFFFFF'>"
	s += "<textarea rows=2 cols='88' name='q"+k+"' disabled style='background-color:#FFFFFF;color: #000'>"
	return s
}
function f0(){
	$('#id0').html(nstud+"<HR><b>"+title+"</b><BR>(<i>"+cond+"</i>)")
	$('#id1').html(fs(1))
	$('#id2').html(fs(2))
	t0 = new Date();
	st0 = t0.getHours() + ":" + t0.getMinutes()+ ":" + t0.getSeconds();
	tm0 = 60*60*t0.getHours()+60*t0.getMinutes()+ t0.getSeconds(); // 60*60*t0.getHours() + 
	if (res0.length>0) mres = res0.split(';')
	fnext()
}
function fres(b){
	if (b)kss += ks2; ks2*=2	
	return b?1:0
}	
function fn(s){
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
function fnext(){
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
	if (k1>=0){
		ss = "";// пред.тест4
		kss = 0; ks2 = 1;
		ss += fres(frm1.b1.checked); 
		ss += fres(frm1.b2.checked); 
		ss0 = "00";
		if (qq[k1][3].length>0){
			ss += fres(frm1.b3.checked); ss0 += "0";
		}	
		if (qq[k1][4].length>0){
			ss += fres(frm1.b4.checked); ss0 += "0";
		}	
		if (qq[k1][5].length>0){
			ss += fres(frm1.b5.checked); ss0 += "0";
		}	
	}
	if (k1<0){
		k1++; 
	}else{// ss0 из одних 0 	
		if (ss!=ss0 && ss.length>0){ // пустые не пропускать!
			k1++; 
			res += (ss+";") // резульаты ответов 1100;11000; ... 
		} 
	}	
	if (k1>=qq.length){ // последний вопрос
		frm1.nq.value = ""
		frm1.next.disabled = true
		frm1.nq.value = "Все"
		res = res.replace(/;;/g, ";")
		$('#result').load("test_res.php?res="+res+"&stud="+stud+"&grup="+grup+"&kres="+kres) // запись результата
		// первый раз kres=0 второй kres=1
		//alert(res)
	}else{	// replace(/automotive/g, "sea")
		frm1.nq.value = k1+1
		frm1.qqq.value=fn(qq[k1][0]);
		frm1.b1.checked = false;
		frm1.b2.checked = false;
		frm1.q1.value = fn(qq[k1][1]);
		frm1.q2.value = fn(qq[k1][2]);
		if (qq[k1][3].length>0){
			$('#id3').html(fs(3))
			frm1.q3.value = fn(qq[k1][3]);
		}else $('#id3').html("<BR>")
		if (qq[k1][4].length>0){
			$('#id4').html(fs(4))
			frm1.q4.value=fn(qq[k1][4]);
		} else 	$('#id4').html("<BR>")
		if (qq[k1][5].length>0){
			$('#id5').html(fs(5))
			frm1.q5.value=fn(qq[k1][5]);
		} else 	$('#id5').html("<BR>")
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

<div id="id0"></div>
<table border = '1' cellspacing = '0' cellpadding='5' class="t0" style='background-color:#F0F0F0'>

	<tr>
		<td rowspan='2' valign='top'>
		 <div class="clocks">
            <canvas id="canvas" width="200" height="200"></canvas>
        </div>
		<br><img src="FU/gu_87.gif"  height='120'> 
		<td>
		<div id="id-time"></div>
	<tr><td valign='top'>
	<form name='frm1'>		
	Вопрос № <input name='nq' disabled value='1' size='1' style='background-color:#FFFFFF;color:#000'>
	<BR>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<textarea name='qqq' cols='88' rows='3' disabled style='background-color:#FFFFFF;color:#000'>
	</textarea>
	<div id="id1" ></div>
	<div id="id2"></div>
	<div id="id3"></div>
	<div id="id4"></div>
	<div id="id5"></div>
	<BR><input name='next' type='button' onclick='fnext()' value='Следующий' class='b2'>
	<font color='red'><b><div id="result"></div></b></font>
	</form>
</table>
</body>