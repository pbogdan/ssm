<?php

if(!class_exists('locale')) {
	require_once('classes/locale.class.php');
}
/**
* Wrapper for locale class, created with templates in mind.
* @param va_list
* @return string
*/
function i18n() {
	$locale =& locale::instance();
	return $locale->Translate(func_get_args());
}

?>