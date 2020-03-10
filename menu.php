<?php 

// pobranie projektu (je¶li nie istnieje) ze zmiennej lub pliku (bezpiecznik)

if ($projekt == '') {
	$projekt= $_GET['projekt'];
}
if ($projekt == '') {
	$f = 'przelacznik.txt';
	if (file_exists($f)) {
		$fp = fopen($f,'rb');
		$projekt = trim(fgets($fp));
		fclose($fp);	
	} else {
		$projekt = 'nie wybrany';
	}
}

$fx = array('1' => 'przelacznik1.txt', '2' => 'przelacznik2.txt');
foreach($fx as $numer => $file) {
	if (!(file_exists($file))) {
		$fp = fopen($file,'wb');
		$zapis = 'wy³±czone' . "\r\n" . 'start';
		fwrite($fp, $zapis);
		fclose($fp);
	} else {
		$fp = fopen($file,'rb');
		${'funkcja' . $numer} = trim(fgets($fp));
		${'projekt' . $numer} = fgets($fp);
		fclose($fp);	
	}
}
$file = 'ip.txt';
if (file_exists($file)) {
	$fp = fopen($file,'rb');
	$ip = trim(fgets($fp));
	fclose($fp);
} else {
$ip = '';
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
	<title>pinger v. 0.2.2 rc - <?php echo $projekt; if (!($ip == '')) { echo ' - IP out: ' . $ip . ' - IP in: ' . $_SERVER['SERVER_ADDR']; } ?></title>
  <meta name="robots" content="none">
	
	<style type="text/css">

input, input.onMouseOut {
	border: 2px outset gray;
	background: gray;
	color: yellow;
}
input.onMouseOver {
	border-left:white; 
	border-right:black;
	border-top:white;
	border-bottom:black;
}
input.onMouseDown {
	border-left:black;
	border-right:white;
	border-top:black;
	border-bottom:white;
}
	
/* 
outset ridge grove inset
*/
	
		.row {display: inline;}
		#notes {
		 position: absolute;
		 top: 125px;
		 left: 350px;
		}
		
/* ================================================================ 
This copyright notice must be untouched at all times.

The original version of this stylesheet and the associated (x)html
is available at http://www.cssplay.co.uk/menus/pro_left_right_line.html
Copyright (c) 2005-2007 Stu Nicholls. All rights reserved.
This stylesheet and the associated (x)html may be modified in any 
way to fit your requirements.
=================================================================== */
.pro_linedrop {
height:35px;
width:100%;
background:url(black_0.gif);
position:relative; 
font-family:arial, verdana, sans-serif; 
font-size:11px;
z-index:100;
}

.pro_linedrop .select {
margin:0; 
padding:0; 
list-style:none; 
white-space:nowrap;
}

.pro_linedrop li {
float:left;
background:url(black_1.gif);
}

.pro_linedrop li.lrt {
float:right;
background:url(blue_1.gif);
}


.pro_linedrop .select a {
display:block; 
height:35px; 
float:left; 
background: url(black_0.gif); 
padding:0 0 0 15px; 
text-decoration:none; 
line-height:33px; 
white-space:nowrap; 
color:#fc0;
}


.pro_linedrop .select li.lrt a {color:#0ff;}


.pro_linedrop .select a b {
display:block;
padding:0 30px 0 15px; 
background:url(black_0.gif) right top;
}

.pro_linedrop .select li.line a b {
background:url(black_0a.gif) right top;
}

.pro_linedrop .select a:hover, 
.pro_linedrop .select li:hover a {
background: url(black_1.gif); 
padding:0 0 0 15px;
cursor:pointer; 
color:#fff;
}

.pro_linedrop .select li.lrt a:hover, 
.pro_linedrop .select li.lrt:hover a {
background: url(blue_1.gif); 
}

.pro_linedrop .select a:hover b, 
.pro_linedrop .select li:hover a b {
display:block; 
float:left;
padding:0 30px 0 15px; 
background:url(black_1.gif) right top; 
cursor:pointer;
}

.pro_linedrop .select li.line a:hover b, 
.pro_linedrop .select li.line:hover a b {
background:url(black_1a.gif) right top; 
}

.pro_linedrop .select li.lrt a:hover b, 
.pro_linedrop .select li.lrt:hover a b {
background:url(blue_1.gif) right top; 
}

.pro_linedrop .select li.lrt a:hover b.arrow, 
.pro_linedrop .select li.lrt:hover a b.arrow {
background:url(blue_1a.gif) right top; 
}

.pro_linedrop .sub {
position:absolute; left:-9999px; width:0;
}

/* IE6 only */
.pro_linedrop table {
border-collapse:collapse; 
margin:-1px -10px -1px -1px; 
font-size:1em; 
width:0; 
height:0;
}

.pro_linedrop .sub {
margin:0; 
padding:0;
list-style:none;
}

.pro_linedrop .sub li {background:transparent;}

.pro_linedrop .select :hover .sub {
height:25px;
position:absolute;
width:100%;
top:35px; 
left:0; 
text-align:center;
background:#fff url(fade.gif);
border:1px solid #aaa;
}

.pro_linedrop .select :hover .rt li {float:right;}

.pro_linedrop .select :hover .sub li a 
{display:block; height:25px; line-height:22px; float:left; background:#fff url(fade.gif); padding:0 16px; margin:0; white-space:nowrap; color:#333;font-size:10px;}

.pro_linedrop .select :hover .sub li.subline a {color:#c00;}

.pro_linedrop .select :hover .sub li a:hover,
.pro_linedrop .select :hover .sub li:hover
{color:#000; line-height:20px; position:relative; background:#fff url(fade.gif) left bottom;}

	</style>
	
</head>

<body>

<div class="pro_linedrop">
<ul class="select">

<li class="line"><a href="index.php?projekt=<?php echo $projekt; ?>"><b>przegl±d</b><!--[if IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul class="sub">
		<li><a href="index.php?projekt=<?php echo $projekt; ?>">przegl±d</a></li>
		<li><a href="rss.php">RSS</a></li>
		<li><a href="bany.php?projekt=<?php echo $projekt; ?>">log</a></li>
		</ul>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>
<li class="line"><a href="konfig.php?projekt=<?php echo $projekt; ?>&amp;aktualne=true&amp;kolejka=true&amp;import=true&amp;pinger=true&amp;czas=true"><b>konfiguracja</b><!--[if IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul class="sub">
		<li><a href="dodaj-projekt.php?projekt=<?php echo $projekt; ?>&amp;kontrolka=1">dodaj projekt</a></li>
		<li><a href="konfig.php?projekt=<?php echo $projekt; ?>&amp;aktualne=true">prze³±czniki</a></li>
		<li><a href="konfig.php?projekt=<?php echo $projekt; ?>&amp;kolejka=true">kolejka</a></li>
		<li><a href="konfig.php?projekt=<?php echo $projekt; ?>&amp;import=true">import-eksport</a></li>
		<li><a href="konfig.php?projekt=<?php echo $projekt; ?>&amp;pinger=true">pingowanie</a></li>
		<li><a href="konfig.php?projekt=<?php echo $projekt; ?>&amp;czas=true">czas serwera</a></li>
		</ul>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>
<li class="line"><a href="#nogo"><b>narzêdzia</b><!--[if IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul class="sub">
		<li><a href="http://www.auditmypc.com/xml-sitemap.asp">generator map</a></li>
		<li><a href="pingowanie-reczne.php?projekt=<?php echo $projekt; ?>&amp;kontrolka=1">test pingera</a></li>
	</ul>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>
<li class="line lrt"><a href="#nogo"><b class="arrow">hosty</b><!--[if IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul class="sub rt">
