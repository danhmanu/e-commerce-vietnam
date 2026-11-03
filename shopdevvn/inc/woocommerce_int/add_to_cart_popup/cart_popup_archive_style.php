<script>
(function($){
	$(document).ready(function(){
		$('body').on( 'added_to_cart', function( ev, fragmentsJSON, cart_hash, button ){

	        if( typeof fragmentsJSON == 'undefined' )
	            fragmentsJSON = $.parseJSON( sessionStorage.getItem( wc_cart_fragments_params.fragment_name ) );
			
		    $.each( fragmentsJSON, function( key, value ) {
	        	if( key == '.mini-cart-body' ){
	                $('.popup_cart,.floating_popup_cart').html(value);
	            }
	        	var wWidth = $(window).width();
	            var optionFancybox;
	            if(wWidth >= 768){
	                optionFancybox = {
	                    padding: 0,
	                    maxWidth	: 800,
	                	maxHeight	: 610,
	                	fitToView	: false,
	                	width		: '90%',
	                	height		: '98%',
	                	autoSize	: false,
	                	closeClick	: false,
	                	openEffect	: 'none',
	                	closeEffect	: 'none',
	                	autoCenter: true,
	                	beforeClose: function(){
	                		$('.add_to_cart_button').removeClass('added');
	                	}
	                };
	            }else{
	                optionFancybox = {
	                        padding: 0,
	                        maxWidth	: 800,
	                    	maxHeight	: 610,
	                    	autoCenter: true,
	                    	beforeClose: function(){
	                    		$('.add_to_cart_button').removeClass('added');
	                    	}
	            	};
	            }
	            if ( key == 'devvn_wacp_message' ) {
	            	$.fancybox(value,optionFancybox);
	                return false;
	            }
	        });
		});
	});
})(jQuery);
</script>