<?php
/**
 * Plugin Name: Tiên Lập – Sản phẩm (thay WooCommerce)
 * Description: Quản lý sản phẩm dạng bài giới thiệu (không giỏ hàng, không đặt hàng). Giữ nguyên đường dẫn /san-pham/ và /danh-muc/, có nút Gọi – Zalo – Yêu cầu báo giá, công cụ chuyển dữ liệu từ WooCommerce (có thể chuyển ngược).
 * Version: 1.1.1
 * Author: Dịch Vụ Kỹ Thuật Tiên Lập
 * Text Domain: tienlap-san-pham
 * Requires PHP: 7.4
 */
if (!defined('ABSPATH')) exit;

define('TLSP_VER', '1.0.0');
define('TLSP_DIR', plugin_dir_path(__FILE__));
define('TLSP_URL', plugin_dir_url(__FILE__));
define('TLSP_PT', 'tl_san_pham');
define('TLSP_TAX', 'tl_danh_muc');
define('TLSP_TAG', 'tl_tu_khoa');

/* ------------------------------------------------------------------
 * 1. Kiểu nội dung + phân loại
 * ----------------------------------------------------------------*/
add_action('init', 'tlsp_register', 5);
function tlsp_register() {
    // Khi WooCommerce còn bật, nó đang giữ slug /san-pham/ và /danh-muc/ -> tạm dùng slug khác để không xung đột.
    $wc = class_exists('WooCommerce');
    $p_slug = $wc ? 'tl-san-pham' : 'san-pham';
    $c_slug = $wc ? 'tl-danh-muc' : 'danh-muc';

    register_post_type(TLSP_PT, array(
        'labels' => array(
            'name' => 'Sản phẩm', 'singular_name' => 'Sản phẩm', 'menu_name' => 'Sản phẩm',
            'add_new' => 'Thêm sản phẩm', 'add_new_item' => 'Thêm sản phẩm mới', 'edit_item' => 'Sửa sản phẩm',
            'new_item' => 'Sản phẩm mới', 'view_item' => 'Xem sản phẩm', 'search_items' => 'Tìm sản phẩm',
            'not_found' => 'Chưa có sản phẩm', 'all_items' => 'Tất cả sản phẩm', 'featured_image' => 'Ảnh sản phẩm',
            'set_featured_image' => 'Chọn ảnh sản phẩm', 'remove_featured_image' => 'Bỏ ảnh sản phẩm',
        ),
        'public' => true,
        'has_archive' => $p_slug,
        'rewrite' => array('slug' => $p_slug, 'with_front' => false),
        'menu_icon' => 'dashicons-products',
        'menu_position' => 26,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author'),
        'taxonomies' => array(TLSP_TAX, TLSP_TAG),
        'show_in_rest' => true,
    ));

    register_taxonomy(TLSP_TAX, TLSP_PT, array(
        'labels' => array(
            'name' => 'Danh mục sản phẩm', 'singular_name' => 'Danh mục', 'menu_name' => 'Danh mục',
            'add_new_item' => 'Thêm danh mục', 'edit_item' => 'Sửa danh mục', 'all_items' => 'Tất cả danh mục',
            'parent_item' => 'Danh mục cha', 'search_items' => 'Tìm danh mục',
        ),
        'hierarchical' => true,
        'public' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => $c_slug, 'with_front' => false, 'hierarchical' => false),
    ));

    register_taxonomy(TLSP_TAG, TLSP_PT, array(
        'labels' => array('name' => 'Từ khóa sản phẩm', 'singular_name' => 'Từ khóa', 'menu_name' => 'Từ khóa'),
        'hierarchical' => false,
        'public' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => $wc ? 'tl-tu-khoa' : 'tu-khoa-san-pham', 'with_front' => false),
    ));
}

register_activation_hook(__FILE__, function () { tlsp_register(); flush_rewrite_rules(); });
register_deactivation_hook(__FILE__, function () { flush_rewrite_rules(); });

// Đổi slug khi WooCommerce bật/tắt -> làm mới rewrite một lần.
add_action('init', function () {
    $state = class_exists('WooCommerce') ? 'wc' : 'nowc';
    if (get_option('tlsp_rewrite_state') !== $state) {
        flush_rewrite_rules(false);
        update_option('tlsp_rewrite_state', $state, true);
    }
}, 99);

/* ------------------------------------------------------------------
 * 2. Trường thông tin sản phẩm
 * ----------------------------------------------------------------*/
function tlsp_fields() {
    return array(
        '_tl_hang'      => 'Hãng sản xuất',
        '_tl_model'     => 'Model',
        '_tl_xuat_xu'   => 'Xuất xứ',
        '_tl_tinh_trang'=> 'Tình trạng',
        '_tl_do_moi'    => 'Độ mới',
        '_tl_gia'       => 'Giá tham khảo',
    );
}
function tlsp_tinh_trang_options() {
    return array('' => '— Không ghi —', 'moi' => 'Mới 100%', 'da-qua-su-dung' => 'Đã qua sử dụng', 'thanh-ly' => 'Thanh lý');
}
function tlsp_get($post_id, $key) { return trim((string) get_post_meta($post_id, $key, true)); }
function tlsp_tinh_trang_label($post_id) {
    $v = tlsp_get($post_id, '_tl_tinh_trang'); $o = tlsp_tinh_trang_options();
    if (!$v || !isset($o[$v])) return '';
    $l = $o[$v]; $dm = tlsp_get($post_id, '_tl_do_moi');
    return ($dm && $v !== 'moi') ? $l . ' (' . $dm . ')' : $l;
}
function tlsp_price_html($post_id) {
    $g = tlsp_get($post_id, '_tl_gia');
    if ($g === '') return 'Liên hệ';
    if (preg_match('/^[\d.,\s]+$/', $g)) { $n = (int) preg_replace('/\D/', '', $g); if ($n > 0) return number_format($n, 0, ',', '.') . ' ₫'; }
    return esc_html($g);
}

add_action('add_meta_boxes', function () {
    add_meta_box('tlsp_info', 'Thông tin sản phẩm', 'tlsp_box_info', TLSP_PT, 'normal', 'high');
    add_meta_box('tlsp_gallery', 'Ảnh phụ (thư viện ảnh)', 'tlsp_box_gallery', TLSP_PT, 'side', 'low');
});
function tlsp_box_info($post) {
    wp_nonce_field('tlsp_save', 'tlsp_nonce');
    $o = tlsp_tinh_trang_options();
    echo '<style>.tlsp-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px 18px}.tlsp-grid label{font-weight:600;display:block;margin-bottom:4px}.tlsp-grid input,.tlsp-grid select{width:100%}</style><div class="tlsp-grid">';
    foreach (array('_tl_hang' => 'VD: Dräger', '_tl_model' => 'VD: Savina 300', '_tl_xuat_xu' => 'VD: Đức') as $k => $ph) {
        $f = tlsp_fields();
        printf('<p><label for="%1$s">%2$s</label><input type="text" id="%1$s" name="%1$s" value="%3$s" placeholder="%4$s"></p>', esc_attr($k), esc_html($f[$k]), esc_attr(tlsp_get($post->ID, $k)), esc_attr($ph));
    }
    echo '<p><label for="_tl_tinh_trang">Tình trạng</label><select id="_tl_tinh_trang" name="_tl_tinh_trang">';
    $cur = tlsp_get($post->ID, '_tl_tinh_trang');
    foreach ($o as $k => $l) printf('<option value="%s"%s>%s</option>', esc_attr($k), selected($cur, $k, false), esc_html($l));
    echo '</select></p>';
    printf('<p><label for="_tl_do_moi">Độ mới</label><input type="text" id="_tl_do_moi" name="_tl_do_moi" value="%s" placeholder="VD: 90%%"></p>', esc_attr(tlsp_get($post->ID, '_tl_do_moi')));
    printf('<p><label for="_tl_gia">Giá tham khảo</label><input type="text" id="_tl_gia" name="_tl_gia" value="%s" placeholder="Để trống = hiện “Liên hệ”"><span class="description">Nhập số (VD 125000000) hoặc chữ (VD: Từ 120 triệu).</span></p>', esc_attr(tlsp_get($post->ID, '_tl_gia')));
    echo '</div><p class="description">Mô tả ngắn: dùng ô <strong>Tóm tắt (Excerpt)</strong> bên dưới trình soạn thảo. Bật trong “Tùy chọn hiển thị” nếu chưa thấy.</p>';
}
function tlsp_box_gallery($post) {
    $ids = array_filter(array_map('absint', explode(',', (string) get_post_meta($post->ID, '_tl_gallery', true))));
    echo '<input type="hidden" id="_tl_gallery" name="_tl_gallery" value="' . esc_attr(implode(',', $ids)) . '"><ul id="tlsp-gal" style="display:flex;flex-wrap:wrap;gap:6px;margin:0 0 8px">';
    foreach ($ids as $id) echo '<li data-id="' . $id . '" style="margin:0;cursor:move">' . wp_get_attachment_image($id, array(70, 70)) . '</li>';
    echo '</ul><button type="button" class="button" id="tlsp-gal-add">Chọn ảnh</button> <button type="button" class="button-link-delete" id="tlsp-gal-clear">Xóa hết</button>';
    ?>
    <script>
    jQuery(function($){
        var frame;
        $('#tlsp-gal-add').on('click',function(e){e.preventDefault();
            if(frame){frame.open();return;}
            frame=wp.media({title:'Chọn ảnh phụ',button:{text:'Dùng các ảnh này'},multiple:true,library:{type:'image'}});
            frame.on('select',function(){var ids=$('#_tl_gallery').val()?$('#_tl_gallery').val().split(','):[];
                frame.state().get('selection').each(function(a){var id=String(a.id);if(ids.indexOf(id)<0){ids.push(id);var u=(a.attributes.sizes&&a.attributes.sizes.thumbnail)?a.attributes.sizes.thumbnail.url:a.attributes.url;$('#tlsp-gal').append('<li data-id="'+id+'" style="margin:0;cursor:move"><img src="'+u+'" width="70" height="70" style="object-fit:cover"></li>');}});
                $('#_tl_gallery').val(ids.join(','));});
            frame.open();});
        $('#tlsp-gal-clear').on('click',function(){$('#tlsp-gal').empty();$('#_tl_gallery').val('');});
        if($.fn.sortable){$('#tlsp-gal').sortable({update:function(){$('#_tl_gallery').val($('#tlsp-gal li').map(function(){return $(this).data('id');}).get().join(','));}});}
    });
    </script>
    <?php
}
add_action('admin_enqueue_scripts', function ($hook) {
    global $post_type;
    if (in_array($hook, array('post.php', 'post-new.php'), true) && $post_type === TLSP_PT) { wp_enqueue_media(); wp_enqueue_script('jquery-ui-sortable'); }
});
add_action('save_post_' . TLSP_PT, function ($post_id) {
    if (!isset($_POST['tlsp_nonce']) || !wp_verify_nonce($_POST['tlsp_nonce'], 'tlsp_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    foreach (array_keys(tlsp_fields()) as $k) {
        if (isset($_POST[$k])) update_post_meta($post_id, $k, sanitize_text_field(wp_unslash($_POST[$k])));
    }
    if (isset($_POST['_tl_gallery'])) {
        $ids = array_filter(array_map('absint', explode(',', wp_unslash($_POST['_tl_gallery']))));
        update_post_meta($post_id, '_tl_gallery', implode(',', $ids));
    }
});

// Cột "Tình trạng / Giá" ở danh sách sản phẩm
add_filter('manage_' . TLSP_PT . '_posts_columns', function ($c) {
    $n = array();
    foreach ($c as $k => $v) { if ($k === 'title') $n['tlsp_thumb'] = 'Ảnh'; $n[$k] = $v; if ($k === 'title') { $n['tlsp_tt'] = 'Tình trạng'; $n['tlsp_gia'] = 'Giá'; } }
    return $n;
});
add_action('manage_' . TLSP_PT . '_posts_custom_column', function ($col, $id) {
    if ($col === 'tlsp_thumb') echo get_the_post_thumbnail($id, array(50, 50));
    if ($col === 'tlsp_tt') echo esc_html(tlsp_tinh_trang_label($id));
    if ($col === 'tlsp_gia') echo wp_kses_post(tlsp_price_html($id));
}, 10, 2);

/* ------------------------------------------------------------------
 * 3. Giao diện ngoài trang
 * ----------------------------------------------------------------*/
add_filter('template_include', function ($t) {
    if (is_singular(TLSP_PT)) return TLSP_DIR . 'templates/single.php';
    if (is_post_type_archive(TLSP_PT) || is_tax(array(TLSP_TAX, TLSP_TAG)) || (is_search() && tlsp_is_product_search())) return TLSP_DIR . 'templates/archive.php';
    return $t;
}, 99);

add_action('wp_enqueue_scripts', function () {
    wp_register_style('tlsp', TLSP_URL . 'assets/tlsp.css', array(), TLSP_VER);
    if (is_singular(TLSP_PT) || is_post_type_archive(TLSP_PT) || is_tax(array(TLSP_TAX, TLSP_TAG)) || (is_search() && tlsp_is_product_search())) wp_enqueue_style('tlsp');
    if (is_singular(TLSP_PT)) wp_enqueue_script('tlsp', TLSP_URL . 'assets/tlsp.js', array(), TLSP_VER, true);
    // Trang liên hệ: tự điền tên sản phẩm khi bấm "Yêu cầu báo giá"
    if (!empty($_GET['sp'])) {
        wp_register_script('tlsp-prefill', '', array(), TLSP_VER, true);
        wp_enqueue_script('tlsp-prefill');
        wp_add_inline_script('tlsp-prefill', 'document.addEventListener("DOMContentLoaded",function(){var v=' . wp_json_encode(sanitize_text_field(wp_unslash($_GET['sp']))) . ';var f=document.querySelector("input[name=your-device]");if(f&&!f.value){f.value=v;}var m=document.querySelector("textarea[name=your-message]");if(m&&!m.value){m.value="Tôi cần báo giá: "+v;}var s=document.querySelector("select[name=your-service]");if(s){for(var i=0;i<s.options.length;i++){if(/Mua thiết bị/.test(s.options[i].text)){s.selectedIndex=i;break;}}}var w=document.querySelector(".wpcf7");if(w&&w.scrollIntoView){setTimeout(function(){w.scrollIntoView({behavior:"smooth",block:"center"});},300);}});');
    }
});

function tlsp_contact() {
    return apply_filters('tlsp_contact', array(
        'phone' => '0903304691', 'phone_text' => '090 330 4691',
        'zalo' => 'https://zalo.me/0964483381', 'quote' => home_url('/lien-he/'),
    ));
}
function tlsp_card($post_id) {
    $tt = tlsp_tinh_trang_label($post_id);
    $img = has_post_thumbnail($post_id) ? get_the_post_thumbnail($post_id, 'medium', array('loading' => 'lazy', 'alt' => esc_attr(get_the_title($post_id)))) : '<span class="tlsp-noimg"></span>';
    $h = '<article class="tlsp-card"><a class="tlsp-card-img" href="' . esc_url(get_permalink($post_id)) . '">' . $img . ($tt ? '<span class="tlsp-badge">' . esc_html($tt) . '</span>' : '') . '</a>';
    $h .= '<div class="tlsp-card-body"><h3 class="tlsp-card-title"><a href="' . esc_url(get_permalink($post_id)) . '">' . esc_html(get_the_title($post_id)) . '</a></h3>';
    $hang = tlsp_get($post_id, '_tl_hang');
    if ($hang) $h .= '<div class="tlsp-card-meta">' . esc_html($hang) . '</div>';
    $h .= '<div class="tlsp-card-price">' . wp_kses_post(tlsp_price_html($post_id)) . '</div></div></article>';
    return $h;
}

// Shortcode: [tl_san_pham danh_muc="thiet-bi-y-te" so_luong="8" cot="4"]
add_shortcode('tl_san_pham', function ($a) { return tlsp_render_grid(shortcode_atts(array('danh_muc' => '', 'so_luong' => 8, 'cot' => 4, 'ids' => ''), $a, 'tl_san_pham')); });
function tlsp_render_grid($a) {
    $a = wp_parse_args($a, array('danh_muc' => '', 'so_luong' => 8, 'cot' => 4, 'ids' => ''));
    $q = array('post_type' => TLSP_PT, 'posts_per_page' => (int) $a['so_luong'], 'no_found_rows' => true);
    if ($a['danh_muc']) $q['tax_query'] = array(array('taxonomy' => TLSP_TAX, 'field' => 'slug', 'terms' => array_map('trim', explode(',', $a['danh_muc']))));
    if ($a['ids']) { $q['post__in'] = array_map('absint', explode(',', $a['ids'])); $q['orderby'] = 'post__in'; }
    $posts = get_posts($q);
    if (!$posts) return '';
    wp_enqueue_style('tlsp');
    $out = '<div class="tlsp-grid tlsp-cols-' . max(1, min(6, (int) $a['cot'])) . '">';
    foreach ($posts as $p) $out .= tlsp_card($p->ID);
    return $out . '</div>';
}

/* ------------------------------------------------------------------
 * 4. Tương thích theme DevVN khi WooCommerce đã tắt
 * ----------------------------------------------------------------*/
add_action('plugins_loaded', function () {
    if (class_exists('WooCommerce') || (is_admin() && !wp_doing_ajax())) return;
    if (!function_exists('get_product_search_form')) {
        function get_product_search_form($echo = true) {
            $f = '<form role="search" method="get" class="woocommerce-product-search" action="' . esc_url(home_url('/')) . '">'
               . '<input type="search" id="woocommerce-product-search-field" class="search-field" placeholder="Tìm kiếm sản phẩm" value="' . esc_attr(get_search_query()) . '" name="s" />'
               . '<input type="hidden" name="post_type" value="product" />'
               . '<button type="submit" class="btn btn-default" id="searchsubmit">Tìm kiếm</button></form>';
            if ($echo) { echo $f; return null; } return $f;
        }
    }
    if (!function_exists('woocommerce_mini_cart')) { function woocommerce_mini_cart($args = array()) {} }
    if (!function_exists('is_shop')) { function is_shop() { return false; } }
    if (!function_exists('is_product')) { function is_product() { return is_singular(TLSP_PT); } }
    if (!function_exists('is_product_category')) { function is_product_category($t = '') { return is_tax(TLSP_TAX, $t); } }
    if (!function_exists('is_product_tag')) { function is_product_tag($t = '') { return is_tax(TLSP_TAG, $t); } }
    if (!function_exists('is_product_taxonomy')) { function is_product_taxonomy() { return is_tax(array(TLSP_TAX, TLSP_TAG)); } }
    if (!function_exists('wc_get_featured_product_ids')) { function wc_get_featured_product_ids() { return array(); } }
    if (!function_exists('is_cart')) { function is_cart() { return false; } }
    if (!function_exists('is_checkout')) { function is_checkout() { return false; } }
    if (!function_exists('is_account_page')) { function is_account_page() { return false; } }
    if (!function_exists('is_woocommerce')) { function is_woocommerce() { return false; } }
    if (!function_exists('wc_get_cart_url')) { function wc_get_cart_url() { return home_url('/'); } }
    if (!function_exists('wc_get_template')) { function wc_get_template($n = '', $a = array(), $p = '', $d = '') {} }
    // Shortcode sản phẩm của WooCommerce mà theme/trang chủ đang dùng -> hiển thị bằng plugin mới
    $map = function ($a, $c, $tag) {
        $a = (array) $a;
        $args = array('so_luong' => isset($a['per_page']) ? $a['per_page'] : (isset($a['limit']) ? $a['limit'] : 8), 'cot' => isset($a['columns']) ? $a['columns'] : 4);
        if ($tag === 'product_category' && !empty($a['category'])) $args['danh_muc'] = $a['category'];
        if ($tag === 'products' && !empty($a['ids'])) $args['ids'] = $a['ids'];
        if ($tag === 'products' && !empty($a['category'])) $args['danh_muc'] = $a['category'];
        return tlsp_render_grid($args);
    };
    foreach (array('product_category', 'products', 'recent_products', 'featured_products', 'sale_products', 'best_selling_products', 'top_rated_products') as $tag) {
        if (!shortcode_exists($tag)) add_shortcode($tag, $map);
    }
}, 1);

// Trang cửa hàng / giỏ hàng / thanh toán / tài khoản cũ của WooCommerce -> chuyển về danh sách sản phẩm
add_action('template_redirect', function () {
    if (class_exists('WooCommerce') || !is_404()) return;
    $path = trim((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    if (in_array($path, array('cua-hang', 'gio-hang', 'thanh-toan', 'tai-khoan', 'shop', 'cart', 'checkout', 'my-account'), true)) { wp_safe_redirect(get_post_type_archive_link(TLSP_PT), 301); exit; }
});

// Theme DevVN dùng TGMPA "force_activation" để tự bật lại WooCommerce trong wp-admin -> bỏ yêu cầu này
add_action('tgmpa_register', function () {
    if (empty($GLOBALS['tgmpa']) || !is_object($GLOBALS['tgmpa'])) return;
    if (method_exists($GLOBALS['tgmpa'], 'deregister')) $GLOBALS['tgmpa']->deregister('woocommerce');
    elseif (isset($GLOBALS['tgmpa']->plugins['woocommerce'])) unset($GLOBALS['tgmpa']->plugins['woocommerce']);
}, 99);

// Theme gắn các hook giỏ hàng vào header/footer -> gỡ khi không còn WooCommerce
add_action('after_setup_theme', function () {
    if (class_exists('WooCommerce')) return;
    remove_action('woo_mini_cart', 'woo_mini_cart_int');
    remove_action('devvn_mini_cart', 'mini_cart_int');
    remove_action('wp_footer', 'add_floating_right', 51);
    remove_action('wp_footer', 'woo_add_popup_and_style', PHP_INT_MAX);
    // Sắp xếp "sản phẩm nổi bật" của theme dùng hàm WooCommerce
    remove_action('pre_get_posts', 'devvn_category_orderby_featured');
    remove_filter('posts_orderby', 'devvn_order_by_change', 99);
}, 99);
add_filter('acf/load_value/name=hidden_shopping', function ($v) { return class_exists('WooCommerce') ? $v : 1; });
add_filter('acf/load_value/name=show_minicart_right', function ($v) { return class_exists('WooCommerce') ? $v : 0; });

// Trang danh sách: 12 sản phẩm / trang, mới nhất trước
add_action('pre_get_posts', function ($q) {
    if (is_admin() || !$q->is_main_query()) return;
    if ($q->is_post_type_archive(TLSP_PT) || $q->is_tax(array(TLSP_TAX, TLSP_TAG))) $q->set('posts_per_page', 12);
});

// Tìm kiếm: ô tìm sản phẩm (post_type=product cũ hoặc tl_san_pham) -> chỉ sản phẩm;
// ô tìm chung -> bài viết + sản phẩm. Chạy sau hook của theme (theme ép post_type=post).
function tlsp_is_product_search() {
    $pt = isset($_GET['post_type']) ? sanitize_key(wp_unslash($_GET['post_type'])) : '';
    return in_array($pt, array('product', TLSP_PT), true);
}
add_action('pre_get_posts', function ($q) {
    if (is_admin() || !$q->is_main_query() || !$q->is_search()) return;
    if (tlsp_is_product_search()) { $q->set('post_type', TLSP_PT); $q->set('posts_per_page', 12); }
    else $q->set('post_type', array('post', TLSP_PT));
}, 999);

/* ------------------------------------------------------------------
 * 5. Rank Math: breadcrumb + sitemap
 * ----------------------------------------------------------------*/
add_filter('rank_math/sitemap/exclude_post_type', function ($ex, $type) { return $type === TLSP_PT ? false : $ex; }, 10, 2);
add_filter('rank_math/sitemap/exclude_taxonomy', function ($ex, $tax) { return $tax === TLSP_TAX ? false : $ex; }, 10, 2);

/* ------------------------------------------------------------------
 * 6. Công cụ chuyển dữ liệu WooCommerce <-> plugin
 * ----------------------------------------------------------------*/
require_once TLSP_DIR . 'migrate.php';
