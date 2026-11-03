<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product, $woocommerce;
$attachment_ids = $product->get_gallery_image_ids();
if ( has_post_thumbnail() && $attachment_ids ) {
?>
<div id="product_thumbnails" class="owl-carousel">
	<?php	
	if ( has_post_thumbnail() ) {
		$attachment_count = count( $product->get_gallery_image_ids() );
		$gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
		$props            = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
		$image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_thumbnail' ), array(
			'title'	 => $props['title'],
			'alt'    => $props['alt'],
		) );						
		?>
    	<div class="item" data-id="1">
    	<?php 
    	echo apply_filters(
			'woocommerce_single_product_image_html',
			sprintf('%s', $image),
			$post->ID
		);
    	?>
	    </div>
    	<?php 
	} else {
		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
	}

	if ( $attachment_ids ) {
		$loop = 2;		
		foreach ( $attachment_ids as $attachment_id ) {

			$classes = array( 'zoom' );

			$image_class = implode( ' ', $classes );
			$props       = wc_get_product_attachment_props( $attachment_id, $post );

			if ( ! $props['url'] ) {
				continue;
			}

			echo apply_filters(
				'woocommerce_single_product_image_thumbnail_html',
				sprintf(
					'<div class="item" data-id="%d">%s</div>',
					$loop,
					wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $props )
				),
				$attachment_id,
				$post->ID,
				esc_attr( $image_class )
			);
			$loop++;
		}
	}
?>
</div>
<?php }?>