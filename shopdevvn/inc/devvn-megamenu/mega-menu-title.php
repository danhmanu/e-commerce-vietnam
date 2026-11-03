<?php
$title_submega = get_sub_field('title_submega');
?>
<div class="devvn_mega_menu_title">
	<?php if($title_submega):?><h2><?php echo $title_submega;?></h2><?php endif;?>
	<?php if(have_rows('sub_menu_mega')):?>
	<ul>
		<?php while (have_rows('sub_menu_mega')):the_row();
		$label_submenu = get_sub_field('label');
		$link_to_submenu = get_sub_field('link_to');
		?>
		<li><a href="<?php echo esc_url($link_to_submenu)?>" title="<?php echo esc_attr($label_submenu)?>"><?php echo esc_attr($label_submenu)?></a></li>
		<?php endwhile;?>
	</ul>
	<?php endif;?>
</div>