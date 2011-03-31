<?php

// +--------------------------------------------------------------------------+
// | selectquery.class.php                                                    |
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
// $Id: selectquery.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

require_once(dirname(__FILE__).'/tokenize.class.php');

/**
* @package manager
* @subpackage parsers
*/
class selectquery extends tokenize {
	
	private $_query;
	
	function __construct($query) {
		try {
			if(empty($query)) {
				throw new Exception("Empty SQL query.");
			}
			if(!$this->_tokenGetPos(tokenize::TK_SELECT)) {
				throw new Exception("Missing TK_SELECT token");
			}
		} catch(Exception $e) {
			print $e->getMessage();
		}
		
		$this->_query = $query;
		
		parent::_tokenize($this->_query);
	}
	
	public function hasLimit() {
		if(($pos = parent::_tokenGetPos(tokenize::TK_LIMIT)) < 0) {
			return false;
		} else {
			if($this->_tokenGetPos(tokenize::TK_OFFSET) > 0) {
				return (parent::_tokenGetPos(tokenize::TK_LIMIT) + 2);
			} else {
				
				$pos = parent::_tokenGetPos(tokenize::TK_LIMIT);
				$pos++; // on TK_SPACE
				$pos++; // on TK_INTEGER
				
				$pos++; // what's that?
				
				if($this->_tokens[$pos]['tokenCode'] == tokenize::TK_SPACE) { // if space, skip it
					$pos++;
				}
				
				if($this->_tokens[$pos]['tokenCode'] == tokenize::TK_COMMA) {
					$pos++;
					if($this->_tokens[$pos]['tokenCode'] == tokenize::TK_SPACE) { // skip spaces
						$pos++;
					}
				} else {
					$pos--;
				}
				
				return $pos;
			}
		}
	}
	
	public function setLimit($limit) {
		if(($pos = self::hasLimit()) === false) {
			if(($pos = self::hasOffset()) === false) {
				if(($pos = self::hasOrderBy()) === false) {
					// at the end
					$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
					$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_LIMIT, 'content' => 'LIMIT'));
					$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
					$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_INTEGER, 'content' => $limit));
				} else {
					// before ORDER BY
					$pos = $this->_tokenGetPos(parent::TK_ORDER);
					$pos--;
					$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
					$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_LIMIT, 'content' => 'LIMIT'));
					$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
					$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_INTEGER, 'content' => $limit));
				}
			} else {
				// before OFFSET
				$pos = $this->_tokenGetPos(parent::TK_OFFSET);
				$pos--;
				$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
				$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_LIMIT, 'content' => 'LIMIT'));
				$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
				$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_INTEGER, 'content' => $limit));
			}
		} else {
			$this->_replaceToken($pos, array('tokenCode' => tokenize::TK_INTEGER, 'content' => $limit));
		}
	}
	
	public function getLimit() {
		if(($pos = self::hasLimit()) === false) {
			return false;
		} else {
			return $this->_tokens[$pos]['content'];
		}
	}
	
	public function hasOffset() {
		/**
		* OFFSET can be given in ways like this:
		* - OFFSET x
		* - LIMIT x, y
		**/

		if(($pos = parent::_tokenGetPos(tokenize::TK_OFFSET)) < 0) { // no OFFSET token, search for LIMIT
			
			if(($pos = parent::_tokenGetPos(tokenize::TK_LIMIT)) < 0) { // no LIMIT, so no OFFSET
				return false;
			}
			
			$pos++; // move to TK_SPACE
			$pos++; // move to TK_INTEGER
			
			$pos++; // move to next token
			if($this->_tokens[$pos]['tokenCode'] == tokenize::TK_SPACE) { // skip spaces
				$pos++;
			}

			// now $pos points to something that is not space
			if($this->_tokens[$pos]['tokenCode'] == tokenize::TK_COMMA) { // if this is comma we've got something like: #LIMIT x\s*,\s*y#
				$pos--;
				if($this->_tokens[$pos]['tokenCode'] == tokenize::TK_SPACE) $pos--;
				return $pos;
			}
			
			return false; // there were only LIMIT y
		} else {
			return ($pos + 2);
		}
	}
	
	public function setOffset($offset) {
		if(($pos = self::hasOffset()) === false) {
			if(($pos = self::hasOrderBy()) === false) {
				// no ORDER BY, insert OFFSET x at the end
				$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
				$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_OFFSET, 'content' => 'OFFSET'));
				$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
				$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_INTEGER, 'content' => $offset));
			} else {
				$pos = $this->_tokenGetPos(parent::TK_ORDER);
				$pos--;
				$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
				$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_OFFSET, 'content' => 'OFFSET'));
				$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
				$this->_insertToken($pos++, array('tokenCode' => tokenize::TK_INTEGER, 'content' => $offset));
			}
		} else {
			$this->_tokens[$pos]['content'] = $offset;
		}
	}
	
	public function getOffset() {
		if(($pos = self::hasOffset()) === false) {
			return false;
		} else {
			return $this->_tokens[$pos]['content'];
		}
	}
	
	public function hasOrderBy() {
		if(($pos = parent::_tokenGetPos(tokenize::TK_ORDER)) < 0) {
			return false;
		} else {
			return ($pos + 4);
		}
	}
	
	public function setOrderBy($order) {
		if(($pos = self::hasOrderBy()) === false) {
			// no ORDER BY found
			if(($pos = $this->_tokenGetPos(tokenize::TK_LIMIT) < 0)) {
				// no LIMIT, search for OFFSET
				if(($pos = $this->_tokenGetPos(tokenize::TK_OFFSET) < 0)) {
					// no, LIMIT no OFFSET - insert at the end of query
					$pos = $this->_tokenGetPos(tokenize::TK_OFFSET);

					$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
					$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_ORDER, 'content' => 'ORDER'));
					$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
					$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_BY, 'content' => 'BY'));
					$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
					$this->_insertToken(sizeof($this->_tokens), array('tokenCode' => tokenize::TK_ILLEGAL, 'content' => $order));
				} else {
					// there is OFFSET, no LIMIT - before OFFSET
					
					$pos = $this->_tokenGetPos(tokenize::TK_OFFSET);
					$this->_insertToken(($pos - 1), array('tokenCode' => tokenize::TK_ILLEGAL, 'content' => $order));
					$this->_insertToken(($pos - 1), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
					$this->_insertToken(($pos - 1), array('tokenCode' => tokenize::TK_BY, 'content' => 'BY'));
					$this->_insertToken(($pos - 1), array('tokenCode' => tokenize::TK_ORDER, 'content' => 'ORDER'));
					$this->_insertToken(($pos - 1), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
				}
			} else {
				// LIMIT found, doesn't matter if there is OFFSET - before LIMIT
				$pos = $this->_tokenGetPos(tokenize::TK_LIMIT);
				$this->_insertToken(($pos - 1), array('tokenCode' => tokenize::TK_ILLEGAL, 'content' => $order));
				$this->_insertToken(($pos - 1), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
				$this->_insertToken(($pos - 1), array('tokenCode' => tokenize::TK_BY, 'content' => 'BY'));
				$this->_insertToken(($pos - 1), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
				$this->_insertToken(($pos - 1), array('tokenCode' => tokenize::TK_ORDER, 'content' => 'ORDER'));
				$this->_insertToken(($pos - 1), array('tokenCode' => tokenize::TK_SPACE, 'content' => ' '));
			}
		} else {
			// ORDER BY - replacement for current order
			$pos = self::hasOrderBy();
			$this->_replaceToken($pos, array('tokenCode' => tokenize::TK_ILLEGAL, 'content' => $order));
		}
	}
	
	public function getOrderBy() {
		if(($pos = self::hasOrderBy()) === false) {
			return false;
		} else {
			return $this->_tokens[$pos]['content'];
		}
	}
	
	public function dumpTokens() {
		print nl2br(var_export($this->_tokens, true));
	}
	
	public function getQuery() {
		$query = '';
		
		$tokens = $this->_tokens;
		
		while($t = array_shift($tokens)) {
			$query .= $t['content'];
		}
		
		return $query;
	}
	
}

?>