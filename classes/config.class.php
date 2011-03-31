<?php

// +--------------------------------------------------------------------------+
// | config.class.php                                                         |
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
// $Id: config.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

/**
* @package core
*/
class config {
	
	const configFile = 'config/config.xml';
	
	/**
	* @var string Group name
	*/
	private $_group = NULL;
	/**
	* @var boolean Whether config properties were modified
	*/
	private $_modified;
	/**
	* @var array List of all values read from config file
	*/
	private $_properties;
	/**
	* @var SimpleXMLElement Holds XML representation of config file
	*/
	private $_xml;
	
	function __construct() {
		$xml = simplexml_load_file(config::configFile);
		$this->_xml = $xml;
		
		foreach($xml->group as $group) {
			foreach($group->option as $option) {
				$_group = (string)$group['name'];
				$_name  = (string)$option['name'];
				$this->_properties[$_group][$_name] = $this->_readProperty($option);
			}
		}
	}
	
	function __destruct() {
		/**
		* @TODO: use DOM, more logical, than generating string
		**/
		/*if($this->_modified) {
			$output = "<?xml version='1.0'?>\n<config>\n";
			
			foreach($this->_properties as $groupName=>$group) {
				$output .= "\t<group name=\"$groupName\">\n";
				foreach($group as $optionName=>$optionValue) {
					$output .= "\t\t".$this->_dumpProperty($optionName, $optionValue)."\n";
				}
				$output .= "\t</group>\n";
			}
			$output .= "</config>";
			
			$xml =& simplexml_load_string($output); // for output formatting
			
			try {
				$f = @fopen(config::configFile, "wb");
				
				if($f === false) {
					throw new ioexception(i18n("Can't open file %s",config::configFile));
				}
				
				$written = fwrite($f, $xml->asXML(), strlen($xml->asXML()));
				
				if($written === false) {
					throw new ioexception(i18n("Can't write file %s",config::configFile));
				}
				
				fclose($f);
			} catch(ioexception $e) {
				$e->Show();
			}
		}*/
	}
	
	/**
	* Global instance of config object.
	* @return config
	*/
	static function &instance() {
		if(array_key_exists('core.config', $GLOBALS)) {
			$instance = $GLOBALS['core.config'];
		} 
		
		if(!@is_object($instance) || get_class($instance) != 'config') {
			$instance = new config();
			$GLOBALS['core.config'] = $instance;
		}
		
		return $instance;
	}
	
	/**
	* Converts XML object into variable, setting it typem content, and
	* escaping XML special chars.
	* @param SimpleXMLElement &$xml XML object containing property
	* @return mixed
	*/
	private function _readProperty(SimpleXMLElement &$xml) {
		$name  = (string)$xml['name'];
		$type  = (string)$xml['type'];
		$value = (string)$xml['value'];
		
		switch($type) {
			case 'bool': {
				if($value == 'false') {
					$property = false;
				} else {
					$property = true;
				}
				break;
			}
			case 'string': {
				$pattern = array(
					'/\&amp;/',
					'/\&lt;/',
					'/\&gt;/',
					'/\&apos;/',
					'/\&qout;/'
				);
				
				$replace = array(
					'&',
					'<',
					'>',
					"'",
					'"'
				);
				$property = preg_replace($pattern, $replace, $value);
				break;
			}
			default: {
				$property = $value;
				settype($property, $type);
				break;
			}
		}
		
		return $property;
	}
	
	/**
	* Converts value into XML code.
	* @param string $name Name of variable
	* @param mixed $value Value of variable
	* @return string
	*/
	private function _dumpProperty($name, $value) {
		$type = gettype($value);
		
		$output = "<option name=\"$name\" ";
		
		switch($type) {
			case 'bool': {
				$output .= "type=\"bool\" value=\"";
				if($value) {
					$output .= "true\" />";
				} else {
					$output .= "false\" />";
				}
				break;
			}
			case 'string': {
				$pattern = array(
					'/&/',
					'/</',
					'/>/',
					"/'/",
					'/"/'
				);
				$replace = array(
					'&amp;',
					'&lt;',
					'&gt;',
					'&apos;',
					'&qout;'
				);
				
				$output .= "type=\"$type\" value=\"".preg_replace($pattern, $replace, $value)."\" />";
				break;
			}
			default: {
				$output .= "type=\"$type\" value=\"$value\" />";
				break;
			}
		}
		
		return $output;
	}
	
	/**
	* Return property from current group.
	* @param string name Name of property
	* @return mixed
	*/
	public function getProperty($name) {
		if(is_null($this->_group) || !@array_key_exists($name, $this->_properties[$this->_group])) {
		} else {
			return $this->_properties[$this->_group][$name];
		}
	}
	
	/**
	* Sets property value.
	* @param string name Name of property
	* @param mixed Value of property
	* @return void
	*/
	public function setProperty($name, $value) {
		if(is_null($this->_group)) {
		} else {
			if($this->_properties[$this->_group][$name] === $value) {
				return;
			}
			$this->_properties[$this->_group][$name] = $value;
			$this->_modified = true;			
		}
	}
	
	/**
	* Switches to specified group.
	* @param string $group Name of group
	* @return void
	*/
	public function setGroup($group) {
		if(!@array_key_exists($group, $this->_properties)) {
			$this->_properties[$group] = array();
		}
		$this->_group = $group;
	}
	
}

?>