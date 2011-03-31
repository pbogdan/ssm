<?php

require_once('classes/manager/manager.class.php');

$functions = new functions();
$functions->initAll();

$tpl =& template::instance();

$queries = array();

foreach($_SESSION['queries'] as $query) {
	$q = new query($_GET['db'], $query);
	$q->Execute();
	
	$c = new colorizequery();
	$c->setQuery($query);
	
	$queries[] = array(
		'text' => $c->getQuery(),
		'timing' => $q->getTiming(),
		'message' => $q->getMessage(),
		'isSelect' => $q->isSelectQuery(),
		'result' => ($q->isSelectQuery()) ? $q->Result() : NULL
	);
}

session_unregister('queries');

$tpl->assign('queries', $queries);
$tpl->display('database/queryresults.tpl.php');

?>