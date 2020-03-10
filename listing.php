<?php 

$projekt = $_GET['projekt'];
$jaki = $_GET['jaki'];

include('menu.php');
include('user.php'); 

echo '<strong>' . $jaki . ':</strong><br /><br />';

if ($jaki == 'mapa') {
	$file = $projekt . '-urle.txt';
} elseif ($jaki == 'pinger') {
	$file = $projekt . '-urle-do-pingowania.txt';
} elseif ($jaki == 'indeksacja') {
	$file = $projekt . '-urle-do-indeksacji.txt';
} elseif ($jaki == 'raport') {
	$file = $projekt . '-raport-indeksacji.txt';
} else {
	$file = $projekt . '-' . $jaki . '-urle.txt';	
}

$licznik = 0;
if (file_exists($file)) { 
	$fp = fopen($file,'r');
	if (!((filesize($file)) == 0)) {
		while (!feof($fp)) {
			$buforek = fgets($fp);
			echo $buforek . ' <br />';
			$licznik++;
		}
	} else { $licznik = 0; }
	fclose($fp);	
} else {
	echo 'B³±d: jeszcze nie ma pliku ' . $file;
}
echo '<br /><strong>Razem: ' . $licznik . ' podstron.</strong><br />
</body>
</html>';

?>