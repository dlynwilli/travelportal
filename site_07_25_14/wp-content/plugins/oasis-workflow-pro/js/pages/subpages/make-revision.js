jQuery(document).ready(function() {	
	function load_setting(){
		var allowed_post_types = jQuery.parseJSON(owf_make_revision_vars.allowedPostTypes);
		var current_post_type = jQuery('#post_type').val();		
		// If not allowed post/page type then do not show
		if(jQuery.inArray(current_post_type, allowed_post_types) != -1)
		{			
		jQuery("#publishing-action").append("<input type='button' id='oasiswf_make_revision' class='button button-primary button-large'" +
											" value='" + owf_make_revision_vars.makeRevisionButton + "' style='float:left;' />").css({"width": "100%"});
		
		jQuery("#publishing-action").css({"margin-top": "10px"}) ;	
		}
		
		jQuery('.inline-edit-status').hide() ;
		
		jQuery('.error').hide() ;
		
	}
	
	load_setting();
	
	jQuery( document ).on( "click", "#oasiswf_make_revision", function(){
		data = {
				action: 'save_as_new_post_draft',
				post: jQuery("#hi_post_id").val()
			   };
		
		jQuery(this).parent().children(".loading").show();		
		jQuery.post(ajaxurl, data, function( response ) {
			window.location = 'post.php?action=edit&post=' + response;
		});
	});	
}) ;



