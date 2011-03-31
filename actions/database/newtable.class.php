<?php

// +--------------------------------------------------------------------------+
// | newtable.class.php                                                       |
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
// $Id: newtable.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

/**
* @package actions
*/
class newtable extends action {
	
	function __construct() {
	}
	
	public function &Perform(actionmapping &$actionMapping) {
		$form =& $this->buildForm();
		
		$validationResult = $form->Validate();
		
		if($validationResult !== true) {
			list($var, $messages) = $validationResult;
			$message = array_shift($messages);
			$actionErrors = actionerrors::instance();
			$actionErrors->addError($form->getDescription($var)." {$message}");
			$actionForward =& $actionMapping->getForward('d-main');
			$actionForward->addParam('db', $form->getVar('db'));
			return $actionForward;
		}
		
		$actionForward =& $actionMapping->getForward('d-newtable');
		$actionForward->addParam('db', $form->getVar('db'));
		$actionForward->addParam('tblName', $form->getVar('tblName'));
		$actionForward->addParam('tblColumns', $form->getVar('tblColumns'));
		return $actionForward;
	}
	
	public function &buildForm() {
		$form = actionform::instance();
		
		$validator = validator::instance($form->getVar('tblName'));
		$validator->addRule('empty');
		$validator->addRule('alphanumeric');
		$validator->addRule('shorter', 255);
		$form->addValidator('tblName', $validator);
		$form->addDescription('tblName', i18n("Name of table"));
		
		$validator = validator::instance($form->getVar('tblColumns'));
		$validator->addRule('empty');
		$validator->addRule('digit');
		$validator->addRule('positive');
		$validator->addRule('lower', 255);
		$form->addValidator('tblFields', $validator);
		$form->addDescription('tblFields', i18n("Number of columns"));
		
		return $form;
	}
	
}

?>