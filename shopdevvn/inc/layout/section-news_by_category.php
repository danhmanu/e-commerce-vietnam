<?php
$custom_title = get_sub_field('custom_title');
$cat_id = get_sub_field('choose_category');
$link_to = get_sub_field('link_to');
$type_news = get_sub_field('type_news');
$style_news = get_sub_field('style_news');
$args = array(
    'post_type' => 'post'
);
if($style_news == 'style_1'){
    $args['posts_per_page'] = 5;
}else{
    $args['posts_per_page'] = 4;
}
if($cat_id && is_array($cat_id) && ($type_news == 'by_category' || !$type_news)){
    $args['category__in'] = $cat_id;
}
$news = new WP_Query($args);
if($news->have_posts()):
$max_post_count = $news->post_count;
?>
<div class="devvn_box_news_wrap <?php echo ($style_news == 'style_1') ? 'lasted_news_wrap' : '';?>">
    <?php if($custom_title):?>
    <div class="section_cvp_title">
        <h3><span><?php echo $custom_title;?></span></h3>
        <?php if($link_to):?><a href="<?php echo esc_url($link_to)?>" class="section_cvp_link" title="<?php _e('Xem thêm','devvn');?> <?php echo $custom_title;?>"><?php _e('Xem thêm','devvn');?></a><?php endif;?>
    </div>
    <?php endif;?>
    <div class="devvn_box_news_row">
        <?php $stt = 1; while ($news->have_posts()):$news->the_post();?>
            <?php if($stt == 1):?><div class="devvn_box_news_col1"><?php endif;?>
            <?php if($stt == 2):?><div class="devvn_box_news_col2"><?php endif;?>
            <?php
            $thumb = 'thumbnail';
            if($stt == 1) $thumb = 'full';
            $thumb_img = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $thumb );
            $urlThumb = $thumb_img['0'];
            ?>
            <div class="devvn_box_news_box <?php if(has_post_thumbnail()):?>devvn_box_news_has_thumbnail<?php endif;?>">
                <?php if(has_post_thumbnail()):?>
                    <div class="devvn_box_news_thumb"><a href="<?php the_permalink();?>" title="<?php the_title()?>" <?php if($stt == 1):?>style="background: url(<?php echo $urlThumb;?>) no-repeat center center"<?php endif;?>><?php the_post_thumbnail($thumb);?></a></div>
                <?php endif;?>
                <div class="devvn_box_news_infor">
                    <a href="<?php the_permalink();?>" title="<?php the_title()?>" class="devvn_box_news_title"><?php the_title();?></a>
					<div class="devvn_box_news_date"><?php _e('Ngày cập nhật','devvn');?> <strong><?php echo get_the_date()?></strong></div>
                    <?php if($stt == 1):?><div class="devvn_box_news_excerpt"><?php the_excerpt();?></div><?php endif;?>
                </div>
            </div>
            <?php if($stt == 1 || $stt == $max_post_count):?></div><?php endif;?>
        <?php $stt++; endwhile;?>
    </div>
</div>
<?php
endif;wp_reset_query();
