<?php
$cart = WC()->cart->get_cart();
?>
<div id="devvn-wacp-popup">	
	<div class="devvn-wacp-wrapper woocommerce">
		<div class="devvn-wacp-main">			
			<div class="devvn-wacp-content">
				<h2><?php printf(__('%d Sản phẩm đã được thêm vào giỏ hàng.','devvn'),$number_items);?></h2>
				<div class="info-box">
					<div class="product-thumb">
						<?php
						$thumbnail = $product->get_image();
						printf( '<a href="%s">%s</a>', esc_url( $product->get_permalink() ), $thumbnail );
						?>
					</div>
					<div class="product-info">						
						<h3 class="product-title">
							<a href="<?php echo esc_url( $product->get_permalink() ) ?>">
								<?php echo $product->get_title(); ?>
							</a>
						</h3>
						<span class="product-price">
							<?php echo $product->get_price_html(); ?>
						</span>
						<?php if( $product->is_type('variation') ) : ?>
							<div class="product-variation">
								<?php foreach( $cart as $key => $value ) {
									if( isset( $value['variation_id'] ) && $value['variation_id'] == $product->get_id() ){
										// Meta data
										echo wc_get_formatted_cart_item_data( $cart[$key] );
										break;
									}
								}?>
							</div>
						<?php endif; ?>
					</div>
				</div>	
				<?php if( ! empty( $cart ) ) : ?>				
				<div class="cart-info">		
					<h3><?php _e('Giỏ hàng của bạn','devvn')?></h3>		
					<?php if( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) :
						// calculate shipping
						WC()->cart->calculate_shipping();
						?>
						<div class="cart-shipping">
							<?php echo __( 'Phí vận chuyển', 'devvn' ) . ':' ?>
							<span class="shipping-cost">
								<?php echo WC()->cart->get_cart_shipping_total() ?>
							</span>
						</div>
					<?php endif; ?>
					<div class="cart-totals">
						<?php echo __( 'Tổng', 'devvn' ) . ':' ?>
						<span class="cart-cost">
							<?php
							//calculate totals
							WC()->cart->calculate_totals();
		
							echo WC()->cart->get_cart_total();
							?>
						</span>
					</div>
				</div>				
				<?php endif; ?>	
				<div class="clearfix"></div>	
				<div class="text-right button_popup_cart">
					<a href="#" class="tat_popup"><?php _e('Mua thêm sản phẩm','devvn');?></a>
					<a href="<?php echo get_permalink(get_option( 'woocommerce_checkout_page_id' ))?>"><?php _e('Đặt hàng và thanh toán','devvn');?></a>
				</div>
				<div class="clearfix"></div>
				<?php if(!wp_is_mobile()):?>
				<?php include 'devvn-wacp-popup-related.php';?>
				<?php endif;?>					
			</div>
		</div>
	</div>
</div>