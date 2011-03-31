<?php

require_once('classes/manager/manager.class.php');

$tpl =& template::instance();
require_once('views/menu.inc.php');

$tpl->display('table/newcolumns.tpl.php');

?>