<?php

// +--------------------------------------------------------------------------+
// | table.class.php                                                          |
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
// $Id: table.class.php,v 1.12 2004/09/28 22:04:18 silence Exp $

/**
* @package manager
*/
class table {
	/**
	* @var string Name of database that table belongs to
	*/
	private $_db;
	/**
	* @var array List of columns
	*/
	private $_columns;
	/**
	* @var resource Connection to database
	*/
	private $_connection;
	/**
	* @var string Name of table
	*/
	private $_name;
	
	function __construct($dsn) {
		list($this->_db, $this->_name) = explode(':', $dsn);
		$manager =& manager::instance();
		$this->_connection =& $manager->getConnection($this->_db);
		
		try {
			$q = "PRAGMA TABLE_INFO($this->_name)";
			$r = @sqlite_query($q, $this->_connection);
			
			if(sqlite_last_error($this->_connection)) {
				throw new sqlitexception($this->_connection, $q);
			}
		} catch(sqlitexception $e) {
			$e->Show();
		}
		
		while($column = @sqlite_fetch_array($r, SQLITE_ASSOC)) {
			$name = $column['name'];
			$this->_columns[] = column::fromDB("$dsn:$name");
		}
	}

	/**
	* Overloading for accessing columns.
	* @param $var Name of column
	* @return column
	*/
	public function __get($var) {
		foreach($this->_columns as $col) {
			if($col->name == $var) {
				return $col;
			}
		}
		return false;
	}
	
	/**
	* Name of table.
	* @return string
	*/
	public function Name() {
		return $this->_name;
	}
	
	/**
	* Name of database.
	* @return string
	*/
	public function Db() {
		return $this->_db;
	}
	
	/**
	* Overloading for updating columns.
	* @param string $var Name of column
	* @param column $value Column instance
	* @return boolean
	*/
	public function __set($var, $value) {
		if(!($value instanceof column)) {
			return false;
		}
		foreach($this->_columns as $k=>$col) {
			if($col->name == $var) {
				$this->_columns[$k] = $value;
			}
		}
		return true;
	}
	
	/**
	* Updates table definition.
	* @return boolean
	*/
	public function Update() {
		return $this->_alter();
	}
	
	/**
	* Drops primary key.
	* @return boolean
	*/
	public function dropPrimary() {
		foreach($this->_columns as $k=>$col) {
			$this->_columns[$k]->primary = false;
		}
		return $this->_alter();
	}
	
	/**
	* Whether table has primary key.
	* @return boolean
	*/
	public function hasPrimary() {
		foreach($this->Columns() as $column) {
			if($this->$column->primary) {
				return true;
			}
		}
		return false;
	}
	
	/**
	* Sets primary key on specified column.
	* @param column &$col Column object
	* @return boolean
	*/
	public function setPrimary(column &$col) {
		$name = $col['name'];
		$this->$name->primary = true;
		return $this->_alter();
	}
	
	/**
	* Creates new columns in table.
	* @param array $columns List of columns
	* @param string $position Where to insert columns
	* @return boolean
	*/
	public function addColumns($columns, $position) {
		switch($position) {
			case 'start': {
				while($col = array_shift($columns)) {
					array_unshift($this->_columns, $col);
				}
				break;
			}
			case 'end': {
				while($col = array_shift($columns)) {
					$this->_columns[] = $col;
				}
				break;
			}
			default: {
				$newColumns = array();
				while($col = array_shift($this->_columns)) {
					$newColumns[] = $col;
					if($col->name == $position) {
						foreach($columns as $c) {
							$newColumns[] = $c;
						}
					}
				}
				$this->_columns = $newColumns;
				break;
			}
		}
		
		return $this->_alter();
	}
	
	/**
	* Rebuilds table. Tries to determine columns additions/deletions.
	* @return boolean
	*/
	private function _alter() {
		$queries = array();
		
		$queries[] = ('BEGIN TRANSACTION');
		$q = "SELECT * FROM _ssm_backup";
		$r = @sqlite_query($q, $this->_connection);
		
		if(!sqlite_last_error($this->_connection)) {
			sqlite_query("DROP TABLE _ssm_backup", $this->_connection);
		}
		
		$export = new export('sql');
		
		@sqlite_query("CREATE TABLE _ssm_backup AS SELECT * FROM {$this->_name}", $this->_connection);
		$t = new table("{$this->_db}:_ssm_backup");
		
		$queries[] = "DROP TABLE {$this->_name}";

		$sql = $export->structTable($this);
		$queries[] = $sql;
		$insertQuery = $this->_alterBuildInsertQuery($t);
		$queries[] = $insertQuery;
		$queries[] = "COMMIT TRANSACTION";
		
		$ret = true;

		foreach($queries as $query) {
			@sqlite_query($query, $this->_connection);
			if(@sqlite_last_error($this->_connection)) {
				$ret = false;
			}
		}
		
		@sqlite_query("DROP TABLE _ssm_backup", $this->_connection);
		return $ret;
	}
	
	private function _alterBuildInsertQuery(table &$oldTable) {
		$newColumns = array();
		if(sizeof($oldTable->Columns()) < sizeof($this->Columns())) { // new columns were added
			foreach($this->Columns() as $col) {
				if($this->$col->primary) {
					$newColumns[] = 'NULL';
				} else {
					if(in_array($col, $oldTable->Columns())) {
						$newColumns[] = $col;
					} else {
						if($this->$col->default) {
							$newColumns[] = "'".sqlite_escape_string($this->$col->default)."'";
						} else if($this->$col->null) {
							$newColumns[] = 'NULL';
						} else {
							$newColumns[] = "''";
						}
					}
				}
			}
			$insertQuery = "INSERT OR ROLLBACK INTO {$this->_name} (".implode(',', $this->Columns()).") SELECT ".implode(',', $newColumns)." FROM _ssm_backup";
		} else if(sizeof($oldTable->Columns()) > sizeof($this->Columns())) { // columns were deleted
			$newColumns = $this->Columns();
			$insertQuery = "INSERT OR ROLLBACK INTO {$this->_name} (".implode(',', $this->Columns()).") SELECT ".implode(',', $newColumns)." FROM _ssm_backup";
		} else { //  properties of columns were changed
		
			$oldTable = new table("{$this->_db}:{$this->_name}");
			$notNullColumns = array();
			
			foreach($this->Columns() as $c) {
				$newCol = $this->$c;
				$oldCol = $oldTable->$c;
				if(!$newCol->null && $oldCol->null) {
					$notNullColumns[] = $c;
				}
			}
			if(sizeof($notNullColumns) == 0) {
				$insertQuery = "INSERT OR ROLLBACK INTO {$this->_name} (".implode(',', $this->Columns()).") SELECT ".implode(',', $this->Columns())." FROM _ssm_backup";
			} else {
			
				$firstStepColumns = array();
				
				foreach($notNullColumns as $column) {
					$firstStepColumns[] = "{$c} NOT NULL";
				}
				
				$firstStep = sprintf("SELECT * FROM %s WHERE %s", '_ssm_backup', implode(' AND ', $firstStepColumns));
				
				$replace = array();
				
				foreach($this->Columns() as $c) {
					if(in_array($c, $notNullColumns)) {
						$replace[] = "''";
					} else {
						$replace[] = $c;
					}
				}
				
				$secondStepColumns = array();
				
				foreach($notNullColumns as $column) {
					$secondStepColumns[] = "{$c} IS NULL";
				}
				
				$secondStep = sprintf("SELECT %s FROM %s WHERE %s", implode(', ', $replace), '_ssm_backup', implode(' AND ', $secondStepColumns));
				
				$q = sprintf("%s UNION %s", $firstStep, $secondStep);
				$insertQuery = "INSERT OR ROLLBACK INTO {$this->_name} (".implode(',', $this->Columns()).") {$q}";
			}
		}
		return $insertQuery;
	}
	
	/**
	* Drops column.
	* @param column &$col Column object
	* @return boolean
	*/
	public function dropColumn(column &$col) {
		try {
			if(!in_array($col->name, $this->Columns())) {
				throw new siteexception(i18n("Unknown column %s", $col->name));
			}
		} catch(siteexception $e) {
			$e->Show();
		}
		
		foreach($this->_columns as $k=>$c) {
			if($c->name == $col->name) {
				unset($this->_columns[$k]);
				break;
			}
		}
		
		return $this->_alter();
	}
	
	public function Clean() {
	}
	
	/**
	* List of columns.
	* @return array
	*/
	public function Columns() {
		$cols = array();
		
		foreach($this->_columns as $col) {
			$cols[] = $col->name;
		}
		
		return $cols;
	}
	
	/**
	* Number of rows that table holds.
	* @return integer
	*/
	public function numRows() {
		$q = "SELECT COUNT(*) FROM {$this->_name}";
		$r = @sqlite_query($q, $this->_connection);
		list($numRows) = sqlite_fetch_array($r, SQLITE_NUM);
		return $numRows;
	}
	
}

?>