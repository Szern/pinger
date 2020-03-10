<?php

$kontrolka = $_GET['kontrolka'];
if ($kontrolka == '') {
	$kontrolka = $_POST['kontrolka'];
}

$projekt = $_GET['projekt'];
if ($projekt == '') {
	$projekt = $_POST['projekt'];
}

if (!($kontrolka == '0')) {
	include('menu.php');
	include('user.php');
}

if (($kontrolka == '1') || ($kontrolka == '0')) {

?>

<div class="pudlo">
  <form enctype="multipart/form-data" action="dodaj-projekt.php" method="POST">
		<div><input type="hidden" name="kontrolka" value="2" /></div>
    <div class="row">
      <span class="label">nazwij projekt: </span><span
class="formw"><input type="text" name="projekt" size="50" /></span>
    </div>
		<div>
		  <span class="label">podaj adres sitemapy (zaczynaj±c od "http://"): </span><span
class="formw"><input type="text" name="mapa1" size="50" /></span> lub
		</div>
    <div class="row">
      <span class="label">wczytaj listê plików z dysku: </span><span
class="formw"><input type="file" name="mapa3" size="100" /></span> lub
    </div>
		<div>
		  <span class="label">wklej adresy podstron (jeden adres w jednym wierszu, ograniczona liczba w zale¿no¶ci od serwera):</span><span
class="formw"><textarea name="mapa2" cols="125" rows ="17"></textarea></span>
		</div>
		<div>
			<span class="label">listuj mapê po za³adowaniu</span>
			<span class="formw"><input type="checkbox" name="listing" value="1" /></span>
    </div>
		<div>
			<input type="submit" value="dodaj projekt i wczytaj sitemapê" />
    </div>
	</form>
</div>

<?php
if ($kontrolka == '0') {
?>
<div class="pudlo">
  <form enctype="multipart/form-data" action="funkcje.php" method="post">
		<div><input type="hidden" name="switch" value="pobierz" /></div>
		<div class="row">
			<input type="submit" value="wczytaj projekt: " />
      <span class="formw"><input type="file" name="paczka" size="100" /></span>
    </div>
	</form>
</div>
<?php
}

} else {

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

	function url_exists($url){
			$url = str_replace("http://", "", $url);
			if (strstr($url, "/")) {
					$url = explode("/", $url, 2);
					$url[1] = "/".$url[1];
			} else {
					$url = array($url, "/");
			}

			$fh = fsockopen($url[0], 80);
			if ($fh) {
					fputs($fh,"GET ".$url[1]." HTTP/1.1\nHost:".$url[0]."\n\n");
					if (fread($fh, 22) == "HTTP/1.1 404 Not Found") { return FALSE; }
					else { return TRUE;    }

			} else { return FALSE;}
	}

// zapisujemy nowy projekt

	if (!(file_exists($projekt . '-urle.txt'))) {

		$mapa1 = $_POST['mapa1'];
		$mapa2 = $_POST['mapa2'];
		$plik_tmp = $_FILES['mapa3']['tmp_name'];
		$plik_nazwa = $_FILES['mapa3']['name'];
		$plik_rozmiar = $_FILES['mapa3']['size'];
		
		$linki = $projekt . '-urle.txt'; // plik do zapisu mapy;
		
		if (!($mapa1 == '')) { // wczytujemy sitemapê
			if (url_exists($mapa1)) {
				if ((substr($mapa1, -2)) == 'gz') {
					$sitemapa = implode("", gzfile($mapa1)); // wczytanie urli z sitemapy gz
				} elseif ((substr($mapa1, -3)) == 'xml') {
					$sitemapa = implode("", file($mapa1)); // wczytanie urli z sitemapy xml
				} else {
					exit('Nierozpoznany format mapy');
				}
				if (!((preg_match("/The document has moved/", $sitemapa)) || (preg_match("/Bad Request/", $sitemapa)))) {
					// wyci±gniêcie linków z sitemapy
					$ilosc = substr_count($sitemapa, "<loc>") - 1;
					for ( $i = 0; ($i < ($ilosc + 1)) ; $i++ ) {
						$sitemapa = substr($sitemapa, (strpos($sitemapa, "<loc>") + 5));
						$buforek = substr($sitemapa, 0, (strpos($sitemapa, '</loc>')));	
						dopisz($linki, $buforek);
					}

					echo 'wczytano mapê<br /><br />';
					$file = 'projekty.txt';	
					$fp = fopen($file,'ab');
					if (!((filesize($file)) == 0)) {
						$do_pliku = "\r\n" . $projekt;
					} else {
					$do_pliku = $projekt;
					}
					fwrite($fp, $do_pliku);
					fclose($fp);
				} else {
					exit('Nie znaleziono pliku mapy (sprawd¼ z www i bez)');
				}
			} else { exit('Nie ma takiego pliku ' . $mapa1); }
		} elseif (!($mapa2 =='')) { // dostali¶my linki
			dopisz($linki, $mapa2);
			$file = 'projekty.txt';	
			$fp = fopen($file,'ab');
			if (!((filesize($file1)) == 0)) {
				$do_pliku = "\r\n" . $projekt;
			} else {
			$do_pliku = $projekt;
			}
			fwrite($fp, $do_pliku);
			fclose($fp);
		} elseif (!($plik_rozmiar == 0)) {
			if(is_uploaded_file($plik_tmp)) {
				move_uploaded_file($plik_tmp, "$linki"); 	
			}
			$file = 'projekty.txt';	
			$fp = fopen($file,'ab');
			if (!((filesize($file)) == 0)) {
				$do_pliku = "\r\n" . $projekt;
			} else {
			$do_pliku = $projekt;
			}
			fwrite($fp, $do_pliku);
			fclose($fp);
		} else {
		exit('Nie poda³e¶ mapy ani linków!');
		}
	} else { echo 'Mapa ju¿ by³a wczytana.<br /><br />'; }

// usuniêcie pustych liniii
	
		$fp = fopen($linki,'rb');
		$calosc = file_get_contents($linki);
		fclose($fp);
		$file1 = 'roboczy.txt';
		$fp1 = fopen($file1,'wb');
		fwrite($fp1, $calosc);
		fclose($fp1);
		$fp1 = fopen($file1,'rb');
		$fp = fopen($linki,'wb');
		while (!feof($fp1)) {
			$buforek = fgets($fp1);
			if (strlen(trim($buforek))) {
			fwrite($fp, $buforek);
			}
		}
		fclose($fp);
		fclose($fp1);
		unlink($file1);
	
// listing mapy

	$listing = $_GET['listing'];
	if ($listing == '') {
		$listing = $_POST['listing'];
	}
	$file3 = $projekt . '-urle.txt';
	$fp3 = fopen($file3,'r');
	echo 'liczba podstron w mapie: ' . count(file($file3)) . '<br /><br />';
	if ($listing) {
		while (!feof($fp3)) {
			$buforek = fgets($fp3);
			echo $buforek . '<br />';
			}
	}
	fclose($fp3);
}
?>

</body>
</html>