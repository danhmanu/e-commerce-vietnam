<?php
if (!defined('ABSPATH')) exit;
get_header();
$term = is_tax() ? get_queried_object() : null;
$is_s = is_search();
$title = $term ? $term->name : ($is_s ? 'Tìm sản phẩm: “' . get_search_query(false) . '”' : 'Sản phẩm');
?>
<div class="main_container col-md-12 col-sm-12 col-xs-12">
<main id="main" class="site-main tlsp-archive" role="main">
    <nav class="tlsp-bc" aria-label="Breadcrumb">
        <?php if (function_exists('rank_math_the_breadcrumbs')) { ob_start(); rank_math_the_breadcrumbs(); $bc = ob_get_clean(); }
        if (!empty($bc)) { echo $bc; } else { ?>
            <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a> / <?php if ($term) { ?><a href="<?php echo esc_url(get_post_type_archive_link(TLSP_PT)); ?>">Sản phẩm</a> / <?php } ?><span><?php echo esc_html($title); ?></span>
        <?php } ?>
    </nav>
    <h1 class="tlsp-archive-title"><?php echo esc_html($title); ?></h1>
    <?php
    if ($term && $term->description) echo '<div class="tlsp-term-desc">' . wp_kses_post(wpautop($term->description)) . '</div>';
    // Danh mục con có sản phẩm
    $children = get_terms(array('taxonomy' => TLSP_TAX, 'parent' => $term && $term->taxonomy === TLSP_TAX ? $term->term_id : 0, 'hide_empty' => true));
    if (!$is_s && $children && !is_wp_error($children)) {
        echo '<div class="tlsp-chips">';
        foreach ($children as $ch) echo '<a href="' . esc_url(get_term_link($ch)) . '">' . esc_html($ch->name) . ' <span>(' . (int) $ch->count . ')</span></a>';
        echo '</div>';
    }
    if (have_posts()) {
        echo '<div class="tlsp-grid tlsp-cols-4">';
        while (have_posts()) { the_post(); echo tlsp_card(get_the_ID()); }
        echo '</div>';
        the_posts_pagination(array('prev_text' => '‹', 'next_text' => '›', 'class' => 'tlsp-pagination'));
    } elseif (!$term || !$term->description) {
        $c = tlsp_contact();
        if ($is_s) echo '<div class="tlsp-empty"><p>Không tìm thấy sản phẩm phù hợp với “' . esc_html(get_search_query()) . '”. Hãy thử từ khóa ngắn hơn (ví dụ: tên hãng hoặc loại máy), hoặc liên hệ để được tư vấn.</p></div>';
        else echo '<div class="tlsp-empty"><p>Sản phẩm đang được cập nhật. Liên hệ để nhận danh sách thiết bị và báo giá mới nhất.</p><p><a class="tlsp-btn tlsp-btn-call" href="tel:' . esc_attr($c['phone']) . '">Gọi ' . esc_html($c['phone_text']) . '</a> <a class="tlsp-btn tlsp-btn-zalo" href="' . esc_url($c['zalo']) . '" target="_blank" rel="noopener">Chat Zalo</a></p></div>';
    }
    ?>
</main>
</div>
<?php get_footer();
