<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;
?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="row row-padding-15">
    <div class="col-md-9 col-sm-12 col-xs-12 product-left">
		<div class="img-product clearfix">
			<?php
				/**
				 * woocommerce_before_single_product_summary hook.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
			?>
		</div>
		<div class="summary entry-summary">
	
			<?php
				/**
				 * woocommerce_single_product_summary hook.
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 */
				do_action( 'woocommerce_single_product_summary' );
			?>
	
		</div><!-- .summary -->
        <div class="row row-padding-15">
            <div class="col-md-12 col-sm-12 col-xs-12 product-infor-left">
                <?php
                /**
                 * woocommerce_after_single_product_summary hook.
                 *
                 * @hooked woocommerce_output_product_data_tabs - 10
                 * @hooked woocommerce_upsell_display - 15
                 * @hooked woocommerce_output_related_products - 20
                 */
                do_action( 'woocommerce_after_single_product_summary' );
                ?>
            </div>
        </div>
        <div class="row row-padding-15">
            <?php
            $hidden_shopping = get_field('hidden_shopping','option');
            if(
                ($product->get_attributes() || $product->has_weight() || $product->has_dimensions())
                && $product->is_in_stock() && $product->is_purchasable() && !$hidden_shopping
            ):
                ?>
                <div class="col-sm-6 col-xs-12 product-review-left">
                    <div class="section_cvp_title margin-b-15"><span><?php _e('Thông số kỹ thuật','devvn') ?></span></div>
                    <div class="tinymce">
                        <?php wc_display_product_attributes($product); ?>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12 product-review-right">
                    <?php do_action('after_content_right_woo');?>
                </div>
            <?php elseif(($product->get_attributes() || $product->has_weight() || $product->has_dimensions())):?>
                <div class="col-sm-12 col-xs-12 product-review-left">
                    <div class="section_cvp_title margin-b-15"><span><?php _e('Thông số kỹ thuật','devvn') ?></span></div>
                    <div class="tinymce">
                        <?php wc_display_product_attributes($product); ?>
                    </div>
                </div>
            <?php elseif($product->is_in_stock() && $product->is_purchasable() && !$hidden_shopping):?>
                <div class="col-sm-12 col-xs-12 product-review-left">
                    <?php do_action('after_content_right_woo');?>
                </div>
            <?php endif;?>
        </div>
        <?php do_action('after_tskt_msp');?>
	</div>
	<div class="col-md-3 col-sm-12 col-xs-12 product-right">
        <?php if(have_rows('chinh_sach_cua_shop','option')):?>
		<div class="chinh_sach_cua_shop">
			<ul>
				<?php while (have_rows('chinh_sach_cua_shop','option')):the_row();
				$icon = get_sub_field('icon');
				$content = get_sub_field('content');
				$linkto = get_sub_field('linkto');
				?>
					<li class="<?php echo ($icon)?'has-icon':''?>">
						<?php if($linkto):?><a href="<?php echo $linkto;?>" target="_blank"><?php endif;?>
						<?php if($icon):?><div class="chinhsach_icon"><?php echo wp_get_attachment_image($icon,'full');?></div><?php endif;?>
						<?php if($content):?><div class="chinhsach_content"><?php echo $content;?></div><?php endif;?>
						<?php if($linkto):?></a><?php endif;?>
					</li>
				<?php endwhile;?>
			</ul>
		</div>
        <?php endif;?>
        <?php do_action('devvn_product_infor_right');?>
        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('single-shop-sidebar')) : ?><?php endif; ?>
	</div>
</div>
<?php
$comment_product = get_field('comment_product','option');
$classs = 'col-sm-6 col-xs-12';
if($comment_product == 'allow_both' || !$comment_product){
    $classs = 'col-sm-6 col-xs-12';
}else{
    $classs = 'col-sm-12 col-xs-12';
}
?>
<div class="row row-padding-15">
    <?php if($comment_product == 'woo' || $comment_product == 'allow_both' || !$comment_product):?>
	<div class="<?php echo $classs;?> product-review-left">
		<div class="section_cvp_title margin-b-15"><span><?php _e('Đánh giá sản phẩm','devvn');?></span></div>
		<?php comments_template();?>
	</div>
    <?php endif;?>
    <?php if($comment_product == 'face' || $comment_product == 'allow_both' || !$comment_product):?>
	<div class="<?php echo $classs;?> product-review-right">
		<div class="section_cvp_title margin-b-15"><span><?php _e('Bình luận trên Facebook','devvn');?></span></div>
		<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-numposts="10" data-order-by="time" data-width="100%"></div>
	</div>
    <?php endif;?>
</div>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
