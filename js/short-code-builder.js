var $jwptm = jQuery.noConflict();
$jwptm(function(){

	var selector = $jwptm('#shortcode_output_box'),
		catslug = $jwptm( "#tm_cat" ).val(),
		orderby = $jwptm( "#tm_orderby" ).val(),
		tm_limit = $jwptm( "#tm_limit" ).val(),
		tm_remove_id = $jwptm( "#tm_remove_id" ).val(),
		tm_layout = $jwptm( "#tm_layout" ).val(),
		tm_image_layout = $jwptm( "#tm_image_layout" ).val();
		tm_image_size = $jwptm( "#tm_image_size" ).val();

		
	$jwptm( '#tm_cat' ).change(function() {

		catslug = $jwptm(this).val();

	});
	$jwptm( '#tm_orderby' ).change(function() {

		orderby = $jwptm(this).val();
	});
	$jwptm( '#tm_limit' ).change(function() {

		tm_limit = $jwptm(this).val();
	});	
	$jwptm( '#tm_remove_id' ).change(function() {

		tm_remove_id = $jwptm(this).val();
	});		
	$jwptm( '#tm_layout' ).change(function() {

		tm_layout = $jwptm(this).val();
	});	
	$jwptm( '#tm_image_layout' ).change(function() {

		tm_image_layout = $jwptm(this).val();
	});		

	$jwptm( '#tm_image_size' ).change(function() {

		tm_image_size = $jwptm(this).val();
 	});	
 	
	$jwptm('#tm_short_code').change(function() {

		var shortcodegenerated = 
		"[team_manager category='"+catslug+"' orderby='"+orderby+"' limit='"+tm_limit+"' exclude='"+tm_remove_id+"' layout='"+tm_layout+"' image_layout='"+tm_image_layout+"' image_size='"+tm_image_size+"']";

		selector.empty().append(shortcodegenerated);

	});


});
