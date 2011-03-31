<?php

/**
* 
* Abstract Savant_Plugin class.  You have to extend this class for it to
* be useful; e.g., "class Savant_Plugin_example extends Savant_Plugin".
* 
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU Lesser General Public License as
* published by the Free Software Foundation; either version 2.1 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
*
* @license http://www.gnu.org/copyleft/lesser.html LGPL
* 
* @author Paul M. Jones <pmjones@ciaweb.net>
* 
* @package Savant
* 
* @version $Id: Plugin.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $
* 
*/

class Savant_Plugin {
	
	/**
	* 
	* A reference to the calling Savant object.
	* 
	* @access public
	* 
	* @var object
	* 
	*/
	
	var $savant;
	
	
	/**
	* 
	* Constructor.  If your extended class is static (which is the
	* default), you don't need to deal with this at all.
	* 
	* @access public
	* 
	* @param object &$savant A reference to the calling Savant object.
	* 
	*/
	
	function Savant_Plugin(&$savant)
	{
		$this->savant =& $savant;
		return;
	}
}
?>