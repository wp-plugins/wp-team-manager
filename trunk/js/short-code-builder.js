jQuery(document).ready(function($) {

	var $selector = $('#shortcode_output_box'),
		catslug = $( "#tm_cat" ).val(),
		orderby = $( "#tm_orderby" ).val(),
		tm_limit = $( "#tm_limit" ).val(),
		tm_remove_id = $( "#tm_remove_id" ).val(),
		tm_layout = $( "#tm_layout" ).val(),
		tm_image_layout = $( "#tm_image_layout" ).val();
		tm_image_size = $( "#tm_image_size" ).val();

		
	$( '#tm_cat' ).change(function() {

		catslug = $(this).val();

	});
	$( '#tm_orderby' ).change(function() {

		orderby = $(this).val();
	});
	$( '#tm_limit' ).change(function() {

		tm_limit = $(this).val();
	});	
	$( '#tm_remove_id' ).change(function() {

		tm_remove_id = $(this).val();
	});		
	$( '#tm_layout' ).change(function() {

		tm_layout = $(this).val();
	});	
	$( '#tm_image_layout' ).change(function() {

		tm_image_layout = $(this).val();
	});		

	$( '#tm_image_size' ).change(function() {

		tm_image_size = $(this).val();
 	});	
 	
	$('#tm_short_code').change(function() {

		var shortcodegenerated = 
		"[team_manager category='"+catslug+"' orderby='"+orderby+"' limit='"+tm_limit+"' exclude='"+tm_remove_id+"' layout='"+tm_layout+"' image_layout='"+tm_image_layout+"' image_size='"+tm_image_size+"']";

		$selector.empty().append(shortcodegenerated);

	});


});
