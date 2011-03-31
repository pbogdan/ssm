<?php

require_once('classes/manager/manager.class.php');

$tpl = template::instance();
require_once('views/menu.inc.php');

$db = new database($_GET['db']);
$tbl = $_GET['tbl'];

$cols = array();

foreach($db->$tbl->Columns() as $c) {
	$col = $db->$tbl->$c;
	
	$cols[] = array(
		'name'    => $col->name,
		'type'    => $col->type,
		'length'  => $col->length,
		'null'    => $col->null,
		'default' => $col->default,
		'primary' => $col->primary,
		'value'   => urldecode($_REQUEST[$c])
	);
	
}

$tpl->assign('cols', $cols);

$tpl->display('table/editrecord.tpl.php');

?>