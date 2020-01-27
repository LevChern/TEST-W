<?php // результат тренинга
// testHistory.php 
//	$.get("testResult.php?gr="+gr+"&fam="+fam+"&disc="+disc+"&qy="+qyes+"&qa="+qq)
//[  ["prog",0],  ["tiim",2],  ["WPr",4]]  - сколько роздано
 header("Content-Type: text/html; charset=UTF-8");
	$gr	= $_REQUEST["gr"];		// группа
	$fam = $_REQUEST["fam"];	// фамилия
	include "lib1_file.php";
	$Lfam = translit8(trim($fam));
	$kdisc = $_REQUEST["disc"];	// код дисциплины
	$mdisc = explode(";",$kdisc); $disc = $mdisc[0]; $name = $mdisc[1];
	$nf = "$gr/$Lfam/trening-$disc.json";
	// [["1","1","2","17-06-25","23:10","12"]]
	//    0   1   2    3          4      5  
	//echo $nf;
	if (is_file($nf)){
		$mm = file($nf); $smm = ""; foreach($mm as $s) $smm.=$s;
		$mj = json_decode($smm);
		$temap=0; $ks=0;
		foreach($mj->history as $dt){ // {"history":[["1","1",  "2","17-08-10","22:25","15"],
			$d3 = substr($dt[3],3);   //              тема прав всего    
			$ks++;
			if ($dt[1]==$dt[2]){ // пройденная тема
				$temap = 1*$dt[0];	
			}
			$mout[] = "<tr align='center'><td style='width:60px'>$dt[0]<td style='width:60px'>$dt[1]<td  style='width:60px'>$dt[2]<td style='width:60px'>$d3<td style='width:60px'>$dt[4]<td style='width:60px'>$dt[5]";
		}
		if ($ks<10) $title="<tr><th style='width:60px'>тема<th style='width:60px'>прав<th style='width:60px'>всего".
			"<th style='width:60px'>дата<th style='width:60px'>время<th style='width:60px'>сек";
		else $title="<tr><th  style='width:46px'>тема<th style='width:46px'>прав<th style='width:46px'>всего".
			"<th style='width:51px'>дата<th style='width:54px'>время<th>сек";
		
		if ($temap==0){
			echo "Пройдено тем <span id='temap'>".$temap."</span>";
		}else if ($temap==1){
			echo "Пройдена тема <span id='temap'>".$temap."</span>";
		}else{
			echo "Пройдены темы 1-<span id='temap'>".$temap."</span>";
		}	
//	echo "#dd {overflow:auto; height:400px; border:1px solid #777; width:".$wt."px; }";
?>		
		<br><table border cellspacing=0 cellpadding='3' style='width:360px'  >
		<?php echo $title; ?>
		</table>
		<div id='dd' style='overflow:auto; height:262px; border:1px solid #777;width:360px' >
		<table border cellspacing=0 cellpadding='3'>
<?php
		foreach($mout as $dt){
			echo $dt;
		}
		echo "</table></div>";
		
	}else{
		//echo "<font color='red'>Не было сеансов</font>";
		echo "Пройденных тем <span id='temap'>0</span>";
	}
?>