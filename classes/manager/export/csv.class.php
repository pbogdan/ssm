<?php

// +--------------------------------------------------------------------------+
// | csv.class.php                                                            |
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
// $Id: csv.class.php,v 1.2 2004/10/16 15:33:33 silence Exp $

/**
* @package manager
* @subpackage export
*/
class eCSV implements exporter {
	
	function __construct() {
	}
	
	public function structColumn(column &$col) {
		return '';
	}
	
	public function structTable(table &$tbl) {
		return '';
	}
	
	public function structDatabase(database &$db) {
		return array();
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
		
		$csv = array();
		
		while(sqlite_has_more($r)) {
			$values = array();
			$row = sqlite_fetch_array($r, SQLITE_ASSOC);
			
			foreach($row as $col=>$value) {
				$values[] = $this->_exportData($tbl->$col->type, $value);
			}
			
			$csv[] = implode("\t", $values);
		}
		
		return $csv;
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
			return '"\n"';
		}
		
		$type = strtolower($type);
		
		switch($type) {
			case 'blob': {
				$data = sqlite_udf_encode_binary($data);
			}
			case 'integer':
			case 'double':
			case 'float':
			case 'decimal': return $data; break;
			default: {
				$data = addcslashes($data, "\t\\");
				return '"'.$data.'"';
			}
		}
	}
	
	public function dataDatabase(database &$db) {
		return array();
	}
}

?>