<?php // test_jput.php ����� �� index_prep ������ �����(��������):
// ������ "������� ��������"
// �������, ������� � ��������
//$.get("test_jput.php?gr="+gr+"&disc="+dkod+"&sq="+sq+"&sk="+sk+&prep="+prep);
function mix0($ms,$n){ // ������������ � ������� $n ���
	foreach ($ms as $x) $mk[] = $x;
	$nn = sizeof($mk);
	for ($i = 0; $i<$n; $i++) {  // �������������
		$k1 = rand(0,$nn-1); 
		$k2 = rand(0,$nn-1);
		$qq = $mk[$k1];	$mk[$k1] =  $mk[$k2];	$mk[$k2] = $qq;
	}
	return $mk;
}// mix0
	$gr	= $_REQUEST["gr"];		// ������
	$disc = $_REQUEST["disc"];	// ��� ����������
	$open = $_REQUEST["open"];	// 
	$close = $_REQUEST["close"];	// 
	$clear = $_REQUEST["clear"];	// 
	$fnc = "GR-$gr/control.json";
	if (!empty($open)){ // ������� ������ � ����������
		$min = $_REQUEST["min"];	// 
		$ball0 = $_REQUEST["ball0"];	// 
		$ball1 = $_REQUEST["ball1"];	// 
		$psw = $_REQUEST["psw"];	// 
		$person = $_REQUEST["person"];	// 
		$t0 = $_REQUEST["t0"];	// 
		$mout = array("disc"=>$disc,"min"=>1*$min,"psw"=>$psw,
			"ball0"=>1*$ball0,"ball1"=>1*$ball1,"person"=>$person,"t0"=>$t0);
		$ss = json_encode($mout);
		$h = fopen($fnc,"w");
		fputs($h,$ss);
		fclose($h);	
		//echo "������ ������";
		return;
	}
	if (!empty($close)){
		if(is_file($fnc)) unlink($fnc);
		//echo "������ ������";
		return;
	}
	if (!empty($clear)){
		$dh = opendir("GR-$gr");
		$nmax = 0; $nmax1 = 0;$nmax2 = 0;
		$res = array();
		while ($file = readdir($dh)) {
			$x = substr($file,0,5);  // test.txt + test-res.txt
			if ($x=="test-"){
				unlink("GR-$gr/$file");
				$nmax++; 
			}	
		}
		unlink("GR-$gr/testcontrol.txt");
		echo "������� �� ����� ������ $gr ������: $nmax";
		return;
	}

// ������� �������
	$name = $_REQUEST["name"];
	$sq = $_REQUEST["sq"]; 		// ������� �������� �� �����
	$sk = $_REQUEST["sk"]; 		// ����� �������� �� �����
	$prep = $_REQUEST["prep"]; 		// ������
	//echo "<br>$gr $disc $sq $sk $prep";
	$mq = explode(",",$sq); 
	$kq = count($mq);  
	$mk = explode("|",$sk);
	$ktem = count($mk); // ����� ���

	$ngr = "GR-$gr/GR-$gr".".txt";   // ������ 
	$mgr = file($ngr); $kgr = count($mgr);
	//echo "<br>kgr=$kgr";
	
	$ii=0; $k=0;	
	foreach ($mk as $kk){ // �� �����
		$kq = $mq[$ii];   //  ������� �������
		//echo "<br>tema $ii $kq";
		$m1 = range(0,$kk-1);
		$m2 = mix0($m1,200); // ������������ ������� � ����
		//$ss=""; foreach ($m2 as $kn)$ss.=("$kn,"); echo "<br>$ss";
		$j = 0;   //   0 1 2 3 4 
		for ($i=1; $i<=$kgr; $i++){ // �� ��������� 
			//echo "<br>";
			$iq = 0; 
			while ($iq < $kq ){ // ������� $kq ��������
				$jq = $m2[$j];   // $j = 0 1 ... $kk-1 
				$ii1 = $ii+1;
				$mst[$i][$k+$iq] = "$ii1-$jq";   // 
				//echo " $j_($i)[$k+$iq]";
				$iq++;
				$j++;
				if ($j+$kq > $kk){  // 3+3>5
					//echo "<br>mix0";
					$m2 = mix0($m1,200); // ������������ ������� � ����
					$j = 0;
				}
			}
		}
		$ii++;
		$k += $kq;
	}

	for ($i=1; $i<=$kgr; $i++){ // �� ��������� 
		// echo "<br>$i)";
		$nst = "GR-$gr/test-$i".".json";   // ���� ��� i-�� ���������
		if (is_file($nst)) unlink($nst);
		$mh[$i] = fopen($nst,"a");	
		fputs($mh[$i], '{"test":{"assessmentItem":['."\n");	
		//foreach ($mst[$i] as $ss){ // 1-1 1-2 1-0 2-1 2-3 3-1 3-2 4-1
		//}	
		$mnum[$i]=0; // ��������� ��������
	}
	$jj=0; 
	for ($nt=1; $nt<=$ktem; $nt++){ // �� �����
		$npr = "TEST-$prep/TEST-$disc-$nt".".json";   // ���� ����
/* ��������� � ��������� �� ��������
	{"test":{"assessmentItem":
	[
	{"responseDeclaration": 
		{"correctResponse": 
			{"value":["ChoiceA","ChoiceB"]},
			"_identifier": "RESPONSE",
			"_cardinality": "multiple"
		},
	"itemBody": 
			{"choiceInteraction": 
				{"prompt": "1-1 ������� ������������� ������ ��������������� ������ �������� ��������� ���� � �������� XX ���� ��������: ",
				 "simpleChoice": 
					[
					{"_identifier": "ChoiceA","__text": "*��������������"},
					{"_identifier": "ChoiceB","__text": "*�����������"},
					{"_identifier": "ChoiceC","__text": "�����������"},
					{"_identifier": "ChoiceD","__text": "������������"},
					{"_identifier": "ChoiceE","__text": "��������������"}					
					]
				}
			}
	},
*/		
		if (is_file($npr)){ 	//echo "<br>$npr";
			$mm = file($npr); $smm = ""; foreach($mm as $s) $smm.=$s;
			$mj = json_decode($smm);
			$i=0;
			foreach($mj->test->assessmentItem as $quest){
				$sq = json_encode($quest);
				$mquest[$i] = $sq;
				$i++;
				//echo "<br>$sq";
				//echo "<br>";
			}
			//echo "<br>npr=$npr i=$i";
		
			for ($i=1; $i<=$kgr; $i++){ // �� ��������� 
				foreach ($mst[$i] as $qq){
					$mm = explode("-",$qq); // 
					if ($mm[0]==$nt){
						// �������� ������� � ������� $mm[1]
						$zz = ($mnum[$i]==0)?"":",";
						$mnum[$i]++;
						fputs($mh[$i], $zz.$mquest[$mm[1]]."\n");
						$jj++;	
					}
				}
			}
		}	
	}
	
	for ($i=1; $i<=$kgr; $i++){ // �� ��������� 
		fputs($mh[$i], ']}}');	
		fclose($mh[$i]);	
	}
	echo "������� � ������ $gr (��������� $kgr)";
	$h = fopen("GR-$gr/testcontrol.txt","w"); fputs($h,$name);fclose($h);
?>