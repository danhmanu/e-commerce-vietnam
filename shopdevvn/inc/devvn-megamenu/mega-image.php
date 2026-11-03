<?php
$title_submega = get_sub_field('title_submega');
$image = get_sub_field('image');
$link_to = get_sub_field('link_to');
?>
<?php if($image):?>
<div class="devvn_mega_menu_image">
	<?php if($title_submega):?><h2><?php echo esc_attr($title_submega)?></h2><?php endif;?>
	<?php if($link_to):?><a href="<?php echo esc_url($link_to);?>" title=""><?php endif;?>
	<?php echo wp_get_attachment_image($image,'full');?>
	<?php if($link_to):?></a><?php endif;?>
</div>
<?php endif;?>