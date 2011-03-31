<?php

// +--------------------------------------------------------------------------+
// | sql.class.php                                                            |
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
// $Id: sql.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

/**
* @package manager
* @subpackage import
*/
class iSQL implements importer  {
	
	private $_buffer;
	private $_queries;
	
	function __construct($buffer) {
		$this->_buffer = $buffer;
		$this->_parse();
	}
	
	private function _parse() {
		$sql = $this->_buffer;
		$sql_length = strlen($sql);
		
		$char = '';
		$into_string = false;
		$string_start = '';
		
		$time = time();
		
		$query = array();
		
		for($i = 0; $i < $sql_length; $i++) {
			$char = $sql[$i];
			
			if($into_string) {
				for(;;) {
					$i = strpos($sql, $string_start, $i);
					if(!$i) {
						$this->_queries[] = $sql;
						return true;
					} else if ($string_start == '`' || $sql[$i-1] != '\\') {
                        $string_start = '';
                        $into_string  = false;
                        break;
					} else {
						$i++;
					}
				}
			} else {
				if($char == ';') {
					$this->_queries[] = substr($sql, 0, $i);
					$sql = ltrim(substr($sql, min($i + 1, $sql_length)));
					$sql_length = strlen($sql);
					
					if($sql_length) {
						$i = -1;
					} else {
						return true;
					}
					
				} else if($char == '"' || ($char == '\'' && $sql[$i-1] != '\'') || $char == '`') {
					$into_string = true;
					$string_start = $char;
				} else if($char == '#' || ($char == ' ' && $i > 1 && $sql[$i-1].$sql[$i-2] == '--')) {
					$comment_start = ($char == '#') ? $i : ($i-2);
					$comment_end   = strpos($sql, "\n", $i);
					if(!$comment_end) {
						$this->_queries[] = $sql;
						return true;
					} else {
						$sql = substr($sql, 0, $start_of_comment).ltrim(substr($sql, $end_of_comment));
                    	$sql_length = strlen($sql);
                    	$i--;
					}
				}
			}
			
			if($time < time() - 30) {
				$time = time();
				header('Ping: Pong');
			}
		}
		
		if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
            $this->_queries[] = $sql;
        }
        
        unset($this->_buffer);
        return true;
	}
	
	public function getQuery() {
		return array_shift($this->_queries);
	}
	
	public function Queries() {
		return $this->_queries;
	}
	
}

?>