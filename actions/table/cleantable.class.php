<?php

// +--------------------------------------------------------------------------+
// | cleantable.class.php                                                     |
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
// $Id: cleantable.class.php,v 1.11 2004/10/17 17:50:09 silence Exp $

class cleantable extends action {
	
	public function &Perform(actionmapping &$actionMapping) {
		$form =& $this->buildForm();
		$q = "DELETE FROM ".$form->getVar('tbl');
		$_SESSION['queries'][] = $q;
		$forward =& $actionMapping->getForward('d-main');
		$forward->addParam('db', $form->getVar('db'));
		return $forward;
	}
	
	public function &buildForm() {
		$form =& actionform::instance();
		return $form;
	}
	
}

?>