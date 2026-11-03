<?php
$image = get_sub_field('image');
$link_to = get_sub_field('link_to');
$link_target = get_sub_field('link_target');
if($image):
?>
<div class="section_oneimage">
	<?php if($link_to):?><a href="<?php echo esc_url($link_to);?>" title="" target="_<?php echo ($link_target)?$link_target:'self'?>"><?php endif;?>
		<?php echo wp_get_attachment_image($image,'full');?>
	<?php if($link_to):?></a><?php endif;?>
</div>
<?php
endif;