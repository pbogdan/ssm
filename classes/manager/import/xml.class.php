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
// $Id: xml.class.php,v 1.2 2004/10/17 12:32:14 silence Exp $

/**
* @package manager
* @subpackage import
*/
class iXML implements importer {
	
	private $_buffer;
	private $_queries;
	
	function __construct($buffer) {
		$this->_buffer = $buffer;
		$this->_parse();
	}
	
	private function _parse() {
		$xml =& simplexml_load_string($this->_buffer);
		
		// import structure
		if($xml->structure->table instanceof SimpleXMLElement) {
			foreach($xml->structure->table as $table) {
				$cols = array();
				$export = new export('sql');
				
				foreach($table->column as $col) {
					$c = column::fromMemory(
						array(
							'default' => ((string)$col->default == 'false') ? false : (string)$col->default,
							'length'  => ((string)$col->length == 'false') ? false : (string)$col->length,
							'name'    => (string)$col['name'],
							'null'    => ((string)$col->null == 'false') ? false : true,
							'primary' => ((string)$col->primary == 'false') ? false : true,
							'type'    => (string)$col->type
						)
					);
					$cols[] = $export->structColumn($c);
				}
				$this->_queries[] = sprintf("CREATE TABLE %s (%s)", (string)$table['name'], implode(', ', $cols));
			}
		}
		
		// import data
		if($xml->data->table instanceof SimpleXMLElement) {
			foreach($xml->data->table as $table) {
				foreach($table->row as $row) {
					$cols = array();
					$values = array();

					foreach($row->column as $col) {
						$cols[] = (string)$col['name'];
						$values[] = $this->_importData($this->_unescape((string)$col));
					}
					
					$this->_queries[] = sprintf("INSERT INTO %s(%s) values (%s)", (string)$table['name'], implode(', ', $cols), implode(', ', $values));
				}
			}
		}
	}
	
	private function _importData($value) {
		if(is_numeric($value)) {
			return $value;
		} else if($value == 'NULL') {
			return 'NULL';
		} else {
			return "'$value'";
		}
	}
	
	private function _unescape($value) {
		$pattern = array(
			'/&amp;/',
			'/&lt;/',
			'/&gt;/',
			'/&apos;/',
			'/&qout;/'
		);
		$replace = array(
			'&',
			'<',
			'>',
			"'",
			'"'
		);
		
		return preg_replace($pattern, $replace, $value);
	}
	
	public function getQuery() {
		return array_shift($this->_queries);
	}
	
	public function Queries() {
		return $this->_queries;
	}
	
}

?>