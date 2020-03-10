<?php

$projekt= $_GET['projekt'];
$notatka= $_GET['notatka'];

$file1 = $projekt . '-notes.txt';
$fp1 = fopen($file1,'wb');
fwrite($fp1,$notatka);
fclose($fp1);

$adres = "Location: index.php";
header($adres);

?>