<?php

// +--------------------------------------------------------------------------+
// | editcolumns.class.php                                                    |
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
// $Id: editcolumns.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

require_once('classes/manager/manager.class.php');

class editcolumns extends action {
	
	function __construct() {
	}
	
	public function &Perform(actionmapping &$actionMapping) {
		$form =& $this->buildForm();
		
		$columns = $form->getVar('selectedCols');
		
		if(!is_array($columns)) {
			$actionErrors =& actionerrors::instance();
			$actionErrors->addError(i18n("Please chose at least 1 column"));
			$forward =& $actionMapping->getForward('t-main');
			$forward->addParam('db', $form->getVar('db'));
			$forward->addParam('tbl', $form->getVar('tbl'));
			return $forward;
		}
		
		$db  = new database($form->getVar('db'));
		$tblName = $form->getVar('tbl');
		$tbl = $db->$tblName;
		
		foreach($columns as $col) {
			if(!in_array($col, $tbl->Columns())) {
				$actionErrors->addError(i18n("Unknown column %s", $col));
				$forward =& $actionMapping->getForward('t-main');
				$forward->addParam('db', $form->getVar('db'));
				$forward->addParam('tbl', $form->getVar('tbl'));
				return $forward;
			}
		}
		$forward =& $actionMapping->getForward('c-edit');
		$forward->addParam('db', $form->getVar('db'));
		$forward->addParam('tbl', $form->getVar('tbl'));
		$forward->addParam('columns', urlencode(serialize($columns)));
		return $forward;
		
	}
	
	public function &buildForm() {
		$form = actionform::instance();
		return $form;
	}
}

?>