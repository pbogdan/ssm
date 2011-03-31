<?php

require_once('classes/manager/manager.class.php');

$tpl =& template::instance();
require_once('views/menu.inc.php');

$db = new database($_GET['db']);
$tpl->assign('tables', $db->Tables());

$tpl->display('database/main.tpl.php');

?>