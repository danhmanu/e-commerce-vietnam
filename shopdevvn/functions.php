<?php
define( "TEMP_URL" , get_bloginfo('template_url'));
define( "DEVVN_VERSION" ,'1.0.6');
define( "DEVVN_DEV_MODE" , true);

if( class_exists('acf') ) { 
	define('GOOLE_MAPS_API', get_field('google_maps_api','option'));
	define('FACE_APP_ID', get_field('face_app_id','option'));
	define('FACE_USER_ID', get_field('face_user_id','option'));
}

require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
require get_template_directory() . '/inc/aq_resizer.php';
require get_template_directory() . '/inc/copyright/copyright_svl.php';
// Chỉ nạp phần WooCommerce của theme khi WooCommerce đang bật (site hiện dùng plugin Tiên Lập – Sản phẩm)
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce_int/woo_int.php';
}
require get_template_directory() . '/inc/style_script_int.php';
require get_template_directory() . '/inc/devvn-megamenu/mega_menu_init.php';
require get_template_directory() . '/inc/widget/devvn-recentpostcustoms.php';
require get_template_directory() . '/inc/widget/devvn-videos.php';
require get_template_directory() . '/inc/widget/devvn-tintucdanhmuc.php';
require get_template_directory() . '/inc/widget/devvn-videosdanhmuc.php';
require get_template_directory() . '/inc/widget/devvn-mostviewbaw.php';
require get_template_directory() . '/inc/widget/devvn-tintucnoibat.php';
//require get_template_directory() . '/inc/cpt-shorturl.php';
require get_template_directory() . '/inc/theme_customize/customizer.php';


function my_acf_google_map_api( $api ){	
	$api['key'] = GOOLE_MAPS_API;	
	return $api;	
}
add_filter('woocommerce_checkout_fields','custom_email_devvn_checkout_fields', 9999999);
function custom_email_devvn_checkout_fields($fields){
    unset($fields['billing']['billing_email']);
    return $fields;
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');
/*
 * Setup theme
 */
function devvn_setup() {
	load_theme_textdomain( 'devvn', get_template_directory() . '/languages' );
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	//Add thumbnail to post
	add_theme_support( 'post-thumbnails' );
	//Shortcode in widget
	add_filter('widget_text', 'do_shortcode');
	//menu
	/********
	 * Call: wp_nav_menu(array('theme_location'  => 'header','container'=> ''));
	 * *********/
	register_nav_menus( array(
		'header' => __( 'Header menu', 'devvn' ),
		'footer'  => __( 'Footer menu', 'devvn' ),
		'top_nav'  => __( 'Top bar menu', 'devvn' ),
		'menu_doc'  => __( 'Menu dọc', 'devvn' )
	));
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );
	//Remove version
	remove_action('wp_head', 'wp_generator');
	//Remove Default WordPress Image Sizes
	function svl_remove_default_image_sizes( $sizes) {
		//unset( $sizes['thumbnail']);
		unset( $sizes['medium']);
		unset( $sizes['large']);
		unset( $sizes['medium_large']);
		 
		return $sizes;
	}
	// Giữ các cỡ ảnh medium/large để WordPress tạo srcset, ảnh nhẹ hơn trên điện thoại
	//add_filter('intermediate_image_sizes_advanced', 'svl_remove_default_image_sizes');
	if ( function_exists( 'add_image_size' ) ) {
		//add_image_size( 'homepage-thumb', 50, 50, true ); //(cropped)
	}
    if(function_exists('get_field') && get_field('enable_combo_tocart','option')) {
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
        add_action('woocommerce_single_product_summary', 'devvn_woocommerce_upsell_display', 60);
    }
}
add_action( 'after_setup_theme', 'devvn_setup' );
//Sidebar
/*
 <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('main-sidebar')) : ?><?php endif; ?>
 */
add_action( 'widgets_init', 'theme_slug_widgets_init' );
function theme_slug_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Main Sidebar', 'devvn' ),
        'id' => 'main-sidebar',        
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title-sidebar">',
		'after_title'   => '</div>',
    ));
    register_sidebar( array(
        'name' => __( 'News Sidebar', 'devvn' ),
        'id' => 'blog-sidebar',        
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title-sidebar">',
		'after_title'   => '</div>',
    ));
    register_sidebar( array(
        'name' => __( 'Shop Sidebar', 'devvn' ),
        'id' => 'shop-sidebar',        
        'before_widget' => '<div id="%1$s" class="widget block-sidebar %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title-sidebar">',
		'after_title'   => '</div>',
    ));
    register_sidebar( array(
        'name' => __( 'Single shop sidebar', 'devvn' ),
        'id' => 'single-shop-sidebar',
        'before_widget' => '<div id="%1$s" class="widget block-sidebar %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="title-sidebar">',
        'after_title'   => '</div>',
    ));
    register_sidebar( array(
        'name' => __( 'Footer 1', 'devvn' ),
        'id' => 'footer-1',        
        'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title-sidebar-footer">',
		'after_title'   => '</div>',
    ));
    register_sidebar( array(
        'name' => __( 'Footer 2', 'devvn' ),
        'id' => 'footer-2',        
        'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title-sidebar-footer">',
		'after_title'   => '</div>',
    ));
    register_sidebar( array(
        'name' => __( 'Footer 3', 'devvn' ),
        'id' => 'footer-3',        
        'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title-sidebar-footer">',
		'after_title'   => '</div>',
    ));
    register_sidebar( array(
        'name' => __( 'Footer 4', 'devvn' ),
        'id' => 'footer-4',        
        'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title-sidebar-footer">',
		'after_title'   => '</div>',
    ));
    register_sidebar( array(
        'name' => __( 'Footer Tags', 'devvn' ),
        'id' => 'footer-tags',        
        'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title-sidebar-footer">',
		'after_title'   => '</div>',
    ));
}
//Title
function svl_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() )
		return $title;
	$title .= get_bloginfo( 'name', 'display' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
		$title = "$title $sep " . sprintf( __( 'Trang %s', 'devvn' ), max( $paged, $page ) );
	return $title;
}
add_filter( 'wp_title', 'svl_wp_title', 10, 2 );

function show_megamenu_sidebar($is_header = false){
    $list_danh_muc = get_field('list_danh_muc','option');
	if( ($is_header || (is_page_template('templates/home-default.php') || is_page_template('templates/temp-homefullwidthhasmenu.php') || is_front_page())) && $list_danh_muc) return true;
	return false;
}
// Add specific CSS class by filter
add_filter( 'body_class', 'devvn_mobile_class' );
function devvn_mobile_class( $classes ) {
	if(wp_is_mobile()){
		$classes[] = "devvn_mobile";
	}else{
		$classes[] = "devvn_desktop";
	}
	if(is_singular('product')){
		$classes[] = "full-width";
	}
	if(show_megamenu_sidebar()){
		$classes[] = "sidebar_to_menu";
	}
    $kich_hoat_top_header = get_field('kich_hoat_top_header','option');
	if(!$kich_hoat_top_header){
        $classes[] = "hidden_top_header";
    }
    $top_header_stick = get_field('top_header_stick','option');
	if($top_header_stick == 'menu_stick'){
        $classes[] = 'menu_only_stick';
    }elseif($top_header_stick == "top_header_stick"){
	    $classes[] = 'top_header_stick';
    }else{
        $classes[] = 'logo_menu_stick';
    }
	return $classes;
}

//Theme Options
if( function_exists('acf_add_options_page') ) {
 
	$option_page = acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title' 	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
        'capability' 	=> 'edit_posts',
        'redirect' 	    => false,
        'position'      =>  4
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Danh mục sản phẩm',
		'menu_title' 	=> 'Menu danh mục sản phẩm',
		'parent_slug' 	=> 'theme-general-settings',
        'capability' 	=> 'moderate_comments',
	));
	if ( class_exists( 'WooCommerce' ) ) acf_add_options_sub_page(array(
		'page_title' 	=> 'Woocommerce',
		'menu_title' 	=> 'Woocommerce',
		'parent_slug' 	=> 'theme-general-settings',
        'capability' 	=> 'moderate_comments',
	));
 
}

//Code phan trang
function wp_corenavi_table() {
		global $wp_query;
		$big = 999999999; 
		$translated = "";
		$total = $wp_query->max_num_pages;
		if($total > 1) echo '<div class="paginate_links">';
		echo paginate_links( array(
			'base' 		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' 	=> '?paged=%#%',
			'current' 	=> max( 1, get_query_var('paged') ),
			'total' 	=> $wp_query->max_num_pages,
			'mid_size'	=> '10',
			'prev_text'    => __('Trước','devvn'),
			'next_text'    => __('Tiếp','devvn'),
		) );
		if($total > 1) echo '</div>';
}
function div_wrapper($content) {
    $pattern = '~<iframe.*src=".*(youtube.com|youtu.be).*</iframe>|<embed.*</embed>~'; //only iframe youtube
    preg_match_all($pattern, $content, $matches);
    foreach ($matches[0] as $match) { 
        $wrappedframe = '<div class="videoWrapper">' . $match . '</div>';
        $content = str_replace($match, $wrappedframe, $content);
    }
    return $content;    
}
add_filter('the_content', 'div_wrapper');

function get_thumbnail($img_size = 'thumbnail', $w = '', $h = ''){
	global $post;
	$url_thumb_full = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );  		
  	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $img_size );
	$url_thumb = $thumb['0'];
	$w_thumb = $thumb['1'];
	$h_thumb = $thumb['2'];
	if(($w_thumb != $w || $h_thumb != $h) && $url_thumb_full && $w != "" && $h != "") $url_thumb = aq_resize($url_thumb_full,$w,$h,true,true,true);
	if(!$url_thumb) $url_thumb = TEMP_URL.'/images/no-image-featured-image.png';
	return $url_thumb;
}

function get_excerpt($limit = 130){
	$excerpt = get_the_excerpt();
	if(!$excerpt) $excerpt = get_the_content();
	$excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
	$excerpt = strip_shortcodes($excerpt);
	$excerpt = strip_tags($excerpt);
	// Cắt theo ký tự (UTF-8) để không vỡ chữ tiếng Việt có dấu
	if ( mb_strlen($excerpt, 'UTF-8') > $limit ) {
		$excerpt = mb_substr($excerpt, 0, $limit, 'UTF-8');
		$space = mb_strrpos($excerpt, ' ', 0, 'UTF-8');
		if ( $space ) $excerpt = mb_substr($excerpt, 0, $space, 'UTF-8');
	}
	$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
	if($excerpt){
		$permalink = get_the_permalink();
		$excerpt = $excerpt.'... <a href="'.$permalink.'" title="" rel="nofollow">xem thêm</a>';
	}
	return $excerpt;
}

function devvn_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'devvn_excerpt_more' );

add_action( 'tgmpa_register', 'devvn_register_required_plugins' );
if(!function_exists('devvn_register_required_plugins')) {
    function devvn_register_required_plugins()
    {
        $plugins = array(
            array(
                'name' => 'Advanced Custom Fields PRO',
                'slug' => 'advanced-custom-fields-pro',
                'source' => get_template_directory() . '/inc/plugins/advanced-custom-fields-pro.zip',
                'required' => true,
                'force_activation' => true,
                'force_deactivation' => true
            )
            // WooCommerce không còn bắt buộc: site dùng plugin Tiên Lập – Sản phẩm
        );
        $config = array(
            'id' => 'devvn',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu' => 'tgmpa-install-plugins', // Menu slug.
            'parent_slug' => 'themes.php',            // Parent menu slug.
            'capability' => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices' => true,                    // Show admin notices or not.
            'dismissable' => false,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg' => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message' => '',                      // Message to output right before the plugins table.
        );
        tgmpa($plugins, $config);
    }
}

add_action('devvn_news_category', 'devvn_tintuc_lienquan_sanpham',10);
function devvn_tintuc_lienquan_sanpham(){
    global $post;
    $chon_danhmuc_tintuc_lienquan = array();
    $select_tintuc_sanpham = null;
    if(is_single()){
        $select_tintuc_sanpham = get_field('select_tintuc_sanpham');
        if(!$select_tintuc_sanpham) {
            $taxonomy_names = wp_get_post_terms($post->ID, 'product_cat', array("fields" => "ids"));
            $danhmuc_tintuc_mer = array();
            if($taxonomy_names && is_array($taxonomy_names)){
                foreach ($taxonomy_names as $_term){
                    $danhmuc_tintuc = get_field('chon_danhmuc_tintuc_lienquan','product_cat_'.$_term);
                    if($danhmuc_tintuc){
                        $danhmuc_tintuc_mer = array_merge($danhmuc_tintuc_mer, $danhmuc_tintuc);
                    }
                }
            }
            $chon_danhmuc_tintuc_lienquan = array_unique($danhmuc_tintuc_mer);
        }
    }elseif (is_tax('product_cat')){
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
        $chon_danhmuc_tintuc_lienquan = get_field('chon_danhmuc_tintuc_lienquan','product_cat_'.$term_id);
    }else {
        return false;
    }

    if($select_tintuc_sanpham) {
        $tintuc_lienquan = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => 5,
            'post__in' => $select_tintuc_sanpham
        ));
    }else{
        if(empty($chon_danhmuc_tintuc_lienquan) || !is_array($chon_danhmuc_tintuc_lienquan)) return false;
        $tintuc_lienquan = new WP_Query(array(
            'post_type'			=>	'post',
            'posts_per_page'	=>	5,
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field'    => 'term_id',
                    'terms'    => $chon_danhmuc_tintuc_lienquan,
                ),
            ),
        ));
    }

    if($tintuc_lienquan->have_posts()):
        if(!is_tax('product_cat')):
            ?>
            <div class="tintuc_lienquan">
            <div class="section_cvp_title margin-b-15"><span><?php _e('Tin tức','devvn');?></span></div><?php endif;?>
        <ul class="news_list">
            <?php while ($tintuc_lienquan->have_posts()):$tintuc_lienquan->the_post();?>
                <li class="<?php echo (has_post_thumbnail())?'has-thumbnail':''?>">
                    <a href="<?php the_permalink()?>" title="<?php the_title();?>">
                        <?php echo get_the_post_thumbnail($post->ID,'thumbnail'); ?>
                        <h3><?php the_title();?></h3>
                        <?php ?>
                    </a>
                </li>
            <?php endwhile;?>
        </ul>
        <?php if(!is_tax('product_cat')):?></div><?php endif;?>
        <?php
    endif;wp_reset_query();
}

add_action('devvn_videos_category', 'devvn_videos_lienquan_sanpham',10);
function devvn_videos_lienquan_sanpham(){
    global $post;
    $chon_danhmuc_tintuc_lienquan = array();
    $select_video_sanpham = null;
    if(is_single()){
        $select_video_sanpham = get_field('select_video_sanpham');
        if(!$select_video_sanpham){
            $taxonomy_names = wp_get_post_terms($post->ID, 'product_cat', array("fields" => "ids"));
            $danhmuc_tintuc_mer = array();
            if($taxonomy_names && is_array($taxonomy_names)){
                foreach ($taxonomy_names as $_term){
                    $danhmuc_tintuc = get_field('chon_danhmuc_videos_lienquan','product_cat_'.$_term);
                    if($danhmuc_tintuc){
                        $danhmuc_tintuc_mer = array_merge($danhmuc_tintuc_mer, $danhmuc_tintuc);
                    }
                }
            }
            $chon_danhmuc_tintuc_lienquan = array_unique($danhmuc_tintuc_mer);
        }
    }elseif (is_tax('product_cat')){
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
        $chon_danhmuc_tintuc_lienquan = get_field('chon_danhmuc_videos_lienquan','product_cat_'.$term_id);
    }else {
        return false;
    }

    if($select_video_sanpham){
        $tintuc_lienquan = new WP_Query(array(
            'post_type'			=>	'videos',
            'posts_per_page'	=>	5,
            'post__in'			=>	$select_video_sanpham
        ));
    }else{
        if(empty($chon_danhmuc_tintuc_lienquan) || !is_array($chon_danhmuc_tintuc_lienquan)) return false;
        $tintuc_lienquan = new WP_Query(array(
            'post_type'			=>	'videos',
            'posts_per_page'	=>	5,
            'tax_query' => array(
                array(
                    'taxonomy' => 'danhmuc_videos',
                    'field'    => 'term_id',
                    'terms'    => $chon_danhmuc_tintuc_lienquan,
                ),
            ),
        ));
    }

    if($tintuc_lienquan->have_posts()):
        if(!is_tax('product_cat')):
            ?>
            <div class="tintuc_lienquan">
            <div class="section_cvp_title margin-b-15"><span><?php _e('Videos','devvn');?></span></div><?php endif;?>
        <ul class="news_list videos_list">
            <?php while ($tintuc_lienquan->have_posts()):$tintuc_lienquan->the_post();?>
                <li class="<?php echo (has_post_thumbnail())?'has-thumbnail':''?>">
                    <a href="<?php the_permalink()?>" title="<?php the_title();?>">
                        <?php echo get_the_post_thumbnail($post->ID,'thumbnail'); ?>
                        <h3><?php the_title();?></h3>
                        <?php ?>
                    </a>
                </li>
            <?php endwhile;?>
        </ul>
        <?php if(!is_tax('product_cat')):?></div><?php endif;?>
        <?php
    endif;wp_reset_query();
}

function devvn_related_post(){
	global $post;	
	$_post_type =  get_post_type($post);
	switch ($_post_type){
		case 'videos':
			$categories_name = 'danhmuc_videos';
			break;
		default:
			$categories_name = 'category';
			break;
	}
	$categories = get_the_terms( $post, $categories_name );
	if ( ! $categories || is_wp_error( $categories ) )
		$categories = array();

	$categories = array_values( $categories );

	foreach ( array_keys( $categories ) as $key ) {
		_make_cat_compat( $categories[$key] );
	}
	if ($categories){
		echo '<div class="relatedcat">';
		$category_ids = array();
		foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
		$args = array(			
			'post__not_in' 		=>	array($post->ID),
			'showposts' 		=>	6,
			'post_type'			=>	$_post_type,
			'tax_query' => array(
				array(
					'taxonomy' => $categories_name,
					'field'    => 'term_id',
					'terms'    => $category_ids,
				),
			),
		);
		$my_query = new wp_query($args);
		if( $my_query->have_posts() ){
			echo '<div class="section_cvp_title margin-b-15"><span>'.__('Có thể bạn quan tâm','devvn').'</span></div>';
			echo '<div class="row row-padding-15">';
			while ($my_query->have_posts()){
			$my_query->the_post();
			?>
				<div class="col-sm-6 col-xs-12 margin-b-15">
				<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail('medium');?>
					<?php the_title(); ?>
				</a>
				<div class="post-info">
                	<span class="thetime updated"><i class="fa fa-clock-o"></i> <span><?php echo get_the_date()?></span></span>
                </div>
				</div>
			<?php
			}
			echo '</div>';
		}
		echo '</div>';
	}
	wp_reset_query();
}

function mySearchPostsFilter($query){
	$search_product = isset($_GET['post_type'])?esc_attr($_GET['post_type']):'';
	if($search_product == 'product') return;
	if ( !is_admin() && $query->is_main_query() ) {
		if ($query->is_search ){
			$query->set('post_type','post');
		}
	}
	return $query;
}
add_filter('pre_get_posts','mySearchPostsFilter');

function my_columns_filter( $columns ) {
	unset($columns['wpseo-title']);
 	unset($columns['wpseo-score']);
 	unset($columns['wpseo-metadesc']);
 	unset($columns['wpseo-focuskw']);
 	unset($columns['wpseo-score-readability']);
 	return $columns;
} 
add_filter( 'manage_edit-post_columns', 'my_columns_filter',10, 1 );
add_filter( 'manage_edit-page_columns', 'my_columns_filter',10, 1 );
add_filter( 'manage_edit-videos_columns', 'my_columns_filter',10, 1 );
add_filter( 'manage_edit-product_columns', 'my_columns_filter',10, 1 );

function devvn_single_share(){
	global $post;
	?>
	<ul class="social_list">
		<li class="facebook_like"><div class="fb-like" data-href="<?php echo get_the_permalink($post->ID)?>" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div></li>
		<li class="google_shared"><div class="g-plusone" data-size="medium"></div></li>
	</ul>
	<?php
}
add_action('devvn_after_single', 'devvn_single_share');

function show_phone_mobile(){
	$phone_show_mobile = get_field('phone_show_mobile','option');
	if(!$phone_show_mobile) return;
	?>
	<div class="show_phone_mobile"><?php echo $phone_show_mobile;?></div>
	<?php
}
add_action('wp_footer', 'show_phone_mobile');

function back_to_top(){
	ob_start();
	?>
	<a title="<?php _e('Lên đầu trang','devvn');?>" href="#" class="scrollTo_top"><i class="fa fa-arrow-up"></i></a>
	<?php
	echo ob_get_clean();
}
add_action('wp_footer', 'back_to_top', 50);

function maps_to_page(){
	if(!is_page_template(array('templates/temp-lienhe.php'))) return;
	ob_start();
	?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOLE_MAPS_API;?>"></script>
	<?php
	echo ob_get_clean();
}
add_action('wp_footer', 'maps_to_page', 52);

function list_5sp_noibat(){
	ob_start();
	?>
	<div class="clearfix"></div>
	<div class="col-xs-12">
		<div class="section_custom_view_products">
			<div class="section_cvp_title">
				<span><?php _e('Sản phẩm nổi bật','devvn')?></span>
			</div>	
			<div class="section_cvp_content">
				<?php echo do_shortcode('[featured_products per_page="5" columns="5"]')?>
			</div>
		</div>
	</div>
	<?php
	echo ob_get_clean();
}
add_action('after_archive_news', 'list_5sp_noibat');

/*Delete all transient*/
add_action( 'save_post', 'devvn_delete_all_transient' );
add_action( 'wp_insert_post', 'devvn_delete_all_transient' );
add_action( 'publish_post', 'devvn_delete_all_transient' );
add_action( 'trash_post', 'devvn_delete_all_transient' );
function devvn_delete_all_transient() {
    global $wpdb;
    $menus = $wpdb->get_col( 'SELECT option_name FROM '.$wpdb->prefix.'options WHERE option_name LIKE "_transient_devvn_cache_svl_%" ' );
    foreach( $menus as $menu ) {
        $key = str_replace( '_transient_', '', $menu );
        delete_transient( $key );
    }
    wp_cache_flush();
}

add_action('wp_head','devvn_code_html_head_func',99);
function devvn_code_html_head_func(){
    if(is_admin()) return;
    $code_html_head = get_field('code_html_head','option');
    if($code_html_head) echo $code_html_head;
}
add_action('wp_footer','devvn_code_html_footer_func',99);
function devvn_code_html_footer_func(){
    if(is_admin()) return;
    $code_html_footer = get_field('code_html_footer','option');
    if($code_html_footer) echo $code_html_footer;
}
add_action('devvn_after_body','devvn_code_html_body_func',99);
function devvn_code_html_body_func(){
    if(is_admin()) return;
    $code_html_body = get_field('code_html_body','option');
    if($code_html_body) echo $code_html_body;
}

if ( ! function_exists( 'devvn_ilc_mce_buttons' ) ) {
    function devvn_ilc_mce_buttons($buttons){
        array_push($buttons,
            "alignjustify",
            "subscript",
            "superscript"
        );
        return $buttons;
    }
    add_filter("mce_buttons", "devvn_ilc_mce_buttons");
}
if ( ! function_exists( 'devvn_ilc_mce_buttons_2' ) ) {
    function devvn_ilc_mce_buttons_2($buttons){
        array_push($buttons,
            "backcolor",
            "anchor",
            "fontselect",
            "fontsizeselect",
            "cleanup"
        );
        return $buttons;
    }
    add_filter("mce_buttons_2", "devvn_ilc_mce_buttons_2");
}
// Customize mce editor font sizes
if ( ! function_exists( 'devvn_mce_text_sizes' ) ) {
    function devvn_mce_text_sizes( $initArray ){
        $initArray['fontsize_formats'] = "9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 30px 32px 36px";
        return $initArray;
    }
    add_filter( 'tiny_mce_before_init', 'devvn_mce_text_sizes' );
}
add_filter('get_the_archive_title', function ($title) {
    return preg_replace('/.+: /', '', $title);
});

//add_filter('woocommerce_structured_data_breadcrumblist', 'devvn_woocommerce_structured_data_breadcrumblist', 10, 2);
function devvn_woocommerce_structured_data_breadcrumblist($markup, $breadcrumbs){
    $crumbs = $breadcrumbs->get_breadcrumb();

    if ( empty( $crumbs ) || ! is_array( $crumbs ) ) {
        return;
    }

    $markup                    = array();
    $markup['@type']           = 'BreadcrumbList';
    $markup['itemListElement'] = array();

    foreach ( $crumbs as $key => $crumb ) {
        $markup['itemListElement'][ $key ] = array(
            '@type'    => 'ListItem',
            'position' => $key + 1,
            'item'     => array(
                'name' => $crumb[0],
            ),
        );

        if(count( $crumbs ) == $key + 1 && is_single()){
            $markup['itemListElement'][ $key ]['item']['url'] = get_the_permalink();
            $markup['itemListElement'][ $key ]['item']['@id'] = get_the_permalink();
        }

        if ( ! empty( $crumb[1] ) && count( $crumbs ) !== $key + 1 ) {
            $markup['itemListElement'][ $key ]['item'] += array( '@id' => $crumb[1] );
        }
    }
    return $markup;
}

/*
 * Author levantoan.com
 * Hướng dẫn chặn tính năng so sánh giá của trình duyệt cốc cốc
 * https://levantoan.com/huong-dan-chan-tinh-nang-so-sanh-gia-cua-trinh-duyet-coccoc-tren-website-wordpress-su-dung-woocommerce/
 * */
add_filter('woocommerce_structured_data_product_offer','devvn_woocommerce_structured_data_product_offer', 10, 2);
function devvn_woocommerce_structured_data_product_offer($markup_offer, $product){
    if ('' !== $product->get_price()) {
        if ($product->is_type('variable')) {
            if(isset($markup_offer['price'])){
                $markup_offer['price'] = 0;
            }
            $markup_offer['priceSpecification']['price'] = 0;
        } else {
            $markup_offer['price'] = 0;
            if(isset($markup_offer['priceSpecification']['price'])){
                $markup_offer['priceSpecification']['price'] = 0;
            }
        }
    }
    return $markup_offer;
}


function devvn_comment_scripts()
{
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action( 'wp_enqueue_scripts', 'devvn_comment_scripts', 100 );

function devvn_comment($comment, $args, $depth)    {
    $GLOBALS['comment'] = $comment; ?>
    <li <?php comment_class();?> id="li-comment-<?=get_comment_ID();?>">
        <div id="comment-<?=get_comment_ID();?>" class="clearfix">
            <div class="comment-author vcard">
                <?php echo get_avatar($comment, $size='70', ''); ?>
            </div><!-- end comment autho vcard-->

            <div class="commentBody">
                <div class="comment-meta commentmetadata">
                    <?php printf(__('<p class="fn">%s</p>'), get_comment_author_link()); ?>
                </div><!--end .comment-meta-->
                <?php if($comment->comment_approved == '0') : ?>
                    <em><?php echo 'Your coment is waiting for moderation.';?></em>
                <?php endif; ?>
                <div class="noidungcomment">
                    <?php comment_text(); ?>
                </div>
                <div class="tools_comment">
                    <?php comment_reply_link(array_merge($args,array('respond_id' => 'formcmmaxweb','depth' => $depth, 'max_depth'=> $args['max_depth'])));?>
                    <?php edit_comment_link(__('Sửa'),' ',''); ?>
                    <?php printf(__('<a href="#comment-%d" class="ngaythang">%s</a>'),get_comment_ID(),get_comment_date('d/m/Y'));?>
                </div>

            </div><!--end #commentBody-->
        </div><!--end #comment-author-vcard-->
    </li>
<?php }
//validate comments
function comment_validation_init() {
    if(is_singular() && comments_open() ) { ?>
        <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#commentform').validate({
                    onfocusout: function(element) {
                        this.element(element);
                    },
                    rules: {
                        author: {
                            required: true,
                            minlength: 2
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        comment: {
                            required: true,
                        }
                    },
                    messages: {
                        author: "Ô họ và tên là bắt buộc.",
                        email: "Ô Email là bắt buộc.",
                        comment: "Ô bình luận là bắt buộc."
                    },
                    errorElement: "div",
                    errorPlacement: function(error, element) {
                        element.after(error);
                    }
                });
            });
        </script>
        <?php
    }
}
//add_action('wp_footer', 'comment_validation_init');

add_action('devvn_after_search_header', 'devvn_devvn_after_search_header');
function devvn_devvn_after_search_header(){
    if(have_rows('logo_duoi_search','option')):
        echo '<div class="logo_duoi_search">';
        while(have_rows('logo_duoi_search','option')):the_row();
            $logo = get_sub_field('logo');
            $link_to = get_sub_field('link_to');
            if($logo):
                ?>
                <?php if($link_to):?><a href="<?php echo esc_url($link_to);?>" title=""><?php endif;?>
                    <?php echo wp_get_attachment_image($logo,'full');?>
                <?php if($link_to):?></a><?php endif;?>
                <?php
            endif;
        endwhile;
        echo '</div>';
    endif;
}

add_filter( 'woocommerce_get_catalog_ordering_args', 'devvn_shortcode_woo_orderby_post_in', 10, 2 );
function devvn_shortcode_woo_orderby_post_in( $args, $orderby ) {
    switch($orderby) {
        case 'post__in':
            $args['orderby']  = 'post__in';
            break;
    }

    return $args;
}

add_filter('wpseo_canonical', 'devvn_wpseo_canonical');
function devvn_wpseo_canonical($canonical) {
    if (is_paged()) {
        if (is_home()) {
            return home_url();
        }

        if (is_archive()) {
            $url = get_category_link(get_queried_object_id());
            return $url;
        }
    }

    return $canonical;
}

add_filter('xmlrpc_enabled', '__return_false');

/*
* Author: Le Van Toan - https://levantoan.com
* Đoạn code thu gọn nội dung bao gồm cả nút xem thêm và thu gọn lại sau khi đã click vào xem thêm
*/
add_action('wp_footer','devvn_readmore_flatsome');
function devvn_readmore_flatsome(){
    if(!get_field('rut_gon_noidung','option')) return false;
    $options = get_theme_mods();
    $main_site_color = isset($options['main_site_color']) ? sanitize_hex_color($options['main_site_color']) : '#e03232';
    ?>
    <style>
        .woocommerce-Tabs-panel--description.entry-content .tinymce {
            overflow: hidden;
            position: relative;
            padding-bottom: 25px;
        }
        .single-product .tab-panels div#tab-description.panel:not(.active) {
            height: 0 !important;
        }
        .devvn_readmore_flatsome {
            text-align: center;
            cursor: pointer;
            position: absolute;
            z-index: 10;
            bottom: 0;
            width: 100%;
            background: #fff;
        }
        .devvn_readmore_flatsome:before {
            height: 55px;
            margin-top: -45px;
            content: "";
            background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
            background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 100%);
            background: linear-gradient(to bottom, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff00', endColorstr='#ffffff',GradientType=0 );
            display: block;
        }
        .devvn_readmore_flatsome a {
            color: <?php echo $main_site_color;?>;
            display: block;
        }
        .devvn_readmore_flatsome a:after {
            content: '';
            width: 0;
            right: 0;
            border-top: 6px solid <?php echo $main_site_color;?>;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            display: inline-block;
            vertical-align: middle;
            margin: -2px 0 0 5px;
        }
        .devvn_readmore_flatsome_less a:after {
            border-top: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-bottom: 6px solid <?php echo $main_site_color;?>;
        }
        .devvn_readmore_flatsome_less:before {
            display: none;
        }
    </style>
    <script>
        (function($){
            $(document).ready(function(){
                $(window).load(function(){
                    if($('.woocommerce-Tabs-panel--description.entry-content .tinymce').length > 0){
                        var wrap = $('.woocommerce-Tabs-panel--description.entry-content .tinymce');
                        var wrapTop = wrap.offset().top;
                        var current_height = wrap.height();
                        var your_height = 500;
                        if(current_height > your_height){
                            wrap.css('height', your_height+'px');
                            wrap.append(function(){
                                return '<div class="devvn_readmore_flatsome devvn_readmore_flatsome_more"><a title="Xem thêm" href="javascript:void(0);">Xem thêm</a></div>';
                            });
                            wrap.append(function(){
                                return '<div class="devvn_readmore_flatsome devvn_readmore_flatsome_less" style="display: none;"><a title="Xem thêm" href="javascript:void(0);">Thu gọn</a></div>';
                            });
                            $('body').on('click','.devvn_readmore_flatsome_more', function(){
                                wrap.removeAttr('style');
                                $('body .devvn_readmore_flatsome_more').hide();
                                $('body .devvn_readmore_flatsome_less').show();
                            });
                            $('body').on('click','.devvn_readmore_flatsome_less', function(){
                                wrap.css('height', your_height+'px');
                                $('body .devvn_readmore_flatsome_less').hide();
                                $('body .devvn_readmore_flatsome_more').show();
                                $('html, body').animate({scrollTop:wrapTop-44}, 500 );
                            });
                        }
                    }
                });
            })
        })(jQuery)
    </script>
    <?php
}

add_filter( 'menu_image_default_sizes', function($sizes){
    // remove the default 36x36 size
    unset($sizes['menu-24x24']);
    unset($sizes['menu-36x36']);
    unset($sizes['menu-48x48']);
    return $sizes;
});

  /*cai dat them nut san*/
function link_san_tmdt() {
    $link_lazada = get_field('link_lazada');
    $link_tiki = get_field('link_tiki');
    $link_shopee = get_field('link_shopee');
    $link_sendo = get_field('link_sendo');
    ob_start();
    echo '<div class="link-san-tmdt">';
    // code đưa link lazada
    if($link_lazada) echo '<div class="link-item"><a href="'.$link_lazada.'" class="link-lazada" target="_blank">Mua rẻ hơn trên Lazada</a></div>';	
    // code đưa link shopee
    if($link_shopee) echo '<div class="link-item"><a href="'.$link_shopee.'" class="link-shopee" target="_blank">Mua rẻ hơn trên Shopee</a></div>';
    // code đưa link sendo
    if($link_sendo) echo '<div class="link-item"><a href="'.$link_sendo.'" class="link-sendo" target="_blank">Mua trên Sendo</a></div>';	
    // code đưa link tiki
    if($link_tiki) echo '<div class="link-item"><a href="'.$link_tiki.'" class="link-tiki" target="_blank">Mua rẻ hơn trên Tiki</a></div>';
      echo '</div>';	
    /*sua giao dien css*/
    echo '
    <style>
  .link-san-tmdt{margin-bottom:15px}
  .link-san-tmdt:after{content:"";display:table;clear:both}
  .link-san-tmdt>div{float:left;width:50%;padding:2px}
  .link-san-tmdt a{display:block;width:100%;text-align:center;padding:10px;color:#fff;font-size:16px;}
  a.link-lazada{background:#f57224}
  a.link-tiki{background:#017fff}
  a.link-shopee{background:#d0011b}
  a.link-sendo{background:#bc000b}</style>
    ';
    $link = ob_get_contents();
    ob_end_clean();
    echo $link; 
}
add_action('woocommerce_product_meta_start','link_san_tmdt',5);
  /*cai dat them nut san*/
  /*chuyen giao dien bai bit cu*/
//add_filter('use_block_editor_for_post', '__return_false'); // (trình soạn thảo cổ điển do plugin Classic Editor đảm nhiệm)

/* ------------------------------------------------------------------
 * Tiên Lập: breadcrumb (Rank Math, dự phòng Yoast) và khung liên hệ kỹ thuật
 * ----------------------------------------------------------------*/
function devvn_breadcrumb() {
	if ( is_front_page() ) return;
	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		echo '<nav class="breadcrumb tl-breadcrumb" aria-label="Breadcrumb">';
		rank_math_the_breadcrumbs();
		echo '</nav>';
	} elseif ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav class="breadcrumb tl-breadcrumb" aria-label="Breadcrumb">', '</nav>' );
	}
}

function devvn_tl_contact_box() {
	$lien_he = home_url( '/lien-he/' );
	?>
	<div class="tl-cta-box">
		<div class="tl-cta-text">
			<p class="tl-cta-title"><?php _e( 'Cần kỹ sư kiểm tra thiết bị?', 'devvn' ); ?></p>
			<p><?php _e( 'Gửi tên máy, model và tình trạng lỗi – Tiên Lập báo giá sửa chữa, bảo trì hoặc thay thế trong ngày.', 'devvn' ); ?></p>
		</div>
		<div class="tl-cta-actions">
			<a class="tl-cta-btn tl-cta-call" href="tel:0903304691"><i class="fa fa-phone"></i> 0903 304 691</a>
			<a class="tl-cta-btn tl-cta-zalo" href="https://zalo.me/0964483381" target="_blank" rel="noopener">Chat Zalo</a>
			<a class="tl-cta-btn tl-cta-form" href="<?php echo esc_url( $lien_he ); ?>"><?php _e( 'Gửi yêu cầu', 'devvn' ); ?></a>
		</div>
	</div>
	<?php
}
