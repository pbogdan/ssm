<?php

// +--------------------------------------------------------------------------+
// | newcolumns.class.php                                                     |
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
// $Id: newcolumns.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

class newcolumns extends action {
	
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
			$actionForward =& $actionMapping->getForward('t-main');
			$actionForward->addParam('db', $form->getVar('db'));
			$actionForward->addParam('tbl', $form->getVar('tbl'));
			return $actionForward;
		}
		
		$forward =& $actionMapping->getForward('t-newcolumns');
		$forward->addParam('db', $form->getVar('db'));
		$forward->addParam('tbl', $form->getVar('tbl'));
		$forward->addParam('numCols', $form->getVar('numCols'));
		$forward->addParam('columnsPosition', $form->getVar('columnsPosition'));
		return $forward;
	}
	
	public function &buildForm() {
		$form = actionform::instance();
		
		$validator = validator::instance($form->getVar('numCols'));
		$validator->addRule('empty');
		$validator->addRule('digit');
		$validator->addRule('positive');
		$validator->addRule('lower', 255);
		$form->addValidator('numCols', $validator);
		$form->addDescription('numCols', i18n("Number of columns"));
		
		return $form;
	}
	
}

?>