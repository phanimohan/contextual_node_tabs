/**
 * @file
 * Attaches behavior for the Contextual Node Tabs module.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';
	
	$(".cnt-icon").button({
		icons: {
			secondary: "ui-icon-triangle-1-s"
		}
	});
	
  // Dropdown toggle
  $('.dropdown-toggle').click(function(e){
    e.preventDefault();
    $(this).next('.dropdown').toggle();
  });

  $(document).click(function(e) {
    var target = e.target;
    if (!$(target).is('.dropdown-toggle') && !$(target).parents().is('.dropdown-toggle')) {
      $('.dropdown').hide();
    }
  });
  
  $('.region-content').hover(
    function() {
      $('.auto-hide').children('.cnt-icon').show();
      $('.dropdown').hide();
    }, function() {
      $('.auto-hide').children('.cnt-icon').hide();
      $('.dropdown').hide();
    }
  );

})(jQuery, Drupal, drupalSettings);
