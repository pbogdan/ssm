<?php

// +--------------------------------------------------------------------------+
// | actionform.class.php                                                     |
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
// $Id: actionform.class.php,v 1.11 2004/09/27 20:01:06 silence Exp $

/**
* @package MVC
*/
class actionform {
	
	private $_descriptions;
	private $_validators;
	private $_vars;
	
	function __construct() {
		$this->_vars =& $_REQUEST;
	}
	
	/**
	* Return form var.
	* @param string $var Name of variable
	* @return mixed
	*/
	public function getVar($var) {
		if(!@array_key_exists($var, $this->_vars)) {
			return false;
		}
		return $this->_vars[$var];
	}
	
	/**
	* All names of variables in current form.
	* @return array
	*/
	public function listVars() {
		return array_keys($this->_vars);
	}
	
	/**
	* Adds validator for form variable.
	* @param string $var Name of variable
	* @param validator $validator Validator object with set of rules
	* @return void
	*/
	public function addValidator($var, validator $validator) {
		if(!@array_key_exists($var, $this->_vars)) {
		}
		$this->_validators[$var] = $validator;
	}
	
	public function addDescription($var, $description) {
		settype($description, 'string');
		$this->_descriptions[$var] = $description;
	}
	
	/**
	* Return description of form variable.
	* @param string $var Name of variableZ
	* @return string
	*/
	public function getDescription($var) {
		if(!@array_key_exists($var, $this->_descriptions)) {
			return $var;
		}
		return $this->_descriptions[$var];
	}
	
	/**
	* Returns validator for specified variable (if set before).
	* @param string $var Name of variableZ
	* @return validator
	*/
	public function getValidator($var) {
		if(!@array_key_exists($var, $this->_validators)) {
			return false;
		}
		return $this->_validators[$var];
	}
	
	/**
	* Creates new instance if actionform.
	* @return actionform
	*/
	static function &instance() {
		$instance = new actionform();
		return $instance;
	}
	
	/**
	* Checks form variables against set validators.
	* @return mixed
	*/
	public function Validate() {
		foreach($this->_validators as $var=>$validator) {
			if($validator->Process()) {
				continue;
			}
			return array($var, $validator->Messages());
		}
		return true;
	}
	
}

?>