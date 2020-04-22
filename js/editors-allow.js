/*
 * Hide unwanted theme options from Editors
 */

jQuery(document).ready(function($) {
	'use strict';

	// Backend
	$( editorsAllow.themes ).remove();
	$( editorsAllow.submenu ).removeAttr("href");

	$( editorsAllow.menus ).remove();
	$( editorsAllow.widgets ).remove();
	$( editorsAllow.customize ).remove();
	$( editorsAllow.bg ).remove();

	// Frontend
	$( editorsAllow.themes_f ).remove();
	$( editorsAllow.menus_f ).remove();
	$( editorsAllow.widgets_f ).remove();
	$( editorsAllow.customize_f ).remove();
	$( editorsAllow.bg_f ).remove();
	
});