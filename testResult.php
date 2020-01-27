<?php // результат тренинга
// testResult.php?gr=pi40&fam=petrov&disc=op&tema=2&qy=5&qa=3&time=10
//	вызов из get_json $.get("testResult.php?gr="+gr+"&fam="+fam+"&disc="+disc+"&qy="+qyes+"&qa="+qq)
//[  ["prog",0],  ["tiim",2],  ["WPr",4]]  - сколько роздано
	$gr	= $_REQUEST["gr"];		// группа   gr  = GR-pi4-1
	$fam = $_REQUEST["fam"];	// фамилия
	$disc = $_REQUEST["disc"];	// код дисциплины
	$tema = $_REQUEST["tema"];	// тема дисциплины
	$qy = $_REQUEST["qy"]; 		// правильных
	$qa = $_REQUEST["qa"]; 		// всего
	$dtime = $_REQUEST["time"]; 	// потрачено минут
	$nf = "GR-$gr/$fam/trening-$disc.json";
	$h1=fopen("debug1","w");  // no GR-pi4/antonovсоздан GR-pi4/antonov/trening-tiim.json
	if (!is_dir("$gr/$fam")){
		fputs($h1,"no $gr/$fam");
		mkdir("$gr/$fam");
	}
	// fclose($h1);
	//print_r($mj);
	$today=date("y-m-d");
	$time=date("H:i");
	$mout=array();
	if (!is_file($nf)){
		$h = fopen($nf,"w"); // debug.json
		fputs($h1,"создан $nf\n");
	}else{ // есть файл 
		$mm = file($nf); $smm = ""; foreach($mm as $s) $smm.=$s;
		$hist = json_decode($smm);
		foreach($hist->history as $dt){
			$mout[] = $dt;
		}
		$h = fopen($nf,"w"); // trening-$disc.json
	}
	fclose($h1); // debug1
	$dd = array();
	$dd[0]=$tema;
	$dd[1]=$qy;
	$dd[2]=$qa;
	$dd[3]=$today;
	$dd[4]=$time;
	$dd[5]=$dtime;
	$mout[]=$dd;
	// {"history":[["1","1","2","17-06-25","23:10","12"]]}
	$hist = array("history" => $mout);
	$ss = json_encode($hist);
	fputs($h,$ss);
	fclose($h);
	//fclose($h1);

?>