<?php get_header();?>
	<div class="main_container col-md-9 col-sm-9 col-xs-12">
		<main id="main" class="site-main" role="main">
			<?php devvn_breadcrumb(); ?>
			<div class="page_content">
            <?php
            $page_for_posts = get_option( 'page_for_posts' );
            $view_list_category = get_field('view_list_category',$page_for_posts);
            if($view_list_category):
            ?>
                <?php get_template_part('content','customnews');?>
            <?php else:?>
                <?php if(have_posts()):?>
                    <section id="latest-posts" class="clearfix">
                        <?php while(have_posts()):the_post();?>
                            <?php get_template_part('content');?>
                        <?php endwhile;?>
                    </section>
                    <?php wp_corenavi_table();?>
                <?php else:?>
                    <?php get_template_part('content','none')?>
                <?php endif;?>
            <?php endif;?>
			</div>		
		</main>
	</div>
	<?php get_sidebar('blog');?>
	<?php do_action('after_archive_news');?>
<?php get_footer();?>