<?php

// +--------------------------------------------------------------------------+
// | src.php                                                                  |
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
// $Id: src.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

function doDirListing($path) {
	$dh = opendir($path);
	while(($dir_entry = readdir($dh)) !== false) {
		if($dir_entry == '.' || $dir_entry == '..' || $dir_entry == 'smarty' || $dir_entry == 'compile' || eregi('savant', $dir_entry)) {
			continue;
		}
		if(is_dir("$path/$dir_entry")) {
			doDirListing("$path/$dir_entry");
		} else {
			if(!preg_match('/(\.php|\.tpl)$/i', $dir_entry)) {
				continue;
			} else {
				global $lines_total, $entries, $size_total;
				$path = preg_replace('#^\.\/#', '', $path);
				$lines = sizeof(file("$path/$dir_entry"));
				$lines_total += $lines;
				$size = filesize("$path/$dir_entry") / 1024;
				$size_total += $size;
				$size = sprintf("%03.2f kb", $size);
				$entries++;
				echo "<tr>";
				echo "<td><a href=\"src.php?path=$path/$dir_entry\">$dir_entry</a></td>";
				echo "<td>$path</td>";
				echo "<td>$lines</td>";
				echo "<td>$size</td>";
				echo "</tr>";
			}
		}
	}
}

/**
* List of dirs, where we should look for source files.
*/
$sources_dir = array(
	'.',
//	'libs',
//	'modules'
);

if(@$_GET['path']) {
	$path = $_GET['path'];
	if(preg_match('#\.\.#', $path)) {
		die('bad path');
	}
	
	$pattern = implode('|', $sources_dir);
	$pattern = "($pattern)";
	if(!preg_match("#$pattern#", $path)) {
		die('bad path');
	}
	show_source($path);
} else {
	echo "<table border=0 width=700 align=center cellspacing=0>";
	echo "<tr><td style=\"border-bottom: solid 1px #000\">filename</td><td style=\"border-bottom: solid 1px #000\">path</td><td style=\"border-bottom: solid 1px #000\">number of lines</td><td style=\"border-bottom: solid 1px #000\">size</td></tr>";
	foreach($sources_dir as $dir) {
		$lines_total = 0;
		$size_total = 0;
		$entries = 0;
		doDirListing($dir);
	}
	$size_total = sprintf("%02.2f kb", $size_total);
	echo "<tr><td style=\"border-top: solid 1px #000\">TOTAL:</td><td style=\"border-top: solid 1px #000\">$entries</td><td style=\"border-top: solid 1px #000\">$lines_total</td><td style=\"border-top: solid 1px #000\">$size_total</td></tr>";
	echo "</table>";
}

?>
