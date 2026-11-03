<div class="tl-none">
	<?php if ( is_search() ) : ?>
		<h1 class="title-page"><?php printf( __( 'Chưa có kết quả cho “%s”', 'devvn' ), get_search_query() ); ?></h1>
		<p><?php _e( 'Hãy thử từ khóa ngắn hơn – ví dụ tên hãng (Dräger, Erbe, GE) hoặc loại máy (máy thở, siêu âm, X-quang).', 'devvn' ); ?></p>
	<?php else : ?>
		<h1 class="title-page"><?php _e( 'Chưa có nội dung', 'devvn' ); ?></h1>
		<p><?php _e( 'Mục này đang được cập nhật. Bạn có thể tìm nội dung khác hoặc liên hệ để được tư vấn.', 'devvn' ); ?></p>
	<?php endif; ?>
	<div class="tl-none-search"><?php get_search_form(); ?></div>
	<?php if ( function_exists( 'devvn_tl_contact_box' ) ) devvn_tl_contact_box(); ?>
</div>
