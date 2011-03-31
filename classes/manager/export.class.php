<?php

// +--------------------------------------------------------------------------+
// | export.class.php                                                         |
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
// $Id: export.class.php,v 1.12 2004/10/16 15:32:46 silence Exp $

/**
* @package manager
* @subpackage export
*/
interface exporter {
	public function structColumn(column &$col);
	public function structTable(table &$tbl);
	public function structDatabase(database &$db);
	public function dataTable(table &$tbl);
	public function dataDatabase(database &$db);
	public function Header();
	public function Footer();
}

/**
* @package manager
* @subpackage export
*/
class export {
	
	/**
	* @var array Supported formats
	*/
	private $_formats = array();
	/**
	* @var exporter Object of class implementing exporter in desired format
	*/
	private $_exporter;
	
	function __construct($format) {
		try {
			$d = opendir(dirname(__FILE__).'/export');
			
			if($d === false) {
				throw new ioexception(i18n("Can't open directory %s", dirname(__FILE__).'/export'));
			}
		} catch(ioexception $e) {
			$e->Show();
		}
		
		while(($file = readdir($d)) !== false) {
			if($file == '.' || $file == '..') {
				continue;
			}
			$exporter = explode('.', $file);
			$this->_formats[] = array_shift($exporter);
		}
		
		@closedir($d);
		
		try {
			if(!@in_array($format, $this->_formats)) {
				throw new siteexception(i18n("Unknown format %s", $format));
			}
			require_once(dirname(__FILE__)."/export/{$format}.class.php");
			if(!class_exists("e".strtoupper($format))) {
				throw new siteexception(i18n("Class %s doesn't exist", $format));
			}
			
		} catch(siteexception $e) {
			$e->Show();
		}
		
		$class = "e".strtoupper($format);
		$this->_exporter = new $class();
		
		try {
			if(!($this->_exporter instanceof exporter)) {
				throw new siteexception(i18n("Wrong class %s", $format));
			}
		} catch(siteexception $e) {
			$e->Show();
		}
	}
	
	/**
	* Wrapper for function calls to exporter object
	* @param string $method Name of method
	* @param mixed $params List of parameters
	*/
	public function __call($method, $params) {
		try {
			if(!method_exists($this->_exporter, $method)) {
				throw new siteexception(i18n("Method %s doesn't exist", $method));
			}
		} catch(siteexception $e) {
			$e->Show();
		}
		
		return $this->_exporter->$method(array_shift($params));
	}
	
	/**
	* Instance of exporter class.
	* @return validator
	*/
	static function &instance($format) {
		$instance = new export($format);
		return $instance;
	}
	
}


?>