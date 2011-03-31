<?php

// +--------------------------------------------------------------------------+
// | xml.class.php                                                            |
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
// $Id: xml.class.php,v 1.2 2004/10/16 15:33:48 silence Exp $

/**
* @package manager
* @subpackage export
*/
class eXML implements exporter {
	
	function __construct() {
	}
	
	public function structColumn(column &$col) {
		$xml  = "\t<column name=\"".$this->_escape($col->name)."\">\n";
		$xml .= "\t\t<type>".$this->_escape($col->type)."</type>\n";
		
		if($col->length) {
			$xml .= "\t\t<length>".$this->_escape($col->length)."</length>\n";
		} else {
			$xml .= "\t\t<length>false</length>\n";
		}
		
		if($col->primary) {
			try {
				if($col->type != 'INTEGER') {
					throw new siteexception(i18n("Can set PRIMARY KEY only on integer field: %s", $col->name));
				}
				$xml .= "\t\t<null>false</null>\n";
				$xml .= "\t\t<default>false</default>\n";
				$xml .= "\t\t<primary>true</primary>\n";
			} catch(siteexception $e) {
				$e->Show();
			}
		} else {
			if(!$col->null) {
				$xml .= "\t\t<null>false</null>\n";
			} else {
				$xml .= "\t\t<null>true</null>\n";
			}
			
			if($col->default) {
				$xml .= "\t\t<default>".$this->_escape($col->default)."</default>\n";
			} else {
				$xml .= "\t\t<default>false</default>\n";
			}
			
			$xml .= "\t\t<primary>false</primary>\n";
		}
		
		$xml .= "	</column>";
		return $xml;
	}
	
	public function structTable(table &$tbl) {
		
		$cols = array();
		
		foreach($tbl->Columns() as $col) {
			$tmp = $tbl->$col;
			$cols[] = $this->structColumn($tmp);
		}
		
		$xml = sprintf("<structure>\n\t<table name=\"%s\">\n%s\n\t</table>\n</structure>", $this->_escape($tbl->Name()), implode("\n", $cols));
		
		return $xml;
	}
	
	public function structDatabase(database &$db) {
		$xml = array();
		
		foreach($db->Tables() as $tbl) {
			$xml[] = $this->structTable($db->$tbl);
		}
		
		return $xml;
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
		
		if(!sqlite_num_rows($r)) {
			return array();
		}
		
		$xml = array();
		$xml[] = "<data>\n\t<table name=\"".$this->_escape($tbl->Name())."\">";
		
		while(sqlite_has_more($r)) {
			$values = array();
			$row = sqlite_fetch_array($r, SQLITE_ASSOC);
			
			$xml[] = "\t\t<row>";
			foreach($row as $col=>$value) {
				$xml[] = "\t\t<column name=\"{$col}\">".$this->_exportData($tbl->$col->type, $value)."</column>";
			}
			
			$xml[] = "\t\t</row>";
		}
		
		$xml[] = "\t</table>\n</data>\n";
		return $xml;
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
			default: {
				$data = sqlite_escape_string($data);
				$data = $this->_escape($data);
				return $data;
			}
		}
	}
	
	public function dataDatabase(database &$db) {
		$xml = array();
		
		foreach($db->Tables() as $tbl) {
			$tmp = $db->$tbl;
			$xml[] = $this->dataTable($tmp);
		}
		
		return $xml;
	}
	
	public function Header() {
		$header = "<pre>&lt!--
Simple SQLite Manager dump
SQLite version: ".sqlite_libversion()."
PHP Version: ".phpversion()."
".i18n("Created on %s", date("d/m/Y G:i:s"))."
-->
&lt;export&gt;</pre>";
		return $header;
	}
	
	public function Footer() {
		return "<pre>&lt;/export&gt;</pre>";
	}
	
	private function _escape($value) {
		$pattern = array(
			'/\&/',
			'/\</',
			'/\>/',
			"/'/",
			'/"/'
		);
		$replace = array(
			'&amp;',
			'&lt;',
			'&gt;',
			'&apos;',
			'&qout;'
		);
		
		return preg_replace($pattern, $replace, $value);
	}
	
}

?>