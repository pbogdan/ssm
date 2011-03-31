<?php

// +--------------------------------------------------------------------------+
// | droptable.class.php                                                      |
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
// $Id: droptable.class.php,v 1.1 2004/09/28 22:47:03 silence Exp $

require_once('classes/manager/manager.class.php');

class droptable extends action {
	
	public function &Perform(actionmapping &$actionMapping) {
		$form =& $this->buildForm();
		
		$manager =& manager::instance();
		$connection =& $manager->getConnection($form->getVar('db'));
		
		try {
			$q = sprintf("DROP TABLE %s", $form->getVar('tbl'));
			@sqlite_query($q, $connection);
		
			if(sqlite_last_error($connection)) {
				throw new sqlitexception($connection, $q);
			}
		} catch(sqlitexception $e) {
			$e->Show();
		}
		
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