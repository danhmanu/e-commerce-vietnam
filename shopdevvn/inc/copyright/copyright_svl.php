<?php
function annointed_admin_bar_remove() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'annointed_admin_bar_remove', 0);

function mw_login_styles() {
	$logo = get_field('logo','option');
  	$logo = ($logo) ? $logo : TEMP_URL.'/images/logo.png';
	echo '<style type="text/css">.login h1 a { background: url('.$logo.') no-repeat center top;width: inherit;height: 50px;background-size: auto 100%;-moz-background-size: auto 100%;-webkit-background-size: auto 100%;}</style>';
}
add_action('login_head', 'mw_login_styles');

// Change Login URL
function mw_login_url() {
	return esc_url(home_url());
}
add_filter( 'login_headerurl', 'mw_login_url' );

// Change Login Title
function mw_login_title() {
	return get_bloginfo('description');
}
add_filter( 'login_headertext', 'mw_login_title' );

// Admin footer modification
function remove_footer_admin () 
{
    echo 'Quản trị website <a href="' . esc_url( home_url('/') ) . '" target="_blank">Dịch Vụ Kỹ Thuật Tiên Lập</a> – Hotline kỹ thuật 0903 304 691';
}
add_filter('admin_footer_text', 'remove_footer_admin');

function remove_dashboard_meta() {
        //remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        //remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
        //remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
        //remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        //remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        //remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
        //remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        //remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
}
add_action( 'admin_init', 'remove_dashboard_meta' );

add_action('update_right_now_text', 'devvn_update_right_now_text');
function devvn_update_right_now_text(){
	return __('Website Dịch Vụ Kỹ Thuật Tiên Lập','devvn');
}

// (Đã bỏ widget RSS của tác giả theme gốc khỏi Bảng tin)

function adminScriptsAndCSS_svl() {
   wp_enqueue_style('themesvl-admin', get_template_directory_uri().'/inc/copyright/admin_svl.css', array(), '1.0'); 
}
add_action('admin_enqueue_scripts', 'adminScriptsAndCSS_svl');