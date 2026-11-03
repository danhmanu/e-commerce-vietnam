<style>
div#devvn-wacp-popup {
    background: #fff;
    padding: 10px;
    width: 100%;
    max-width: 900px;
}
.devvn-wacp-content h2 {
    font-size: 18px;
    margin: 0 0 10px;
    font-weight: 700;
    color: #e03232;
}
div#devvn-wacp-popup .info-box {
    overflow: hidden;
    float: left;
    padding-right: 230px;
    width: 100%;
}
.devvn-wacp-content {
    position: relative;
}
div#devvn-wacp-popup .cart-info {
    float: right;
    width: 220px;
    padding: 15px 10px 10px 10px;
    border: 1px solid #ccc;
    position: absolute;
    line-height: 22px;
    background: #fff;
    right: 0;
    top: 50px;
}
div#devvn-wacp-popup .product-thumb {
    float: left;
    width: 110px;
    margin: 0 20px 0 0;
}
div#devvn-wacp-popup .product-info {
    padding-top: 20px;
    overflow: hidden;
}
div#devvn-wacp-popup .cart-info h3 {
    font-size: 15px;
    font-weight: 700;
    margin: 0;
    background: #fff;
    padding: 5px;
    position: absolute;
    top: -15px;
}
div#devvn-wacp-popup .cart-info span.shipping-cost,
div#devvn-wacp-popup .cart-info span.cart-cost {
    font-weight: 700;
    color: #e03232;
}
div#devvn-wacp-popup .info-box .product-info h3 {
    margin: 0 0 10px;
    font-size: 18px;
    font-weight: 700;
}
div#devvn-wacp-popup .info-box .product-info span.product-price {
    color: #e03232;
    text-decoration: none;
    font-weight: 700;
    font-size: 16px;
}
div#devvn-wacp-popup .info-box .product-info span.product-price del {
    opacity: 1;
    display: inline-block;
    color: #898989;
    font-weight: 400;
    font-size: 14px;
}
div#devvn-wacp-popup .info-box .product-info span.product-price ins {
    background: 0 0;
    font-weight: 700;
    text-decoration: none;
    font-size: 16px;
}
.button_popup_cart a {
    display: inline-block;
    background: #e03232;
    color: #fff;
    padding: 6px 18px;
    border-radius: 15px;
    margin: 0 4px;
    text-decoration: none;
    border: 1px solid #e03232;
}
.button_popup_cart a.tat_popup {
    background: #fff;
    color: #e03232;
}
.button_popup_cart a:hover {
    background: #6d6d6d;
    border-color: #6d6d6d;
}
.button_popup_cart a.tat_popup:hover {
    background: #e03232;
    color: #fff;
    border-color: #e03232;
}
.devvn-wacp-related > h3 {
    font-size: 18px;
    font-weight: 700;
    color: #e03232;
}
@media (max-width: 767px){
	div#devvn-wacp-popup .info-box {
	    padding-right: 0;
	}
	div#devvn-wacp-popup .cart-info {
	    position: relative;
	    width: 100%;
	    top: 0;
	    margin: 15px 0;
	}	
}
@media (max-width: 399px){	
	.devvn-wacp-content h2 {
	    font-size: 14px;
	    line-height: 20px;
	}
	div#devvn-wacp-popup .info-box .product-info h3 {
	    margin: 0 0 10px;
	    font-size: 14px;
	    font-weight: 400;
	    line-height: 20px;
	}
	div#devvn-wacp-popup .product-info {
	    padding-top: 0;
	}
	.button_popup_cart a {
	    margin: 0 auto 5px;
	    width: 100%;
	}
	.text-right {
	    text-align: center;
	}
}
</style>
<?php
/*
<script>
jQuery(document).ready(function($) {
    "use strict";    
	if( typeof woocommerce_params == 'undefined' )
		return;
	if(typeof $.fancybox == 'undefined') {
		return;
	}
	
	var devvn_popup = $("#devvn-wacp-popup");
	$('body').on('click','.tat_popup',function(){
		$.fancybox.close();
		$('.single_add_to_cart_button').removeClass('added');
		return false;
	});
    $('body').on( 'added_to_cart devvn_wacp_single_added_to_cart', function( ev, fragmentsJSON, cart_hash, button ){
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
                		$('.single_add_to_cart_button').removeClass('added');
                	}
                };
            }else{
                optionFancybox = {
                        padding: 0,
                        maxWidth	: 800,
                    	maxHeight	: 610,
                    	autoCenter: true,
                    	beforeClose: function(){
                    		$('.single_add_to_cart_button').removeClass('added');
                    	}
            	};
            }
            if ( key == 'devvn_wacp_message' ) {
            	$.fancybox(value,optionFancybox);
                return false;
            }            
        });
    });
    var form_cart_single = $( '.cart_after_content form.cart, div.summary form.cart, div.summary form.bundle_form' );
    if( ! form_cart_single.length ) {
        form_cart_single = $( '.cart_after_content form.cart, div.product-box form.cart, #product-box form.cart' );
    }

    form_cart_single.on( 'submit', function( ev ) {

        if( typeof wc_cart_fragments_params === 'undefined' ) {
            return;
        }

        // check if exluded
        var t = $(this),
            button = t.find( 'button[type="submit"]'),
            exclude = t.find( 'input[name="yith-wacp-is-excluded"]' ),
            qty = parseInt(t.find('.qty ').val());

        if( exclude.length )
            return;

        ev.preventDefault();

        button.addClass('loading')
            .removeClass('added');

        var form = t.serializeArray();

        // add action
        form.push({ name: "action", value: 'devvn_wacp_add_item_cart' }, { name: "context", value: "frontend" } );
        
        $.ajax({
            url: wc_cart_fragments_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'devvn_wacp_add_item_cart' ),
            data: $.param( form ),
            dataType: 'json',
            type: 'POST',
            success: function( res ) {
            	
                // add error notice               
                if( res.msg ) {

                    // add mess and scroll to Top
                    t.parents( 'div.product' ).before( res.msg );
                    $('body').animate({
                        scrollTop: 0
                    }, 500);

                    // reset button
                    button.removeAttr( 'disabled')
                        .removeClass( 'loading');

                    return false;
                }

                // refresh fragments
                var $supports_html5_storage,
                    $ajax_url,
                    $data_ajax = {
                        product_id: res.prod_id,
                        variation_id: res.variation_id,
                        number_items: qty,
                        ywacp_is_single: 'yes',
                        context: 'frontend'
                    };

                try {
                    $supports_html5_storage = ( 'sessionStorage' in window && window.sessionStorage !== null );

                    window.sessionStorage.setItem( 'wc', 'test' );
                    window.sessionStorage.removeItem( 'wc' );
                } catch( err ) {
                    $supports_html5_storage = false;
                }

                //Support wc 2.3.x
                if( typeof wc_cart_fragments_params.wc_ajax_url !== 'undefined' ) {
                    $ajax_url = wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments');
                }
                else {
                    $ajax_url = wc_cart_fragments_params.ajax_url;
                    $data_ajax.action = 'woocommerce_get_refreshed_fragments';
                }
                
                $.ajax({
                    url: $ajax_url,
                    type: 'POST',
                    data: $data_ajax,
                    success: function( data ) {
                        if ( data && data.fragments ) {
                            $.each( data.fragments, function( key, value ) {
                                $( key ).replaceWith( value );
                            });

                            if ( $supports_html5_storage ) {
                                sessionStorage.setItem( wc_cart_fragments_params.fragment_name, JSON.stringify( data.fragments ) );
                                sessionStorage.setItem( 'wc_cart_hash', data.cart_hash );
                            }

                            $( 'body' ).trigger( 'wc_fragments_refreshed' )
                                .trigger( 'devvn_wacp_single_added_to_cart' );

                            // remove disabled from submit button
                            button.removeAttr( 'disabled')
                                .removeClass( 'loading')
                                .addClass('added');
                        }
                    }
                });
            }
        });
    });
});
</script>
*/?>