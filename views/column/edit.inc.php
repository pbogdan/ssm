<?php

require_once('classes/manager/manager.class.php');

if(ini_get('magic_quotes_gpc')) {
	$columns = unserialize(urldecode(stripslashes($_GET['columns'])));
} else  {
	$columns = unserialize(urldecode($_GET['columns']));
}

$tpl =& template::instance();
require_once('views/menu.inc.php');

$db = new database($_GET['db']);
$tbl = $_GET['tbl'];

$cols = array();

foreach($columns as $col) {
	$col = $db->$tbl->$col;
	
	$cols[] = array(
		'name'    => $col->name,
		'type'    => $col->type,
		'length'  => $col->length,
		'null'    => $col->null,
		'default' => $col->default,
		'primary' => $col->primary,
	);
}

$tpl->assign('cols', $cols);
$tpl->display('column/edit.tpl.php');

?>