<?php

// +--------------------------------------------------------------------------+
// | myexception.class.php                                                    |
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
// $Id: myexception.class.php,v 1.13 2004/09/24 22:23:51 silence Exp $

require_once(dirname(__FILE__).'/exceptions/ioexception.class.php');
require_once(dirname(__FILE__).'/exceptions/phpexception.class.php');
require_once(dirname(__FILE__).'/exceptions/siteexception.class.php');
require_once(dirname(__FILE__).'/exceptions/sqlitexception.class.php');
require_once(dirname(__FILE__).'/exceptions/validatorexception.class.php');

/**
* @package core
* @subpackage exception
*/
class myexception extends Exception {
	
	function __construct() {
		$tpl =& template::instance();
		
		/* while(ob_get_level() > 0) { */
		/* 	ob_end_clean(); */
		/* } */
		
		ob_start();
		$tpl->display('top.tpl.php');
		
		$tpl->assign('exceptionName', get_class($this));
		$tpl->assign('exceptionLine', $this->getLine());
		$tpl->assign('exceptionFile', $this->getFile());
		$tpl->assign('exceptionTrace', nl2br($this->getTraceAsString()));
		$tpl->display('exceptions/myexception.tpl.php');
	}
	
	function __destruct() {
		$tpl =& template::instance();
		$tpl->display('bottom.tpl.php');
		@ob_end_flush();
		exit;
	}
}

?>