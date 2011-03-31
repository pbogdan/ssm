<?php

list($bwString, $bwType) = getBrowser();
list($osString, $osType) = getOS();

if($osType == USR_OS_WINDOWS && ($bwType == USR_BROWSER_IE || $bwType == USR_BROWSER_OPERA)) {
    $fontSize     = 'x-small';
    $fontBiggest  = 'large';
    $fontBigger   = 'medium';
    $fontSmaller  = '90%';
    $fontSmallest = '7pt';
} else if($osString == USR_OS_WINDOWS) {
    $fontSize     = 'small';
    $fontBiggest  = 'large';
    $fontBigger   = 'medium';
    $fontSmaller  = 'x-small';
    $fontSmallest = 'x-small';
} else if($osType == USR_OS_MAC) {
    $fontSize     = 'x-small';
    $fontBiggest  = 'large';
    $fontBigger   = 'medium';
    $fontSmaller  = '90%';
    $fontSmallest = '7pt';
} else if($bwType == USR_BROWSER_KONQUEROR) {
    $fontSize     = 'medium';
    $fontBiggest  = 'x-large';
    $fontBigger   = 'large';
    $fontSmaller  = 'small';
    $fontSmallest = 'x-small';
} else {
    $fontSize     = 'small';
    $fontBiggest  = 'large';
    $fontBigger   = 'medium';
    $fontSmaller  = 'x-small';
    $fontSmallest = 'x-small';
}

?>

	<style type="text/css">
	
	body {
		background-color: #FFF;
	}
	
	font {
		font-size: <?= $fontSize ?>;
	}
	
	td {
		font-size: <?= $fontSize ?>;
	}
	
	th {
		font-size: <?= $fontSize ?>;
		color: #FFF;
		background-color: #2a5d8a;
	}
	
	img {
		border: solid 0px #000;
	}
	
	a:link {
		font-size: <?= $fontSize ?>; 
		color: #000;
		text-decoration: none;
	}
	
	a:visited {
		font-size: <?= $fontSize ?>; 
		color: #000;
		text-decoration: none;
	}
	
	a:hover {
		font-size: <?= $fontSize ?>; 
		color: #000;
		text-decoration: underline;
	}
	
	</style>
