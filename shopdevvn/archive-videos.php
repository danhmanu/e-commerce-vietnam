<?php get_header();?>
	<div class="main_container col-md-9 col-sm-9 col-xs-12">
		<main id="main" class="site-main" role="main">
			<?php if ( function_exists('yoast_breadcrumb') ) {
				yoast_breadcrumb('<div class="breadcrumb">','</div>');
			} ?>
			<div class="page_content">
			<?php if(have_posts()):?>
				<header>
					<h1 class="title-page"><?php _e('Videos','devvn');?></h1>
				</header><!-- .page-header -->
				<section id="latest-posts" class="row row-padding-15 clearfix">
					<?php while(have_posts()):the_post();?>
						<?php get_template_part('content','videos');?>
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
<?php get_footer();?>