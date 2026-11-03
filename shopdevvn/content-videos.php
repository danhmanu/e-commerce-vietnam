<article class="videos-box col-sm-4 col-xs-6 <?php if(has_post_thumbnail()):?>has_thumbnail<?php endif;?>">
	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">	
		<?php if(has_post_thumbnail()):
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'shop_catalog' );
		$url = $thumb['0'];
		?>
		<div class="video-thumb" style="background: url(<?php echo $url;?>) no-repeat center center;">			
			<?php the_post_thumbnail('shop_catalog');?>			
		</div>
		<h2 class="title post-title"><?php the_title(); ?></h2>						
	<?php endif;?>
	</a>
	<div class="post-info">
    	<span class="thetime updated"><i class="fa fa-clock-o"></i> <span><?php echo get_the_date()?></span></span>
    </div>	
</article>