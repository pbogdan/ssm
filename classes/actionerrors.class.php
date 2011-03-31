<?php

// +--------------------------------------------------------------------------+
// | actionerrors.class.php                                                   |
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
// $Id: actionerrors.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

/**
* @package MVC
*/
class actionerrors {
	
	private $_key;
	
	function __construct() {
		$cfg =& config::instance();
		$cfg->setGroup('mvc');
		$errorKey = $cfg->getProperty('errorKey');
		$this->_key = $errorKey;
                $_SESSION[$this->_key] = array();
	}
	
	function __destruct() {
		session_write_close();
	}
	
	/**
	* Global instance of actionerrors class.
	* @return actionerrors
	*/
	static function &instance() {
		if(array_key_exists('core.errors', $GLOBALS)) {
			$instance = $GLOBALS['core.errors'];
		} 
		
		if(!@is_object($instance) || get_class($instance) != 'actionerrors') {
			$instance = new actionerrors();
			$GLOBALS['core.errors'] = $instance;
		}
		
		return $instance;
	}
	
	/**
	* Pushes new error onto errors stack.
	* @param string $error Error message
	* @return void
	*/
	public function addError($error) {
		$_SESSION[$this->_key][] = $error;
	}
	
	/**
	* Cleans errors stack.
	* @return void
	*/
	public function Clean() {
		$_SESSION[$this->_key] = array();
	}
	
	/**
	* Displays all errors.
	* @param boolean $cleanUp Whether clean errors stack after displaying.
	* @return void
	*/
	public function Show($cleanUp) {
		if(sizeof($_SESSION[$this->_key])) {
			$tpl =& template::instance();
			$tpl->assign('errors', $_SESSION[$this->_key]);
			$tpl->display('errors.tpl.php');
		}
		if($cleanUp) {
			$this->Clean();
		}
	}
	
}

?>