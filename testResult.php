<?php // ��������� ��������
// testResult.php?gr=pi40&fam=petrov&disc=op&tema=2&qy=5&qa=3&time=10
//	����� �� get_json $.get("testResult.php?gr="+gr+"&fam="+fam+"&disc="+disc+"&qy="+qyes+"&qa="+qq)
//[  ["prog",0],  ["tiim",2],  ["WPr",4]]  - ������� �������
	$gr	= $_REQUEST["gr"];		// ������   gr  = GR-pi4-1
	$fam = $_REQUEST["fam"];	// �������
	$disc = $_REQUEST["disc"];	// ��� ����������
	$tema = $_REQUEST["tema"];	// ���� ����������
	$qy = $_REQUEST["qy"]; 		// ����������
	$qa = $_REQUEST["qa"]; 		// �����
	$dtime = $_REQUEST["time"]; 	// ��������� �����
	$nf = "GR-$gr/$fam/trening-$disc.json";
	$h1=fopen("debug1","w");  // no GR-pi4/antonov������ GR-pi4/antonov/trening-tiim.json
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
		fputs($h1,"������ $nf\n");
	}else{ // ���� ���� 
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