<?php get_header();?>
	<div class="main_container col-md-9 col-sm-9 col-xs-12">
		<main id="main" class="site-main" role="main">
			<?php devvn_breadcrumb(); ?>
			<div class="page_content">
			<?php if(have_posts()):?>
				<header>
					<h1 class="title-page"><?php the_title();?></h1>					
					<div class="post-info">
                        <span class="thetime updated"><i class="fa fa-clock-o"></i> <span><?php echo get_the_date()?></span></span>
                        <?php $cats = get_the_category(); if ( $cats ) : ?><span class="post_cat"><i class="fa fa-folder-open-o"></i> <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a></span><?php endif; ?>
                        <span class="post_count"><i class="fa fa-eye"></i> <span><?php echo do_shortcode('[post_view]')?></span></span>
                    </div>
				</header>
				<?php do_action('before_after_single');?>
				<div class="tinymce">
					<?php while(have_posts()):the_post();?>
						<?php the_content();?>
					<?php endwhile;?>
				</div>
				<?php do_action('devvn_after_single');?>
				<?php if ( function_exists('devvn_tl_contact_box') && strpos( (string) get_post_field( 'post_content', get_the_ID() ), 'tel:0903304691' ) === false ) devvn_tl_contact_box(); ?>
				<div class="post_navigation">
				<?php 
				the_post_navigation( array(
					'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Bài tiếp', 'devvn' ) . '</span> <br>'.
						'<span class="post-title">%title</span>',
					'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Bài trước', 'devvn' ) . '</span> <br>' .
						'<span class="post-title">%title</span>',
				) );
				?>
				</div>
				<?php devvn_related_post();?>
				<?php /* Khung bình luận Facebook đã bỏ: trang không nạp Facebook SDK nên khung luôn trống */ ?>
                <?php
                /*if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;*/
                ?>
			<?php else:?>
				<?php get_template_part('content','none')?>
			<?php endif;?>
			</div>		
		</main>
	</div>
	<?php get_sidebar('blog');?>
<?php get_footer();?>