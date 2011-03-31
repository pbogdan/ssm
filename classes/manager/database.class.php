<?php

// +--------------------------------------------------------------------------+
// | database.class.php                                                       |
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
// $Id: database.class.php,v 1.11 2004/09/22 21:21:21 silence Exp $

/**
* @package manager
*/
class database {
	
	/**
	* @var resource Connection to database
	*/
	private $_connection;
	/**
	* @var string Name of table
	*/
	private $_name;
	/**
	* @var array List of database tables
	*/
	private $_tables;

	function __construct($dbName) {
		$this->_name = $dbName;
		$manager = manager::instance();
		$this->_connection =& $manager->getConnection($dbName);
		
		try {
			$q = "SELECT name FROM sqlite_master WHERE type='table' ORDER BY name";
			$r = @sqlite_query($q, $this->_connection);
			
			if(sqlite_last_error($this->_connection)) {
				throw new sqlitexception($this->_connection, $q);
			}
		} catch(sqlitexception $e) {
			$e->Show();
		}
		
		while($table = @sqlite_fetch_array($r, SQLITE_ASSOC)) {
			$name = $table['name'];
			$this->_tables[$name] = NULL;
		}
	}

	/**
	* List of database tables.
	* @return array
	*/
	public function Tables() {
		return @array_keys($this->_tables);
	}
	
	/**
	* Creates new table in database.
	* @param string $tblName Name of table
	* @param array $columns List of column objects
	* @return void
	*/
	public function addTable($tblName, $columns) {
		try {
			if(!@is_array($columns)) {
				throw new siteexception(i18n("Invalid parameter %s", var_export($columns, true)));
			}
			foreach($columns as $col) {
				if(get_class($col) != 'column') {
					throw new siteexception(i18n("Invalid parameter %s", var_export($col, true)));
				}
			}
		} catch(siteexception $e) {
			$e->Show();
		}
		
		// TODO: build table here
		
		$cols = array();

		$export =& export::instance('sql');
		
		foreach($columns as $col) {
			$cols[] = $export->structColumn($col);
		}
		
		$sql = sprintf("CREATE TABLE %s (%s)", $tblName, implode(', ', $cols));
		
		try {
			@sqlite_query($sql, $this->_connection);
			
			if(sqlite_last_error($this->_connection)) {
				throw new sqlitexception($this->_connection, $sql);
			}
		} catch(sqlitexception $e) {
			$e->Show();
		}
		
		$this->_tables[$tblName] = NULL;
	}
	
	/**
	* Name of table
	* @return string
	*/
	public function Name() {
		return $this->_name;
	}
	
	/**
	* Overloading for accessing tables.
	* @param string $var Name of table
	* @return table
	*/
	public function __get($var) {
		if(@array_key_exists($var, $this->_tables)) {
			if(!@is_object($this->_tables[$var])) {
				$dsn = "{$this->_name}:{$var}";
				$this->_tables[$var] = new table($dsn);
			}
			return $this->_tables[$var];
		} else {
			return false;
		}
	}
}

?>