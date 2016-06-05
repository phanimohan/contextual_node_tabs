/**
 * @file
 * Attaches behavior for the Contextual Node Tabs Block configure.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';
	
	var contextualNodeTabsSettings = drupalSettings.contextualNodeTabs;

  $('#edit-settings-alignment-icon').change(function() {
		var $selectedIndex = $(this).val();
		if ($selectedIndex !== 'custom') {
			var iconPath = contextualNodeTabsSettings.iconPath + '/icon-' + $selectedIndex + '.png';
			$('#edit-settings-alignment-icon-image img').attr('src', iconPath);	
		}
	});

})(jQuery, Drupal, drupalSettings);
