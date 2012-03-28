jQuery(document).ready( function($) {
	prettyPrint();

	// close postboxes that should be closed
	$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
	
	// postboxes setup
	postboxes.add_postbox_toggles('easy_og');
	
	$('.meta-box-sortables').sortable({
		disabled: true
	});
	
	$('h3.hndle').after('<div class="blocker"></div>');
	
	$('#normal-sortables .postbox').each( function() {
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
	
	// Upload
	$('#upload-default-image').click(function () {
		tb_show('Upload default image', 'media-upload.php?type=image&TB_iframe=true&height=400&width=600');
		// return false; // not sure if you actually need/want this. Try it out.
	});
	
	window.send_to_editor = function(html) {
		// attempt to extract the image attachment ID
		var classes = jQuery('img', html).attr('class').split(' ');
		var attachmentID = parseInt( classes[classes.length - 1].substring(9, classes[classes.length - 1].length) );
		
		// Set the attachment ID
		$('input[name="easy_og_options[image-uploaded]"]').val(attachmentID);
		
		// Get the img src
		var imgurl = jQuery('img',html).attr('src');
		
		// Set the image src
		$('div#image-uploaded').html('<img src="' + imgurl + '">');
		
		tb_remove();
	}
	
	// Hide preview output where options are unchecked
	$.fn.updatePreviewOutput = function() {
		
		($('#easy_og_options_article-pubdate').prop('checked')) ? $('.code-preview-article_published_time').show() : $('.code-preview-article_published_time').hide();
		($('#easy_og_options_article-moddate').prop('checked')) ? $('.code-preview-article_modified_time').show() : $('.code-preview-article_modified_time').hide();
		($('#easy_og_options_article-article-tag').prop('checked')) ? $('.code-preview-article_tag').show() : $('.code-preview-article_tag').hide();
		($('#easy_og_options_profile-realnames').prop('checked')) ? $('.code-preview-profile_first_name').show() : $('.code-preview-profile_first_name').hide();
		($('#easy_og_options_profile-realnames').prop('checked')) ? $('.code-preview-profile_last_name').show() : $('.code-preview-profile_last_name').hide();
		($('#easy_og_options_profile-usernames').prop('checked')) ? $('.code-preview-profile_username').show() : $('.code-preview-profile_username').hide();
		($('#easy_og_options_image-dimensions').prop('checked')) ? $('.code-preview-og_image_width').show() : $('.code-preview-og_image_width').hide();
		($('#easy_og_options_image-dimensions').prop('checked')) ? $('.code-preview-og_image_height').show() : $('.code-preview-og_image_height').hide();
		($('#easy_og_options_site-name').prop('checked')) ? $('.code-preview-og_site_name').show() : $('.code-preview-og_site_name').hide();
		($('#easy_og_options_description-status').prop('checked')) ? $('.code-preview-og_description').show() : $('.code-preview-og_description').hide();
		($('#easy_og_options_fbprops-status').prop('checked')) ? $('.code-preview-fb_admins').show() : $('.code-preview-fb_admins').hide();
		($('#easy_og_options_fbprops-status').prop('checked')) ? $('.code-preview-fb_app_id').show() : $('.code-preview-fb_app_id').hide();		
	};
	
	$('input:checkbox').click(function() {
		$().updatePreviewOutput();
	});
	
	$().updatePreviewOutput(); // run on load too
	
});