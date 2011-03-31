<?php

// +--------------------------------------------------------------------------+
// | locale.class.php                                                         |
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
//  $Id: locale.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

/**
* @package core
*/
class locale {
	
	/**
	* @var string Currently selected translation
	*/
	private $_current;
	/**
	* @var string Default translation
	*/
	private $_default;
	/**
	* @var array Translation table
	*/
	private $_messages = array();
	/**
	* @var string Path where to look for translation files
	*/
	private $_messagesDir;
	/**
	* @var boolean Was translation table modified
	*/
	private $_modified;
	
	function __construct() {
		$cfg =& config::instance();
		$cfg->setGroup('language');
		$this->_default = $cfg->getProperty('default');
		$this->_current = $cfg->getProperty('current');
		$this->_messagesDir = $cfg->getProperty('messagesDir');
		
		if($this->_current != $this->_default) {
			$this->_readTable();
		}
		
	}
	
	function __destruct() {
	}
	
	/**
	* Gets message translation.
	* @param array $array Array holding message and values to be substituted in it.
	* @see _transform()
	* @return string
	*/
	public function Translate($array) {
		$message = array_shift($array);
		
		
		if($this->_current != $this->_default) {
			$message = $this->_getMessage($message);
		}
		
		if(sizeof($array)) {
			$message = $this->_transform($message, $array);
		}
		return $message;
	}
	
	/**
	* Global instance of locale class.
	* @return locale
	*/
	static function &instance() {
		if(array_key_exists('core.locale', $GLOBALS)) {
			$instance = $GLOBALS['core.locale'];
		} 
		
		if(!@is_object($instance) || get_class($instance) != 'locale') {
			$instance = new locale();
			$GLOBALS['core.locale'] = $instance;
		}
		
		return $instance;
	}
	
	/**
	* Loads translation table from file.
	* @return void
	*/
	private function _readTable() {
		$path = APP_BASE_DIR."/{$this->_messagesDir}/{$this->_current}.lang.php";

		if(@file_exists($path)) {
			require_once($path);
			$this->_messages = $__MESSAGES;
		}

	}
	
	/**
	* Looks up message in translation table.
	* @param string $message Message to be translated
	* @return string
	*/
	private function _getMessage($message) {
		if(@array_key_exists($message, $this->_messages)) {
			return $this->_messages[$message];
		}
		$this->_messages[$message] = $message;
		$this->_modified = true;
		return $message;
	}
	
	/**
	* Substitutes % codes with values, works same as printf, sprintf, etc..
	* @param string $message Message to be transformated
	* @param array $args List of parameters
	* @return string
	*/
	private function _transform($message, $args) {
		$array = str_split($message);
		
		$new = '';
		
		while($char = array_shift($array)) {
			if($char == '%') {
				
				$tmp  = @array_shift($args);
				$code = array_shift($array);
				
				switch($code) {
					case 'b': {
						$new .= sprintf('%b', $tmp);
						break;
					}
					case 'c': {
						$new .= sprintf('%c', $tmp);
						break;
					}
					case 'd': {
						$new .= sprintf('%d', $tmp);
						break;
					}
					case 'u': {
						$new .= sprintf('%u', $tmp);
						break;
					}
					case 'f': {
						$new .= sprintf('%f', $tmp);
						break;
					}
					case 's': {
						$new .= sprintf('%s', $tmp);
						break;
					}
					case 'x': {
						$new .= sprintf('%x', $tmp);
						break;
					}
					case 'X': {
						$new .= sprintf('%X', $tmp);
						break;
					}
					default: {
						$new .= $char;
					}
				}
			} else {
				$new .= $char;
			}
		}
		
		return $new;
	}
	
}

?>