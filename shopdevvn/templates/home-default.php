<?php
/*
 * Template Name: Home - Left Sidebar - Default
 */
?>
<?php get_header();?>
	<div class="main_container col-md-9 col-sm-9 col-xs-12 col-sm-push-3">
        <?php get_template_part('templates/section','homebox');?>
	</div>
	<?php get_sidebar();?>
<?php get_footer();?>