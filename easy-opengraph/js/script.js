jQuery(document).ready( function($) {
	// close postboxes that should be closed
	$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
	
	// postboxes setup
	postboxes.add_postbox_toggles('easy_og');
	
	$('.meta-box-sortables').sortable({
		disabled: true
	});
	
	$('h3.hndle').after('<div class="blocker"></div>');
	
	$('h3.hndle span input:checkbox').click( function(event) {
		
		$(this).parents('.postbox').each( function() {
			var checked = $(this).find('h3.hndle span input:checked').length;
			
			if (checked == 1) {
				$(this).find('.blocker').removeClass('click-block');
				
				$(this).removeClass('easy-og-closed');
				$(this).fadeTo(0, 1);
			} else {
				$(this).find('.blocker').addClass('click-block');
				
				$(this).addClass('easy-og-closed');
				$(this).fadeTo(0, 0.7);
			}
		});
		
	});
	
	// Tabs
	$('.wp-tab-bar a').click(function(event){
		event.preventDefault();
		
		// Limit effect to the container element.
		var context = $(this).closest('.wp-tab-bar').parent();
		$('.wp-tab-bar li', context).removeClass('wp-tab-active');
		$(this).closest('li').addClass('wp-tab-active');
		$('.wp-tab-panel', context).hide();
		$( $(this).attr('href'), context ).show();
	});

	// Make setting wp-tab-active optional.
	$('.wp-tab-bar').each(function(){
		if ( $('.wp-tab-active', this).length )
			$('.wp-tab-active', this).click();
		else
			$('a', this).first().click();
	});
});