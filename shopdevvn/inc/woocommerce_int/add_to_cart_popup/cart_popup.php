<?php
function woo_add_popup_and_style() {
    ob_start();
    include 'cart_popup_content.php';
    echo ob_get_clean();
} 
add_action( 'wp_footer', 'woo_add_popup_and_style',PHP_INT_MAX );

add_filter( 'woocommerce_add_to_cart_fragments', 'devvn_add_to_cart_success_ajax', 99, 1 );
function devvn_add_to_cart_success_ajax( $datas ) {
	
	if ( ! isset( $_REQUEST['product_id' ] ) ) {
		return $datas;
	}

	$product_id = (isset( $_REQUEST['variation_id' ] ) && $_REQUEST['variation_id' ]) ? intval( $_REQUEST['variation_id' ] ) : intval( $_REQUEST['product_id' ] );

	// get product
	$product = wc_get_product( $product_id );	
	$number_items = isset($_REQUEST['number_items']) ? intval($_REQUEST['number_items']):1;
		
	ob_start();
	include 'cart_popup_content_product.php';
	$datas['devvn_wacp_message'] = ob_get_clean();

	return $datas;
}

add_action( 'wc_ajax_devvn_wacp_add_item_cart', 'devvn_add_item_cart_ajax' );
function devvn_add_item_cart_ajax(){
	if( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'devvn_wacp_add_item_cart' || ! isset( $_REQUEST['add-to-cart'] ) ) {
		die();
	}

	// get woocommerce error notice
	$error = wc_get_notices( 'error' );
	$html = '';

	if( $error ){
		// print notice
		ob_start();
		foreach( $error as $value ) {
			wc_print_notice( $value, 'error' );
		}
		$html = ob_get_clean();
	}

	// clear other notice
	wc_clear_notices();

	echo wp_json_encode( array(
		'msg'   		=>	$html,
		'prod_id' 		=>	isset($_REQUEST['add-to-cart'])?$_REQUEST['add-to-cart']:'',
		'number_items'	=>	isset($_REQUEST['number_items'])?$_REQUEST['number_items']:1,
		'variation_id'	=>	isset( $_REQUEST['variation_id'] ) ? $_REQUEST['variation_id'] : 0
	) );

	die();
}