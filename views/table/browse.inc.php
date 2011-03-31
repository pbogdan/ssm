<?php

require_once('classes/manager/manager.class.php');

$tpl =& template::instance();
require_once('views/menu.inc.php');

/**
* writing it in hurry, after 30 hours of coding,
* and 4 hours before deadline
*/
$oldErrorReporting = error_reporting(0);

$db = new database($_GET['db']);
$tblName = $_GET['tbl'];
$tbl = $db->$tblName;

if(!@array_key_exists('query', $_GET)) {
	$query = "SELECT * FROM {$_GET['tbl']}";
} else {
	/**
	* if(ini_get"magic_quotes_gpc")) then 
	*   use stripslashes
	**/
	$query = urldecode(stripslashes($_GET['query']));
}

if(!@array_key_exists('limit', $_GET)) {
	$cfg = config::instance();
	$cfg->setGroup('general');
	$limit = $cfg->getProperty('browseLimit');
} else {
	$limit = $_GET['limit'];
}

if(!@array_key_exists('offset', $_GET)) {
	$offset = 0;
} else {
	$offset = $_GET['offset'];
}

if(!@array_key_exists('orderBy', $_GET)) {
	$order = false;
} else {
	$order = $_GET['orderBy'];
}

if(!@array_key_exists('dir', $_GET)) {
	$dir = 'ASC';
} else {
	$dir = $_GET['dir'];
}

$selectquery = new selectquery($query);
$selectquery->setLimit($limit);
$selectquery->setOffset($offset);

if($order !== false) {
	$order = "{$order} {$dir}";
	$selectquery->setOrderBy($order);
}

$query = new query($_GET['db'], $selectquery->getQuery());
if(!$query->Execute()) {
	$message = "Failed";
} else {
	$message = $query->getMessage();
}

error_reporting($oldErrorReporting);

$colorize = new colorizequery();
$colorize->setQuery($selectquery->getQuery());

$tpl->assign('query', array(
		'text' => $colorize->getQuery(),
		'timing' => $query->getTiming(),
		'message' => $message,
		'pure'    => $selectquery->getQuery()
	)
);

$prev = (($offset != 0) ? true : false);
$next = ((($tbl->numRows() - $limit) > $offset) ? true : false);

$tpl->assign('prev', $prev);
$tpl->assign('next', $next);

$tpl->assign('limit', $limit);
$tpl->assign('offset', $offset);
$tpl->assign('nextoffset', $offset + $limit);
$tpl->assign('prevoffset', $offset - $limit);

if($dir == 'ASC') {
	$tpl->assign('dir', 'DESC');
} else {
	$tpl->assign('dir', 'ASC');
}

$pages = $tbl->numRows() / $limit;

if(is_double($pages)) {
	$pages = (int)$pages;
	$pages++;
}
if($pages) {
	foreach(range(1, $pages) as $number) {
		$tmp[--$number * $limit] = ++$number;
	}
} else {
	$tmp = array();
}

$tpl->assign('pages', $tmp);
$tpl->assign('result', $query->Result());
$tpl->assign('cols', $tbl->Columns());
$tpl->display('table/browse.tpl.php');

?>
