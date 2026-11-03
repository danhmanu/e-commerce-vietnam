<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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
?>
<div class="images">
			<div id="product_slider">
			<?php
				if ( has_post_thumbnail() ) {
					$attachment_count = count( $product->get_gallery_image_ids() );
					$gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
					$props            = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
					$image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
						'title'	 => $props['title'],
						'alt'    => $props['alt'],
                        'data-no-lazy'  =>  1
					) );						
					?>
			    	<div class="item">
			    	<?php 
			    	echo apply_filters(
						'woocommerce_single_product_image_html',
						sprintf(
							'<a href="%s" itemprop="image" class="woocommerce-main-image zoom single-product-lightbox" title="%s" rel="single-gallery" data-rel="prettyPhoto%s">%s</a>',
							esc_url( $props['url'] ),
							esc_attr( $props['caption'] ),
							$gallery,
							$image
						),
						$post->ID
					);
			    	?>
			    	</div>
		    	<?php 
				} else {
					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
				}
				
		    	$attachment_ids = $product->get_gallery_image_ids();
				if ( $attachment_ids ) {
					$loop 		= 0;
					
					foreach ( $attachment_ids as $attachment_id ) {
			
						$classes = array( 'zoom' );
			
						$image_class = implode( ' ', $classes );
						$props       = wc_get_product_attachment_props( $attachment_id, $post );

						/*if($loop == 0){
						    $props['data-no-lazy'] = 1;
                        }*/

						if ( ! $props['url'] ) {
							continue;
						}
			
						echo apply_filters(
							'woocommerce_single_product_image_thumbnail_html',
							sprintf(
								'<div class="item"><a href="%s" class="%s single-product-lightbox" title="%s" rel="single-gallery" data-rel="prettyPhoto[product-gallery]">%s</a></div>',
								esc_url( $props['url'] ),
								esc_attr( $image_class ),
								esc_attr( $props['caption'] ),
                                wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_single' ), 0, $props )
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
			<?php

		do_action( 'woocommerce_product_thumbnails' );
	?>
</div>
