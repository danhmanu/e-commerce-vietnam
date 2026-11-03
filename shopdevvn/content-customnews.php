<?php
$page_for_posts = get_option( 'page_for_posts' );
?>
<div class="lasted_news_wrap">
<?php
$args = array(
    'post_type'         => 'post',
    'posts_per_page'    => 5
);
$news = new WP_Query($args);
if($news->have_posts()):
    $max_post_count = $news->post_count;
    ?>
    <div class="devvn_box_news_wrap">
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
endif;wp_reset_query();?>
</div>
<?php if(have_rows('content_news',$page_for_posts)):?>
    <?php while (have_rows('content_news',$page_for_posts)):the_row();?>
        <?php if(get_row_layout() == 'slider'):?>
            <?php include 'inc/layout/section-slider.php';?>
        <?php elseif (get_row_layout() == 'one_image'): ?>
            <?php include 'inc/layout/section-image.php';?>
        <?php elseif (get_row_layout() == 'html'): ?>
            <?php include 'inc/layout/section-html.php';?>
        <?php elseif (get_row_layout() == 'news_by_category'): ?>
            <?php include 'inc/layout/section-news_by_category.php';?>
        <?php endif;?>
    <?php endwhile;?>
<?php endif;?>