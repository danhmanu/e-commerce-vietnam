<?php
class DevVN_Videos_Category extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'videos_category_widget',
			'description' => 'Hiển thị videos liên quan của taxonomy.',
		);
		parent::__construct( 'videos_category_widget', '[Tiên Lập] Video theo danh mục', $widget_ops );
	}
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$widgetID = 'widget_'.$args['widget_id'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		do_action('devvn_videos_category');
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
function register_videos_category_widget() {
    register_widget( 'DevVN_Videos_Category' );
}
add_action( 'widgets_init', 'register_videos_category_widget' );