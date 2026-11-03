<?php get_header();?>
	<div class="main_container col-md-9 col-sm-9 col-xs-12">
		<main id="main" class="site-main" role="main">
			<?php devvn_breadcrumb(); ?>
			<div class="page_content">
			<?php if(have_posts()):?>
				<header>
					<?php
						the_archive_title( '<h1 class="title-page">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->
				<section id="latest-posts" class="clearfix">
					<?php while(have_posts()):the_post();?>
						<?php get_template_part('content');?>
					<?php endwhile;?>
				</section>
				<?php wp_corenavi_table();?>
			<?php else:?>
				<?php get_template_part('content','none')?>
			<?php endif;?>
			</div>		
		</main>
	</div>
	<?php get_sidebar('blog');?>
	<?php do_action('after_archive_news');?>
<?php get_footer();?>