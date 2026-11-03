<aside id="sidebar" class="sidebar col-md-3 col-sm-3 col-xs-12 col-sm-pull-9" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">
    <?php get_template_part('templates/section','megamenu');?>
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('main-sidebar')) : ?><?php endif; ?>
</aside>