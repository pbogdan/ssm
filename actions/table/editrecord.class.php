<?php

// +--------------------------------------------------------------------------+
// | editrecord.class.php                                                     |
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
// $Id: editrecord.class.php,v 1.1 2004/10/04 13:58:37 silence Exp $

require_once('classes/manager/manager.class.php');

class editrecord extends action {
	
	public function &Perform(actionmapping &$actionMapping) {
		$form =& $this->buildForm();
		
		$db  = new database($form->getVar('db'));
		$tblName = $form->getVar('tbl');
		$tbl = $db->$tblName;
		
		try {
			foreach($tbl->Columns() as $col) {
				if(!@array_key_exists($col, $_REQUEST)) {
					throw new siteexception(i18n("Missing column %s", $col));
					break;
				}
			}
		} catch(siteexception $e) {
			$e->Show();
		}
		
		$forward =& $actionMapping->getForward('t-editrecord');
		$forward->addParam('db', $form->getVar('db'));
		$forward->addParam('tbl', $form->getVar('tbl'));
		
		foreach($tbl->Columns() as $col) {
			// no need to urlencode(), as they were not decoded yet
			$forward->addParam($col, $form->getVar($col));
		}
		return $forward;
	}
	
	public function &buildForm() {
		$form =& actionform::instance();
		return $form;
	}
	
}

?>