(function($){
	var style = $( '#devvn-color-scheme-css' ),
		api = wp.customize;
	
	if ( ! style.length ) {
		style = $( 'head' ).append( '<style type="text/css" id="devvn-color-scheme-css" />' )
		                    .find( '#devvn-color-scheme-css' );
	}
	/*api("footer_text", function(value) {
		value.bind(function(newval) {
			$(".footer_text").html(newval);
		});
	});
	api("footer_logo", function(value) {
		value.bind(function(newval) {
			$(".footer_logo img").attr('src',newval);
		});
	});*/
	// Color Scheme CSS.
	api.bind( 'preview-ready', function() {
		api.preview.bind( 'update-color-scheme-css', function( css ) {
			style.html( css );
		} );
	} );
})(jQuery);