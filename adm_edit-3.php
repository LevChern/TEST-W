<?php  header("Content-Type: text/html; charset=UTF-8");
// adm_edit-3.php
// вызов из adm_edit.php   adm_edit_up.php,  
// запись/изменение в XML
	$add = $_REQUEST["add"]; // добавить
	$ch = $_REQUEST["change"]; // изменить
	$del = $_REQUEST["del"]; // удалить
	$fxml = $_REQUEST["xml"];	  // файл
	$nitem = $_REQUEST["nitem"];	  // имя элемента
	$attr = $_REQUEST["attr"];   // полный список атрибутов
	$num = $_REQUEST["num"];  // номер элемента
	//echo "f=$fxml $nitem $attr"; return;
	//$h=fopen("d.txt","w");
	//fputs($h,"\nnitem=$nitem");
if (!empty($del)){ // удалить
	$xml = simplexml_load_file($fxml);
	$data = $xml->asXML(); //$data - строка с xml
	//echo u2a($data);
	$doc = new DOMDocument;
	$doc->loadXML($data); 
	$root = $doc->documentElement;
	$elem = $root->getElementsByTagName($nitem)->item($num-1); // элемент по номеру
	$elem->parentNode->removeChild($elem); //Получаем родительский узел и удаляем 
	$doc->save($fxml);
	//file_put_contents($fxml,$doc);		// записать в файл
} else if (!empty($add) || !empty($ch) ){ // добавить или изменить
	$mh = explode(";",$attr);
	$i=1;
	$xml = simplexml_load_file($fxml);
	$ma = explode(';',$attr);
	if ($ch) { // изменить
		$i=1;
		foreach ($xml->$nitem as $p) { // поиск строки
			if ($i==$num){
				$pr = $p; break;
			}	
			$i++;
		}
		//fputs($h,"\npr=$pr");
	}else{ // добавить
		$pr = $xml->addChild($nitem);   // новый элемент
	}
	foreach ($_REQUEST as $n=>$v) { // значения полей. 
		if (substr($n,0,1)=="v"){  // v1 ... v10, v11 ... v20 - имена полей 
			$mv = explode("(",$n); if (count($mv)>1) continue;
			$ka = strlen($n);
			if ($ka==2) $b = substr($n,1,1); else $b = substr($n,1,2); // $b=1,2,44
			$k = 0+$b;
			$atr = $ma[$k]; 
			//fputs($h,"\n$atr=$v");
			if (!empty($v)){
				$v = str_replace("_"," ",$v); 
				$pr[$atr] = $v; 
			}
		}
	}
	file_put_contents($fxml,$xml->asXML());		// записать в файл
	
	if ($ch) {
		//echo "<br>Изменена запись с номером $num"; //  в $fxml 
	}else{ // добавить
		//echo "<input type='button' value='Обновить' onclick='window.location.reload(true)'>";
		//echo "<br>Добавлена запись "; // в $fxml
	}
	//fclose($h);
}
?>

