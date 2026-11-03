<?php if(have_rows('list_section')):?>
    <?php while (have_rows('list_section')):the_row();?>
        <?php if(get_row_layout() == 'slider'):?>
            <?php include get_template_directory().'/inc/layout/section-slider.php';?>
        <?php elseif (get_row_layout() == 'one_image'): ?>
            <?php include get_template_directory().'/inc/layout/section-image.php';?>
        <?php elseif (get_row_layout() == 'html'): ?>
            <?php include get_template_directory().'/inc/layout/section-html.php';?>
        <?php elseif (get_row_layout() == 'custom_view_products'): ?>
            <?php include get_template_directory().'/inc/layout/section-customviewproducts.php';?>
        <?php elseif (get_row_layout() == 'products_slider_category'): ?>
            <?php include get_template_directory().'/inc/layout/section-products-slider.php';?>
        <?php elseif (get_row_layout() == 'news_by_category'): ?>
            <?php include get_template_directory().'/inc/layout/section-news_by_category.php';?>
        <?php elseif (get_row_layout() == 'multi_image'): ?>
            <?php include get_template_directory().'/inc/layout/section-multiimages.php';?>
        <?php endif;?>
    <?php endwhile;?>
<?php endif;?>