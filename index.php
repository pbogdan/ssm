<?php

// +--------------------------------------------------------------------------+
// | index.php                                                                |
// +--------------------------------------------------------------------------+
// | Copyright (c) 2004 Piotr Bogdan <ppbogdan@gmail.com>                     |
// +--------------------------------------------------------------------------+
// | Licensed under the revised BSD License;                                  |
// | you may not use this file except in compliance with the License.         |
// | You may obtain a copy of the License at:                                 |
// |   http://www.opensource.org/licenses/bsd-license.php                     |
// +--------------------------------------------------------------------------+
// | Unless required by applicable law or agreed to in writing, software      |
// | distributed under the License is distributed on an "AS IS" BASIS,        |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. |
// | See the License for the specific language governing permissions and      |
// | limitations under the License.                                           |
// +--------------------------------------------------------------------------+
// $Id: index.php,v 1.17 2004/10/06 20:49:59 silence Exp $

require_once('thirdparty/savant/Savant.php');

require_once('functions/locale.inc.php');
require_once('functions/phperrorhandler.inc.php');
require_once('functions/util.inc.php');

error_reporting(E_ALL | E_STRICT);
set_error_handler('phperrorhandler');

require_once('classes/config.class.php');
require_once('classes/controller.class.php');
require_once('classes/myexception.class.php');
require_once('classes/template.class.php');
require_once('classes/validator.class.php');

define('APP_BASE_DIR', dirname(__FILE__));

session_start();

try {
	/* while(ob_get_level() > 0) { */
	/* 	ob_end_clean(); */
	/* } */

	fixMagicQuotes();
	
	/* ob_start('ob_gzhandler'); */
	ob_start();
	
	$tpl_options = array(
		'plugin_path'   => APP_BASE_DIR.'/thirdparty/savant/Savant/plugins/',
		'filter_path'   => APP_BASE_DIR.'/thirdparty/savant/Savant/filters/',
		'template_path' => APP_BASE_DIR.'/templates/'
	);
	
	$tpl =& template::instance($tpl_options);
	$controller = new controller();
	
	$controller->Process();
	ob_end_flush();
} catch(phpexception $e) {
	$e->Show();
}

?>
