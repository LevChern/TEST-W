<?php
$xx = "'<>\"";
//$xx = htmlspecialchars_decode($xx, ENT_QUOTES);
$xx = htmlentities($xx, ENT_QUOTES); 
$xx = str_replace("'","&#92;",$xx);
$xx = str_replace("\\","&#92;",$xx);
$h = fopen("tt.txt","w");
fputs($h,$xx);
fclose($h);
echo $xx;
?>