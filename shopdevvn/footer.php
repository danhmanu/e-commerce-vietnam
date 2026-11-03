  			</div>
  		</div>
  	</div>
  	<footer class="footer" role="contentinfo" itemscope="" itemtype="http://schema.org/WPFooter">
  		<div class="container">
  			<div class="row footer_wrap">
  				<div class="footer1 col-md-3 col-sm-3 col-xs-12">
  					<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-1')) : ?><?php endif; ?>
  				</div>
  				<div class="footer2 col-md-3 col-sm-3 col-xs-12">
  					<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-2')) : ?><?php endif; ?>
  				</div>
  				<div class="footer3 col-md-3 col-sm-3 col-xs-12">
  					<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-3')) : ?><?php endif; ?>
  				</div>
  				<div class="footer4 col-md-3 col-sm-3 col-xs-12">
  					<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-4')) : ?><?php endif; ?>
  				</div>				
  			</div>
  			<?php if(!get_field('an_tags_widget','option')):?>
            <div class="footer_tags"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-tags')) : ?><?php endif; ?></div>
            <?php endif;?>
  			<div class="footer_copyright">
  				<div class="footer_copyright_left"><?php echo get_field('copyright_text','option');?></div>
  			</div>
  		</div>
  	</footer>
  	<div class="box_overlay"></div>
    <div class="cart_mini_mobile"><?php do_action('woo_mini_cart');?></div>
  	<?php wp_footer(); ?>
  	
  	</body>
</html>