<?php

// +--------------------------------------------------------------------------+
// | insertrecord.class.php                                                   |
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
// $Id: insertrecord.class.php,v 1.11 2004/10/17 16:27:07 silence Exp $

require_once('classes/manager/manager.class.php');

class insertrecord extends action {
	
	public function &Perform(actionmapping &$actionMapping) {
		$form =& $this->buildForm();
		
		$manager = manager::instance();
		
		$db  = new database($form->getVar('db'));
		$tblName = $form->getVar('tbl');
		$tbl = $db->$tblName;
		
		$inserts = array();
		
		foreach($tbl->Columns() as $c) {
			$col = $tbl->$c;
			$colToInsert = $form->getVar($c);
			
			if(!$col->primary && !array_key_exists('null', $colToInsert) && (strlen(trim($colToInsert['value'])) == 0)) {
				$actionErrors =& actionerrors::instance();
				$actionErrors->addError(i18n('Empty value for field  %s', $c));
				$forward =& $actionMapping->getForward('t-insertrecord');
				$forward->addParam('db', $form->getVar('db'));
				$forward->addParam('tbl', $form->getVar('tbl'));
				return $forward;
			}
			
			if($col->primary) {
				$inserts[] = 'NULL';
				continue;
			}
			
			if($colToInsert['function'] != '') {
				list($funcName, $funcType, $funcBuiltIn) = explode(':', $colToInsert['function']);
				
				$functions = new functions();
				
				if(!$functions->initUDF($colToInsert['function'])) {
					$actionErrors =& actionerrors::instance();
					$actionErrors->addError(i18n('Can\'t init function %s', $funcName));
					$forward =& $actionMapping->getForward('t-insertrecord');
					$forward->addParam('db', $form->getVar('db'));
					$forward->addParam('tbl', $form->getVar('tbl'));
					return $forward;
				}
				
				if($colToInsert['value'] != '') {
					$inserts[] = $funcName.'('.$colToInsert['value'].')';
				} else {
					$inserts[] = strtoupper($funcName).'()';
				}
				
				continue;
			}
			$type = strtolower($col->type);
			
			if(@$colToInsert['null']) {
				$inserts[] = 'NULL';
			} else {
				switch($type) {
					case 'smallint':
					case 'tinyint':
					case 'mediumint':
					case 'bigint':
					case 'integer':
					case 'double':
					case 'float':
					case 'decimal': $inserts[] = $colToInsert['value']; break;
					default: {
						$inserts[] = "'".sqlite_escape_string($colToInsert['value'])."'";
						break;
					}
				}
			}
		}
		
		/*$connection =& $manager->getConnection($form->getVar('db'));

		try {
			
			$r = @sqlite_query($q, $connection);
		
			if(sqlite_last_error($connection)) {
				throw new sqlitexception($connection, $q);
			}
		} catch(sqlitexception $e) {
			$e->Show();
		}*/
		
		$q = sprintf("INSERT INTO %s VALUES (%s)", $tblName, implode(', ', $inserts));
		$_SESSION['queries'][] = $q;
		
		if($form->getVar('insertAnotherRecord') == 1) {
			$forward =& $actionMapping->getForward('t-insertrecord');
		} else {
			$forward =& $actionMapping->getForward('t-browse');
		}
		
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