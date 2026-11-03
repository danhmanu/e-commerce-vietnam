<?php
if (!defined('ABSPATH')) exit;

add_action('admin_menu', function () {
    add_submenu_page('edit.php?post_type=' . TLSP_PT, 'Chuyển dữ liệu từ WooCommerce', 'Chuyển dữ liệu', 'manage_options', 'tlsp-migrate', 'tlsp_migrate_page');
});

function tlsp_counts() {
    global $wpdb;
    return array(
        'wc_posts'  => (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='product' AND post_status NOT IN ('auto-draft')"),
        'wc_cats'   => (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE taxonomy='product_cat'"),
        'wc_tags'   => (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE taxonomy='product_tag'"),
        'tl_posts'  => (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type=%s", TLSP_PT)),
        'tl_cats'   => (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE taxonomy=%s", TLSP_TAX)),
        'variations'=> (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='product_variation'"),
    );
}

function tlsp_do_migrate() {
    global $wpdb;
    $ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type='product' AND post_status NOT IN ('auto-draft')");
    foreach ($ids as $id) {
        $gal = get_post_meta($id, '_product_image_gallery', true);
        if ($gal && !get_post_meta($id, '_tl_gallery', true)) update_post_meta($id, '_tl_gallery', $gal);
        $price = get_post_meta($id, '_regular_price', true);
        if ($price !== '' && $price !== false && !get_post_meta($id, '_tl_gia', true)) update_post_meta($id, '_tl_gia', $price);
        // Thuộc tính WooCommerce đơn giản -> trường mới (nếu khớp tên)
        $attrs = get_post_meta($id, '_product_attributes', true);
        if (is_array($attrs)) {
            foreach ($attrs as $a) {
                $n = isset($a['name']) ? mb_strtolower($a['name']) : ''; $v = isset($a['value']) ? trim(str_replace('|', ',', $a['value'])) : '';
                if (!$v || !empty($a['is_taxonomy'])) continue;
                if (strpos($n, 'hãng') !== false && !get_post_meta($id, '_tl_hang', true)) update_post_meta($id, '_tl_hang', $v);
                if (strpos($n, 'xuất xứ') !== false && !get_post_meta($id, '_tl_xuat_xu', true)) update_post_meta($id, '_tl_xuat_xu', $v);
                if (strpos($n, 'model') !== false && !get_post_meta($id, '_tl_model', true)) update_post_meta($id, '_tl_model', $v);
            }
        }
    }
    $n_posts = $ids ? $wpdb->query("UPDATE {$wpdb->posts} SET post_type='" . TLSP_PT . "' WHERE post_type='product' AND post_status NOT IN ('auto-draft')") : 0;
    $n_cats  = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->term_taxonomy} SET taxonomy=%s WHERE taxonomy='product_cat'", TLSP_TAX));
    $n_tags  = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->term_taxonomy} SET taxonomy=%s WHERE taxonomy='product_tag'", TLSP_TAG));
    // Mục menu trỏ tới danh mục / sản phẩm
    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value=%s WHERE meta_key='_menu_item_object' AND meta_value='product_cat'", TLSP_TAX));
    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value=%s WHERE meta_key='_menu_item_object' AND meta_value='product_tag'", TLSP_TAG));
    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value=%s WHERE meta_key='_menu_item_object' AND meta_value='product'", TLSP_PT));
    update_option('tlsp_migrated_ids', array_map('intval', $ids), false);
    update_option('tlsp_migrated_at', current_time('mysql'), false);
    clean_term_cache(array(), TLSP_TAX); clean_term_cache(array(), 'product_cat');
    wp_cache_flush();
    delete_option('tlsp_rewrite_state');
    flush_rewrite_rules(false);
    return compact('n_posts', 'n_cats', 'n_tags');
}

function tlsp_do_revert() {
    global $wpdb;
    $n_posts = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->posts} SET post_type='product' WHERE post_type=%s", TLSP_PT));
    $n_cats  = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->term_taxonomy} SET taxonomy='product_cat' WHERE taxonomy=%s", TLSP_TAX));
    $n_tags  = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->term_taxonomy} SET taxonomy='product_tag' WHERE taxonomy=%s", TLSP_TAG));
    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value='product_cat' WHERE meta_key='_menu_item_object' AND meta_value=%s", TLSP_TAX));
    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value='product_tag' WHERE meta_key='_menu_item_object' AND meta_value=%s", TLSP_TAG));
    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value='product' WHERE meta_key='_menu_item_object' AND meta_value=%s", TLSP_PT));
    wp_cache_flush(); delete_option('tlsp_rewrite_state'); flush_rewrite_rules(false);
    return compact('n_posts', 'n_cats', 'n_tags');
}

// Xử lý nút bấm ở admin_init rồi chuyển hướng (tải lại trang không gửi lại lệnh)
add_action('admin_init', function () {
    if (empty($_POST['tlsp_action']) || !current_user_can('manage_options')) return;
    check_admin_referer('tlsp_migrate');
    $a = sanitize_key(wp_unslash($_POST['tlsp_action']));
    $back = admin_url('edit.php?post_type=' . TLSP_PT . '&page=tlsp-migrate');
    if ($a === 'revert' && empty($_POST['tlsp_confirm'])) { set_transient('tlsp_msg_' . get_current_user_id(), 'Chưa chuyển ngược: hãy đánh dấu ô xác nhận.', 60); wp_safe_redirect($back); exit; }
    $r = $a === 'migrate' ? tlsp_do_migrate() : ($a === 'revert' ? tlsp_do_revert() : null);
    if ($r) {
        $log = (array) get_option('tlsp_log', array());
        $log[] = array(current_time('mysql'), $a, get_current_user_id());
        update_option('tlsp_log', array_slice($log, -20), false);
        set_transient('tlsp_msg_' . get_current_user_id(), sprintf('Đã chuyển %d sản phẩm, %d danh mục, %d từ khóa.', $r['n_posts'], $r['n_cats'], $r['n_tags']), 60);
    }
    wp_safe_redirect($back); exit;
});

function tlsp_migrate_page() {
    if (!current_user_can('manage_options')) return;
    $msg = get_transient('tlsp_msg_' . get_current_user_id());
    if ($msg) delete_transient('tlsp_msg_' . get_current_user_id());
    $c = tlsp_counts(); $wc = class_exists('WooCommerce');
    echo '<div class="wrap"><h1>Chuyển dữ liệu sản phẩm</h1>';
    if ($msg) echo '<div class="notice notice-success"><p>' . esc_html($msg) . '</p></div>';
    echo '<table class="widefat striped" style="max-width:640px"><tbody>';
    printf('<tr><td>WooCommerce</td><td><strong>%s</strong></td></tr>', $wc ? 'Đang bật' : 'Đã tắt');
    printf('<tr><td>Sản phẩm WooCommerce</td><td>%d</td></tr><tr><td>Danh mục WooCommerce</td><td>%d</td></tr><tr><td>Từ khóa WooCommerce</td><td>%d</td></tr>', $c['wc_posts'], $c['wc_cats'], $c['wc_tags']);
    printf('<tr><td>Sản phẩm (plugin mới)</td><td>%d</td></tr><tr><td>Danh mục (plugin mới)</td><td>%d</td></tr>', $c['tl_posts'], $c['tl_cats']);
    if ($c['variations']) printf('<tr><td>Biến thể sản phẩm (không chuyển)</td><td>%d</td></tr>', $c['variations']);
    echo '</tbody></table>';
    echo '<h2>1. Chuyển từ WooCommerce sang plugin mới</h2><p>Giữ nguyên ID, nội dung, ảnh, SEO (Rank Math), danh mục và đường dẫn. Ảnh phụ và giá được chép sang trường mới. Sau khi chuyển, hãy <strong>tắt WooCommerce</strong> để đường dẫn trở về /san-pham/ và /danh-muc/.</p>';
    echo '<form method="post">'; wp_nonce_field('tlsp_migrate');
    echo '<button class="button button-primary" name="tlsp_action" value="migrate"' . ($c['wc_posts'] || $c['wc_cats'] ? '' : ' disabled') . ' onclick="return confirm(\'Chuyển toàn bộ sản phẩm và danh mục WooCommerce sang plugin mới?\')">Chuyển dữ liệu</button></form>';
    echo '<h2>2. Chuyển ngược (khôi phục WooCommerce)</h2><p>Trả sản phẩm và danh mục về WooCommerce. Làm bước này <strong>trước khi</strong> bật lại WooCommerce.</p>';
    echo '<form method="post">'; wp_nonce_field('tlsp_migrate');
    echo '<button class="button" name="tlsp_action" value="revert"' . ($c['tl_posts'] || $c['tl_cats'] ? '' : ' disabled') . ' onclick="return confirm(\'Trả toàn bộ sản phẩm về WooCommerce?\')">Chuyển ngược</button> <label><input type="checkbox" name="tlsp_confirm" value="1"> Tôi chắc chắn muốn trả sản phẩm về WooCommerce</label></form>';
    $log = (array) get_option('tlsp_log', array());
    if ($log) { echo '<h2>Lịch sử chuyển dữ liệu</h2><ul>'; foreach (array_reverse(array_slice($log, -5)) as $l) { $u = get_userdata($l[2]); printf('<li>%s – %s – %s</li>', esc_html($l[0]), $l[1] === 'migrate' ? 'Chuyển sang plugin mới' : 'Chuyển ngược về WooCommerce', esc_html($u ? $u->display_name : '#' . $l[2])); } echo '</ul>'; }
    do_action('tlsp_migrate_page_after');
    echo '</div>';
}

/* ------------------------------------------------------------------
 * Chế độ thử: chỉ trình duyệt có cookie bí mật mới chạy như khi WooCommerce đã tắt
 * (dùng mu-plugin vì danh sách plugin được nạp trước mọi plugin thường)
 * ----------------------------------------------------------------*/
function tlsp_test_mu_path() { return WPMU_PLUGIN_DIR . '/tlsp-test-mode.php'; }
function tlsp_test_install() {
    $token = wp_generate_password(32, false, false);
    update_option('tlsp_test_token', $token, false);
    $code = "<?php\n// Tiên Lập – chế độ thử (tạo bởi plugin Tiên Lập – Sản phẩm). Xóa an toàn.\n"
        . "if (isset(\$_COOKIE['tlsp_nowc']) && hash_equals('" . $token . "', (string) \$_COOKIE['tlsp_nowc'])) {\n"
        . "  if (!defined('WP_SANDBOX_SCRAPING')) define('WP_SANDBOX_SCRAPING', true);\n"
        . "  @ini_set('display_errors', '1'); error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR);\n"
        . "  define('TLSP_TEST_MODE', true);\n"
        . "  add_filter('option_active_plugins', function (\$p) { return array_values(array_filter((array) \$p, function (\$x) { return !preg_match('#^(woocommerce/|devvn-quick-buy/|devvn-woo-address-selectbox/|devvn-local-store/|devvn-woocommerce-price-list-filter/|devvn-stickytooltip/)#', \$x); })); }, 1);\n"
        . "}\n";
    if (!is_dir(WPMU_PLUGIN_DIR)) wp_mkdir_p(WPMU_PLUGIN_DIR);
    $ok = (bool) @file_put_contents(tlsp_test_mu_path(), $code);
    if ($ok) setcookie('tlsp_nowc', $token, time() + 6 * HOUR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), false);
    return $ok;
}
function tlsp_test_remove() {
    if (file_exists(tlsp_test_mu_path())) @unlink(tlsp_test_mu_path());
    delete_option('tlsp_test_token');
    if (file_exists(WP_CONTENT_DIR . '/tlsp-test-error.log')) @unlink(WP_CONTENT_DIR . '/tlsp-test-error.log');
    setcookie('tlsp_nowc', '', time() - 3600, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), false);
}
add_action('admin_init', function () {
    if (empty($_POST['tlsp_test']) || !current_user_can('manage_options') || !check_admin_referer('tlsp_test')) return;
    if ($_POST['tlsp_test'] === 'on') { $ok = tlsp_test_install(); wp_safe_redirect(add_query_arg('tlsp_test_msg', $ok ? 'on' : 'fail', wp_get_referer())); exit; }
    if ($_POST['tlsp_test'] === 'off') { tlsp_test_remove(); wp_safe_redirect(add_query_arg('tlsp_test_msg', 'off', wp_get_referer())); exit; }
});
add_action('tlsp_migrate_page_after', function () {
    $on = file_exists(tlsp_test_mu_path());
    echo '<h2>3. Chế độ thử (chỉ trình duyệt của bạn)</h2><p>Trình duyệt của bạn sẽ chạy website như khi WooCommerce và 5 plugin DevVN đã tắt, lỗi PHP (nếu có) hiện trực tiếp trên trang. Khách truy cập không bị ảnh hưởng. Tự hết hạn sau 6 giờ.</p>';
    echo '<p>Trạng thái: <strong>' . ($on ? 'ĐANG BẬT' : 'Tắt') . '</strong>' . (defined('TLSP_TEST_MODE') ? ' – trình duyệt này đang ở chế độ thử' : '') . '</p>';
    echo '<form method="post">'; wp_nonce_field('tlsp_test');
    echo $on ? '<button class="button" name="tlsp_test" value="off">Tắt chế độ thử</button>' : '<button class="button" name="tlsp_test" value="on">Bật chế độ thử</button>';
    echo '</form>';
});
