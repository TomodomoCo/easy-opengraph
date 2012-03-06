jQuery(document).ready( function($) {
	// close postboxes that should be closed
	$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
	
	// postboxes setup
	postboxes.add_postbox_toggles('easy_og');
	
	$('.meta-box-sortables').sortable({
		disabled: true
	});
	
	/* $('.meta-box-sortables').removeClass('ui-sortable').removeClass('meta-box-sortables');
	
	$('#side-sortables').removeAttr('id');
	
	$('#main-sortables').removeAttr('id'); */
});