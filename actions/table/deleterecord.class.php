<?php

// +--------------------------------------------------------------------------+
// | deleterecord.class.php                                                   |
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
// $Id: deleterecord.class.php,v 1.13 2004/10/16 15:55:45 silence Exp $

require_once('classes/manager/manager.class.php');

class deleterecord extends action {
	
	public function &Perform(actionmapping &$actionMapping) {
		$form =& $this->buildForm();
		
		$manager = manager::instance();
		$db  = new database($form->getVar('db'));
		$tblName = $form->getVar('tbl');
		$tbl = $db->$tblName;
		
		$where = array();
		
		foreach($tbl->Columns() as $c) {
			$col = $tbl->$c;
			if($form->getVar($c) == 'NULL') {
				$where[] = "{$c} IS NULL";
				continue;
			}
			switch(strtolower($col->type)) {
				case 'smallint':
				case 'tinyint':
				case 'mediumint':
				case 'bigint':
				case 'integer':
				case 'double':
				case 'float':
				case 'decimal': $where[] = "{$c}={$form->getVar($c)}";; break;
				default: {
					$where[] = "{$c}='{$form->getVar($c)}'";
				}
			}
		}
		
		/*try {
			$connection =& $manager->getConnection($form->getVar('db'));
			$r = @sqlite_query($q, $connection);
		
			if(sqlite_last_error($connection)) {
				throw new sqlitexception($connection, $q);
			}
		} catch(sqlitexception $e) {
			$e->Show();
		}*/
		
		$q = sprintf("DELETE FROM %s WHERE %s", $tblName, implode(' AND ', $where));
		$_SESSION['queries'][] = $q;
		
		$actionForward =& $actionMapping->getForward('t-browse');
		$actionForward->addParam('db', $form->getVar('db'));
		$actionForward->addParam('tbl', $form->getVar('tbl'));
		return $actionForward;
	}
	
	
	public function &buildForm() {
		$form = & actionform::instance();
		return $form;
	}
	
}

?>