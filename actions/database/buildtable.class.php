<?php

// +--------------------------------------------------------------------------+
// | buildtable.class.php                                                     |
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
// $Id: buildtable.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

require_once('classes/manager/manager.class.php');

/**
* @package actions
*/
class buildtable extends action {
	
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
			$forward = $actionMapping->getForward('d-newtable');
			$forward->addParam('db', $form->getVar('db'));
			$forward->addParam('tblName', $form->getVar('tblName'));
			$forward->addParam('tblColumns', sizeof($form->getVar('fields')));
			return $forward;
		}
		
		$cols = array();
		$export = new export('sql');
		
		$i = 1;
		
		$primary = @$form->getVar('primary');

		foreach($form->getVar('fields') as $f) {
			$col = column::fromMemory(
				array(
					'default' => @$f['default'],
					'length'  => @$f['length'],
					'name'    => @$f['name'],
					'null'    => @$f['null'],
					'primary' => ($i == $primary) ? true : false,
					'type'    => @$f['type']
				)
			);
			$cols[] = $col;
			$i++;
		}
		
		$db = new database($form->getVar('db'));
		$db->addTable($form->getVar('tblName'), $cols);
		
		$forward =& $actionMapping->getForward('d-main');
		$forward->addParam('db', $form->getVar('db'));
		return $forward;
	}
	
	public function &buildForm() {
		$form =& actionform::instance();
		
		$fields = $form->getVar('fields');
		foreach(range(1, sizeof($fields)) as $number) {
			$field = $fields[$number];
			
			$validator =& validator::instance(@$field['name']);
			$validator->addRule('empty');
			$validator->addRule('alphanumeric');
			$validator->addRule('shorter', 255);
			$form->addValidator('columns-name-'.$number, $validator);
			$form->addDescription('columns-name-'.$number, i18n("Name of column %d", $number));
			
			$validator =& validator::instance(@$field['length']);
			$validator->addRule('empty');
			$validator->addRule('digit');
			$validator->addRule('positive');
			$validator->addRule('lower', 255);
			$form->addValidator('columns-length-'.$number, $validator);
			$form->addDescription('columns-length-'.$number, i18n("Length of column %d", $number));
		}
		
		return $form;
	}
	
	
}

?>