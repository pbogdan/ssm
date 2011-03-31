<?php

// +--------------------------------------------------------------------------+
// | manager.class.php                                                        |
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
// $Id: manager.class.php,v 1.13 2004/10/15 16:25:31 silence Exp $

require_once(dirname(__FILE__).'/column.class.php');
require_once(dirname(__FILE__).'/database.class.php');
require_once(dirname(__FILE__).'/export.class.php');
require_once(dirname(__FILE__).'/functions.class.php');
require_once(dirname(__FILE__).'/import.class.php');
require_once(dirname(__FILE__).'/query.class.php');
require_once(dirname(__FILE__).'/table.class.php');

/**
* @package manager
*/
class manager {
	
	/**
	* @var array List of databases to manage
	*/
	private $_databases = array();
	
	function __construct() {
		$cfg = config::instance();
		$cfg->setGroup('general');
		
		$dbsFile = $cfg->getProperty('dbsFile');
		
		try {
			if(!file_exists($dbsFile)) {
				throw new ioexception("File $dbsFile doesn't exist");
			} else {
				$xml = simplexml_load_file($dbsFile);
				$this->_readDatabases($xml);
			}
		} catch(ioexception $e) {
			$e->Show();
		}
	}
	
	/**
	* Global instance of manager object.
	* @return manager
	*/
	static function &instance() {
		if(array_key_exists('app.manager', $GLOBALS)) {
			$instance = $GLOBALS['app.manager'];
		} 
		
		if(!@is_object($instance) || get_class($instance) != 'manager') {
			$instance = new manager();
			$GLOBALS['app.manager'] = $instance;
		}
		
		return $instance;
	}
	
	/**
	* Processes SimpleXMLElement to get list of databases.
	* @param SimpleXMLElement &$xml Object holding list of databases
	* @return void
	*/
	private function _readDatabases(SimpleXMLElement &$xml) {
		foreach($xml->database as $database) {
			if((string)$database['visible'] == 'false') {
				continue;
			}
			
			$name = (string)$database['name'];
			$path = (string)$database['path'];
			
			try {
				if(!file_exists($path)) {
					throw new ioexception("Database {$path} doesn't exist");
				}
				$connection = @sqlite_open($path, 0666);
				
				if(!is_resource($connection)) {
					throw new ioexception("Can't connect to database {$path}");
				}
				$this->_databases[] = array(
					'name' => $name,
					'path' => $path,
					'connection' => $connection
				);
			} catch(ioexception $e) {
				$e->Show();
			}
		}
	}
	
	/**
	* List of databases.
	* @return array
	*/
	public function getDatabases() {
		return $this->_databases;
	}
	
	/**
	* Connection/name/path for specified database.
	* @param string $dbName Name of database
	* @return array
	*/
	public function getDatabase($dbName) {
		foreach($this->getDatabases() as $db) {
			if($db['name'] == $dbName) {
				return $db;
			}
		}
		return false;
	}
	
	/**
	* Connection resource for specified database.
	* @param string $dbName Name of database
	* @return resource
	*/
	public function &getConnection($dbName) {
		foreach($this->_databases as $k=>$database) {
			if($database['name'] == $dbName) {
				return $this->_databases[$k]['connection'];
			}
		}
		return false;
	}
}

?>