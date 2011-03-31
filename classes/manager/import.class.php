<?php

// +--------------------------------------------------------------------------+
// | import.class.php                                                         |
// +--------------------------------------------------------------------------+
// | Copyright (c) 2004 Piotr Bogdan <silence@dotgeek.org>                    |
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
// $Id: import.class.php,v 1.11 2004/09/22 21:45:02 silence Exp $

/**
* @package manager
* @subpackage import
*/
interface importer {
	public function getQuery();
	public function Queries();
}

/**
* @package manager
* @subpackage import
*/
class import {
	
	/**
	* @var array Supported formats
	*/
	private $_formats = array();
	/**
	* @var exporter Object of class implementing importer in desired format
	*/
	private $_importer;
	
	function __construct($format, $buffer) {
		try {
			$d = opendir(dirname(__FILE__).'/import');
			
			if($d === false) {
				throw new ioexception(i18n("Can't open directory %s", dirname(__FILE__).'/import'));
			}
		} catch(ioexception $e) {
			$e->Show();
		}
		
		while(($file = readdir($d)) !== false) {
			if($file == '.' || $file == '..') {
				continue;
			}
			$importer = explode('.', $file);
			$this->_formats[] = array_shift($importer);
		}
			
		@closedir($d);
		
		try {
			if(!@in_array($format, $this->_formats)) {
				throw new siteexception(i18n("Unknown format %s", $format));
			}
			require_once(dirname(__FILE__)."/import/{$format}.class.php");
			
			if(!class_exists("i".strtoupper($format))) {
				throw new siteexception(i18n("Class %s doesn't exist", $format));
			}
			
		} catch(siteexception $e) {
			$e->Show();
		}
		
		$class = "i".strtoupper($format);
		$this->_importer = new $class($buffer);
		
		try {
			if(!($this->_importer instanceof importer)) {
				throw new siteexception(i18n("Wrong class %s", $format));
			}
		} catch(siteexception $e) {
			$e->Show();
		}
	}
	
	/**
	* Wrapper for function calls to importer object
	* @param string $method Name of method
	* @param mixed $params List of parameters
	*/
	public function __call($method, $params) {
		try {
			if(!method_exists($this->_importer, $method)) {
				throw new siteexception(i18n("Method %s doesn't exist", $method));
			}
		} catch(siteexception $e) {
			$e->Show();
		}
		
		return $this->_importer->$method(array_shift($params));
	}
	
	/**
	* Instance of importer class.
	* @return validator
	*/
	static function &instance($format = NULL) {
		
		if(is_null($format) && array_key_exists('app.import', $_SESSION)) {
			$instance = $_SESSION['app.import'];
			unset($_SESSION['app.import']);
			session_write_close();
			session_start();
		} else {
			$instance = new import($format);
		}
		
		return $instance;
	}
}


?>