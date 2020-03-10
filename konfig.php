<?php 

include('menu.php');
include('user.php');

if ($_GET['aktualne']==true) {

?>

<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div><input type="hidden" name="projekt" value="<?php echo $projekt; ?>" /></div>
		<div><input type="hidden" name="switch" value="wyczysc" /></div>
		<div><input type="hidden" name="plik" value="<?php echo $projekt; ?>-urle-do-indeksacji.txt" /></div>
		<div class="row">
			<input type="submit" value="wyczy¶æ" /> 
<?php
	$file = $projekt . '-urle-do-indeksacji.txt';
	if (file_exists($file)) {
		$fp = fopen($file,'rb');
		echo 'zawarto¶æ <a href="listing.php?projekt=' . $projekt . '&jaki=indeksacja">bufora indeksacji</a>: ' . count(file($file)) .' ';
		fclose($fp);
	} else {
		$fp = fopen($file,'wb');
		echo 'zawarto¶æ bufora indeksacji: 0';
		fclose($fp);
	}
?>
		</div>
	</form>
</div>

<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div><input type="hidden" name="projekt" value="<?php echo $projekt; ?>" /></div>
		<div><input type="hidden" name="switch" value="wyczysc" /></div>
		<div><input type="hidden" name="plik" value="<?php echo $projekt; ?>-urle-do-pingowania.txt" /></div>
		<div class="row">
			<input type="submit" value="wyczy¶æ" /> 
<?php
	$file = $projekt . '-urle-do-pingowania.txt';
	if (file_exists($file)) {
		$fp = fopen($file,'rb');
		echo 'zawarto¶æ <a href="listing.php?projekt=' . $projekt . '&jaki=pinger">bufora pingera</a>: ' . count(file($file)) .' ';
		fclose($fp);
	} else {
		$fp = fopen($file,'wb');
		echo 'zawarto¶æ bufora pingera: 0';
		fclose($fp);
	}
?>
		</div>
	</form>
</div>

<hr />

<strong>aktualne dzia³ania</strong>:<br />
indeksacja: <strong><?php echo $funkcja1; ?></strong>
<?php if (!($funkcja1 == 'wy³±czone')) { ?>
&nbsp;dla projektu <strong><?php echo $projekt1; ?></strong>
<?php } ?>
<br />
pingowanie: <strong><?php echo $funkcja2; ?></strong>
<?php if (!($funkcja2 == 'wy³±czone')) { ?>
&nbsp;dla projektu <strong><?php echo $projekt2; ?></strong>
<?php } ?>
<br /><br />

<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div><input type="hidden" name="switch" value="indeksacja" /></div>
		<div class="row">
			<input type="submit" value="zmieñ aktualne dzia³anie indeksacji" />&nbsp;na&nbsp;
    </div>
    <div class="row">
			<select name="funkcja">
				<option>sprawdzanie wszystkich</option>
				<option>sprawdzanie niezaindeksowanych</option>
				<option>wy³±czone</option>
			</select>
			dla projektu
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

<br />

<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div><input type="hidden" name="switch" value="pinger" /></div>
		<div class="row">
			<input type="submit" value="zmieñ aktualne dzia³anie pingera" />&nbsp;na&nbsp;
    </div>
    <div class="row">
			<select name="funkcja">
				<option>pingowanie wszystkich</option>
				<option>pingowanie niezaindeksowanych</option>
				<option>wy³±czone</option>
			</select>
			dla projektu
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

<br />

<?php } 
if ($_GET['kolejka']==true) {
?>

<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div><input type="hidden" name="projekt" value="<?php echo $projekt; ?>" /></div>
		<div><input type="hidden" name="switch" value="kolejka" /></div>
		<div class="row">
			<input type="submit" value="dodaj do kolejki" />&nbsp;na&nbsp;
    </div>
    <div class="row">
			<select name="funkcja">
				<option>sprawdzanie wszystkich</option>
				<option>sprawdzanie niezaindeksowanych</option>
				<option>pingowanie wszystkich</option>
				<option>pingowanie niezaindeksowanych</option>
			</select>
			dla projektu
			<select name="projekt">
				<option>wszystkie</option>
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

<br /><hr />

<?php

$licznik = 1;
$file = 'kolejka-indeksowanie.txt';
echo '<strong>Pliki w kolejce do indeksacji</strong>:<br />';
if ((file_exists($file)) && (!((filesize($file)) == 0))) {
	$fp = fopen($file,'r');
	while (!feof($fp)) {
		$buforek1 = fgets($fp);
		$buforek2 = fgets($fp);
		echo $licznik . '. projekt: ' . $buforek2 . ' - funkcja: ' . $buforek1 . '<br />';
		$licznik++;
	}
	fclose($fp);	
} else {
	echo 'Brak plików w kolejce';
}
?>
<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div><input type="hidden" name="switch" value="zkolejki" /></div>
		<div><input type="hidden" name="kolejka" value="indeksowanie" /></div>
		<div class="row">
			<input type="submit" value="usuñ ostatni projekt z kolejki indeksacji" /> 
		</div>
	</form>
</div>
<br />
<?php
$licznik = 1;
$file = 'kolejka-pingowanie.txt';
echo '<strong>Pliki w kolejce do pingowania</strong>:<br />';
if ((file_exists($file)) && (!((filesize($file)) == 0))) {
	$fp = fopen($file,'r');
	while (!feof($fp)) {
		$buforek1 = fgets($fp);
		$buforek2 = fgets($fp);
		echo $licznik . '. projekt: ' . $buforek2 . ' - funkcja: ' . $buforek1 . '<br />';
		$licznik++;
	}
	fclose($fp);	
} else {
	echo 'Brak plików w kolejce';
}
?>
<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div><input type="hidden" name="switch" value="zkolejki" /></div>
		<div><input type="hidden" name="kolejka" value="pingowanie" /></div>
		<div class="row">
			<input type="submit" value="usuñ ostatni projekt z kolejki pingowania" /> 
		</div>
	</form>
</div>

<hr /><br />

<?php } 
if ($_GET['import']==true) {
?>

<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div><input type="hidden" name="switch" value="eksport" /></div>
		<div class="row">
			<input type="submit" value="pobierz projekt: " />&nbsp;
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

<br />

<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div><input type="hidden" name="switch" value="usun" /></div>
		<div class="row">
			<input type="submit" value="usuñ projekt: " />&nbsp;
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

<br />

<div class="pudlo">
  <form enctype="multipart/form-data" action="funkcje.php" method="post">
		<div><input type="hidden" name="switch" value="pobierz" /></div>
		<div class="row">
			<input type="submit" value="wczytaj projekt: " />
    </div>
    <div class="row">
      <span class="formw"><input type="file" name="paczka" size="100" /></span>
    </div>
	</form>
</div>

<br /><hr />

<?php } 
if ($_GET['pinger']==true) {
	echo '<span style="color:red;">aktywny zestaw:</span><br />';
	$file = 'pinglist-wzor.txt';
	$fp = fopen($file,'rb');
	while (!feof($fp)) {
		$buf[] =  trim(fgets($fp));
	}
	fclose($fp);
	foreach ($buf as $b) {
		echo $b . '<br />';
	}
	echo '<br />';
	$l = 1;
	$file = 'pinglist-' . $l . '.txt';
	while (file_exists($file)) {
?>

	<div id="pingery">
		<form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
			<div><input type="hidden" name="projekt" value="<?php echo $projekt; ?>" /></div>
			<div><input type="hidden" name="switch" value="pingery" /></div>
			<div><input type="hidden" name="plik" value="<?php echo $file; ?>" /></div>
			<div class="row">
				<textarea name="pingery" cols="50" rows ="3"><?php
		$fp = fopen($file,'rb');
		readfile($file);
		fclose($fp);
	?></textarea>
			</div>
			<div class="row">
				<input type="submit" value="zapisz <?php echo $l; ?> zestaw" />
			</div>
		</form>
	</div>

<?php 
		$l++;
		$file = 'pinglist-' . $l . '.txt';
	}
?>

	<div id="pingery">
		<form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
			<div><input type="hidden" name="projekt" value="<?php echo $projekt; ?>" /></div>
			<div><input type="hidden" name="switch" value="pingery" /></div>
			<div><input type="hidden" name="plik" value="<?php echo $file; ?>" /></div>
			<div class="row">
				<textarea name="pingery" cols="50" rows ="3"></textarea>
			</div>
			<div class="row">
				<input type="submit" value="zapisz <?php echo $l; ?> zestaw" />
			</div>
		</form>
	</div>

<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div><input type="hidden" name="projekt" value="<?php echo $projekt; ?>" /></div>
		<div><input type="hidden" name="switch" value="wyborpingera" /></div>
		<div class="row">
			<input type="submit" value="ustaw aktywny zestaw " />&nbsp;
    </div>
    <div class="row">
			<select name="zestaw">
<?php
	$l = 1;
	$file = 'pinglist-' . $l . '.txt';
	while (file_exists($file)) {
		echo '<option>' . $l . '</option>';
		$l++;
		$file = 'pinglist-' . $l . '.txt';
	}
?>
			</select>
		</div>
	</form>
</div>

<br /><hr />

<?php } 
if ($_GET['czas']==true) {
?>

<div class="pudlo">
czas serwera: <?php echo date("d.m.Y H:i:s"); ?><br />
czas pingera: <?php

$f = 'czas.txt';
if (file_exists($f)) {
	$fp = fopen($f,'rb');
	$znak = trim(fgets($fp));
	$czas = (int)fgets($fp);
	fclose($fp);
}
if ($znak=='+') {
	$czas = (date("H")+$czas)%24;
	if ($czas<10) {$czas = '0' . $czas;}
} elseif ($znak=='-') {
	$czas = (date("H")-$czas);
	if ($czas<0) {
		$czas = 24+$czas;
		if ($czas<10) {$czas = '0' . $czas;}
	}
} else {
	$czas = date("H");
}
echo date("d.m.Y ") . $czas . date(":i:s"); ?><br />

  <form enctype="application/x-www-form-urlencoded" action="funkcje.php" method="get">
		<div><input type="hidden" name="switch" value="godzina" /></div>
		<div class="row">
			<input type="submit" value="zweryfikuj czas serwera o" />
    </div>
		<div class="row">
			<select name="znak">
				<option>+</option>
				<option>-</option>
			</select>
		<div class="row">
			<select name="liczba">
				<option>1</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
				<option>6</option>
				<option>7</option>
				<option>8</option>
				<option>9</option>
				<option>10</option>
				<option>11</option>
				<option>12</option>
				<option>13</option>
				<option>14</option>
				<option>15</option>
				<option>16</option>
				<option>17</option>
				<option>18</option>
				<option>19</option>
				<option>20</option>
				<option>21</option>
				<option>22</option>
				<option>23</option>
				<option>24</option>			
			</select>
    </div>
	</form>
</div>

<?php } ?>

</body>
</html>