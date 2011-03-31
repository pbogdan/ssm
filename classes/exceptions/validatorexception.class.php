<?php

// +--------------------------------------------------------------------------+
// | validatorexception.class.php                                             |
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
// $Id: validatorexception.class.php,v 1.12 2004/09/26 20:08:28 silence Exp $

/**
* @package core
* @subpackage exception
*/
class validatorexception extends myexception {
	
	private $_param;
	private $_rule;
	private $_variable;
	
	function __construct($variable, $rule, $param) {
		//parent::__construct();
		
		$this->_variable = $variable;
		$this->_rule = $rule;
		$this->_param = $param;
	}
	
	function __destruct() {
		//parent::__destruct();
	}
	
	public function Show() {
		//self::__destruct();
	}
	
}

?>