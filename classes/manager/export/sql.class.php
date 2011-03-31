<?php

// +--------------------------------------------------------------------------+
// | sql.class.php                                                            |
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
// $Id: sql.class.php,v 1.12 2004/10/16 15:33:33 silence Exp $

/**
* @package manager
* @subpackage export
*/
class eSQL implements exporter {
	
	function __construct() {
	}
	
	public function structColumn(column &$col) {
		$sql  = "{$col->name}";
		
		if($col->primary) {
			try {
				if($col->type != 'INTEGER') {
					throw new siteexception(i18n("Can set PRIMARY KEY only on integer field: %s", $col->name));
				}
				$sql .= " INTEGER PRIMARY KEY NOT NULL";
			} catch(siteexception $e) {
				$e->Show();
			}
		} else {
			$sql .= " {$col->type}";
			if($col->length) {
				$sql .= "({$col->length})";
			}
			if(!$col->null) {
				$sql .= " NOT";
			}
			$sql .= " NULL";
			
			if($col->default) {
				$sql .= " DEFAULT {$col->default}";
			}
		}
		
		return $sql;
	}
	
	public function structTable(table &$tbl) {
		
		$cols = array();
		
		foreach($tbl->Columns() as $col) {
			$tmp = $tbl->$col;
			$cols[] = $this->structColumn($tmp);
		}
		
		$sql = sprintf("CREATE TABLE %s (%s);", $tbl->Name(), implode(', ', $cols));
		
		return $sql;
	}
	public function structDatabase(database &$db) {
		$Sql = array();
		
		foreach($db->Tables() as $tbl) {
			$sql[] = $this->structTable($db->$tbl);
		}
		
		return $sql;
	}
	
	public function dataTable(table &$tbl) {
		$manager =& manager::instance();
		$connection =& $manager->getConnection($tbl->Db());
		
		try {
			$q = "SELECT * FROM {$tbl->Name()}";
			$r = @sqlite_query($q, $connection);
		
			if(sqlite_last_error($connection)) {
				throw new sqlitexception($connection, $q);
			}
		} catch(sqlitexception $e) {
			$e->Show();
		}
		
		$sql = array();
		
		while(sqlite_has_more($r)) {
			$values = array();
			$row = sqlite_fetch_array($r, SQLITE_ASSOC);
			
			foreach($row as $col=>$value) {
				$values[] = $this->_exportData($tbl->$col->type, $value);
			}
			
			$sql[] = sprintf("INSERT INTO %s(%s) values (%s);", $tbl->Name(), implode(', ',$tbl->Columns()), implode(', ', $values));
		}
		
		return $sql;
	}
	
	public function Header() {
		$header = "#<br />
# Simple SQLite Manager dump<br />
# SQLite version: ".sqlite_libversion()."<br />
# PHP Version: ".phpversion()."<br />
# ".i18n("Created on %s", date("d/m/Y G:i:s"))."<br />
#";
		return $header;
	}
	
	public function Footer() {
		return '';
	}
	
	private function _exportData($type, $data) {
		
		if(is_null($data)) {
			return "NULL";
		}
		
		$type = strtolower($type);
		
		switch($type) {
			case 'blob': {
				$data = sqlite_udf_encode_binary($data);
			}
			case 'smallint':
			case 'tinyint':
			case 'mediumint':
			case 'bigint':
			case 'integer':
			case 'double':
			case 'float':
			case 'decimal': return $data; break;
			default: {
				$data = sqlite_escape_string($data);
				return "'{$data}'";
			}
		}
	}
	
	public function dataDatabase(database &$db) {
		$sql = array();
		
		foreach($db->Tables() as $tbl) {
			$tmp = $db->$tbl;
			$sql[] = $this->dataTable($tmp);
		}
		
		return $sql;
	}
	
}

?>