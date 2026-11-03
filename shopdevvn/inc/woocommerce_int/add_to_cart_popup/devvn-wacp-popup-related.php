<?php
$cross_sells = WC()->cart->get_cross_sells();
$loop = 0;
$columns = 4;
$args = apply_filters( 'devvn_wacp_related_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => 4,
	'post__in'             => $cross_sells,
) );

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>

<div class="woocommmerce devvn-wacp-related">

	<h3><?php _e('Một số sản phẩm có thể bạn quan tâm','devvn');?></h3>

	<ul class="products columns-<?php echo $columns ?>">

	<?php

	while ( $products->have_posts() ) :
		$products->the_post();

		// Increase loop count
		$loop++;

		// Extra post classes
		$classes = array( 'product', 'devvn-wacp-related-product' );
		if ( 0 == ( $loop - 1 ) % $columns || 1 == $columns ) {
			$classes[] = 'first';
		}
		if ( 0 == $loop % $columns ) {
			$classes[] = 'last';
		}
		?>

		<li <?php post_class( $classes ); ?>>

			<?php do_action( 'devvn_wacp_before_related_item' ) ?>

			<a href="<?php the_permalink(); ?>">

				<div class="product-image">
					<?php
					wc_get_template( 'loop/sale-flash.php' );
					echo woocommerce_get_product_thumbnail( 'shop_catalog' );
					?>
				</div>

				<h3 class="product-title">
					<?php the_title() ?>
				</h3>

				<div class="product-price">
					<?php wc_get_template( 'loop/price.php' ); ?>
				</div>

			</a>

			<?php do_action( 'devvn_wacp_after_related_item' ) ?>

		</li>

	<?php endwhile; // end of the loop. ?>

	</ul>

</div>

<?php endif;

wp_reset_postdata();