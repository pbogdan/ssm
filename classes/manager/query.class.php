<?php

// +--------------------------------------------------------------------------+
// | query.class.php                                                          |
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
// $Id: query.class.php,v 1.2 2004/10/16 15:33:03 silence Exp $

require_once(dirname(__FILE__).'/parsers/tokenize.class.php');
require_once(dirname(__FILE__).'/parsers/colorizequery.class.php');
require_once(dirname(__FILE__).'/parsers/selectquery.class.php');

/**
* @package manager
*/
class query extends tokenize {
	
	private $_connection;
	private $_query;
	private $_result;
	private $_success;
	private $_timing;
	
	function __construct($dbName, $query) {
		$manager =& manager::instance();
		$this->_connection =& $manager->getConnection($dbName);
		$this->_query = $query;
		parent::_tokenize($this->_query);
	}
	
	public function getMessage() {
		if($this->_success === false) {
			$result = sqlite_error_string(sqlite_last_error($this->_connection));
			if(empty($result)) {
				$result = i18n('Failed');
			}
			return $result;
		}
		if(parent::_tokenGetPos(tokenize::TK_INSERT) >= 0) {
			return i18n("Inserted row ID: %d", sqlite_last_insert_rowid($this->_connection));
		} else if(parent::_tokenGetPos(tokenize::TK_UPDATE) >= 0) {
			return i18n("Affected rows: %d", sqlite_changes($this->_connection));
		} else if(parent::_tokenGetPos(tokenize::TK_DELETE) >= 0) {
			return i18n("Affected rows: %d", sqlite_changes($this->_connection));
		} else if(parent::_tokenGetPos(tokenize::TK_SELECT) >= 0) {
			return i18n("Selected rows: %d", sqlite_num_rows($this->_result));
		} else {
			return i18n('Success');
		}
	}
	
	public function Execute() {
		$start = $this->_getMicroTime();
		$this->_result = @sqlite_query($this->_query, $this->_connection);
		$end = $this->_getMicroTime();
		
		$this->_timing = $end - $start;
		
		if(sqlite_last_error($this->_connection)) {
			$this->_success = false;
			return false;
		}
		
		$this->_success = true;
		return true;
	}
	
	public function isSelectQuery() {
		if(parent::_tokenGetPos(tokenize::TK_SELECT) >= 0) {
			return true;
		}
		return false;
	}
	
	public function Result() {
		return sqlite_fetch_all($this->_result, SQLITE_ASSOC);
	}
	
	public function getTiming() {
		return $this->_timing;
	}

	private function _getMicroTime() { 
    	list($usec, $sec) = explode(" ", microtime()); 
    	return ((float)$usec + (float)$sec); 
	} 
	
}

?>