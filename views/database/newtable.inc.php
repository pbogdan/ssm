<?php

$tpl =& template::instance();
require_once('views/menu.inc.php');

$tblName = $_GET['tblName'];
$tblColumns = $_GET['tblColumns'];

$tpl->assign('tblName', $tblName);
$tpl->assign('tblColumns', $tblColumns);
$tpl->display('database/newtable.tpl.php');

?>