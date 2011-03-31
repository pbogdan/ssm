<?php

// +--------------------------------------------------------------------------+
// | actionmapping.class.php                                                  |
// +--------------------------------------------------------------------------+
// | index.php                                                                |
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
// $Id: actionmapping.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

/**
* @package MVC
*/
class actionmapping {

	private $_class;
	private $_file;
	private $_forwards = array();
	private $_name;
	
	
	function __construct($actionName, $actionFile, $actionClass) {
		$this->_name  = $actionName;
		$this->_file  = $actionFile;
		$this->_class = $actionClass;
	}
	
	/**
	* Pushes new actionforward object onto the stack.
	* @param actionforward &$actionForward Forward object.
	* @return void
	*/
	public function addForward(actionforward &$actionForward) {
		$this->_forwards[] = $actionForward;
	}
	
	/**
	* Returns forward object specified by name.
	* @param string $forwardName Name of forward
	* @return actionforward
	*/
	public function &getForward($forwardName) {
		foreach($this->_forwards as $forward) {
			if($forward->Name() == $forwardName) {
				return $forward;
			}
		}
		return false;
	}
	
	/**
	* Looks up current mapping table and tries to load action specific class.
	* @return action
	*/
	public function &loadAction() {
		try {
			if(!file_exists($this->_file)) {
				throw new ioexception(i18n("File %s doesn't exist", $this->_file));
			} else {
				require_once($this->_file);
			}
			
			if(!class_exists($this->_class)) {
				throw new siteexception(i18n("Class %s implementing action %s doesn't exist"), $this->_class, $this->_name);
			}
			
			$actionObject = new $this->_class();
		} catch(ioexception $e) {
			$e->Show();
		} catch (siteexception $e) {
			$e->Show();
		}
		
		return $actionObject;
	}
	
}

?>