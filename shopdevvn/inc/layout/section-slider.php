<?php $count_row = get_row_index();?>
<?php if(have_rows('list_image')):?>
<?php if($count_row == 1 && is_page_template('templates/temp-homefullwidthhasmenu.php')):?>
    <div class="row row-padding-15">
    <div class="col-md-9 col-sm-12 col-xs-12 col-md-push-3">
    <?php endif;?>
        <div class="slider_section">
            <div class="home_sec_slider">
            <?php while (have_rows('list_image')):the_row();
            $image = get_sub_field('image');
            $image_url = wp_get_attachment_image_url($image, 'full');
            $link_to = get_sub_field('link_to');
            $link_target = get_sub_field('link_target');
            ?>
                <div class="item <?php echo (get_row_index() != 1)?'hiddenFirst':''?>">
                    <?php if($link_to):?><a href="<?php echo esc_url($link_to);?>" title="" target="_<?php echo ($link_target)?$link_target:'self'?>"><?php endif;?>
                        <?php echo wp_get_attachment_image($image, 'full');?>
                    <?php if($link_to):?></a><?php endif;?>
                </div>
            <?php endwhile;?>
            </div>
        </div>
    <?php if($count_row == 1 && is_page_template('templates/temp-homefullwidthhasmenu.php')):?>
    </div>
    <aside id="sidebar" class="sidebar col-md-3 col-sm-12 col-xs-12 col-md-pull-9 hidden-sm">
        <?php get_template_part('templates/section','megamenu');?>
    </aside>
    </div>
<?php endif;?>
<?php endif;?>