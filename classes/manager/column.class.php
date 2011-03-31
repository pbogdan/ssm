<?php

// +--------------------------------------------------------------------------+
// | column.class.php                                                         |
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
// $Id: column.class.php,v 1.2 2004/09/20 21:53:31 silence Exp $

/**
* @package manager
*/
class column {
	
	/**
	* @var array Holds column properties
	*/
	private $_properties;
	
	function __construct() {
	}
	
	/**
	* Overloading for column properties.
	* @param string $var Name of property
	* @return mixed
	*/
	public function __get($var) {
		if(@array_key_exists($var, $this->_properties)) {
			return $this->_properties[$var];
		}
		return false;
	}
	
	/**
	* Overloading for column properties.
	* @param string $var Name of property
	* @param mixed $value Value of property
	*/
	public function __set($var, $value) {
		$this->_properties[$var] = $value;
	}
	
	/**
	* Instance of column with properties fetched from DB.
	* @param string $dsn Path to column in format db:table:column
	* @return column
	*/
	static function fromDB($dsn) {
		list($db, $tbl, $col) = explode(':', $dsn);
		$manager =& manager::instance();
		$connection =& $manager->getConnection($db);
		
		try {
			$q = "PRAGMA TABLE_INFO($tbl)";
			$r = @sqlite_query($q, $connection);
			
			if(sqlite_last_error($connection)) {
				throw new sqlitexception($connection, $q);
			}
		} catch(sqlitexception $e) {
			$e->Show();
		}
		
		while($column = @sqlite_fetch_array($r, SQLITE_ASSOC)) {
			if($column['name'] == $col) {
				break;
			}
		}
		
		$col = new column();	
		
		$col->default = $column['dflt_value'];
		
		if(strchr($column['type'], '(')) {
			preg_match("#\(([0-9]+)\)#", $column['type'], $matches);
			$col->length = $matches[1];
			$col->type   = preg_replace("#\(([0-9]+)\)#", "", $column['type']);
		} else {
			$col->type   = $column['type'];
			$col->length = 0;
		}
		
		$col->name     = $column['name'];
		$col->null     = !$column['notnull'];
		$col->primary  = $column['pk'];
		
		/*if($col->primary) {
			$col->null = false;
		}*/
		
		return $col;
	}
	
	/**
	* Instance of column with properties read from array.
	* @param array $array List of column properties
	* @return column
	*/
	static function fromMemory($array) {
		$col = new column();
		$col->default = $array['default'];
		$col->length  = $array['length'];
		$col->name    = $array['name'];
		$col->null    = $array['null'];
		$col->primary = $array['primary'];
		$col->type    = $array['type'];
		return $col;
	}
	
	
}

?>