<?php

// +--------------------------------------------------------------------------+
// | phpexception.class.php                                                   |
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
// $Id: phpexception.class.php,v 1.11 2004/09/24 22:24:19 silence Exp $

/**
* @package core
* @subpackage exception
*/
class phpexception extends myexception {
	
	private $_errorMap = array(
		E_ERROR           => 'Fatal run-time error',
		E_WARNING         => 'Run-time warning',
		E_PARSE           => 'Compile-time parse error',
		E_NOTICE          => 'Run-time notice',
		E_CORE_ERROR      => 'Fatal error that occured during PHP\'s initial startup',
		E_CORE_WARNING    => 'Warning (non-fatal error) that occured during PHP\'s initial startup',
		E_COMPILE_ERROR   => 'Fatal compile-time errors',
		E_COMPILE_WARNING => 'Compile-time warning (non-fatal error)',
		E_USER_ERROR      => 'User-generated error message',
		E_USER_WARNING    => 'User-generated warning message',
		E_USER_NOTICE     => 'User-generated notice message'
	);
	private $_file;
	private $_line;
	private $_number;
	private $_string;
	
	function __construct($errno, $errstr, $errfile, $errline) {
		parent::__construct();
		$this->_file   = $errfile;
		$this->_line   = $errline;
		$this->_number = $errno;
		$this->_string = $errstr;
	}
	
	function __destruct() {
		parent::__destruct();
	}
	
	public function Show() {
		$tpl =& template::instance();
		$tpl->assign('file', $this->_file);
		$tpl->assign('line', $this->_line);
		$tpl->assign('number', $this->_number);
		$tpl->assign('string', $this->_string);
		$tpl->display('exceptions/phpexception.tpl.php');
		self::__destruct();
	}
	
}

?>