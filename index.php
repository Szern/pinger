<?php 

/*

Pinger ver. 0.2.2 - skrypt do indeksacji
ostatnia modyfikacja: 2010-08-19
copyright 2010 Marcin Kowol
marcin@kowol.pl

This file is part of Pinger.

Pinger is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

Pinger is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Pinger; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Ten plik jest czê¶ci± Pinger'a.

Pinger jest wolnym oprogramowaniem; mo¿esz go rozprowadzaæ dalej
i/lub modyfikowaæ na warunkach Powszechnej Licencji Publicznej GNU,
wydanej przez Fundacjê Wolnego Oprogramowania - wed³ug wersji 2 tej
Licencji lub (wed³ug twojego wyboru) której¶ z pó¼niejszych wersji.

Niniejszy program rozpowszechniany jest z nadziej±, i¿ bêdzie on
u¿yteczny - jednak BEZ JAKIEJKOLWIEK GWARANCJI, nawet domy¶lnej
gwarancji PRZYDATNO¦CI HANDLOWEJ albo PRZYDATNO¦CI DO OKRE¦LONYCH
ZASTOSOWAÑ. W celu uzyskania bli¿szych informacji siêgnij do
Powszechnej Licencji Publicznej GNU.

Z pewno¶ci± wraz z niniejszym programem otrzyma³e¶ te¿ egzemplarz
Powszechnej Licencji Publicznej GNU (GNU General Public License);
je¶li nie - napisz do Free Software Foundation, Inc., 59 Temple
Place, Fifth Floor, Boston, MA  02110-1301  USA

*/

// pierwsze uruchomienie

if (!(file_exists('projekty.txt'))) { 
	header("Location: dodaj-projekt.php?kontrolka=0");
}
if (!(file_exists('przelacznik.txt'))) {
	$fp = fopen('projekty.txt','rb');
	$projekt= fgets($fp);
	fclose($fp);
	$fp = fopen('przelacznik.txt','wb');
	fwrite($fp, 'wy³±czone' . "\r\n" . $projekt);
	fclose($fp);
}

include('menu.php');
include('user.php');

if (file_exists($projekt . '-urle.txt')) {
	$wmapie = count(file($projekt . '-urle.txt'));
	echo 'liczba <a href="listing.php?projekt=' . $projekt . '&jaki=mapa">podstron w mapie: ' . $wmapie . '</a><br /><br />';

	$file = $projekt . '-raport-indeksacji.txt';
	if (file_exists($file)) {
		$zaindeksowane = count(file($file));
		echo 'liczba <a href="listing.php?projekt=' . $projekt . '&jaki=raport"> zaindeksowanych podstron: ' . $zaindeksowane . '</a><br />';
	} else {
		echo 'liczba zaindeksowanych podstron: 0 <br />';
		$zaindeksowane = 0;
	}
	$niezaindeksowane = $wmapie - $zaindeksowane;
	echo 'liczba niezaindeksowanych podstron: ' . $niezaindeksowane . '<br /><br />';
	$file = $projekt . '-urle-do-pingowania.txt';
	if (file_exists($file)) {
		echo 'zawarto¶æ <a href="listing.php?projekt=' . $projekt . '&jaki=pinger">bufora pingera</a>: ' . count(file($file)) .'<br />';
	} else {
		echo 'zawarto¶æ bufora pingera: 0<br />';
	}
	$file = $projekt . '-urle-do-indeksacji.txt';
	if (file_exists($file)) {
		echo 'zawarto¶æ <a href="listing.php?projekt=' . $projekt . '&jaki=indeksacja">bufora indeksacji</a>: ' . count(file($file)) .'<br /><br />';
	} else {
		echo 'zawarto¶æ bufora indeksacji: 0<br /><br />';
	}
	?>
	<div id="notes">
		<form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
			<div><input type="hidden" name="projekt" value="<?php echo $projekt; ?>" /></div>
			<div><input type="hidden" name="switch" value="notes" /></div>
			<div class="row">
				<textarea name="notatka" cols="50" rows ="8"><?php
		$file = $projekt . '-notes.txt';
		if (file_exists($file)) {
			$fp = fopen($file,'rb');
			readfile($file);
			fclose($fp);
		} elseif (!($projekt == 'nie wybrany')) {
			$fp = fopen($file,'wb');
			fclose($fp);	
		}
	?></textarea>
			</div>
			<div class="row">
				<input type="submit" value="zapisz" />
			</div>
		</form>
	</div>
<?php
}
?>

<hr />

<?php

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
	echo '<span style="color:#9f9f9f">' . $linia . '</span>';
}

?>

<br />
<strong>indeksacja</strong>: aktualnie <span style="color:red;"><?php echo $funkcja1; ?></span>
<?php if (!($funkcja1 == 'wy³±czone')) { ?>
&nbsp;dla projektu <span style="color:red;"><?php echo $projekt1; ?></span><br />
<span style="color:#9f9f9f">nastêpny projekt w kolejce do indeksacji: 
<?php

	$file = 'kolejka-indeksowanie.txt';
	if ((file_exists($file)) && (!((filesize($file)) == 0))) {
		$fp = fopen($file,'r');
		$buforek1 = fgets($fp);
		$buforek2 = fgets($fp);
		echo $buforek2 . ' - funkcja: ' . $buforek1;
		fclose($fp);
	} else {
		echo 'brak plików';
	}
}

?>

</span>
<br />
<strong>pingowanie</strong>: aktualnie <span style="color:red;"><?php echo $funkcja2; ?></span>
<?php if (!($funkcja2 == 'wy³±czone')) { ?>
&nbsp;dla projektu <span style="color:red;"><?php echo $projekt2; ?></span><br />
<span style="color:#9f9f9f">nastêpny projekt w kolejce do pingowania: 
<?php

	$file = 'kolejka-pingowanie.txt';
	if ((file_exists($file)) && (!((filesize($file)) == 0))) {
		$fp = fopen($file,'r');
		$buforek1 = fgets($fp);
		$buforek2 = fgets($fp);
		echo $buforek2 . ' - funkcja: ' . $buforek1;
		fclose($fp);
	} else {
		echo 'brak plików';
	}
}

?>

</span><br /><br />

<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div class="row">
			<input type="hidden" name="switch" value="podglad" />
			<input onMouseOver="funkcja1" onMouseDown="funkcja2" 
onMouseOut="funkcja0" type="submit" value="zmieñ podgl±d" /> z projektu <span style="color:red;"><?php echo $projekt; ?></span> na projekt: 
    </div>
    <div class="row">
			<select name="projekt">
<?php
	$file = 'projekty.txt';
	$fp = fopen($file,'r');
	while (!feof($fp)) {
		$buforek = fgets($fp);
		echo '<option>' . $buforek . '</option>';
		}
	fclose($fp);
?>
			</select>
		</div>
	</form>
</div>

</body>
</html>