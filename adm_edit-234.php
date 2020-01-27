<?php    header("Content-Type: text/html; charset=UTF-8");        // 
 echo "<input class='b2' type='button' value='Закрыть окно' onclick = 'window.close()'>";
 $nfile =  $_POST["nfile"]; // файл
 $pps   =  $_POST["pps"];   // папка
 $type 	=  $_POST["type"];   // xml или pdf
 $dr	=  $_SERVER['DOCUMENT_ROOT'];
//Загрузка файлов на сервер при register_globals=Off
//В этом случае PHP формирует массив $_FILES[]. 
//В этом массиве храниться вся информация о всех загружаемых файлах. В нашем случае структура этого массива следующая: 
//$_FILES["myfile"]["tmp_name"] - Имя временного файла 
//$_FILES["myfile"]["name"] - Имя файла на компьютере пользователя 
//$_FILES["myfile"]["size"] - Размер файла в байтах 
//$_FILES["myfile"]["type"] - MIME-тип файла 
//$_FILES["myfile"]["error"] - код ошибки. 
// Если register_globals=Off
// Если upload файла
if(isset($_FILES["myfile"]))   {
	$myfile 	 = $_FILES["myfile"]["tmp_name"];
	$myfile_name = $_FILES["myfile"]["name"];
	$myfile_size = $_FILES["myfile"]["size"];
	$myfile_type = $_FILES["myfile"]["type"];
	$error_flag = $_FILES["myfile"]["error"];
	if ($myfile_size==0) { echo "Не выбран файл или превышен размер файла (10K)"; return; }
	if ($type=="xml"){
		if ($myfile_type!="text/xml") { echo "Неверный тип файла $myfile_type"; return; }
	}
	if($error_flag == 0)   {       // Если ошибок не было
		print("<br>Имя файла на компьютере пользователя: ".$myfile_name);
		if ($myfile_name!=$nfile){
			echo "<br>Выбран не тот файл ($nfile)"; return;
		}
		print("<br>Размер файла: ".$myfile_size);
		$file_name = $nfile;
		$nf = $dr."/$pps/".$file_name; //  
		print("<br>".$nf); 
		$b = move_uploaded_file($myfile,$nf); 
		if ($b) {
			print("<br> файл скопирован");
		} else    print(" ошибка копирования");
		return;    
	} else {
		echo "error_flag $error_flag";
	}
} else {
   print("not isset");
}
 ?>