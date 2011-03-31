<?php

// +--------------------------------------------------------------------------+
// | controller.class.php                                                     |
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
// $Id: controller.class.php,v 1.11 2004/09/23 21:16:28 silence Exp $

require_once('action.class.php');
require_once('actionerrors.class.php');
require_once('actionform.class.php');
require_once('actionforward.class.php');
require_once('actionmapping.class.php');

/**
* @package MVC
*/
class controller {
	
	/**
	* @var array List of mappings
	*/
	private $_mapping = array();
	
	function __construct() {
	}
	
	/**
	* Determines what view to display, or what specific action should take place
	* @return void
	*/
	function Process() {
		$cfg = config::instance();
		$cfg->setGroup('mvc');
		$actionKey = $cfg->getProperty('actionKey');
		
		if(@array_key_exists($actionKey, $_REQUEST)) {
			// perform an action
			$mapFile = $cfg->getProperty('mapFile');
			
			try {
				if(!file_exists($mapFile)) {
					throw new ioexception(i18n("File %s doesn't exist", $mapFile));
				} else {
					$xml =& simplexml_load_file($mapFile);
					$this->_buildMapping($xml);
				}
			} catch(ioexception $e) {
				$e->Show();
			}
			
			$action = $_REQUEST[$actionKey];
			try {
				if(!@array_key_exists($action, $this->_mapping)) {
					throw new siteexception(i18n("Unknown action %s", $action));
				} else {
					$actionMapping = $this->_mapping[$action];
				}
			} catch(siteexception $e) {
				$e->Show();
			}
			
			$actionObject =& $actionMapping->loadAction();
			
			$actionForward =& $actionObject->Perform($actionMapping);
			$actionForward->redirect();
		} else {
			// determine and load view
			$tpl =& template::instance();
			$tpl->display('top.tpl.php');
			
			$actionErrors =& actionerrors::instance();
			$actionErrors->Show(true);
			
			if(@array_key_exists('PATH_INFO', $_SERVER)) {
				$parts = preg_split('/\//', $_SERVER['PATH_INFO'], -1, PREG_SPLIT_NO_EMPTY);
			} else {
				$parts = array('welcome');
			}
			
			$module = $parts[0];
			
			if(sizeof($parts) > 1) {
				$view = $parts[1];
			} else {
				$view = 'main';
			}
			
			try {
				if(!file_exists("views/{$module}/$view.inc.php")) {
					throw new ioexception(i18n("File %s doesn't exist", "views/{$module}/$view.inc.php"));
				} else {
					require_once("views/{$module}/$view.inc.php");
				}
			} catch(ioexception $e) {
				$e->Show();
			}
			
			$tpl->display('bottom.tpl.php');
		}
	}

	/**
	* Actions/forwards relationships are read from SimpleXMLElement
	* @param SimpleXMLElement &$xml Object holding mapping.xml file
	* @return void
	*/
	private function _buildMapping(SimpleXMLElement &$xml) {
		foreach($xml->actions->action as $action) {
			$actionMapping = new actionmapping((string)$action['name'], (string)$action['file'], (string)$action['class']);
			
			foreach($action->forward as $forward) {
				$actionForward = $this->_getForward($xml, (string)$forward['name']);
				
				if($actionForward) {
					$forwardObject =& $this->_buildForward($actionForward);
					$actionMapping->addForward($forwardObject);
				}
			}
			
			$this->_mapping[(string)$action['name']] = $actionMapping;
		}
		
	}
	
	/**
	* Looks for specific forward in forwards list.
	* @param SimpleXMLElement &$xml List of forwards
	* @param string $forwardName Name of forward to look for
	* @return SimpleXMLElement
	*/
	private function _getForward(SimpleXMLElement &$xml, $forwardName) {
		foreach($xml->forwards->forward as $forward) {
			if($forward['name'] == $forwardName) {
				return $forward;
			}
		}
		return false;
	}
	
	/**
	* Creates actionforward instance from its XML definition.
	* @param SimpleXMLElement &$xml Definition of forward
	* @return actionforward
	*/
	private function &_buildForward(SimpleXMLElement &$xml) {
		$forwardObject = new actionforward((string)$xml['name'], (string)$xml['module'], (string)$xml['view']);
		return $forwardObject;
	}
	
}

?>