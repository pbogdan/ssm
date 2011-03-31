<?php

function phperrorhandler($errno, $errstr, $errfile, $errline) {
	if(!error_reporting() || preg_match('/pear/i', $errfile)) { // @ operator used, also skip thirdparty code
	} else {
		throw new phpexception($errno, $errstr, $errfile, $errline);
	}
}

?>