<?php

$switch = $_GET['switch'];
if ($switch  == '') {
	$switch = $_POST['switch'];
}
$projekt = $_GET['projekt'];
if ($projekt  == '') {
	$projekt  = $_POST['projekt'];
}
$funkcja = $_GET['funkcja'];
if ($funkcja  == '') {
	$funkcja  = $_POST['funkcja'];
}

$pliki[] = $projekt . '-urle.txt';
$pliki[] = $projekt . '-zaindeksowane-urle.txt';
$pliki[] = $projekt . '-raport-indeksacji.txt';
$pliki[] = $projekt . '-notes.txt';
$pliki[] = $projekt . '-urle-do-indeksacji.txt';
$pliki[] = $projekt . '-urle-do-pingowania.txt';

// echo $switch . '<br />' . $projekt . '<br />' . $funkcja . '<br /><br />';

function niezaindeksowane($projekt, $indeksacja_pingowanie) {
	if ($indeksacja_pingowanie == 'indeksacja') {
		$file1 = $projekt . '-urle-do-indeksacji.txt';
	} elseif ($indeksacja_pingowanie == 'pingowanie') {
		$file1 = $projekt . '-urle-do-pingowania.txt';	
	}
	if (count(file($file1)) == 0) {
		$file = $projekt . '-urle.txt';
		$fp = fopen($file, 'rb');
		while (!feof($fp)) {
			$buforek[] = trim(fgets($fp));
		}
		fclose($fp);
		$file = $projekt . '-zaindeksowane-urle.txt';
		if (file_exists($file)) {
			$fp = fopen($file, 'rb');
			while (!feof($fp)) {
				$zaindeksowane[] = trim(fgets($fp));
			}
			fclose($fp);
			foreach ($zaindeksowane as $adres) {
				foreach ($buforek as $numer => $buf) {
				if ($adres == $buf) {
					unset($buforek[$numer]);
					}
				}
			}
			$buforek = array_filter($buforek);
		}
		$fp = fopen($file1, 'wb');
		$a = 0;
		foreach ($buforek as $adres) {
			if ($a==0) {
				fwrite($fp, $adres);
				$a++;
			} else {
				fwrite($fp, "\r\n" . $adres);
			}
		}
		fclose($fp);
	}
}

function dopisz($f, $buforek) { // dodanie nowego wiersza w pliku
	if (file_exists($f)) {
		$fp = fopen($f,'ab');
		if (count(file($f)) == '') {
			fwrite($fp, $buforek);
		} else {
			fwrite($fp, "\r\n" . $buforek);
		}
	} else {
		$fp = fopen($f,'wb');
		fwrite($fp, $buforek);
	}
	fclose($fp);
}

function usun_pierwszy($plik) { // usuniêcie pierwszego wiersza z pliku

	$file = $plik;
	$fp = fopen($file,'rb');
	$calosc = file($file);
	fclose($fp);
	$fp = fopen($file,'wb');
	for($i=1;$i<count($calosc);$i++) {fwrite($fp, $calosc[$i]);}
	fclose($fp);

}

if ($switch == 'eksport') {
	$zip = new ZipArchive();
	$zip->open($projekt . '.zip', ZipArchive::OVERWRITE);
	foreach ($pliki as $nazwa) {
		if (file_exists($nazwa)) {
			$zip->addFile($nazwa);
		}
	}
	$zip->close();
	header("Location: " . $projekt . '.zip');

} elseif ($switch == 'usun') {

	function usun($cos) {
		$file = 'projekty.txt';
		$fp = fopen($file, 'rb');
		while (!feof($fp)) {
			$buforek[] =  trim(fgets($fp));
		}
		fclose($fp);
		$fp = fopen($file, 'wb');
		if (!($buforek[0] == $cos)) {
			fwrite($fp, $buforek[0]);
			$j = 1;
		} else {
			fwrite($fp, $buforek[1]);
			$j = 2;
		}
		for($i=$j;$i<count($buforek);$i++) {
			if (!($buforek[$i] == $cos)) {
				fwrite($fp, "\r\n" . $buforek[$i]);
			}
		}
		fclose($fp);
	}
	
	$pliki[] = $projekt . '.zip';
	foreach ($pliki as $nazwa) {
		if (file_exists($nazwa)) {
			unlink($nazwa);
		}
	}
	usun($projekt);
	$projekt = 'nie wybrany';
	$reszta = '&import=true';
	
} elseif ($switch == 'wyczysc') {
	
$file = $_GET['plik'];
$fp = fopen($file,'wb');
fclose($fp);
	
} elseif ($switch == 'pobierz') {

	$plik_tmp = $_FILES['paczka']['tmp_name'];
	$plik_nazwa = $_FILES['paczka']['name'];
	$plik_rozmiar = $_FILES['paczka']['size'];
	$zip = new ZipArchive();   
	$zip->open($plik_tmp);
	$zip->extractTo('./');
	$zip->close();
	$f = 'projekty.txt';
	$projekt = substr($plik_nazwa, 0, -4);
	if (file_exists($f)) {
		$fp = fopen($f,'ab');
		if (count(file($f)) == '') {
			fwrite($fp, $projekt);
		} else {
			fwrite($fp, "\r\n" . $projekt);
		}
	} else {
		$fp = fopen($f,'wb');
		fwrite($fp, $projekt);
	}
	fclose($fp);
	$reszta = '&import=true';
	
} elseif ($switch == 'podglad') {

	$file = 'przelacznik.txt';
	$fp = fopen($file,'wb');
	fwrite($fp, $projekt);
	fclose($fp);

} elseif (($switch == 'kolejka') && (!($projekt == 'wszystkie'))) {

	if (($funkcja == 'sprawdzanie nowych') || ($funkcja == 'sprawdzanie wszystkich') || ($funkcja == 'ponowne sprawdzanie') || ($funkcja == 'sprawdzanie niezaindeksowanych')) {
		$f = 'kolejka-indeksowanie.txt';
		dopisz($f, $funkcja);
		dopisz($f, $projekt);
//		niezaindeksowane($projekt,'indeksacja');
	} elseif (($funkcja == 'pingowanie niezaindeksowanych') || ($funkcja == 'pingowanie wszystkich')) {
		$f = 'kolejka-pingowanie.txt';
		dopisz($f, $funkcja);
		dopisz($f, $projekt);
//		niezaindeksowane($projekt,'pingowanie');
	}
	$reszta = '&kolejka=true';
	
} elseif (($switch == 'kolejka') && ($projekt == 'wszystkie')) {

	$file = 'projekty.txt';
	$fp = fopen($file,'r');
	while (!feof($fp)) {
		$projekciki[] = trim(fgets($fp));
		}
	fclose($fp);
	foreach ($projekciki as $projekt) {
		if (($funkcja == 'sprawdzanie nowych') || ($funkcja == 'sprawdzanie wszystkich') || ($funkcja == 'ponowne sprawdzanie') || ($funkcja == 'sprawdzanie niezaindeksowanych')) {
			$f = 'kolejka-indeksowanie.txt';
			dopisz($f, $funkcja);
			dopisz($f, $projekt);
//			niezaindeksowane($projekt,'indeksacja');
		} elseif (($funkcja == 'pingowanie niezaindeksowanych') || ($funkcja == 'pingowanie wszystkich')) {
			$f = 'kolejka-pingowanie.txt';
			dopisz($f, $funkcja);
			dopisz($f, $projekt);
//			niezaindeksowane($projekt,'pingowanie');
		}
	}
	$reszta = '&kolejka=true';

} elseif ($switch == 'zkolejki') {

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
	$projekt = 'nie wybrany';
	$reszta = '&kolejka=true';

} elseif ($switch == 'notes') {

	$file = $projekt . '-notes.txt';
	$fp = fopen($file,'wb');
	fwrite($fp,$_GET['notatka']);
	fclose($fp);
	
} elseif ($switch == 'godzina') {

	$file = 'czas.txt';
	$fp = fopen($file,'wb');
	fwrite($fp,$_GET['znak'] . "\r\n" . $_GET['liczba']);
	fclose($fp);
	$reszta = '&czas=true';

} elseif ($switch == 'pingery') {

	$file = $_GET['plik'];
	$fp = fopen($file,'wb');
	fwrite($fp,$_GET['pingery']);
	fclose($fp);
	$reszta = '&pinger=true';
	
} elseif ($switch == 'wyborpingera') {

	$file = 'pinglist-' . $_GET['zestaw'] . '.txt';
	$fp = fopen($file,'rb');
	$a = fread($fp, filesize($file));
	fclose($fp);
	$file = 'pinglist-wzor.txt';
	$fp = fopen($file,'wb');
	fwrite($fp, $a);
	fclose($fp);
	$file = 'pinglist.txt';
	$fp = fopen($file,'wb');
	fwrite($fp, $a);
	fclose($fp);
	$reszta = '&pinger=true';
	
} else {
	if (!(file_exists('dc.txt'))) {
		$fp = fopen('dc-wzor.txt','rb');
		$a = fread($fp, filesize('dc-wzor.txt'));
		fclose($fp);
		$fp = fopen('dc.txt','wb');
		fwrite($fp, $a);
		fclose($fp);
	}
	if ($switch == 'indeksacja') {
		usun_pierwszy('dc.txt');
		if (($funkcja == 'sprawdzanie nowych') || ($funkcja == 'sprawdzanie wszystkich')) {
			if (!((count(file($projekt . '-urle.txt'))) == 0)) {
				$file = $projekt . '-urle.txt';
				$fp = fopen($file,'rb');
				$calosc = file($file);
				fclose($fp);
				$file = $projekt . '-urle-do-indeksacji.txt';
				if (!((file_exists($file)) && (!((filesize($file))==0)))) {
					$fp = fopen($file,'wb');
					for($i=0;$i<count($calosc);$i++) {fwrite($fp, $calosc[$i]);}
					fclose($fp);
					$file = $projekt . '-zaindeksowane-urle.txt';
					$fp = fopen($file,'wb');
					fclose($fp);
					$file = $projekt . '-raport-indeksacji.txt';
					$fp = fopen($file,'wb');
					fclose($fp);
				}
			}
		} elseif (($funkcja == 'ponowne sprawdzanie') || ($funkcja == 'sprawdzanie niezaindeksowanych')) {
			if (count(file($projekt . '-urle-do-indeksacji.txt')) == 0) {
				niezaindeksowane($projekt,'indeksacja');
			}
		}
		$file1 = 'przelacznik1.txt';
	} elseif ($switch == 'pinger') {
		if ($funkcja == 'pingowanie wszystkich') {
			$file = $projekt . '-urle.txt';
			$fp = fopen($file,'rb');
			$calosc = file($file);
			fclose($fp);
			$file = $projekt . '-urle-do-pingowania.txt';
			if ((!(file_exists($file))) || ((filesize($file)) == 0)) {
				$fp = fopen($file,'wb');
				for($i=0;$i<count($calosc);$i++) {fwrite($fp, $calosc[$i]);}
				fclose($fp);
			}
		} elseif ($funkcja == 'pingowanie niezaindeksowanych') {
			if ((!(file_exists($projekt . '-urle-do-pingowania.txt'))) || (count(file($projekt . '-urle-do-pingowania.txt')) == 0)) {
				niezaindeksowane($projekt,'pingowanie');
			}
		}
		$file1 = 'przelacznik2.txt';
	}
	$fp = fopen($file1,'wb');
	$zapis = $funkcja . "\r\n" . $projekt;
	fwrite($fp, $zapis);
	fclose($fp);
	$reszta = '&aktualne=true';
}

if (($switch == 'podglad') || ($switch == 'notes')) {
	header("Location: index.php?projekt=" . $projekt);
} elseif (!($switch == 'eksport')) {
	header("Location: konfig.php?projekt=" . $projekt . $reszta);
}

?>