<?php
if (!defined('ABSPATH')) exit;
get_header();
while (have_posts()) : the_post();
    $id = get_the_ID();
    $c = tlsp_contact();
    $gal = array_filter(array_map('absint', explode(',', (string) get_post_meta($id, '_tl_gallery', true))));
    $thumb = get_post_thumbnail_id($id);
    $imgs = array_values(array_unique(array_filter(array_merge(array($thumb), $gal))));
    $cats = get_the_terms($id, TLSP_TAX);
    $info = array();
    foreach (array('_tl_hang' => 'Hãng sản xuất', '_tl_model' => 'Model', '_tl_xuat_xu' => 'Xuất xứ') as $k => $l) { $v = tlsp_get($id, $k); if ($v) $info[$l] = esc_html($v); }
    $tt = tlsp_tinh_trang_label($id); if ($tt) $info['Tình trạng'] = esc_html($tt);
    if ($cats && !is_wp_error($cats)) $info['Danh mục'] = implode(', ', array_map(function ($t) { return '<a href="' . esc_url(get_term_link($t)) . '">' . esc_html($t->name) . '</a>'; }, $cats));
    $title = get_the_title();
?>
<div class="main_container col-md-12 col-sm-12 col-xs-12">
<main id="main" class="site-main tlsp-single" role="main">
    <nav class="tlsp-bc" aria-label="Breadcrumb">
        <?php if (function_exists('rank_math_the_breadcrumbs')) { ob_start(); rank_math_the_breadcrumbs(); $bc = ob_get_clean(); }
        if (!empty($bc)) { echo $bc; } else { ?>
            <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a> / <a href="<?php echo esc_url(get_post_type_archive_link(TLSP_PT)); ?>">Sản phẩm</a>
            <?php if ($cats && !is_wp_error($cats)) echo ' / <a href="' . esc_url(get_term_link($cats[0])) . '">' . esc_html($cats[0]->name) . '</a>'; ?> / <span><?php echo esc_html($title); ?></span>
        <?php } ?>
    </nav>

    <div class="tlsp-top">
        <div class="tlsp-gallery">
            <?php if ($imgs) : ?>
                <div class="tlsp-main-img"><?php echo wp_get_attachment_image($imgs[0], 'large', false, array('id' => 'tlsp-main', 'alt' => esc_attr($title), 'fetchpriority' => 'high')); ?></div>
                <?php if (count($imgs) > 1) : ?>
                <div class="tlsp-thumbs">
                    <?php foreach ($imgs as $i => $aid) : $full = wp_get_attachment_image_url($aid, 'large'); ?>
                        <button type="button" class="tlsp-thumb<?php echo $i ? '' : ' is-active'; ?>" data-full="<?php echo esc_url($full); ?>" aria-label="Xem ảnh <?php echo $i + 1; ?>"><?php echo wp_get_attachment_image($aid, 'thumbnail', false, array('alt' => esc_attr($title . ' – ảnh ' . ($i + 1)), 'loading' => 'lazy')); ?></button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            <?php else : ?><div class="tlsp-main-img tlsp-noimg"></div><?php endif; ?>
        </div>

        <div class="tlsp-summary">
            <h1 class="tlsp-title"><?php echo esc_html($title); ?></h1>
            <div class="tlsp-price"><span>Giá:</span> <?php echo wp_kses_post(tlsp_price_html($id)); ?></div>
            <?php if (has_excerpt()) : ?><div class="tlsp-excerpt"><?php the_excerpt(); ?></div><?php endif; ?>
            <?php if ($info) : ?>
            <table class="tlsp-info"><tbody>
                <?php foreach ($info as $l => $v) echo '<tr><th>' . esc_html($l) . '</th><td>' . $v . '</td></tr>'; ?>
            </tbody></table>
            <?php endif; ?>
            <div class="tlsp-cta">
                <a class="tlsp-btn tlsp-btn-call" href="tel:<?php echo esc_attr($c['phone']); ?>"><i class="fa fa-phone" aria-hidden="true"></i> Gọi <?php echo esc_html($c['phone_text']); ?></a>
                <a class="tlsp-btn tlsp-btn-zalo" href="<?php echo esc_url($c['zalo']); ?>" target="_blank" rel="noopener">Chat Zalo</a>
                <a class="tlsp-btn tlsp-btn-quote" href="<?php echo esc_url(add_query_arg('sp', rawurlencode($title), $c['quote'])); ?>">Yêu cầu báo giá</a>
            </div>
            <ul class="tlsp-trust">
                <li>Tư vấn cấu hình miễn phí</li><li>Giao hàng, lắp đặt toàn quốc</li><li><a href="<?php echo esc_url(home_url('/chinh-sach-bao-hanh/')); ?>">Chính sách bảo hành</a></li>
            </ul>
        </div>
    </div>

    <div class="tlsp-content">
        <h2 class="tlsp-section-title"><span>Mô tả sản phẩm</span></h2>
        <div class="tlsp-desc entry-content"><?php the_content(); ?></div>
    </div>

    <?php
    if ($cats && !is_wp_error($cats)) {
        $rel = get_posts(array('post_type' => TLSP_PT, 'posts_per_page' => 4, 'post__not_in' => array($id), 'no_found_rows' => true,
            'tax_query' => array(array('taxonomy' => TLSP_TAX, 'field' => 'term_id', 'terms' => wp_list_pluck($cats, 'term_id')))));
        if ($rel) {
            echo '<div class="tlsp-related"><h2 class="tlsp-section-title"><span>Sản phẩm liên quan</span></h2><div class="tlsp-grid tlsp-cols-4">';
            foreach ($rel as $p) echo tlsp_card($p->ID);
            echo '</div></div>';
        }
    }
    ?>
</main>
</div>
<?php
endwhile;
get_footer();
