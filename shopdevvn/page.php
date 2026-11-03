<?php get_header();?>
<?php while (have_posts()):the_post();?>
	<div class="main_container col-md-9 col-sm-9 col-xs-12 col-sm-push-3">
		<?php devvn_breadcrumb(); ?>
		<div class="page_content">
			<h1 class="title-page"><?php the_title();?></h1>
			<div class="tinymce">
				<?php the_content();?>
			</div>
		</div>		
	</div>
	<?php get_sidebar();?>
<?php endwhile;?>
<?php get_footer();?>