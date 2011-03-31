<?php

require_once('classes/manager/manager.class.php');

$tpl =& template::instance();
require_once('views/menu.inc.php');

$manager = manager::instance();
$tpl->assign('databases', $manager->getDatabases());

$tpl->display('welcome/main.tpl.php');

?>