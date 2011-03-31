<?php

// +--------------------------------------------------------------------------+
// | functions.class.php                                                      |
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
// $Id: functions.class.php,v 1.15 2004/10/16 15:33:14 silence Exp $

/**
* @package manager
*/
class functions {
	
	/**
	* @var array List of aggregations and their properties.
	*/
	private $_aggregations = array();
	/**
	* @var array List of functions and their properties.
	*/
	private $_functions    = array();
	
	/**
	* Get list of aggregations and functions from XML file.
	* @return void
	*/
	function __construct() {
		$cfg = config::instance();
		$cfg->setGroup('general');
		$funcFile = $cfg->getProperty('funcFile');
		$xml =& simplexml_load_file($funcFile);
		
		foreach($xml->function as $function) {
			$function = array(
				'name'    => (string)$function->name,
				'builtin' => (strtolower((string)$function->builtin) == 'true') ? true : false,
				'code'    => (string)$function->code,
				'args'    => (int)$function->args
			);
			$this->_functions[] = $function;
		}
		
		foreach($xml->aggregation as $aggregation) {
			$aggregation = array(
				'name'      => (string)$aggregation->name,
				'builtin'   => (strtolower((string)$aggregation->builtin) == 'true') ? true : false,
				'stepcode'  => (string)$aggregation->stepcode,
				'finalcode' => (string)$aggregation->finalcode,
				'args'      => (int)$aggregation->args
			);
			$this->_aggregations[] = $aggregation;
		}
	}
	
	/**
	* List of aggregations and their properties.
	* @return array
	*/
	public function getAggregations() {
		return $this->_aggregations;
	}
	
	/**
	* List of functions and their properties.
	* @return array
	*/
	public function getFunctions() {
		return $this->_functions;
	}
	
	/**
	* Initializes aggregation/function.
	* @param string $dsn Format: function name:type:builtin, ex.: max:a:false
	* @return boolean
	*/
	public function initUDF($dsn) {
		list($funcName, $funcType, $funcBuiltIn) = explode(':', $dsn);
		
		if($funcType == 'a') {
			$search =& $this->_aggregations;
		} else if($funcType == 'f') {
			$search =& $this->_functions;
		} else {
			return false;
		}
		
		$func = array();
		
		foreach($search as $item) {
			if($item['name'] == $funcName) {
				if($funcBuiltIn == 'true' && $item['builtin'] ||
				   $funcBuiltIn == 'false' && !$item['builtin']) {
				   	$func = $item;
				   	break;
				}
			}
		}
		
		if(!sizeof($func)) { // no matching function found
			return false;
		}
		
		if($funcType == 'a') {
			return $this->_initAggregation($func);
		} else {
			return $this->_initFunction($func);
		}
		
	}
	
	/**
	* Initializes all aggregations/functions.
	* @return void
	*/
	public function initAll() {
		
		foreach($this->_functions as $function) {
			$this->_initFunction($function);
		}
		
		foreach($this->_aggregations as $aggregation) {
			$this->_initAggregation($aggregation);
		}
		
	}
	
	/**
	* Initializes function.
	* @param array $function Properties of function
	* @return boolean
	*/
	private function _initFunction($function) {
		if($function['builtin']) {
			return true;
		}
		
		$phpFuncName = $this->_initCode($function['code']);
		
		if($phpFuncName == '') {
			return false;
		}
		
		$manager = manager::instance();
		$ret = true;
		
		foreach($manager->getDatabases() as $db) {
			if($function['args'] == -1) {
				if(sqlite_create_function($db['connection'], "{$function['name']}", "{$phpFuncName}") === false) {
					$ret = false;
				}
			} else {
				if(sqlite_create_function($db['connection'], "{$function['name']}", "{$phpFuncName}", $function['args'])  === false) {
					$ret = false;
				}
			}
		}
		
		return $ret;
	}
	
	/**
	* Initializes aggregation.
	* @param array $function Properties of aggregation
	* @return boolean
	*/
	private function _initAggregation($function) {
		if($function['builtin']) {
			return true;
		}
		
		$phpStepCodeFuncName  = $this->_initCode($function['stepcode']);
		$phpFinalCodeFuncName = $this->_initCode($function['finalcode']);
		
		if($phpStepCodeFuncName == '' || $phpFinalCodeFuncName == '') {
			return false;
		}
		
		$manager = manager::instance();
		$ret = true;
		
		foreach($manager->getDatabases() as $db) {
			if($function['args'] == -1) {
				if(sqlite_create_aggregate($db['connection'], $function['name'], $phpStepCodeFuncName, $phpFinalCodeFuncName) === false) {
					$ret = false;
				}
			} else {
				if(sqlite_create_aggregate($db['connection'], $function['name'], $phpStepCodeFuncName, $phpFinalCodeFuncName, $function['args']) === false) {
					$ret = false;
				}
			}
		}
		
		return $ret;
	}
	
	/**
	* Initializes PHP code for user defined aggregation/function.
	* @param string $code PHP code for function
	* @return string Created function name on success, empty string otherwise
	*/
	private function _initCode($code) {
		// if some errors occur those will be caught by phpexception
		// was trying to write to code to file and check it with
		// php_check_syntax(), but SIGSEGV in PHP :D
		
		$funcs = get_defined_functions();
		$oldFuncs = $funcs['user'];
		
		eval($code);
		
		$funcs = get_defined_functions();
		$funcs = $funcs['user'];
		
		if(sizeof($oldFuncs) == sizeof($funcs)) {
			return '';
		}
		$funcName = array_pop($funcs);
		return $funcName;
	}
	
}

?>