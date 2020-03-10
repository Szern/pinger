<?php

include('menu.php');
include('user.php');

$file3 = 'bany.txt';	
if (file_exists($file3)) { 
	$fp3 = fopen($file3,'rb');
	$i = 0;
	while (!feof($fp3)) {
		$buforek = fgets($fp3);
		$bany[$i] = $buforek;
		$i++;
		}
	fclose($fp3);
	$i--;
	while ($i >= 0) {
	echo $bany[$i] . '<br />';
	$i--;
	}
} else {
  	$fp3 = fopen($file3,'wb');
    fclose($fp3);
}

?> 

</body>
</html>