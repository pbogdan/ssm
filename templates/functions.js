function confirmLink(link, query) {
	var is_confirmed = confirm("Are you sure to" + ' :\n' + query);
	return is_confirmed;
}

function setSelectOptions(the_form, the_select, do_check) {
	var selectObject = document.forms[the_form].elements[the_select];
	var selectCount  = selectObject.length;
	
	for (var i = 0; i < selectCount; i++) {
		selectObject.options[i].selected = do_check;
	}
	
	return true;
}

function setCheckBox(the_form, the_checkbox, do_check) {
	var checkboxObject = document.forms[the_form].elements[the_checkbox];
	
	checkboxObject.checked = do_check;
	
	return true;
}