<?php

// +--------------------------------------------------------------------------+
// | colorizequery.class.php                                                  |
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
// $Id: colorizequery.class.php,v 1.2 2004/10/16 16:09:04 silence Exp $

require_once(dirname(__FILE__).'/tokenize.class.php');

/**
* @package manager
* @subpackage parsers
*/
class colorizequery extends tokenize {

	/**
	* @var string SQL Query to be parsed.
	*/
	private $_query;

	function __construct() {
	}
	
	/**
	* Sets text to be highlighted.
	* @param string $query
	* @return void
	*/
	public function setQuery($query) {
		try {
			if(empty($query)) {
				throw new Exception("Empty \$query parameter");
			}
		} catch(Exception $e) {
			$e->getMessage();
		}
		
		$this->_query = $query;
		parent::_tokenize($this->_query);
	}
	
	/**
	* Return highlighted string.
	* @return string
	*/
	public function getQuery() {
		return $this->_parse();
	}
	
	/**
	* Highlights query in $this->_query.
	* @return string
	*/
	private function _parse() {
		// create local copy
		$tokens = $this->_tokens;
		
		$query = '';
		
		while($t = array_shift($tokens)) {
			switch($t['tokenCode']) {
				case tokenize::TK_FLOAT:
				case tokenize::TK_INTEGER:
					$query .= '<font color="#00AA00">'.$t['content'].'</font>';
					break;
				case tokenize::TK_ILLEGAL: // illegal in most cases is just unquoted string, fall through
				case tokenize::TK_STRING:
					$query .= '<font color="#FF0000">'.$t['content'].'</font>';
					break;
				default:
					$query .= '<font color="#0000FF">'.$t['content'].'</font>';
					break;
			}
		}
		return $query;
	}
}
?>