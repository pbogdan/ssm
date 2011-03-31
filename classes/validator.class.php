<?php

// +--------------------------------------------------------------------------+
// | validator.class.php                                                      |
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
// $Id: validator.class.php,v 1.11 2004/10/07 09:51:02 silence Exp $

/**
* @package core
*/
class validator {
	
	/**
	* @var array List of exceptions caught during variable validation
	*/
	private $_exceptions = array();
	/**
	* @var array Validation messages
	*/
	private $_messages = array();
	/**
	* @var array List of rules to check variable against
	*/
	private $_rules;
	/**
	* @var mixed Variable to be validated
	*/
	private $_variable;

	function __construct($variable) {
		$this->_variable = $variable;
	}
	
	/**
	* Instance of validator class.
	* @return validator
	*/
	static function instance($variable) {
		return new validator($variable);
	}
	
	/**
	* Creates new rule.
	* @param string $rule Name of rule
	* @param mixed $param Additional parameter to be passed to rule.
	* @return void
	*/
	public function addRule($rule, $param = NULL) {
		$this->_rules[] = array($rule, $param);
	}
	
	/**
	* List of exceptions caught during variable validation
	* @return array
	*/
	public function Exceptions() {
		if(sizeof($this->_exceptions)) {
			return $this->_exceptions;
		}
		return false;
	}
	
	/**
	* Validation messages
	* @return array
	*/
	public function Messages() {
		if(sizeof($this->_messages)) {
			return $this->_messages;
		}
		return false;
	}
	
	/**
	* Checks variable against passed rules.
	* @return boolean
	*/
	public function Process() {
		
		foreach($this->_rules as $i) {
			$rule  = $i[0];
			$param = $i[1];
			$methodName = '_is'.ucfirst($rule);
			$method = new ReflectionMethod('validator', $methodName);
			
			if(sizeof($method->getParameters()) > 1) {
				$result = $this->$methodName($this->_variable, $param);
			} else {
				$result = $this->$methodName($this->_variable);
			}
			
			if($result === false) {
				$this->_exceptions[] = new validatorexception($this->_variable, $rule, $param);
			}
		}
		
		if(sizeof($this->_exceptions)) {
			return false;
		} else {
			return true;
		}
		
	}
	
	private function _isType($var, $type) {
		if(gettype($var) != $type) {
			$this->_messages[] = i18n("is not of type %s", $type);
			return false;
		} else {
			return true;
		}
	}
	
	private function _isEmpty($var) {
		if(@is_array($var)) {
			if(sizeof($var)) {
				return true;
			} else {
				$this->_messages[] = i18n("is empty");
				return false;
			}
		} else {
			if(strlen(trim($var)) > 0) {
				return true;
			} else {
				$this->_messages[] = i18n("is empty");
				return false;
			}
		}
	}
	
	private function _isNull($var) {
		if(is_null($var)) {
			$this->_messages[] = i18n("is NULL");
			return false;
		} else {
			return true;
		}
	}
	
	private function _isDigit($var) {
		if(is_numeric($var)) {
			return true;
		} else {
			$this->_messages[] = i18n("is not numeric");
			return false;
		}
	}
	
	private function _isAlpha($var) {
		if(preg_match('/^[a-z]+$/i', $var)) {
			return true;
		} else {
			$this->_messages[] = i18n("contains also non-alpha characters");
			return false;
		}
	}
	
	private function _isAlphanumeric($var) {
		if(preg_match('/^([a-z]|[0-9])+$/i', $var)) {
			return true;
		} else {
			$this->_messages[] = i18n("contains also non-alphanumeric characters");
			return false;
		}
	}
	
	private function _isMail($var) {
		$pattern = '/^([a-z]|[0-9])+@([a-z]|[0-9])+\.[a-z]{2,3}$/i';
		if(preg_match($pattern, $var)) {
			// getmxrr
			return true;
		} else {
			$this->_messages[] = i18n("is not valid e-mail address");
			return false;
		}
	}
	
	private function _isIRC($var) {
		if(preg_match('/^\#{1}[a-z]|[0-9]+$/i', $var)) {
			return true;
		} else {
			$this->_messages[] = i18n("is not valid IRC channel name");
			return false;
		}
	}
	
	private function _isShorter($var, $length) {
		if(strlen(trim($var)) < $length) {
			return true;
		} else {
			$this->_messages[] = i18n("is longer than %d", $length);
			return false;
		}
	}
	
	private function _isLonger($var, $length) {
		if(strlen(trim($var)) > $length) {
			return true;
		} else {
			$this->_messages[] = i18n("is shorter than %d", $length);
			return false;
		}
	}
	
	private function _isPositive($var) {
		if($this->_isDigit($var) && $var > 0) {
			return true;
		} else {
			$this->_messages[] = i18n("is negative");
			return false;
		}
	}
	
	private function _isLower($var, $cmp) {
		if($this->_isDigit($var) && $var < $cmp) {
			return true;
		} else {
			$this->_messages[] = i18n("is higher than %d", $cmp);
			return false;
		}
	}
	
	private function _isHigher($var, $cmp) {
		if($this->_isDigit($var) && $var > $cmp) {
			return true;
		} else {
			$this->_messages[] = i18n("is lower than %d", $cmp);
			return false;
		}
	}
	
}
?>
