<?php
function devvn_customize_register($wp_customize) 
{
	//Footer
	$wp_customize->add_section("devvn_custom_color", array(
		'title' 		=> __("Tùy chỉnh màu", "devvn"),
		'priority' 		=> 130,
		'description' 	=> __( 'Tùy chỉnh màu cho website của bạn' ),
	));	

	//Enable change color
    $wp_customize->add_setting('enable_site_color', array(
        'default'   => '',
        'type'      => 'option',
        'transport' 	=> 'postMessage',
    ));
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'enable_site_color',
            array(
                'label'     => __('Áp dụng tùy chỉnh màu sắc', 'devvn'),
                'section'   => 'devvn_custom_color',
                'settings'  => 'enable_site_color',
                'type'      => 'checkbox',
            )
        )
    );

	//Footer background color
	$wp_customize->add_setting("main_site_color", array(
		'default' 		=> '#4f1c78',
		'transport' 	=> 'postMessage',
	));	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_site_color', array(
		'label'   		=> 'Tông màu chính',
		'section' 		=> 'devvn_custom_color',
        'settings'   	=> 'main_site_color',

    )));

    //second site color
    $wp_customize->add_setting("second_site_color", array(
        'default' 		=> '#c670ab',
        'transport' 	=> 'postMessage',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'second_site_color', array(
        'label'   		=> 'Màu khi hover vào 1 số button và link',
        'section' 		=> 'devvn_custom_color',
        'settings'   	=> 'second_site_color',

    )));

    //second site color
    $wp_customize->add_setting("top_header_color", array(
        'default' 		=> '#6d6d6d',
        'transport' 	=> 'postMessage',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_header_color', array(
        'label'   		=> 'Màu background của thanh trên cùng',
        'section' 		=> 'devvn_custom_color',
        'settings'   	=> 'top_header_color',

    )));

    //second site color
    $wp_customize->add_setting("top_header_t_color", array(
        'default' 		=> '#fff',
        'transport' 	=> 'postMessage',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_header_t_color', array(
        'label'   		=> 'Màu chữ của thanh trên cùng',
        'section' 		=> 'devvn_custom_color',
        'settings'   	=> 'top_header_t_color',

    )));

    //Footer background color
    $wp_customize->add_setting("footer_bg_color", array(
        'default' 		=> '#2D2D2D',
        'transport' 	=> 'postMessage',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_bg_color', array(
        'label'   		=> 'Màu nền của footer',
        'section' 		=> 'devvn_custom_color',
        'settings'   	=> 'footer_bg_color',

    )));

    //Footer text color
    $wp_customize->add_setting("footer_text_color", array(
        'default' 		=> '#acacac',
        'transport' 	=> 'postMessage',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_text_color', array(
        'label'   		=> 'Màu text của footer',
        'section' 		=> 'devvn_custom_color',
        'settings'   	=> 'footer_text_color',

    )));

    //Footer title sidebar color
    $wp_customize->add_setting("footer_title_sidebar_color", array(
        'default' 		=> '#fff',
        'transport' 	=> 'postMessage',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_title_sidebar_color', array(
        'label'   		=> 'Màu text tiêu đề của footer',
        'section' 		=> 'devvn_custom_color',
        'settings'   	=> 'footer_title_sidebar_color',

    )));
}

add_action("customize_register","devvn_customize_register");

function devvn_customizer_live_preview(){
	wp_enqueue_script("devvn-themecustomizer", get_template_directory_uri() . "/inc/theme_customize/theme-customizer.js", array("jquery", "customize-preview"), '',  true);
}
add_action("customize_preview_init", "devvn_customizer_live_preview");

function devvn_customize_control_js() {
	wp_enqueue_script( 'color-scheme-control', get_template_directory_uri() . '/inc/theme_customize/color-scheme-control.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '', true );
}
add_action( 'customize_controls_enqueue_scripts', 'devvn_customize_control_js' );


function devvn_get_color_scheme_css( $colors ) {
	$colors = wp_parse_args( $colors, array(
		'main_site_color'      	        =>	'#4f1c78',
		'second_site_color'		        =>	'#c670ab',
		'footer_bg_color'		        =>	'#2D2D2D',
		'footer_text_color'		        =>	'#acacac',
		'footer_title_sidebar_color'	=>	'#fff',
		'top_header_color'		        =>	'#6d6d6d',
		'top_header_t_color'		    =>	'#fff',
	) );

	return <<<CSS
	.bx-wrapper .bx-pager.bx-default-pager a,.wpcf7-form-control.wpcf7-submit,form#searchform #searchsubmit:hover,.search_header button#searchsubmit {border:1px solid {$colors['main_site_color']};}    
    .bx-wrapper .bx-pager.bx-default-pager a.active,.bx-wrapper .bx-pager.bx-default-pager a:focus,.bx-wrapper .bx-pager.bx-default-pager a:hover,.paginate_links .current,.paginate_links .page-numbers:hover,.wpcf7-form-control.wpcf7-submit,form#searchform #searchsubmit:hover,.search_header button#searchsubmit,.popup_cart .buttons .wc-forward,.menu_header_wrap,.section_cvp_title h3,.section_cvp_title h2,.section_cvp_title span,div.title-sidebar,div#product_thumbnails .owl-prev,div#product_thumbnails .owl-next,.woocommerce #respond input#submit,.woocommerce a.button,.woocommerce button.button,.woocommerce input.button,a.contact_to_buy_ajax:hover,.cart_after_content_title span,.thongbao_khuyenmai,.section_noi_dung_khuyen_mai .tinymce,ul.devvn_sub_menu li a:hover,.devvn_mega_menu_title h2:before,.devvn_mega_menu_image h2:before,a.read_more:hover,.nav-links a:hover,.top_header_middle,.top_header_middle:before {background: {$colors['main_site_color']};}
    .section_cvp_title h3:after{border-left: 30px solid {$colors['main_site_color']};}
    a:hover,a:focus,h1.title-page,.top_header a:hover,.woocommerce .popup_cart ul.cart_list li .amount,.woocommerce .popup_cart ul.product_list_widget li .amount,a.section_cvp_link,h1.page-title,.woocommerce .widget_layered_nav.block-sidebar ul li .count,.woocommerce .widget_layered_nav.block-sidebar ul li.chosen a,.woocommerce .widget_layered_nav_filters.block-sidebar ul li .count,.woocommerce .widget_layered_nav_filters.block-sidebar ul li.chosen a,.woocommerce .widget_layered_nav.block-sidebar ul li a:active,.woocommerce .widget_layered_nav.block-sidebar ul li a:focus,.woocommerce .widget_layered_nav.block-sidebar ul li a:hover,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:active,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:focus,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:hover,.woocommerce .widget_layered_nav.block-sidebar ul li .count,.woocommerce .widget_layered_nav.block-sidebar ul li.chosen a,.woocommerce .widget_layered_nav_filters.block-sidebar ul li .count,.woocommerce .widget_layered_nav_filters.block-sidebar ul li.chosen a,.product_meta .sku,.product_meta a,.related.products h2, .related.products > span,.up-sells.upsells.products h2,div#contact_to_buy h3,.cart_totals h2,.woocommerce-info:before,div#customer_details h3,h3#order_review_heading,ul.devvn_mega_menu_ul > li:hover > a,.woocommerce ul.cart_list li ins,.woocommerce ul.product_list_widget li ins,.woocommerce ul.cart_list li .amount,.woocommerce ul.product_list_widget li .amount,h2.post-title a:hover,.contact-detail ul li span,.woocommerce div.product p.price,.woocommerce div.product span.price,.woocommerce ul.products li.product .price,.woocommerce div.product p.price,.tinymce a {color:{$colors['main_site_color']};}    
    .section_cvp_title,h1.page-title {border-bottom:2px solid {$colors['main_site_color']};}
    .woocommerce .widget_layered_nav.block-sidebar ul li a:active::before,.woocommerce .widget_layered_nav.block-sidebar ul li a:focus::before,.woocommerce .widget_layered_nav.block-sidebar ul li a:hover::before,.woocommerce .widget_layered_nav.block-sidebar ul li.chosen a::before,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:active::before,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:focus::before,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:hover::before,.woocommerce .widget_layered_nav_filters.block-sidebar ul li.chosen a::before {color:{$colors['main_site_color']};border-color:{$colors['main_site_color']};}    
    .woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce .widget_price_filter .ui-slider .ui-slider-handle {background-color:{$colors['main_site_color']};}
    .product_meta {border-left:2px solid {$colors['main_site_color']};}
    div#reviews div#comments {border-bottom:1px solid {$colors['main_site_color']};}    
    .woocommerce #respond input#submit.alt,.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt {background-color:{$colors['main_site_color']};}    
    .cart_after_content {border:1px solid {$colors['main_site_color']};}
    .woocommerce-info {border-top-color:{$colors['main_site_color']}}    
    .devvn_sub_mega_content,ul.devvn_sub_menu {border-left:5px solid {$colors['main_site_color']};}
    .scrollTo_top{background:{$colors['main_site_color']} !important;}
    .scrollTo_top:hover {background: {$colors['second_site_color']} !important;}
    .search_header button#searchsubmit:hover{background: {$colors['second_site_color']};border: 1px solid {$colors['second_site_color']};}
    .woocommerce span.onsale,.woocommerce ul.products li.product .onsale {background-color: {$colors['second_site_color']};}
    footer.footer {background-color: {$colors['footer_bg_color']};}
    footer.footer a,footer.footer {color: {$colors['footer_text_color']};}
    div.title-sidebar-footer {color: {$colors['footer_title_sidebar_color']};}
    .top_header {background: {$colors['top_header_color']};}
    .top_header a,.top_header,.top_header a:hover {color: {$colors['top_header_t_color']};}
CSS;
}

function devvn_color_scheme_css_template() {
	$colors = array(
		'main_site_color'      => '{{ data.main_site_color }}',	
		'second_site_color'    => '{{ data.second_site_color }}',
		'footer_bg_color'    => '{{ data.footer_bg_color }}',
		'footer_text_color'    => '{{ data.footer_text_color }}',
		'footer_title_sidebar_color'    => '{{ data.footer_title_sidebar_color }}',
		'top_header_color'    => '{{ data.top_header_color }}',
		'top_header_t_color'    => '{{ data.top_header_t_color }}',
	);
	?>
	<script type="text/html" id="tmpl-devvn-color-scheme">
		<?php echo devvn_get_color_scheme_css( $colors ); ?>
	</script>
	<?php
}
add_action( 'customize_controls_print_footer_scripts', 'devvn_color_scheme_css_template' );

function get_custom_style() {
	$options = get_theme_mods();
    $main_site_color = isset($options['main_site_color']) ? sanitize_hex_color($options['main_site_color']) : '#4f1c78';
    $second_site_color = isset($options['second_site_color']) ? sanitize_hex_color($options['second_site_color']) : '#c670ab';
    $footer_bg_color = isset($options['footer_bg_color']) ? sanitize_hex_color($options['footer_bg_color']) : '#2D2D2D';
    $footer_text_color = isset($options['footer_text_color']) ? sanitize_hex_color($options['footer_text_color']) : '#acacac';
    $footer_title_sidebar_color = isset($options['footer_title_sidebar_color']) ? sanitize_hex_color($options['footer_title_sidebar_color']) : '#fff';
    $top_header_color = isset($options['top_header_color']) ? sanitize_hex_color($options['top_header_color']) : '#6d6d6d';
    $top_header_t_color = isset($options['top_header_t_color']) ? sanitize_hex_color($options['top_header_t_color']) : '#fff';
    $enable_site_color = get_option('enable_site_color') ? true : false;
    if($enable_site_color):
	?>
	<style type="text/css">
        .bx-wrapper .bx-pager.bx-default-pager a,.wpcf7-form-control.wpcf7-submit,form#searchform #searchsubmit:hover,.search_header button#searchsubmit {border:1px solid <?php echo $main_site_color;?>;}
        .bx-wrapper .bx-pager.bx-default-pager a.active,.bx-wrapper .bx-pager.bx-default-pager a:focus,.bx-wrapper .bx-pager.bx-default-pager a:hover,.paginate_links .current,.paginate_links .page-numbers:hover,.wpcf7-form-control.wpcf7-submit,form#searchform #searchsubmit:hover,.search_header button#searchsubmit,.popup_cart .buttons .wc-forward,.menu_header_wrap,.section_cvp_title h3,.section_cvp_title h2,.section_cvp_title span,div.title-sidebar,div#product_thumbnails .owl-prev,div#product_thumbnails .owl-next,.woocommerce #respond input#submit,.woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.cart_after_content_title span,.thongbao_khuyenmai,.section_noi_dung_khuyen_mai .tinymce,ul.devvn_sub_menu li a:hover,.devvn_mega_menu_title h2:before,.devvn_mega_menu_image h2:before,a.read_more:hover,.nav-links a:hover,.top_header_middle,.top_header_middle:before {background:<?php echo $main_site_color;?>;}
        .section_cvp_title h3:after{border-left: 30px solid <?php echo $main_site_color;?>;}
        a:hover,a:focus,h1.title-page,.top_header a:hover,.woocommerce .popup_cart ul.cart_list li .amount,.woocommerce .popup_cart ul.product_list_widget li .amount,a.section_cvp_link,h1.page-title,.woocommerce .widget_layered_nav.block-sidebar ul li .count,.woocommerce .widget_layered_nav.block-sidebar ul li.chosen a,.woocommerce .widget_layered_nav_filters.block-sidebar ul li .count,.woocommerce .widget_layered_nav_filters.block-sidebar ul li.chosen a,.woocommerce .widget_layered_nav.block-sidebar ul li a:active,.woocommerce .widget_layered_nav.block-sidebar ul li a:focus,.woocommerce .widget_layered_nav.block-sidebar ul li a:hover,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:active,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:focus,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:hover,.woocommerce .widget_layered_nav.block-sidebar ul li .count,.woocommerce .widget_layered_nav.block-sidebar ul li.chosen a,.woocommerce .widget_layered_nav_filters.block-sidebar ul li .count,.woocommerce .widget_layered_nav_filters.block-sidebar ul li.chosen a,.product_meta .sku,.product_meta a,.related.products h2, .related.products > span,.up-sells.upsells.products h2,div#contact_to_buy h3,.cart_totals h2,.woocommerce-info:before,div#customer_details h3,h3#order_review_heading,ul.devvn_mega_menu_ul > li:hover > a,.woocommerce ul.cart_list li ins,.woocommerce ul.product_list_widget li ins,.woocommerce ul.cart_list li .amount,.woocommerce ul.product_list_widget li .amount,h2.post-title a:hover,.contact-detail ul li span,.woocommerce div.product p.price,.woocommerce div.product span.price,.woocommerce ul.products li.product .price,.woocommerce div.product p.price,.tinymce a {color:<?php echo $main_site_color;?>}
        .section_cvp_title,h1.page-title {border-bottom:2px solid <?php echo $main_site_color;?>}
        .woocommerce .widget_layered_nav.block-sidebar ul li a:active::before,.woocommerce .widget_layered_nav.block-sidebar ul li a:focus::before,.woocommerce .widget_layered_nav.block-sidebar ul li a:hover::before,.woocommerce .widget_layered_nav.block-sidebar ul li.chosen a::before,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:active::before,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:focus::before,.woocommerce .widget_layered_nav_filters.block-sidebar ul li a:hover::before,.woocommerce .widget_layered_nav_filters.block-sidebar ul li.chosen a::before {color:<?php echo $main_site_color;?>;border-color:<?php echo $main_site_color;?>}
        .woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce .widget_price_filter .ui-slider .ui-slider-handle {background-color:<?php echo $main_site_color;?>}
        .product_meta {border-left:2px solid <?php echo $main_site_color;?>}
        div#reviews div#comments {border-bottom:1px solid <?php echo $main_site_color;?>}
        .woocommerce #respond input#submit.alt,.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt {background-color:<?php echo $main_site_color;?>}
        .cart_after_content {border:1px solid <?php echo $main_site_color;?>}
        .woocommerce-info {border-top-color:<?php echo $main_site_color;?>}
        .devvn_sub_mega_content,ul.devvn_sub_menu {border-left:5px solid <?php echo $main_site_color;?>}
        .scrollTo_top{background:<?php echo $main_site_color;?> !important;}
        /*second_site_color*/
        .scrollTo_top:hover{background: <?php echo $second_site_color?> !important;}
        .search_header button#searchsubmit:hover{background: <?php echo $second_site_color?>;border: 1px solid <?php echo $second_site_color?>;}
        .woocommerce span.onsale,.woocommerce ul.products li.product .onsale {background-color: <?php echo $second_site_color?>;}
        footer.footer {background-color: <?php echo $footer_bg_color;?>;}
        footer.footer a,footer.footer {color: <?php echo $footer_text_color;?>;}
        div.title-sidebar-footer {color: <?php echo $footer_title_sidebar_color;?>;}
        .top_header {background: <?php echo $top_header_color;?>;}
        .top_header a,.top_header,.top_header a:hover {color: <?php echo $top_header_t_color;?>;}
	</style>
    <?php
    endif;
}
add_action( 'wp_head', 'get_custom_style' );