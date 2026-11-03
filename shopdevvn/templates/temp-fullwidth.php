<?php 
/*
 * Template Name: Full width
 */
?>
<?php get_header();?>
<?php while (have_posts()):the_post();?>
	<div class="main_container col-xs-12">
		<?php devvn_breadcrumb(); ?>
		<div class="page_content">
			<h1 class="title-page"><?php the_title();?></h1>
			<div class="tinymce">
				<?php the_content();?>
			</div>
		</div>		
	</div>
<?php endwhile;?>
<?php get_footer();?>