<?php

$tpl = template::instance();
$tpl->display('welcome/menu.tpl.php');

if(array_key_exists('db', $_REQUEST)) {
	$tpl->display('database/menu.tpl.php');
}

if(array_key_exists('tbl', $_REQUEST)) {
	$tpl->display('table/menu.tpl.php');
}

if(array_key_exists('queries', $_SESSION) && sizeof($_SESSION['queries'])) {
	// query box goes here
	require_once('views/database/queryresults.inc.php');
}

?>