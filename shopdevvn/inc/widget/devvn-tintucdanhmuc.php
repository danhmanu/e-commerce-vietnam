<?php
class DevVN_News_Category extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'news_category_widget',
			'description' => 'Hiển thị tin tức liên quan của taxonomy.',
		);
		parent::__construct( 'news_category_widget', '[DevVN] News Category', $widget_ops );
	}
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$widgetID = 'widget_'.$args['widget_id'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		do_action('devvn_news_category');
		echo $args['after_widget'];
	}
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}
function register_news_category_widget() {
    register_widget( 'DevVN_News_Category' );
}
add_action( 'widgets_init', 'register_news_category_widget' );