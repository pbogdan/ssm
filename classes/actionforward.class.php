<?php

// +--------------------------------------------------------------------------+
// | actionforward.class.php                                                  |
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
// $Id: actionforward.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

/**
* @package MVC
*/
class actionforward {
	
	private $_module;
	private $_name;
	private $_path;
	private $_view;
	
	function __construct($name, $module, $view) {
		$this->_name  = $name;
		$this->_path  = "index.php/{$module}/{$view}";
	}
	
	/**
	* Appends new param to query string.
	* @param string $name Name of parameter
	* @param mixed $value Value of parameter
	* @return void
	*/
	public function addParam($name, $value) {
		if(strchr($this->_path, '?')) {
			$this->_path .= "&$name=$value";
		} else {
			$this->_path .= "?$name=$value";
		}
	}
	
	/**
	* Cleans all pending errors.
	* @return void
	*/
	public function cleanUp() {
		$actionError =& actionerrors::instance();
		$actionError->Clean();
	}

	/**
	* Name of forward.
	* @return string
	*/
	public function Name() {
		return $this->_name;
	}
	
	/**
	* Builds URL and redirects to forward location.
	* @return void
	*/
	public function redirect() {
		$path = $_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']);
		header("location: http://$path/{$this->_path}");
		exit;
	}
	
}

?>