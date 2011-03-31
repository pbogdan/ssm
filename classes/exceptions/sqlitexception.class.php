<?php

// +--------------------------------------------------------------------------+
// | sqliteexception.class.php                                                |
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
// $Id: sqlitexception.class.php,v 1.11 2004/09/24 22:24:19 silence Exp $

/**
* @package core
* @subpackage exception
*/
class sqlitexception extends myexception  {
	
	private $_errorMap = array(
	    0  => "not an error",
	    1  => "SQL logic error or missing database",
	    2  => "internal SQLite implementation flaw",
	    3  => "access permission denied",
	    4  =>"callback requested query abort",
	    5  => "database is locked",
	    6  => "database table is locked",
	    7  => "out of memory",
	    8  => "attempt to write a readonly database",
	    9  => "interrupted",
	    10 => "disk I/O error",
	    11 => "database disk image is malformed",
	    12 => "table or record not found",
	    13 => "database is full",
	    14 => "unable to open database file",
	    15 => "database locking protocol failure",
	    16 => "table contains no data",
	    17 => "database schema has changed",
	    18 => "too much data for one table row",
	    19 => "constraint failed",
	    20 => "datatype mismatch",
	    21 => "library routine called out of sequence",
	    22 => "kernel lacks large file support",
	    23 => "authorization denied",
	    24 => "auxiliary database format error",
	    25 => "bind index out of range",
	    26 => "file is encrypted or is not a database"
	   );
    
    private $_connection;
	private $_query;
	
	function __construct(&$connection, $query) {
		parent::__construct();
		$this->_connection = $connection;
		$this->_query = $query;
	}
	
	function __destruct() {
		parent::__destruct();
	}
	
	public function Show() {
		$tpl =& template::instance();
		$tpl->assign('query', $this->_query);
		$tpl->assign('line', $this->getLine());
		$tpl->assign('file', $this->getFile());
		$tpl->assign('number', sqlite_last_error($this->_connection));
		$tpl->assign('string', sqlite_error_string(sqlite_last_error($this->_connection)));
		$tpl->display('exceptions/sqliteexception.tpl.php');
		self::__destruct();
	}
	
}

?>