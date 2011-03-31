<?php

// +--------------------------------------------------------------------------+
// | siteexception.class.php                                                  |
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
// $Id: siteexception.class.php,v 1.11 2004/09/24 22:24:19 silence Exp $

/**
* @package core
* @subpackage exception
*/
class siteexception extends myexception {
	
	function __construct($message) {
		parent::__construct();
		die($message);
	}
	
	public function Show() {
	}
	
}

?>