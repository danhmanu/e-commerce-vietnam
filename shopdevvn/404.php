<?php get_header();?>
<div class="main_container col-xs-12">
	<main id="main" class="site-main tl-404" role="main">
		<div class="tl-404-box">
			<p class="tl-404-code">404</p>
			<h1 class="tl-404-title"><?php _e( 'Không tìm thấy trang bạn cần', 'devvn' ); ?></h1>
			<p class="tl-404-desc"><?php _e( 'Đường dẫn có thể đã thay đổi hoặc trang không còn tồn tại. Bạn thử tìm lại, hoặc liên hệ trực tiếp để được kỹ sư Tiên Lập hỗ trợ.', 'devvn' ); ?></p>
			<div class="tl-404-search"><?php get_search_form(); ?></div>
			<div class="tl-404-actions">
				<a class="tl-cta-btn tl-cta-form" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Về trang chủ', 'devvn' ); ?></a>
				<a class="tl-cta-btn tl-cta-call" href="tel:0903304691"><i class="fa fa-phone"></i> 0903 304 691</a>
				<a class="tl-cta-btn tl-cta-zalo" href="https://zalo.me/0964483381" target="_blank" rel="noopener">Chat Zalo</a>
			</div>
			<ul class="tl-404-links">
				<li><a href="<?php echo esc_url( home_url( '/danh-muc/thiet-bi-y-te/' ) ); ?>"><?php _e( 'Thiết bị y tế', 'devvn' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/danh-muc/thiet-bi-tham-my/' ) ); ?>"><?php _e( 'Thiết bị thẩm mỹ', 'devvn' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/category/dich-vu/' ) ); ?>"><?php _e( 'Dịch vụ kỹ thuật', 'devvn' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/tin-tuc/' ) ); ?>"><?php _e( 'Tin tức', 'devvn' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>"><?php _e( 'Liên hệ', 'devvn' ); ?></a></li>
			</ul>
		</div>
	</main>
</div>
<?php get_footer();?>
