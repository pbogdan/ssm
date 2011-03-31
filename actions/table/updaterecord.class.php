<?php

// +--------------------------------------------------------------------------+
// | updaterecord.class.php                                                   |
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
// $Id: updaterecord.class.php,v 1.12 2004/10/16 15:39:06 silence Exp $

require_once('classes/manager/manager.class.php');

class updaterecord extends action {
	
	public function &Perform(actionmapping &$actionMapping) {
		$form =& $this->buildForm();
		
		$manager = manager::instance();
		
		$db  = new database($form->getVar('db'));
		$tblName = $form->getVar('tbl');
		$tbl = $db->$tblName;
		
		$updates = array();
		$where = array();
		
		foreach($tbl->Columns() as $c) {
			$col = $tbl->$c;
			
			$type = strtolower($col->type);
			
			if($form->getVar("old{$c}") == 'NULL') {
				$where [] = "{$c} IS NULL";
			} else {
				switch($type) {
					case 'smallint':
					case 'tinyint':
					case 'mediumint':
					case 'bigint':
					case 'integer':
					case 'double':
					case 'float':
					case 'decimal': $where[] = $c."=".$form->getVar("old{$c}"); break;
					default: {
						$where[] = $c."='".$form->getVar("old{$c}")."'";
						break;
					}
				}
			}
			
			if($col->primary) {
				continue;
			}
			
			$update = "{$col->name}=";
			
			$edit = $form->getVar($c);
			
			if(@$edit['null']) {
				$update .= 'NULL';
			} else {
				switch($type) {
					case 'smallint':
					case 'tinyint':
					case 'mediumint':
					case 'bigint':
					case 'integer':
					case 'double':
					case 'float':
					case 'decimal': $update .= $edit['value']; break;
					default: {
						$value = sqlite_escape_string($edit['value']);
						$update .= "'$value'";
						break;
					}
				}
			}
			
			$updates[] = $update;
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
		
		$q = sprintf("UPDATE %s SET %s WHERE %s", $tblName, implode(', ', $updates), implode(' AND ', $where));
		$_SESSION['queries'][] = $q;
		
		$forward =& $actionMapping->getForward('t-browse');
		$forward->addParam('db', $form->getVar('db'));
		$forward->addParam('tbl', $form->getVar('tbl'));
		return $forward;
	}
	
	public function &buildForm() {
		$form =& actionform::instance();
		return $form;
	}
	
}

?>