<?php

// +--------------------------------------------------------------------------+
// | buildquery.class.php                                                     |
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
// $Id: buildquery.class.php,v 1.2 2004/10/16 15:32:20 silence Exp $

require_once('classes/manager/manager.class.php');

/**
* @package actions
*/
class buildquery extends action {
	
	function __construct() {
	}
	
	public function &Perform(actionmapping &$actionMapping) {
		$form =& $this->buildForm();
		
		if(@is_uploaded_file($_FILES['sqlFile']['tmp_name'])) {
			$buffer = file_get_contents($_FILES['sqlFile']['tmp_name']);
			unlink($_FILES['sqlFile']['tmp_name']);
			$format = 'sql';
		} else if(@is_uploaded_file($_FILES['xmlFile']['tmp_name'])) {
			$buffer = file_get_contents($_FILES['xmlFile']['tmp_name']);
			unlink($_FILES['xmlFile']['tmp_name']);
			$format = 'xml';
		} else if(strlen(trim($form->getVar('Query'))) > 0) {
			$buffer = $form->getVar('Query');
			$format = 'sql';
		} else {
			$actionErrors =& actionerrors::instance();
			$actionErrors->addError(i18n("Please fill in one of the fields"));
			$forward =& $actionMapping->getForward('d-query');
			$forward->addParam('db', $form->getVar('db'));
			return $forward;
		}
		
		$import = new import($format, $buffer);
		$_SESSION['queries'] = $import->Queries();
		session_register('queries');
		$forward =& $actionMapping->getForward('d-query');
		$forward->addParam('db', $form->getVar('db'));
		return $forward;
	}
	
	public function &buildForm() {
		$form =& actionform::instance();
		return $form;
	}
	
}

?>