<?php

require_once('classes/manager/manager.class.php');

$tpl =& template::instance();
require_once('views/menu.inc.php');

$db = new database($_GET['db']);

try {
	if(!in_array(@$_GET['format'], array('csv', 'sql', 'xml'))) {
		throw new siteexception(i18n("Unknown format %s", @$_GET['format']));
	}
	$format = $_GET['format'];
	if(!sizeof(@$_GET['tables'])) {
		throw new siteexception(i18n("No tables selected"));
	}
} catch(siteexception $e) {
	$e->Show();
}

$tables = array();
$export = export::instance($format);
$i = 0;

foreach($_GET['tables'] as $table) {
	$tables[$i]['name'] = $_GET['tables'][$i];
	if(@$_GET[$format]['structure']) {
		$tables[$i]['structure'] = $export->structTable($db->$table);
	}
	if(@$_GET[$format]['data']) {
		$tables[$i]['data'] = $export->dataTable($db->$table);
	}
	$i++;
}

$tpl->assign('tables', $tables);
$tpl->assign('header', $export->Header());
$tpl->assign('footer', $export->Footer());
$tpl->display('export/view.tpl.php');

?>