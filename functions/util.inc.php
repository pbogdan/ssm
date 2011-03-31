<?php

/**
* Takes care of magic_quotes_gpc setting from php.ini
* @return void
*/
function fixMagicQuotes() {
	
	// magic_quotes_gpc disabled by default in PHP5
	// but never trust the user ;)
	
	if(ini_get('magic_quotes_gpc')) {
		array_walk_recursive($_GET, 'stripslashes');
		array_walk_recursive($_POST, 'stripslashes');
		array_walk_recursive($_COOKIE, 'stripslashes');
	}
	
}

define('USR_BROWSER_IE',        1);
define('USR_BROWSER_OPERA',     2);
define('USR_BROWSER_KONQUEROR', 3);
define('USR_BROWSER_MOZILLA',   4);
define('USR_BROWSER_OTHER',     5);

/**
* Browser identification - string and assigned browser constant.
* @return array
*/
function getBrowser() {

	$bw = $_SERVER['HTTP_USER_AGENT'];
	
	$rules = array(
		array('#^Mozilla/\d+\.\d+ \(compatible; iCab ([^;]); ([^;]); [NUI]; ([^;])\)#', 'iCab $1', USR_BROWSER_OTHER),
		array('#^Opera/(\d+\.\d+) \(([^;]+); [^)]+\)#', 'Opera $1', USR_BROWSER_OPERA),
		array('#^Mozilla/\d+\.\d+ \(compatible; MSIE [^;]+; ([^)]+)\) Opera (\d+\.\d+)#', 'Opera $2', USR_BROWSER_OPERA),
		array('#^Mozilla/\d+\.\d+ \(([^;]+); [^)]+\) Opera (\d+\.\d+)#', 'Opera $2', USR_BROWSER_OPERA),
		array('#^Mozilla/[1-9]\.0 ?\(compatible; MSIE ([1-9]\.[0-9b]+);(?: ?[^;]+;)*? (Mac_[^;)]+|Windows [^;)]+)(?:; [^;]+)*\)#', 'MS Internet Explorer $1', USR_BROWSER_IE),
		array('#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; ([^;]+); [^;]+; Galeon\) Gecko/\d{8}$#', 'Galeon', USR_BROWSER_MOZILLA),
		array('#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; Galeon; [^;]+; ([^;)]+)\)$#', 'Galeon $1', USR_BROWSER_MOZILLA),
		array('#^Mozilla/\d+\.\d+ Galeon/([0-9.]+) \(([^;)]+)\) Gecko/\d{8}$#', 'Galeon $1', USR_BROWSER_MOZILLA),
		array('#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; ([^;]+); [^;]+; rv:[^;]+(?:; [^;]+)*\) Gecko/\d{8} ([a-zA-Z ]+/[0-9.b]+)#', '$2', USR_BROWSER_MOZILLA),
		array('#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; ([^;]+); [^;]+; rv:([^;]+)(?:; [^;]+)*\) Gecko/\d{8}$#', 'Mozilla $2', USR_BROWSER_MOZILLA),
		array('#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; ([^;]+); [^;]+; (m\d+)(?:; [^;]+)*\) Gecko/\d{8}$#', 'Mozilla $2', USR_BROWSER_MOZILLA),
		array('#^Mozilla/\d+\.\d+ \([^;]+; [NIU]; ([^;]+)(?:; [^;]+)*\) Mozilla/(.+)$#', 'Mozilla $2', USR_BROWSER_MOZILLA),
		array('#^Mozilla/5.0 \([^;]+; [NIU]; ([^;)]+)(?:; [^;]+)*\) Gecko/[0-9]{8} Netscape/7\.([0-9b]+)#', 'Netscape 7.$2', USR_BROWSER_MOZILLA),
		array('#^Mozilla/5.0 \([^;]+; [NIU]; ([^;)]+)(?:; [^;]+)*\) Gecko/[0-9]{8} Netscape6/6\.([0-9b]+)#', 'Netscape 6.$2', USR_BROWSER_MOZILLA),
		array('#^Mozilla/4\.(\d+)[^(]+\(X11; [NIU] ?; ([^;]+)(?:; [^;]+)*\)#', 'Netscape 4.$1', USR_BROWSER_MOZILLA),
		array('#^Mozilla/4\.(\d+)[^(]+\((OS/2|Linux|Macintosh|Win[^;]*)[;,] [NUI] ?[^)]*\)#', 'Netscape 4.$1', USR_BROWSER_MOZILLA),
		array('#^Mozilla/3\.(\d+)\S*[^(]+\(X11; [NIU] ?; ([^;]+)(?:; [^;)]+)*\)#', 'Netscape 3.$1', USR_BROWSER_MOZILLA),
		array('#^Mozilla/3\.(\d+)\S*[^(]+\(([^;]+); [NIU] ?(?:; [^;)]+)*\)#', 'Netscape 3.$1', USR_BROWSER_MOZILLA),
		array('#^Mozilla/2\.(\d+)\S*[^(]+\(([^;]+); [NIU] ?(?:; [^;)]+)*\)#', 'Netscape 2.$1', USR_BROWSER_MOZILLA),
		array('#^Mozilla \(X11; [NIU] ?; ([^;)]+)\)#', 'Netscape', USR_BROWSER_MOZILLA),
		array('#^Mozilla/3.0 \(compatible; StarOffice/(\d+)\.\d+; ([^)]+)\)$#', 'StarOffice $1', USR_BROWSER_OTHER),
		array('#^ELinks \((.+); (.+); .+\)$#', 'ELinks $1', USR_BROWSER_OTHER),
		array('#^Mozilla/3\.0 \(compatible; NetPositive/([0-9.]+); BeOS\)$#', 'NetPositive $1', USR_BROWSER_OTHER),
		array('#^Konqueror/(\S+)$#', 'Konqueror $1', USR_BROWSER_KONQUEROR),
		array('#^Mozilla/5\.0 \(compatible; Konqueror/([^;]+); ([^)]+)\).*$#', 'Konqueror $1', USR_BROWSER_KONQUEROR),
		array('#^Lynx/(\S+)#', 'Lynx/$1', USR_BROWSER_OTHER),
		array('#^Mozilla/4.0 WebTV/(\d+\.\d+) \(compatible; MSIE 4.0\)$#', 'WebTV $1', USR_BROWSER_OTHER),
		array('#^Mozilla/4.0 \(compatible; MSIE 5.0; (Win98 A); (ATHMWWW1.1); MSOCD;\)$#', '$2', USR_BROWSER_MOZILLA),
		array('#^(RMA/1.0) \(compatible; RealMedia\)$#', '$1', USR_BROWSER_OTHER),
		array('#^antibot\D+([0-9.]+)/(\S+)#', 'antibot $1', USR_BROWSER_OTHER),
		array('#^Mozilla/[1-9]\.\d+ \(compatible; ([^;]+); ([^)]+)\)$#', '$1', USR_BROWSER_MOZILLA),
		array('#^Mozilla/([1-9]\.\d+)#', 'compatible Mozilla/$1', USR_BROWSER_MOZILLA),
		array('#^([^;]+)$#', '$1', USR_BROWSER_OTHER)
	);
	
	foreach($rules as $r) {
		list($rule, $browser, $type) = $r;
		if(preg_match($rule, $bw, $tmp)) {
			$result = str_replace(
				array('$1', '$2', '$3'),
				array(
				isset($tmp[1]) ? $tmp[1] : '',
				isset($tmp[2]) ? $tmp[2] : '',
				isset($tmp[3]) ? $tmp[3] : ''
				),
				$browser
			);
			return array($result, $type);
		}
	}
	return array('unknown', USR_BROWSER_OTHER);
}

define('USR_OS_WINDOWS', 1);
define('USR_OS_MAC',     2);
define('USR_OS_LINUX',   3);
define('USR_OS_UNIX',    4);
define('USR_OS_OTHER',   5);

/**
* OS identification - string and assigned OS constant.
* @return array
*/
function getOS() {
	
	$bw = $_SERVER['HTTP_USER_AGENT'];
	
	$rules = array(
		array('#Win.*NT 5.0#', 'Windows 2000', USR_OS_WINDOWS),
		array('#Win.*NT 5.1#', 'Windows XP', USR_OS_WINDOWS),
		array('#Win.*(XP|2000|ME|NT|9.?)#', 'Windows $1', USR_OS_WINDOWS),
		array('#Windows .*(3\.11|NT)#', 'Windows $1', USR_OS_WINDOWS),
		array('#Win32#', 'Windows [unknown version]', USR_OS_WINDOWS),
		array('#Linux 2\.(.?)\.#', 'Linux 2.$1.x', USR_OS_LINUX),
		array('#Linux#', 'Linux [unknown version]', USR_OS_LINUX),
		array('#FreeBSD .*-CURRENT$#', 'FreeBSD -CURRENT', USR_OS_UNIX),
		array('#FreeBSD (.?)\.#', 'FreeBSD $1.x', USR_OS_UNIX),
		array('#NetBSD 1\.(.?)\.#', 'NetBSD 1.$1.x', USR_OS_UNIX),
		array('#(Free|Net|Open)BSD#', '$1BSD [unknown version]', USR_OS_UNIX),
		array('#HP-UX B\.(10|11)\.#', 'HP-UX B.$1.x', USR_OS_UNIX),
		array('#IRIX(64)? 6\.#', 'IRIX 6.x', USR_OS_UNIX),
		array('#SunOS 4\.1#', 'SunOS 4.1.x', USR_OS_UNIX),
		array('#SunOS 5\.([4-6])#', 'Solaris 2.$1.x', USR_OS_UNIX),
		array('#SunOS 5\.([78])#', 'Solaris $1.x', USR_OS_UNIX),
		array('#Mac_PowerPC#', 'Mac OS [PowerPC]', USR_OS_MAC),
		array('#Mac#', 'Mac OS', USR_OS_MAC),
		array('#X11#', 'UNIX [unknown version]', USR_OS_UNIX),
		array('#Unix#', 'UNIX [unknown version]', USR_OS_UNIX),
		array('#BeOS#', 'BeOS [unknown version]', USR_OS_OTHER),
		array('#QNX#', 'QNX [unknown version]', USR_OS_OTHER),
	);

	foreach($rules as $r) {
		list($rule, $os, $type) = $r;
		if(preg_match($rule, $bw, $tmp)) {
			$result = str_replace(
				array('$1', '$2', '$3'),
				array(
					isset($tmp[1]) ? $tmp[1] : '',
					isset($tmp[2]) ? $tmp[2] : '',
					isset($tmp[3]) ? $tmp[3] : ''
				),
				$os
			);
			return array($result, $type);
		}
	}
	return array('unknown', USR_OS_OTHER);
}

/**
* Tries to guess visitor's IP address.
* @return string
*/
function getIP() {
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else if(isset($_SERVER['HTTP_VIA'])) {
		$ip = $_SERVER['HTTP_VIA'];
	} else if(isset($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
	} else { 
		$ip = "0.0.0.0";
	}
	
	return $ip;
}

?>
