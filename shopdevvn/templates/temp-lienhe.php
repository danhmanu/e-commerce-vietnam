<?php 
/*
 * Template Name: Liên hệ
 */
?>
<?php get_header();?>
<?php while (have_posts()):the_post();?>
	<div class="main_container col-xs-12">
		<?php if ( function_exists('yoast_breadcrumb') ) {
			yoast_breadcrumb('<div class="breadcrumb">','</div>');
		} ?>
		<div class="page_content">
			<h1 class="title-page"><?php the_title();?></h1>			
			<div class="row row-padding-15">
				<div class="col-sm-6 col-xs-12">
					<div class="lienhe_content tinymce">
						<?php the_content();?>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12">
					<div class="lienhe_form"><?php echo do_shortcode('[contact-form-7 id="'.get_field('form_contact').'" title=""]')?></div>
				</div>				
			</div>
			<div class="maps_full">
				<?php 
				$location = get_field('maps');				
				if( !empty($location) ):
				?>
				<div class="acf-map">
					<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
				</div>
				<?php endif; ?>
			</div>
		</div>		
	</div>
<?php endwhile;?>
<?php get_footer();?>