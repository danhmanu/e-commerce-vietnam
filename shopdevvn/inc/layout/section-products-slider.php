<?php
$title = get_sub_field('title');
$select_type = get_sub_field('select_type');
$choose_products = get_sub_field('choose_products');
$product_category = get_sub_field('product_category');
$product_sub_category = get_sub_field('product_sub_category');
$link_to = get_sub_field('link_to');
$number_product = get_sub_field('number_product');
$number_column = get_sub_field('number_column');
if($number_column && is_array($number_column)){
    $number_column = isset($number_column[0]) ? $number_column[0] : '';
}
$ads_after_title = get_sub_field('ads_after_title');
$link_ads_after_title = get_sub_field('link_ads_after_title');
$ads_left_product = get_sub_field('ads_left_product');
$link_ads_left_product = get_sub_field('link_ads_left_product');
if(!$number_product) $number_product = 8;
if(!$number_column) $number_column = 4;
$orderby = get_sub_field('orderby');
$order = get_sub_field('order');
if($select_type):
?>
<div class="section_custom_view_products <?php if($ads_left_product || $product_sub_category):?>has_left_ads<?php endif;?>  section_product_slider" data-slidercol="<?php echo $number_column;?>">
    <?php if($title):?>
	<div class="section_cvp_title">
        <h3><span><?php echo $title;?></span></h3>
		<?php if($link_to):?><a href="<?php echo esc_url($link_to)?>" class="section_cvp_link" title="<?php _e('Xem thêm','devvn')?> <?php echo $title?>"><?php _e('Xem thêm','devvn')?></a><?php endif;?>
	</div>
    <?php endif;?>
    <?php if($ads_after_title):?>
        <div class="ads_after_title">
            <?php if($link_ads_after_title):?><a href="<?php echo esc_url($link_ads_after_title);?>" title=""><?php endif;?>
                <?php echo wp_get_attachment_image($ads_after_title,'full');?>
                <?php if($link_ads_after_title):?></a><?php endif;?>
        </div>
    <?php endif;?>
	<div class="section_cvp_content">
        <?php if($ads_left_product || $product_sub_category):?>
            <div class="ads_left_product">
                <?php if($ads_left_product):?>
                <?php if($link_ads_left_product):?><a href="<?php echo esc_url($link_ads_left_product);?>" title=""><?php endif;?>
                    <?php echo wp_get_attachment_image($ads_left_product,'full');?>
                <?php if($link_ads_left_product):?></a><?php endif;?>
                <?php endif;?>
                <?php if($product_sub_category):?>
                <?php
                $sub_terms = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false,
                    'parent' => $product_sub_category->term_id
                ));
                if($sub_terms && !is_wp_error($sub_terms)):
                    echo '<div class="product_sub_category_wrap"><div class="overflow_scroll"><ul class="product_sub_category">';
                    foreach($sub_terms as $term):
                        echo '<li><a href="'.get_term_link($term->term_id).'" title="'.$term->name.'"><i class="fa fa-angle-right"></i>'.$term->name.'</a></li>';
                    endforeach;
                    echo '</ul></div></div>';
                endif;
                ?>
                <?php endif;?>
            </div>
        <?php endif;?>
		<?php if($select_type == 'spkm'):?>
			<?php echo do_shortcode('[sale_products orderby="'.$orderby.'" order="'.$order.'" per_page="'.$number_product.'" columns="'.$number_column.'"]');?>
		<?php elseif($select_type == 'spnb'):?>
			<?php echo do_shortcode('[featured_products orderby="'.$orderby.'" order="'.$order.'" per_page="'.$number_product.'" columns="'.$number_column.'"]');?>
        <?php elseif($select_type == 'spmn'):?>
            <?php echo do_shortcode('[recent_products orderby="'.$orderby.'" order="'.$order.'" per_page="'.$number_product.'" columns="'.$number_column.'"]');?>
		<?php elseif($select_type == 'theoctgr'):?>
			<?php
			if($product_category && is_array($product_category)){
                $termSlug = array();
			    foreach ($product_category as $item){
                    $termSlug[] = $item->slug;
                }
                $termSlug = implode(',',$termSlug);
                echo do_shortcode('[product_category category="'.$termSlug.'" orderby="'.$orderby.'" order="'.$order.'" per_page="'.$number_product.'"  columns="'.$number_column.'"]');
            }?>
        <?php elseif($select_type == 'chonsp'):?>
            <?php
            if($choose_products && is_array($choose_products)){
                $choose_products = implode(',',$choose_products);
                echo do_shortcode('[products ids="'.$choose_products.'" orderby="'.$orderby.'" order="'.$order.'" per_page="'.$number_product.'"  columns="'.$number_column.'"]');
            }?>
		<?php endif;?>
	</div>
</div>
<?php
endif;