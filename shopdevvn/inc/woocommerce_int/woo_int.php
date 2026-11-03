<?php
/*add_action( 'after_setup_theme', 'devvn_woo3_setup' );
function devvn_woo3_setup() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}*/
//Hỗ trợ woocommerce
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_filter('woocommerce_show_admin_notice','devvn_woocommerce_show_admin_notice', 10, 2);
function devvn_woocommerce_show_admin_notice($true, $notice){
    if($notice == 'template_files' ){
        return false;
    }
    return $true;
}
/*woo mini cart*/
function woo_mini_cart_int(){
	global $woocommerce; ?>
	<a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e('Hiển thị giỏ hàng', 'devvn'); ?>">
		<span><?php _e('Giỏ hàng của bạn','devvn')?></span>
		<?php if($woocommerce->cart->cart_contents_count != 0):?>
			<small><?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'devvn'), $woocommerce->cart->cart_contents_count);?> sp - <?php echo $woocommerce->cart->get_cart_subtotal(); ?></small>
		<?php else:?>
			<small><?php _e('Chưa có sản phẩm','devvn')?></small>
		<?php endif;?>
        <small class="qty_mobile_only"><?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'devvn'), $woocommerce->cart->cart_contents_count);?></small>
	</a>
<?php
}
add_action('woo_mini_cart', 'woo_mini_cart_int');
/*Woocommerce update minicart with Ajax*/
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	do_action('woo_mini_cart');
	$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
}
/*Action mini cart*/
function mini_cart_int(){?>
	<div class="mini-cart-body">
	<?php woocommerce_mini_cart();?>
	</div>
<?php
}
add_action('devvn_mini_cart','mini_cart_int');
/*Woocommerce update minicart with Ajax*/
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_mini_cart_fragment');
function woocommerce_mini_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	do_action('devvn_mini_cart');
	$fragments['.mini-cart-body'] = ob_get_clean();
	return $fragments;
}

add_action('woocommerce_share', 'devvn_single_share',10);

remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products',20);
add_action('after_tskt_msp', 'woocommerce_output_related_products', 20);
add_action('devvn_product_infor_right', 'devvn_tintuc_lienquan_sanpham', 10);
add_action('devvn_product_infor_right', 'devvn_videos_lienquan_sanpham', 15);

add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
function wcs_woo_remove_reviews_tab($tabs) {
    unset($tabs['reviews']);
    unset($tabs['additional_information']);
    return $tabs;
}

add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart_button_func' );
function add_content_after_addtocart_button_func() {
	$show_hide_contact_button = get_field('show_hide_contact_button','option');
	$contact_form_7_woo = get_field('contact_form_7_woo','option');	
	if(!$show_hide_contact_button || !$contact_form_7_woo) return false;	
	echo '<a href="#contact_to_buy" class="contact_to_buy_ajax">Liên hệ</a>';	
}

add_action('woocommerce_single_product_summary', 'devvn_contact_to_buy',60);
function devvn_contact_to_buy(){
	global $post;
	$show_hide_contact_button = get_field('show_hide_contact_button','option');
	$contact_form_7_woo = get_field('contact_form_7_woo','option');
	if(!$show_hide_contact_button || !$contact_form_7_woo) return false;
	?>
	<div class="form_lienhe_muahang" id="contact_to_buy">
		<span id="infor_product" class="devvn_hidden" data-title="<?php echo esc_attr(get_the_title($post))?>" data-link="<?php echo esc_url(get_the_permalink($post));?>"></span>
		<?php echo do_shortcode('[contact-form-7 id="'.$contact_form_7_woo.'" title="Liên hệ mua hàng"]')?>
	</div>
	<?php
}

add_action('cart_after_content','woocommerce_template_single_add_to_cart');
add_action( 'after_content_right_woo', 'devvn_add_to_cart_after_content', 30 );
function devvn_add_to_cart_after_content() {	
	global $product;
	$cart = '';
	if ( is_singular('product') && $product->is_in_stock() && $product->is_purchasable()) {
		ob_start();
        ?>
        <div class="cart_after_content">
        	<div class="cart_after_content_title"><span><?php _e('Mua sản phẩm này','devvn')?></span></div>
        	<div class="row row-padding-15">
        		<div class="col-xs-12">
        			<h3><?php the_title();?></h3>
        			<?php do_action('cart_after_content');?>
        		</div>
        	</div>
        </div>
        <?php
		$cart = ob_get_clean();		
	}
    echo $cart;
}
add_action('woocommerce_thankyou', 'thongbao_khuyenmai',5,1);
function thongbao_khuyenmai($orderID){
	$show_hide_thong_bao_khi_checkout = get_field('show_hide_thong_bao_khi_checkout','option');
	$thong_bao_khi_checkout = get_field('thong_bao_khi_checkout','option');
	if(!$thong_bao_khi_checkout || !$show_hide_thong_bao_khi_checkout) return;
	echo '<div class="thongbao_khuyenmai">';
	echo $thong_bao_khi_checkout;
	echo '</div>';
}
include 'add_to_cart_popup/cart_popup.php';

function add_floating_right(){
    $show_minicart_right = get_field('show_minicart_right','option');
	ob_start();
    if($show_minicart_right) {
        ?>
        <div class="cart_floating_right">
            <div class="cart_floating_right_button"><strong><i
                            class="fa fa-shopping-basket"></i> <?php _e('Giỏ hàng', 'devvn'); ?></strong></div>
            <div class="cart_floating_right_content">
                <div class="cart_floating_right_title">
                    <span><i class="fa fa-shopping-basket"></i> <?php _e('Giỏ hàng của bạn', 'devvn'); ?></span>
                    <span><i class="fa fa-sign-out"></i> <?php _e('Thu nhỏ', 'devvn'); ?></span>
                </div>
                <div class="cart_floating_right_minicart woocommerce">
                    <div class="floating_popup_cart"><?php woocommerce_mini_cart(); ?></div>
                </div>
            </div>
        </div>
        <style>
            .cart_floating_right {
                position: fixed;
                top: 30%;
                right: 0;
                z-index: 999999
            }

            .cart_floating_right_button {
                width: 115px;
                height: 35px;
                -moz-transform: rotate(270deg);
                -o-transform: rotate(270deg);
                -ms-transform: rotate(270deg);
                -webkit-transform: rotate(270deg);
                transform: rotate(270deg);
                -ms-border-radius: 6px 6px 0 0;
                border-radius: 6px 6px 0 0;
                position: absolute;
                right: -40px;
                top: 62px;
                background: #ed161d;
                font-weight: 400;
                color: #fff;
                text-align: center;
                line-height: 34px;
                font-size: 15px;
                cursor: pointer;
                -moz-transition: .3s ease;
                -o-transition: .3s ease;
                -webkit-transition: .3s ease;
                -ms-transition: .3s ease;
                transition: .3s ease;
                z-index: 9998
            }

            .cart_floating_right_button:hover {
                background: #6d6d6d
            }

            .cart_floating_right_content {
                position: absolute;
                right: 0;
                width: 270px;
                top: 21px;
                min-height: 140px;
                -ms-border-radius: 4px;
                border-radius: 4px;
                border: 1px solid #ed161d;
                float: left;
                background: #fff;
                -moz-transition: .1s ease;
                -o-transition: .1s ease;
                -webkit-transition: .1s ease;
                -ms-transition: .1s ease;
                transition: .1s ease;
                z-index: 9998;
                transform: translate(270px, 0);
                -moz-transform: translate(270px, 0);
                -webkit-transform: translate(270px, 0)
            }

            .open_float_cart .cart_floating_right_content {
                transform: translate(0, 0);
                -moz-transform: translate(0, 0);
                -webkit-transform: translate(0, 0)
            }

            .cart_floating_right_title {
                width: 100%;
                height: 35px;
                background: #ed161d;
                color: #fff;
                padding: 0 10px;
                overflow: hidden;
                cursor: pointer
            }

            .cart_floating_right_title span {
                float: left;
                display: block;
                line-height: 35px
            }

            .cart_floating_right_title span:last-of-type {
                float: right
            }

            .floating_popup_cart {
                padding: 10px
            }

            .floating_popup_cart .mini_cart_item a.remove {
                position: absolute;
                top: 5px;
                left: 0;
                font-size: 14px;
                width: 15px;
                height: 15px
            }

            .floating_popup_cart li.mini_cart_item {
                position: relative;
                padding-left: 25px !important
            }

            .floating_popup_cart ul.cart_list.product_list_widget {
                padding: 0;
                max-height: 210px;
                overflow-x: hidden;
                overflow-y: auto
            }

            .floating_popup_cart p.total {
                border-top: 1px dashed #e03232;
                padding-top: 10px;
                margin-top: 10px
            }

            @media (max-width: 767px) {
                .cart_floating_right {
                    display: none
                }
            }
        </style>
        <script>
            (function ($) {
                $(document).ready(function () {
                    $('.cart_floating_right_button').click(function () {
                        $('.cart_floating_right').addClass('open_float_cart');
                    });
                    $('.cart_floating_right_title').click(function () {
                        $('.cart_floating_right').removeClass('open_float_cart');
                    });
                });
            })(jQuery);
        </script>
        <?php
    }
	echo ob_get_clean();
}
add_action('wp_footer', 'add_floating_right', 51);

function thong_tin_lienhe_singleproduct(){
	$thong_tin_lienhe_singleproduct = get_field('thong_tin_lienhe_singleproduct','option');
	if(!$thong_tin_lienhe_singleproduct) return;
	echo $thong_tin_lienhe_singleproduct;
}
add_action('woocommerce_share', 'thong_tin_lienhe_singleproduct',5);

function title_query_search($tag_text = null, $postType = 'product'){
	global $wpdb;
	$listPostID = $listPostbyTitle = $listPostbyContent = array();
	
	//List post serach by title
	$sql  = "SELECT ID
			 FROM ".$wpdb->prefix."posts
			 WHERE post_type = '".$postType."'
			 AND post_status = 'publish'
			 AND post_title LIKE '%".$tag_text."%'";
	$arrayTag =  $wpdb->get_results($sql);
	if($arrayTag && !is_wp_error($arrayTag)){
		foreach ($arrayTag as $v){
			$listPostbyTitle[] = $v->ID;
		}
	}
	
	//List post serach by content
	$sql  = "SELECT ID
			 FROM ".$wpdb->prefix."posts
			 WHERE post_type = '".$postType."'
			 AND post_status = 'publish'
			 AND post_content LIKE '%".$tag_text."%'";
	$arrayTag =  $wpdb->get_results($sql);
	if($arrayTag && !is_wp_error($arrayTag)){
		foreach ($arrayTag as $v){
			$listPostbyContent[] = $v->ID;
		}
	}
	
	$allPost = array_unique(array_merge($listPostID, $listPostbyTitle, $listPostbyContent));
	
	return $allPost;
}
function search_order_by_title($query){
	if(!isset($query->query_vars['post_type'])) return;
	if($query->query_vars['post_type'] != 'product') return;
	
	if ( !is_admin() && $query->is_main_query() && is_search() ) {
		$query->set('post__in', title_query_search($query->query_vars['s']));
		$query->set('orderby', 'post__in');
	}
	return $query;
}
add_filter('pre_get_posts','search_order_by_title');

/*0đ thành liên hệ*/
function devvn_wc_custom_get_price_html( $price, $product ) {
    $enable_text_price_zero = get_field('enable_text_price_zero','option');
    $text_price_zero = get_field('text_price_zero','option');
    if ( $product->get_price() == '0') {
        if ( $product->is_on_sale() && $product->get_regular_price() ) {
            $regular_price = wc_get_price_to_display( $product, array( 'qty' => 1, 'price' => $product->get_regular_price() ) );
            $price = wc_format_price_range( $regular_price, __( 'Miễn phí', 'devvn' ) );
        } else {
            $price = '<span class="amount">'.__('Miễn phí','devvn').'</span>';
        }
    }elseif(empty($product->get_price())&& $product->get_price() == '' && $enable_text_price_zero){
        $price = '<span class="amount">'.$text_price_zero.'</span>';
    }
    return $price;
}
add_filter( 'woocommerce_get_price_html', 'devvn_wc_custom_get_price_html', 10, 2 );

add_action('devvn_woocommerce_open_linkto_item','woocommerce_template_loop_product_link_open');
add_action('devvn_woocommerce_close_linkto_item','woocommerce_template_loop_product_link_close');


function devvn_woocommerce_upsell_display(){
    global $product, $woocommerce;

    if ( ! $product || !$product->is_in_stock() || ! $product->is_purchasable()) {
        return;
    }

    $upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ));

    if ( $upsells ) : ?>
        <?php
        $devvn_upsells = array();
        $devvn_total = 0;
        if($product->get_type() == 'variable'){
            $price = $product->get_variation_price();
        }else{
            $price = $product->get_price();
        }
        if($price) {
            $devvn_upsells[0]['id'] = $product->get_id();
            $devvn_upsells[0]['attr_id'] = $product->get_id();
            $devvn_upsells[0]['title'] = esc_attr($product->get_title());
            $devvn_upsells[0]['image'] = $product->get_image();
            $devvn_upsells[0]['price'] = $price;
            $devvn_upsells[0]['pricehtml'] = $product->get_price_html();
            $devvn_total += $price;
        }
        $devvn_count = 1;
        foreach ( $upsells as $upsell ) :
            if($upsell->get_type() == 'variable'){
                $price = $upsell->get_variation_price();
            }else{
                $price = $upsell->get_price();
            }
            if($price) {
                $devvn_upsells[$devvn_count]['id'] = ($upsell->get_parent_id()) ? $upsell->get_parent_id() : $upsell->get_id();
                $devvn_upsells[$devvn_count]['attr_id'] = $upsell->get_id();
                $devvn_upsells[$devvn_count]['title'] = esc_attr($upsell->get_title());
                $devvn_upsells[$devvn_count]['image'] = $upsell->get_image();
                $devvn_upsells[$devvn_count]['price'] = $price;
                $devvn_upsells[$devvn_count]['pricehtml'] = $upsell->get_price_html();
                $devvn_total += $price;
                $devvn_count++;
            }
        endforeach;
        ?>
        <div class="devvn_upsells">
            <h3><?php _e( 'Thường được mua cùng', 'woocommerce' ) ?></h3>
            <div class="devvn_list_product_upsell">
                <?php foreach ( $devvn_upsells as $k=>$upsell ) : ?>
                    <?php
                    if($k == 0){
                        $href = 'javascript:void(0)';
                    }else{
                        $href = esc_url( get_permalink( $upsell['id'] ) );;
                    }
                    ?>
                    <div class="devvn_upsells_item" id="combo_<?php echo $upsell['id'];?>">
                        <a href="<?php echo $href;?>" title="<?php echo $upsell['title']; ?>" target="_blank">
                            <?php echo $upsell['image']; ?>
                        </a>
                    </div>
                <?php endforeach;?>
            </div>
            <form id="devvn_combo">
                <div class="devvn_list_upsell_choose">
                    <?php foreach ( $devvn_upsells as $k=>$upsell ) : ?>
                        <?php
                        if($k == 0){
                            $href = 'javascript:void(0)';
                        }else{
                            $href = esc_url( get_permalink( $upsell['id'] ) );;
                        }
                        ?>
                        <label class="devvn_upsell_choose">
                            <input type="checkbox"
                                   name="combo_<?php echo $upsell['id'];?>"
                                   data-id="<?php echo $upsell['id'];?>"
                                   data-price="<?php echo $upsell['price']; ?>"
                                   data-qty="1"
                                   data-availableid="<?php echo $upsell['attr_id'];?>"
                                   data-available=""
                                   checked>
                            <span class="devvn_upsells_icon"></span>
                            <span class="devvn_upsells_text">
                            <a href="<?php echo $href; ?>" title="<?php echo $upsell['title']; ?>" target="_blank">
                                <span class="devvn_upsells_name"><?php echo ($k == 0) ? '<strong>Bạn đang xem: </strong>':''?><?php echo $upsell['title']; ?></span> -
                                <strong class="devvn_upsells_price"><?php echo $upsell['pricehtml']; ?></strong>
                            </a>
                        </span>
                        </label>
                    <?php endforeach;?>
                </div>
                <div class="devn_upsells_tocart">
                    <p>Tổng tiền: <span class="total-price price"><?php echo wc_price($devvn_total);?></span></p>
                    <button class="devvn-add-combo" data-nonce="<?php echo wp_create_nonce('devvn_add_combo');?>">Thêm <span class="devvn_upsells_count"><?php echo $devvn_count?></span> sp vào giỏ hàng</button>
                    <div class="devvn_upsells_mess"></div>
                </div>
            </form>
        </div>
    <?php endif;
    wp_reset_postdata();
}

add_action( 'wp_ajax_devvn_add_combo', 'devvn_add_combo_func' );
add_action( 'wp_ajax_nopriv_devvn_add_combo', 'devvn_add_combo_func' );
function devvn_add_combo_func() {
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "devvn_add_combo")) {
        exit("No naughty business please");
    }
    global $woocommerce;
    if(isset($_POST['dataproid'])) {
        $data = $_POST['dataproid'];
        if($data && is_array($data) && !empty($data)) {
            $count = 0;
            ob_start();
            foreach ($data as $k=>$v) {
                $proid = $v['proid'];
                $qty = $v['qty'];
                $availableid = $v['availableid'];
                if($woocommerce->cart->add_to_cart($proid, $qty, $availableid, null, null)){
                    $count++;
                }
            }
            ob_end_clean();
            wp_send_json_success($count);
        }
    }
    wp_send_json_error();
    die();
}

add_action('woocommerce_single_product_summary','devvn_custom_text_after_title', 6);
function devvn_custom_text_after_title(){
    global $product;
    $custom_text  = get_field('customtext_aftertitle', $product->get_id());
    if($custom_text){
        echo '<div class="item-bestseller">'.$custom_text.'</div>';
    }
}

function devvn_category_orderby_featured( $query ) {
    if (!is_admin() && (is_product_category() || is_product_tag()) && $query->is_main_query()) {
        $ghim_featured_prod = get_field('ghim_featured_prod','option');
        $existing_metaquery = $query->get( 'meta_query' );
        if($ghim_featured_prod && empty($existing_metaquery)):
            $query->set( 'orderby' , "meta_value " . $query->get( 'orderby' ) );
            $query->set( 'order' , "DESC " . $query->get( 'order' ) );
            $query->set('meta_query' , array(
                'relation' => 'OR',
                array(
                    'key'=>'_featured',
                    'compare' => 'EXISTS',
                ),
                array(
                    'key'=>'_featured',
                    'compare' => 'NOT EXISTS',
                )
            ));
        endif;
    }
}
add_action( 'pre_get_posts', 'devvn_category_orderby_featured' );

function devvn_order_by_change( $order_by, $query )
{
    $feture_product_id = wc_get_featured_product_ids();
    if( is_array( $feture_product_id ) && !empty($feture_product_id) && !is_admin() && (is_product_category() || is_product_tag()) && $query->is_main_query())
    {
        $ghim_featured_prod = get_field('ghim_featured_prod','option');
        if($ghim_featured_prod):
            if( empty( $order_by ) ) {
                $order_by =  "FIELD(".$GLOBALS['wpdb']->posts.".ID,'".implode("','",$feture_product_id)."') DESC ";
            }
            else
            {
                $order_by =  "FIELD(".$GLOBALS['wpdb']->posts.".ID,'".implode("','",$feture_product_id)."') DESC, " . $order_by;
            }
        endif;
    }
    return $order_by;
}
add_filter('posts_orderby', 'devvn_order_by_change', 99, 2);

add_action('woocommerce_after_shop_loop_item_title','devvn_add_icon_featured', 20);
function devvn_add_icon_featured(){
    global $product;
    $view_img_featured = get_field('view_img_featured','option');
    $img_is_featured = get_field('img_is_featured','option');
    if ( $product->is_featured() && $view_img_featured && $img_is_featured) {
        echo '<div class="devvn_is_featured">'.wp_get_attachment_image($img_is_featured,'full').'</div>';
    }
}

function devvn_hidden_shopping(){
    if(function_exists('get_field') && get_field('hidden_shopping','option') && !is_admin()) {
        remove_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart', 30);
        remove_action('woocommerce_single_product_summary','woocommerce_template_single_price', 10);
        remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart', 10);
        remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price', 10);
    }
}
add_action( 'after_setup_theme', 'devvn_hidden_shopping' );

add_action( 'woocommerce_checkout_process', 'wc_minimum_order_amount' );
add_action( 'woocommerce_before_cart' , 'wc_minimum_order_amount' );
function wc_minimum_order_amount() {
    $enable_checkout_minimum = get_field('enable_checkout_minimum','option');
    $minimum = get_field('checkout_minimum_price','option');
    $checkout_minimum_price_mess = get_field('checkout_minimum_price_mess','option');
    if ($minimum && $enable_checkout_minimum &&  WC()->cart->total < $minimum ) {
        if( is_cart() ) {
            wc_print_notice(
                sprintf( $checkout_minimum_price_mess ,
                    wc_price( $minimum ),
                    wc_price( WC()->cart->total )
                ), 'error'
            );
        } else {
            wc_add_notice(
                sprintf( $checkout_minimum_price_mess ,
                    wc_price( $minimum ),
                    wc_price( WC()->cart->total )
                ), 'error'
            );
        }
    }
}

add_action('woocommerce_after_shop_loop_item','devvn_add_out_of_stock', 20);
function devvn_add_out_of_stock(){
    global $product;
    $availability = $product->get_availability();
    if ( !$product->is_in_stock() && ! empty( $availability['availability'] )) :
        echo '<div class="devvn-outofstock">' . wp_kses_post( $availability['availability'] ).'</div>';
    endif;
}

function devvn_get_product_cat_array($id, $taxonomy = 'product_cat'){
    $terms = get_the_terms( $id, $taxonomy );

    if ( is_wp_error( $terms ) )
        return $terms;

    if ( empty( $terms ) )
        return false;

    $links = array();

    foreach ( $terms as $term ) {
        $link = get_term_link( $term, $taxonomy );
        if ( is_wp_error( $link ) ) {
            return $link;
        }
        $links[] = $term->term_id;
    }

    return $links;
}

add_action('woocommerce_single_product_summary','devvn_khuyenmai_after_desc', 21);
if(!function_exists('devvn_khuyenmai_after_desc')){
    function devvn_khuyenmai_after_desc(){
        global $product;
        $list_km_img = '';
        if(have_rows('list_km_img')):
            ob_start();
            echo '<ul class="list_km_img">';
            while(have_rows('list_km_img')):the_row();
                $km_icon = get_sub_field('km_icon');
                $km_content = get_sub_field('km_content');
                $link_img = wp_get_attachment_url($km_icon, 'full');
                ?>
                <li title="<?php echo esc_attr($km_content);?>"><a href="<?php echo $link_img;?>" class="km_lightbox" rel="km-gallery" title=""><?php echo wp_get_attachment_image($km_icon, 'full');?></a></li>
                <?php
            endwhile;
            echo '</ul>';
            $list_km_img = ob_get_clean();
        endif;
        $noi_dung_khuyen_mai = get_field('noi_dung_khuyen_mai');
        if($noi_dung_khuyen_mai):
            ?>
            <div class="section_noi_dung_khuyen_mai margin-b-15">
                <div class="tinymce"><?php echo $noi_dung_khuyen_mai;?><?php echo $list_km_img;?></div>
            </div>
        <?php
        else:
            $km = '';
            $terms = devvn_get_product_cat_array($product->get_id());
            if($terms && !is_wp_error($terms)){
                foreach($terms as $term){
                    $content_km_danhmuc = get_field('content_km_danhmuc', 'product_cat_'.$term);
                    if($content_km_danhmuc){
                        $km .= $content_km_danhmuc;
                    }
                }
                if($km || $list_km_img) {
                    ?>
                    <div class="section_noi_dung_khuyen_mai margin-b-15">
                        <div class="tinymce"><?php echo $km; ?><?php echo $list_km_img; ?></div>
                    </div>
                    <?php
                }
            }
        endif;
    }
}

add_action('woocommerce_after_shop_loop_item_title','loop_list_km_icon');
function loop_list_km_icon(){
    global $product;
    $list_km_img = '';
    if(have_rows('list_km_img', $product->get_id())):
        ob_start();
        echo '<ul class="list_km_img">';
        while(have_rows('list_km_img', $product->get_id())):the_row();
            $km_icon = get_sub_field('km_icon');
            $km_content = get_sub_field('km_content');
            ?>
            <li title="<?php echo esc_attr($km_content);?>"><?php echo wp_get_attachment_image($km_icon, 'full');?></li>
        <?php
        endwhile;
        echo '</ul>';
        $list_km_img = ob_get_clean();
    endif;
    ?>
    <?php if($list_km_img):?>
    <div class="loop_list_km_icon">
        <span>Quà tặng</span>
        <div class="loop_list_km_icon_box">
            <?php echo $list_km_img;?>
        </div>
    </div>
    <?php endif;?>
    <?php
}

remove_action('woocommerce_single_product_summary','woocommerce_template_single_sharing', 50);
add_action('woocommerce_before_single_product_summary','woocommerce_template_single_sharing', 30);

add_filter('woocommerce_loop_add_to_cart_link','devvn_change_button_add_to_cart');
function devvn_change_button_add_to_cart($button){
    global $product;
    $add_to_cart_to_readmore = get_field('add_to_cart_to_readmore','option');
    $add_to_cart_to_readmore_text = get_field('add_to_cart_to_readmore_text','option');
    if($add_to_cart_to_readmore) {
        $button = '<a rel="nofollow" href="' . $product->get_permalink() . '" class="button product_type_simple">' . $add_to_cart_to_readmore_text . '</a>';
    }
    return $button;
}