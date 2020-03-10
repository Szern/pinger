<?php

$file0 = $_GET['plik'];
$fp0 = fopen($file0,'wb');
fclose($fp0);

$adres = "Location: konfig.php";
header($adres);
	
?>