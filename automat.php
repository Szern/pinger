<?php

// weryfikacja czasu

$teraz = czas();

// KONSERWACJA

if (((czas("h")) == 23) && (date("i") == 50)) {

	// uzupe³nienie statystyk

	$data = czas();
	$file = 'projekty.txt';
	$fp = fopen($file,'rb');
	$i = 0;
	while (!feof($fp)) {
		$buforek[$i] = fgets($fp);
		$i++;
		}
	fclose($fp);
	$i--;
	$fp = fopen('statystyki.txt','ab');
	while ($i>=0) {
		$file0 = $buforek[$i] . '-urle.txt';
		$file2 = $buforek[$i] . '-zaindeksowane-urle.txt';
		if (file_exists($file0)) {
			$urle = count(file($file0));
		} else {
			$urle = 0;
		}
		if (file_exists($file2)) {
			$zaindeksowane = count(file($file2));
		} else {
			$zaindeksowane = 0;
		}
		fwrite($fp, "\r\n" . $data . "\r\n" . $buforek[$i] . "\r\n" . $urle . "\r\n" . $zaindeksowane);
		$i--;
	}
	fwrite($fp, "\r\n");
	fclose($fp);

	// przegranie logu do archiwum

	$file1 = 'bany.txt';
	$fp1 = fopen($file1,'rb');
	$bany = fread($fp1, filesize($file1));
	fclose($fp1);
	$file1 = 'bany-log.txt';
	$fp1 = fopen($file1,'ab');
	fwrite($fp1, $bany);
	fclose($fp1);

	// wyzerowanie logu

	$file1 = 'bany.txt';
	$fp1 = fopen($file1,'wb');
	fclose($fp1);

}

// kontrolka nie pozwalaj±ca uruchomiæ dwa razy jednocze¶nie skryptu

$f = 't.txt';
if (file_exists($f)) {
	$fp = fopen($f,'rb');
	$t = (int)fgets($fp);
	fclose($fp);
	if ($t < 15) {
		$t++;
		$fp = fopen($f,'wb');
		fwrite($fp, (string)$t);
		fclose($fp);
		exit();
	} else {
		unlink('t.txt');
	}
} else {
	$fp = fopen($f,'wb');
	fwrite($fp, '1');
	fclose($fp);
}

// zabezpieczenie przed banowaniem

$f = 's.txt';
if (file_exists($f)) {
	$fp = fopen($f,'rb');
	$s = (int)fgets($fp);
	fclose($fp);
} else {
	$s = 1;
	$fp = fopen($f,'wb');
	fwrite($fp, (string)$s);
	fclose($fp);
}
if (($s > 50) && ($s < 62)) {
	$fp = fopen('bany.txt','ab');
	fwrite($fp, "\r\n" . 'przerwa ' . $s);
	fclose($fp);
	$s++;
	$fp = fopen($f,'wb');
	fwrite($fp, (string)$s);
	fclose($fp);
	$przerwa = true;
} elseif ($s > 61) {
	$s = 1;
	$fp = fopen($f,'wb');
	fwrite($fp, (string)$s);
	fclose($fp);
}

// wczytanie funkcji i projektów

$fp = fopen('przelacznik1.txt','rb');
$funkcja1 = trim(fgets($fp));
$projekt1 = trim(fgets($fp));
fclose($fp);
$fp = fopen('przelacznik2.txt','rb');
$funkcja2 = trim(fgets($fp));
$projekt2 = trim(fgets($fp));
fclose($fp);

// tabela z useragentami

$user_agents = array(
	"Science Traveller International 1X/1.0",
	"Mozilla/3.0 (compatible)",
	"amaya/9.52 libwww/5.4.0 ",
	"amaya/9.51 libwww/5.4.0",
	"amaya/9.1 libwww/5.4.0",
	"amaya/6.2 libwww/5.3.1",
	"AmigaVoyager/3.4.4 (MorphOS/PPC native)",
	"xChaos_Arachne/5.1.89;GPL,386+",
	"Ubuntu APT-HTTP/1.3 (0.7.23.1ubuntu2)",
	"Ubuntu APT-HTTP/1.3",
	"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.3 (Change: 287 c9dfb30)",
	"Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.2 (Change: 0 )",
	"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser;
Avant Browser; .NET CLR 1.0.3705; .NET CLR 1.1.4322; 
Media Center PC 4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
	"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322; FDM)",
	"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; Avant Browser [avantbrowser.com]; Hotbar 4.4.5.0)",
	"Amiga-AWeb/3.5.07 beta",
	"Mozilla/6.0; (Spoofed by Amiga-AWeb/3.5.07 beta)",
	"MSIE/6.0; (Spoofed by Amiga-AWeb/3.4APL)",
	"gnome-vfs/2.12.0 neon/0.24.7",
	"bluefish 0.6 HTML editor",
	"Mozilla/4.61 [en] (X11; U; ) - BrowseX (2.0.0 Windows)",
	"Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en; rv:1.8.1.14) Gecko/20080409 Camino/1.6 (like Firefox/2.0.0.14)",
	"Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en; rv:1.8.1.6) Gecko/20070809 Camino/1.5.1",
	"Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.0.1) Gecko/20060118 Camino/1.0b2+",
	"Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.5b) Gecko/20030917 Camino/0.7+",
	"Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US; rv:1.0.1) Gecko/20021104 Chimera/0.6",
	"Mozilla/4.08 (Charon; Inferno)",
	"Mozilla/2.0 compatible; Check&Get 1.14 (Windows NT)",
	"Chimera/2.0alpha",
	"Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.1 (KHTML, like Gecko) Chrome/5.0.322.2 Safari/533.1",
	"Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_2; en-US) AppleWebKit/532.9 (KHTML, like Gecko) Chrome/5.0.307.11 Safari/532.9",
	"Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/532.5 (KHTML, like Gecko) Chrome/4.0.249.78 Safari/532.5",
	"Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/532.4 (KHTML, like Gecko) Chrome/4.0.233.0 Safari/532.4",
	"Mozilla/5.0 (X11; U; Linux i686 (x86_64); en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.198.0 Safari/532.0",
	"Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; Valve Steam GameOverlay; ) AppleWebKit/532.1 (KHTML, like Gecko) Chrome/3.0.195.24 Safari/532.1",
	"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13",
	"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.195.33 Safari/532.0",
	"Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.53 Safari/525.19",
	"Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_7; en-US) AppleWebKit/531.0 (KHTML, like Gecko) Chrome/3.0.183 Safari/531.0",
	"Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.53 Safari/525.19",
	"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.36 Safari/525.19",
	"Mozilla/5.0 (Linux; U; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13",
	"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13",
	"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13",
	"Contiki/1.0 (Commodore 64; http://dunkels.com/adam/contiki/)",
	"Contiki/1.0 (Commodore 64; http://dunkels.com/adam/contiki/)",
	"curl/7.7.2 (powerpc-apple-darwin6.0) libcurl 7.7.2 (OpenSSL 0.9.6b)",
	"Democracy/0.8.1 (http://www.participatoryculture.org)",
	"Dillo/0.8.6",
	"Dillo/2.0",
	"Dillo/0.8.5-i18n-misc",
	"Dillo/0.8.5-pre",
	"Dillo/0.8.3",
	"Dillo/0.8.2",
	"Dillo/0.8.2",
	"Dillo/0.6.6",
	"DocZilla/1.0 (Windows; U; WinNT4.0; en-US; rv:1.0.0) Gecko/20020804",
	"edbrowse/2.2.10",
	"ELinks/0.12~pre2.dfsg0-1ubuntu1-lite (textmode; Debian; Linux 2.6.32-4-jolicloud i686; 143x37-2)",
	"ELinks/0.12pre5.GIT (textmode; CYGWIN_NT-6.1 1.7.1(0.218/5/3) i686; 80x24-2)",
	"ELinks/0.11.3-5ubuntu2-lite (textmode; Debian; Linux 2.6.24-19-generic i686; 126x37-2)",
	"ELinks/0.11.4-2 (textmode; Debian; GNU/kFreeBSD 6.3-1-486 i686; 141x21-2)",
	"ELinks (0.4.3; NetBSD 3.0.2_PATCH sparc64; 141x19)",
	"ELinks/0.10.4-7ubuntu1-debian (textmode; Linux 2.6.12-10-k7-smp i686; 80x24-2)",
	"ELinks/0.10.5 (textmode; CYGWIN_NT-5.0 1.5.18(0.132/4/2) i686; 143x51-2)",
	"ELinks (0.4.2; Linux; )",
	"Emacs-W3/4.0pre.46 URL/p4.0pre.46 (i686-pc-linux; X11)",
	"Mozilla/5.0 (X11; U; Linux i686; en-us) AppleWebKit/531.2+ (KHTML, like Gecko) Safari/531.2+ Epiphany/2.29.5",
	"Mozilla/5.0 (X11; U; Linux i686; en; rv:1.9.0.11) Gecko/20080528 Epiphany/2.22 Firefox/3.0",
	"Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/420+ (KHTML, like Gecko)",
	"Mozilla/5.0 (X11; U; Linux x86_64; c) AppleWebKit/525.1+ (KHTML, like Gecko, Safari/525.1+) epiphany",
	"Mozilla/5.0 (X11; U; Linux x86_64; en; rv:1.8.1.4) Gecko/20061201 Epiphany/2.18 Firefox/2.0.0.4 (Ubuntu-feisty)",
	"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.5) Gecko/20060731 Epiphany/2.14 Firefox/1.5.0.5",
	"Mozilla/5.0 (X11; U; Linux i686; en; rv:1.8.1) Gecko/20061203 Epiphany/2.16 Firefox/2.0",
	"Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.8.0.1) Gecko/Debian-1.8.0.1-5 Epiphany/1.8.5",
	"Mozilla/5.0 (X11; U; Linux i686; cs-CZ; rv:1.7.13) Gecko/20060418 Epiphany/1.8.2 (Ubuntu) (Ubuntu package 1.0.8)",
	"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.3) Gecko/20040924 Epiphany/1.4.4 (Ubuntu)",
	"Mozilla/5.0 (X11; U; FreeBSD i386; en-US; rv:1.7) Gecko/20040628 Epiphany/1.2.6",
	"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4.1) Gecko/20031030 Epiphany/1.0.8",
	"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4.1) Gecko/20031114 Epiphany/1.0.4",
	"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4) Gecko/20030704 Epiphany/0.9.2",
	"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4) Gecko/20030703 Epiphany/0.8.4",
	"fetch libfetch/2.0",
	"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8) Gecko/20051111 Firefox/1.5 BAVM/1.0.0",
	"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8 ",
	"Mozilla/5.0 (X11; U; Linux armv61; en-US; rv:1.9.1b2pre) Gecko/20081015 Fennec/1.0a1",
	"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.2pre) Gecko/20100225 Ubuntu/9.10 (karmic) Namoroka/3.6.2pre",
	"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 ",
	"Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.1.8) Gecko/20100215 Solaris/10.1 (GNU) Superswan/3.5.8 (Byte/me)",
	"Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10.5; en-US; rv:1.9.0.3) Gecko/2008092414 Firefox/3.0.3 ",
	"Mozilla/5.0 (X11; U; OpenBSD i386; en-US; rv:1.8.1.14) Gecko/20080821 Firefox/2.0.0.14",
	"Mozilla/5.0 (X11; U; Darwin Power Macintosh; en-US; rv:1.8.0.12) Gecko/20070803 Firefox/1.5.0.12 Fink Community Edition",
	"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.13) Gecko/20060410 Firefox/1.0.8",
	"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.3) Gecko/20041002 Firefox/0.10.1",
	"Mozilla/5.0 (X11; U; SunOS sun4m; en-US; rv:1.4b) Gecko/20030517 Mozilla Firebird/0.6",
	"Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.3a) Gecko/20021207 Phoenix/0.5",
	"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.2b) Gecko/20020923 Phoenix/0.1",
	"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.3) Gecko/2008100716 Firefox/3.0.3 Flock/2.0",
	"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.4) Gecko/20060612 Firefox/1.5.0.4 Flock/0.7.0.17.1",
	"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8b5) Gecko/20051019 Flock/0.4 Firefox/1.0+",
//		"", Galeon
);

// funkcja pobierania strony z Google dla sprawdzania

function curl_get_data($url, $datacenter) {

	global $user_agents;
	$headers = array(
		"GET " . $url . " HTTP/1.1",
		"Host: www.google.com",
		"User-Agent: " . $user_agents[rand(0, count($user_agents)-1)],
		"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		"Accept-Language: pl,en-us;q=0.7,en;q=0.3",
		"Accept-Encoding: gzip,deflate",
		"Accept-Charset: ISO-8859-2,utf-8;q=0.7,*;q=0.7",
		"Connection: close",
	);

//	print_r($headers);
	$ch  = curl_init("http://" . $datacenter . '/' . $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	curl_setopt($ch, CURLOPT_HEADER, $headers);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$file = 'ip.txt';
  if (file_exists($file)) {
		$fp = fopen($file,'rb');
		$ip = trim(fgets($fp));
		fclose($fp);
		curl_setopt($ch, CURLOPT_INTERFACE, $ip);
	}
	$res = curl_exec($ch) ;
	curl_close($ch) ;
//	echo $res;
	return $res;

}

function usun_pierwszy($plik) { // usuniêcie sprawdzonego elementu z bufora
	$file = $plik;
	$fp = fopen($file,'rb');
	$calosc = file($file);
	fclose($fp);
	$fp = fopen($file,'wb');
	for($i=1;$i<count($calosc);$i++) {
		fwrite($fp, $calosc[$i]);
	}
	fclose($fp);

}

function usun_dc($ip) { // usuwanie zbanowanego dc z pliku
	$file = 'dc-wzor.txt';
	$fp = fopen($file, 'rb');
	while (!feof($fp)) {
			$buforek[] = trim(fgets($fp));
			$licznik++;
		}
	fclose($fp);
	$fp = fopen($file, 'wb');
	$i = 0;
	if ($buforek[$i] == $ip) {
		$i++;
	}
	fwrite($fp, $buforek[$i]);
	for ($j = $i + 1; $j<count($buforek); $j++) {
		if (!($buforek[$j] == $ip)) {
			fwrite($fp, "\r\n" . $buforek[$j]);
		}
	}
	fclose($fp);
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

function policz_plik($f) { // policzenie wierszy w pliku
	if (file_exists($f)) {
		return count(file($f));
	} else {
		return 0;
	}
}

function zwieksz_licznik() { // zwiêkszenie licznika odpytañ
	$f = 's.txt';
	if (file_exists($f)) {
		$fp = fopen($f,'rb');
		$s = (int)fgets($fp);
		fclose($fp);
		if ($s > 50) {
		if (file_exists('t.txt')) { unlink('t.txt'); };
		exit();
		} else {
			$s++;
			$fp = fopen($f,'wb');
			fwrite($fp, (string)$s);
			fclose($fp);
		}
	} else {
		$fp = fopen($f,'wb');
		fwrite($fp, '1');
		fclose($fp);
	}
}

function pinger($projekt, $line) {

	$file = $projekt . '-urle-do-pingowania.txt';
	$fp = fopen($file,'r');
	$blogUrl = trim(fgets($fp));
	fclose($fp);
// 	wyci±gniêcie title ze strony
	$data = curl_get_data($adres, 'string');
	$start = strpos($data, '<title>') + 7;
	$end = strpos($data, '</title>');
	$dlugosc = $end - $start;
	if (!(substr($data, $start, $dlugosc))) {
		$title = ' ';
	} else {
		$title = substr($data, $start, $dlugosc);
	}
	$blogTitle = strtolower(strtr($title,"¡ÆÊ£ÑÓ¦¯¬±æê³ñó¶¿¼","ACELNOSZZacelnoszz"));
	$replacementCount=0;
	$userAgent = $user_agents[rand(0, count($user_agents)-1)];
	$line = trim($line);
	$host = $line;
	$host=preg_replace('/^.*http:\/\//','',$host); // Delete anything before http://
	$host=preg_replace('/\/.*$/','',$host); // Delete anything after behind the hostname
	$path=$line;
	$path=preg_replace('/^.*http:\/\/[a-zA-Z0-9\-_\.]*\.[a-zA-Z]{1,3}\//','',$path,-1,$replacementCount); // Delete anything before the path
	if(!$replacementCount) $path=''; // if there was no replacement (i.e. no explicit path), act appropiately
	if($host) $myList[$host]=$path;
	$xml= new DOMDocument('1.0');
	$xml->formatOutput=true;
	$xml->preserveWhiteSpace=false;
	$xml->substituteEntities=false;
	$methodCall=$xml->appendChild($xml->createElement('methodCall'));
	$methodName=$methodCall->appendChild($xml->createElement('methodName'));
	$params=$methodCall->appendChild($xml->createElement('params'));
	$param[1]=$params->appendChild($xml->createElement('param'));
	$value[1]=$param[1]->appendChild($xml->createElement('value'));
	$param[2]=$params->appendChild($xml->createElement('param'));
	$value[2]=$param[2]->appendChild($xml->createElement('value'));
	$methodName->nodeValue="weblogUpdates.ping";
	$value[1]->nodeValue=$blogTitle;
	$value[2]->nodeValue=$blogUrl;
	$xmlrpcReq = $xml->saveXML(); // Write the document into a string
	$xmlrpcLength = strlen( $xmlrpcReq ); // Get the string length.
	$httpReq  = "POST /" . $path . " HTTP/1.0\r\n";
	$httpReq .= "User-Agent: " . $userAgent. "\r\n";
	$httpReq .= "Host: " . $host . "\r\n";
	$httpReq .= "Content-Type: text/xml\r\n";
	$httpReq .= "Content-length: $xmlrpcLength\r\n\r\n";
	$httpReq .= "$xmlrpcReq\r\n";
	// Actually, send ping
	if ($pinghandle = @fsockopen( $host, 80 )) {
		@fputs($pinghandle, $httpReq);
		while (!(feof($pinghandle))) {
			$pingresponse = @fgets($pinghandle, 128);
 echo htmlentities($pingresponse);
			}
		@fclose( $pinghandle );
	}
	$file = 'bany.txt';
	$komunikat = "\r\n" . czas() . ' pingowanie ' . $projekt;	
	$fp = fopen($file,'ab');
	fwrite($fp, $komunikat);
	fclose($fp);
}

function zmiana($funkcja, $czynnosc, $funkcjaa = NULL, $projektt = NULL) {

	if (!((is_null($funkcjaa)) && (is_null($projektt)))) {
		$notatka = czas() . ' zakoñczono ' . $funkcjaa . ' dla projektu ' . $projektt;
		if (($funkcjaa == 'sprawdzanie nowych') || ($funkcjaa == 'sprawdzanie wszystkich') || ($funkcjaa == 'ponowne sprawdzanie') || ($funkcjaa == 'sprawdzanie niezaindeksowanych')) {
			$notatka = $notatka . "\r\n" . 'liczba zaindeksowanych stron: ' . count(file($projektt . '-raport-indeksacji.txt'));
		}
		dopisz($projektt . '-notes.txt', "\r\n" . $notatka);
	}
	$f1 = 'kolejka-' . $czynnosc . '.txt';
	$f2 = 'przelacznik' . $funkcja . '.txt';
	$fp1 = fopen($f1,'rb');
	$fp2 = fopen($f2,'wb');
	if ((file_exists($f1)) && (!(count(file($f1)))==0)) {
		$funkcja_nowa = trim(fgets($fp1));
		$projekt_nowy = trim(fgets($fp1));
		fwrite($fp2, $funkcja_nowa . "\r\n" . $projekt_nowy);
	} else {
		fwrite($fp2, 'wy³±czone' . "\r\n");
		fwrite($fp2, $projektt);
		if (file_exists('t.txt')) { unlink('t.txt'); };
		exit();
	}
	fclose($fp2);
	fclose($fp1);
	usun_pierwszy($f1);
	usun_pierwszy($f1);

	// kopie zapasowe plików
	$ff[] = $projekt_nowy . '-urle.txt';
	$ff[] = $projekt_nowy . '-zaindeksowane-urle.txt';
	$ff[] = $projekt_nowy . '-raport-indeksacji.txt';
	foreach ($ff as $file1) {
		$file2 = $file1 . '.bak';
		$calosc = file($file1);
		$fp2 = fopen($file2,'wb');
		for($i=0;$i<count($calosc);$i++) {
			fwrite($fp2, $calosc[$i]);
		}
		fclose($fp2);
	}
	if (($funkcja_nowa == 'sprawdzanie nowych') || ($funkcja_nowa == 'sprawdzanie wszystkich')) {
		if (!((count(file($projekt_nowy . '-urle.txt'))) == 0)) {
			$calosc = file($projekt_nowy . '-urle.txt');
			$file = $projekt_nowy . '-urle-do-indeksacji.txt';
			if ((!(file_exists($file))) || ((filesize($file)) == 0)) {
				$fp = fopen($file,'wb');
				for($i=0;$i<count($calosc);$i++) {
					fwrite($fp, $calosc[$i]);
				}
				fclose($fp);
				$file = $projekt_nowy . '-zaindeksowane-urle.txt';
				$fp = fopen($file,'wb');
				fclose($fp);
				$file = $projekt_nowy . '-raport-indeksacji.txt';
				$fp = fopen($file,'wb');
				fclose($fp);
			}
		}
	} elseif (($funkcja_nowa == 'ponowne sprawdzanie') || ($funkcja_nowa == 'sprawdzanie niezaindeksowanych')) {
		$file = $projekt_nowy . '-urle-do-indeksacji.txt';
		if ((!(file_exists($file))) || ((filesize($file)) == 0)) {
			niezaindeksowane($projekt_nowy,'indeksacja');
		}
	} elseif ($funkcja_nowa == 'pingowanie niezaindeksowanych') {
		$file = $projekt_nowy . '-urle-do-pingowania.txt';
		if ((!(file_exists($file))) || ((filesize($file)) == 0)) {
			niezaindeksowane($projekt_nowy,'pingowanie');
		}
	} elseif ($funkcjaa == 'pingowanie wszystkich') {
		$file = $projekt_nowy . '-urle-do-pingowania.txt';
		if ((!(file_exists($file))) || ((filesize($file)) == 0)) {
			$file2 = $projekt_nowy . '-urle.txt';
			$calosc = file($file2);
			$fp1 = fopen($file,'wb');
			for($i=0;$i<count($calosc);$i++) {
				fwrite($fp1, $calosc[$i]);
			}
			fclose($fp1);
		}
	}
if (file_exists('t.txt')) { unlink('t.txt'); };
exit();
}

function czas($a = "a") {

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
	if ($a == "a") {
		return date("d.m.Y ") . $czas . date(":i:s");
	} elseif ($a == "h") {
		return $czas;
	}
}

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

// SPRAWDZENIE ZAINDEKSOWANYCH I NIEZAINDEKSOWANYCH Z ZAPISEM DO PLIKÓW

if (($funkcja1 == 'wy³±czone') && (!((count(file('kolejka-indeksowanie.txt'))) == 0))) {
	zmiana('1', 'indeksowanie');
}
if (($funkcja2 == 'wy³±czone') && (!((count(file('kolejka-pingowanie.txt'))) == 0))) {
	zmiana('2', 'pingowanie');
}

if ((($funkcja1 == 'sprawdzanie nowych') || ($funkcja1 == 'sprawdzanie wszystkich') || ($funkcja1 == 'ponowne sprawdzanie') || ($funkcja1 == 'sprawdzanie niezaindeksowanych')) && (!($przerwa)))  {

	$sprawdzenia = 10; // ilo¶æ odpytañ w jednym minutowym cyklu

	if (($funkcja2 == 'pingowanie niezaindeksowanych') || ($funkcja2 == 'pingowanie wszystkich')) {
		$czasmin = 1; // przedzia³ w sekundach dla losowania przerwy 
		$czasmax = 3; // pomiêdzy odpytaniami Google
	} else {
		$czasmin = 2;
		$czasmax = 6;
	}

	$file2 = $projekt1 . '-urle.txt';
	$file3 = $projekt1 . '-zaindeksowane-urle.txt';
	$file5 = $projekt1 . '-urle-do-indeksacji.txt'; // bufor indeksacji
	$file8 = $projekt1 . '-raport-indeksacji.txt';

	if (count(file($file5)) == 0) { // zmiana projektu po skoñczeniu poprzedniego
		zmiana('1', 'indeksowanie', $funkcja1, $projekt1);
	}

	if (count(file($file5)) > 0) {
		if (!(count(file('dc.txt')) > 0)) { // wczytanie listy DC po wykorzystaniu starej
			$fp = fopen('dc-wzor.txt','rb');
			$a = fread($fp, filesize('dc-wzor.txt'));
			fclose($fp);
			$fp = fopen('dc.txt','wb');
			fwrite($fp, $a);
			fclose($fp);
		}
		$fp = fopen('dc.txt','rb'); // pobranie dc
		$dc = trim(fgets($fp));
		fclose($fp);
		$bezpiecznik = 0;
		$ban = 0;
		for ($j=0;($j<$sprawdzenia);$j++) {
		if ((count(file($file5)) > 0) && ($ban == 0)) {
				if ($bezpiecznik == 0) {
					$fp5 = fopen($file5,'rb');
					$buforek = trim(fgets($fp5));
					fclose($fp5);
				}
				$data = curl_get_data('search?hl=pl&q=info%3A' . $buforek, $dc);
//					echo '<br /><br /><br />' . $data . '<br /><br /><br />';
				if ((preg_match("/see you again on Google/", $data)) || (preg_match("/wyszukiwanie, wpisz widoczne/", $data)) || (preg_match("/The document has moved/", $data)) || (preg_match("/Bad Request/", $data))) {
					$komunikat = czas() . ' ban na DC: ' . $dc;
					dopisz('bany.txt', $komunikat);
					usun_pierwszy('dc.txt');
					$ban = 1;
					zwieksz_licznik(); // zindywidualizowæ licznik banów
				} else {
					if (preg_match("/Niestety nie ma informacji dotycz±cych adresu/", $data)) {
						$komunikat = czas() . ' ' . $buforek . ' ' . ' niezaindeksowana DC: ' . $dc;
						usun_pierwszy($file5);
						$bezpiecznik = 0;
						zwieksz_licznik();
					} elseif (preg_match("/wynik/", $data)) { // tu przyda³by siê ¶ci¶lejszy warunek na znalezionie zaindeksowanej wynik<nobr> or wyników<nobr>
						dopisz($file3, $buforek);
						$komunikat = czas() . ' ' . $buforek . ' ' . ' zaindeksowana DC: ' . $dc;
						dopisz($file8, $buforek . ' DC: ' . $dc);
						usun_pierwszy($file5);
						$bezpiecznik = 0;
						zwieksz_licznik();
					} else { // testowy bezpiecznik
						$komunikat = czas() . ' ' . $buforek . ' ' . ' BRAK ODPOWIEDZI DC: ' . $dc;
						$bezpiecznik++;
						if ($bezpiecznik == 10) {
						usun_pierwszy('dc.txt');
						usun_dc($dc);
							$komunikat .= ' - usuwam DC';
						}
					}
					dopisz('bany.txt', $komunikat);
				}
			} elseif ((count(file($file5))) == 0) { // zakoñczenie projektu i pobranie nowego
				zmiana('1', 'indeksowanie', $funkcja1, $projekt1);
			}
			if (($funkcja2 == 'pingowanie niezaindeksowanych') || ($funkcja2 == 'pingowanie wszystkich')) {
				$file = 'pinglist.txt';
				$file1 = 'pinglist-wzor.txt';
				for($i=0;$i<10;$i++) {
					if (count(file($projekt2 . '-urle-do-pingowania.txt')) > 0) {
						if ((count(file($file))) == 0) {
							$fp = fopen($file1,'rb');
							$a = fread($fp, filesize($file1));
							fclose($fp);
							$fp = fopen($file,'wb');
							fwrite($fp, $a);
							fclose($fp);
						}
						$fp = fopen($file,'rb');
						pinger($projekt2, fgets($fp));
						fclose($fp);
						usun_pierwszy($file);
						if ((count(file($file))) == 0){
							usun_pierwszy($projekt2 . '-urle-do-pingowania.txt');
						}
					} elseif (count(file($projekt2 . '-urle-do-pingowania.txt'))==0) { // zakoñczenie projektu i pobranie nowego
						zmiana('2', 'pingowanie', $funkcja2, $projekt2);
					}
				}
			}
			$time = rand($czasmin, $czasmax);
			sleep($time);
		}
	}
} elseif (($funkcja2 == 'pingowanie niezaindeksowanych') || ($funkcja2 == 'pingowanie wszystkich') || ((($funkcja1 == 'sprawdzanie nowych') || ($funkcja1 == 'ponowne sprawdzanie')))) {
	if (count(file($projekt2 . '-urle-do-pingowania.txt')) > 0) {
		$sprawdzenia = 100; // ilo¶æ odpytañ w jednym minutowym cyklu
		$file = 'pinglist.txt';
		$file1 = 'pinglist-wzor.txt';
		for ($k=0;($k<$sprawdzenia);$k++) {
			if ((count(file($file))) == 0) {
				$fp = fopen($file1,'rb');
				$a = fread($fp, filesize($file1));
				fclose($fp);
				$fp = fopen($file,'wb');
				fwrite($fp, $a);
				fclose($fp);
			}
			$fp = fopen($file,'rb');
			pinger($projekt2, fgets($fp));
			fclose($fp);
			usun_pierwszy($file);
			if ((count(file($file))) == 0){
				usun_pierwszy($projekt2 . '-urle-do-pingowania.txt');
			}
		}
	} elseif (count(file($projekt2 . '-urle-do-pingowania.txt'))==0) { // zakoñczenie projektu i pobranie nowego
		zmiana('2', 'pingowanie', $funkcja2, $projekt2);
	}
}

if (file_exists('t.txt')) { unlink('t.txt'); };

?>