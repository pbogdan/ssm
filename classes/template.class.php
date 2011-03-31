<?php

// +--------------------------------------------------------------------------+
// | template.class.php                                                       |
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
// $Id: template.class.php,v 1.12 2004/09/23 22:19:36 silence Exp $

/**
* @package core
*/
class template extends Savant {
	
	function __construct($options) {
		settype($options, 'array');
		parent::Savant($options);
	}
	
	/**
	* Global instance of template class.
	* @return template
	*/
	static function &instance($options = NULL) {
		if(array_key_exists('core.template', $GLOBALS)) {
			$instance = $GLOBALS['core.template'];
		} 
		
		if(!@is_object($instance) || get_class($instance) != 'template') {
			if(is_null($options) || !is_array($options)) {
				$options = array(
					'plugin_path'   => APP_BASE_DIR.'/thirdparty/savant/Savant/plugins/',
					'filter_path'   => APP_BASE_DIR.'/thirdparty/savant/Savant/filters/',
					'template_path' => APP_BASE_DIR.'/templates/'
				);
			}
			$instance = new template($options);
			$GLOBALS['core.template'] = $instance;
		}
		
		return $instance;
	}
	
}

?>