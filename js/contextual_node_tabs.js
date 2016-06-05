/**
 * @file
 * Attaches behavior for the Contextual Node Tabs module.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  var $dropdown = $('div.dropdownWrapper'),
      $drpBtn   = $dropdown.find('div.dropdownLabel');

  $drpBtn.on('click', function(e){
    e.stopPropagation();
    var $element = $(this).parent();
    $element.find('.dropdownPanel').fadeToggle(200);
  });
	
	$(".account").click(function() {
		$(".submenu").toggleClass('menu-show');
	});
	
	//Mouse click on sub menu
	$(".submenu").mouseup(function() {
		return false;
	});

	//Mouse click on my account link
	$(".account").mouseup(function() {
		return false;
	});

  $("document").mouseup(function(){
		$(".submenu").hide();
		$(".account").attr('id', '');
    $('.dropdownPanel').hide(200);
  });

})(jQuery, Drupal, drupalSettings);
