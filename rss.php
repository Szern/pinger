<?php

echo '<?xml version="1.0" encoding="ISO-8859-2"?>
<rss version="0.91">
<channel>
<title>Pinger ' . $_SERVER['HTTP_HOST'] . '</title>
<link>http://' . $_SERVER['HTTP_HOST'] . '/pinger/</link>
<description>dane pingera</description>
<language>pl</language>';

	echo '<item>
	<title>przegl�d</title>
	<link>http://' . $_SERVER['HTTP_HOST'] . '/pinger/bany.txt</link>
	<description>';
	$fx = array('1' => 'przelacznik1.txt', '2' => 'przelacznik2.txt');
	foreach($fx as $numer => $file) {
		if (!(file_exists($file))) {
			$fp = fopen($file,'wb');
			$zapis = 'wy��czone' . "\r\n" . 'start';
			fwrite($fp, $zapis);
			fclose($fp);
		} else {
			$fp = fopen($file,'rb');
			${'funkcja' . $numer} = trim(fgets($fp));
			${'projekt' . $numer} = fgets($fp);
			fclose($fp);	
		}
	}
	$file = 'bany.txt';	
	if (file_exists($file)) {
		$fp = fopen($file,'rb');
		while (!feof($fp)) {
			$bany[] = fgets($fp);
		}
		fclose($fp);
		$linia = array_pop($bany);
		while ((substr($linia, 0, 7)=='przerwa') || (substr($linia, 20, 10)=='pingowanie')) {
		$linia = array_pop($bany);
		}
		echo $linia . '<![CDATA[<br />]]>indeksacja: aktualnie ' . $funkcja1;
	}
	if (!($funkcja1 == 'wy��czone')) {
		echo ' dla projektu ' . $projekt1 . '<![CDATA[<br />]]>nast�pny projekt w kolejce do indeksacji: ';
		$file = 'kolejka-indeksowanie.txt';
		if ((file_exists($file)) && (!((filesize($file)) == 0))) {
			$fp = fopen($file,'r');
			$buforek1 = fgets($fp);
			$buforek2 = fgets($fp);
			echo $buforek2 . ' - funkcja: ' . $buforek1 . '<![CDATA[<br />]]>';
			fclose($fp);
		} else {
			echo 'brak plik�w<![CDATA[<br />]]>pingowanie: aktualnie ' . $funkcja2;
		}
	}
	if (!($funkcja2 == 'wy��czone')) {
		echo ' dla projektu' . $projekt2 . '<![CDATA[<br />]]>nast�pny projekt w kolejce do pingowania:';
		$file = 'kolejka-pingowanie.txt';
		if ((file_exists($file)) && (!((filesize($file)) == 0))) {
			$fp = fopen($file,'r');
			$buforek1 = fgets($fp);
			$buforek2 = fgets($fp);
			echo $buforek2 . ' - funkcja: ' . $buforek1 . '<![CDATA[<br />]]>';
			fclose($fp);
		} else {
			echo 'brak plik�w<![CDATA[<br />]]>';
		}
	}
	echo '</description>
	</item>';

$projekty = file('projekty.txt');
foreach ($projekty as $projekt) {
	$projekt = trim($projekt);
	echo '<item>
	<title>' . $projekt . '</title>
	<link>http://' . $_SERVER['HTTP_HOST'] . '/pinger/</link>
	<description>';
	$file = $projekt . '-urle-do-pingowania.txt';
	if (file_exists($file)) {
		echo 'zawarto�� bufora pingera: ' . count(file($file)) . '<![CDATA[<br />]]>';
	} else {
		echo 'zawarto�� bufora pingera: 0<br />';
	}
	$file = $projekt . '-urle-do-indeksacji.txt';
	if (file_exists($file)) {
		echo 'zawarto�� bufora indeksacji: ' . count(file($file)) . '<![CDATA[<br />]]>';
	} else {
		echo 'zawarto�� bufora indeksacji: 0<br /><br />';
	}
	$file = $projekt . '-notes.txt';
	if ((file_exists($file)) && (!((filesize($file)) == 0))) {
		$fp = fopen($file,'rb');
		$trans = array("\r\n" => "<![CDATA[<br />]]>");
		echo strtr(fread($fp, filesize($file)), $trans);
		fclose($fp);
	}
	echo '</description>
	</item>';
}
	
echo '</channel>
</rss>';

?>