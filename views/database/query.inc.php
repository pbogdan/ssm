<?php

require_once('views/menu.inc.php');

$tpl =& template::instance();
$tpl->display('database/query.tpl.php');

?>