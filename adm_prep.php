<?php  header("Content-Type: text/html; charset=UTF-8");
?>
<input class='b2' type='button' value='Закрыть окно' onclick = 'window.close()'>
<?php
// запись/изменение в XML
$fx = $_REQUEST["fx"];
$fs = $_REQUEST["fs"];
$fout = "pps.txt";
$fout = $_REQUEST["fout"];
include "lib_xsl.php";
include "lib1_file.php";
$fx = $fx."."."xml";
$fs = $fs."."."xsl";
//echo $fx." ".$fs;
$out = trans($fx,$fs);
WriteAll($fout,$out);
echo "Файл $fout обновлен";
?>

