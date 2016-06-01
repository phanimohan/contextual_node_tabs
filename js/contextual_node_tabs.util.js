/**
 * @file
 * Attaches behavior for the Contextual Node Tabs Block configure.
 */

(function ($, Drupal) {

  'use strict';

  $('#edit-settings-alignment-icon').change(function() {
		alert('Selected value: ');
		alert($(this).val);
	});

})(jQuery, Drupal);
