<?php

$file = 'kolejka-' . $_GET['kolejka'] . '.txt';
$fp = fopen($file,'rb');
$calosc = file($file);
fclose($fp);
$fp = fopen($file,'wb');
$j = count($calosc) - 2;
if ($j>0) {
	fwrite($fp, trim($calosc[0]));
}
for($i=1;$i<$j;$i++) {
	fwrite($fp, "\r\n" . trim($calosc[$i]));
}
fclose($fp);

header("Location: konfig.php");

?>