<?php

require_once('classes/manager/manager.class.php');

$tpl =& template::instance();
require_once('views/menu.inc.php');

$db = new database($_GET['db']);
$tpl->assign('tables', $db->Tables());

if(!@empty($_GET['selected'])) {
	$tpl->assign('selected', $_GET['selected']);
} else {
	$tpl->assign('selected', '');
}

$tpl->display('export/options.tpl.php');

?>