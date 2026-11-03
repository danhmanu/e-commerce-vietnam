<?php
/*Add Tips widget*/
class NewsNoibat_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'newsnoibat_widget',
			'description' => 'Chọn tin tức nổi bật để hiển thị.',
		);
		parent::__construct( 'newsnoibat_widget', '[Tiên Lập] Tin tức nổi bật', $widget_ops );
	}
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$widgetID = 'widget_'.$args['widget_id'];
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		$tintuc_lienquan = new WP_Query(array(
			'post_type'			=>	'post',
			'posts_per_page'	=>	$number,
			'meta_query' => array(
				array(
					'key'     => 'tinnoibat',
					'value'   => '1',
					'compare' => '=',
				),
			),
		));	  	
		if($tintuc_lienquan->have_posts()):
		?>	
		<div class="tintuc_lienquan">			
			<ul class="news_list">
				<?php while ($tintuc_lienquan->have_posts()):$tintuc_lienquan->the_post();?>
				<li class="<?php echo (has_post_thumbnail())?'has-thumbnail':''?>">
				<a href="<?php the_permalink()?>" title="<?php the_title();?>">
				<?php echo get_the_post_thumbnail(get_the_ID(),'thumbnail'); ?>
				<h3><?php the_title();?></h3>
				<?php ?>
				</a>	
				</li>
				<?php endwhile;?>
			</ul>
		</div>
		<?php	
		endif;wp_reset_query();
		echo $args['after_widget'];
	}
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
        <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

        <?php
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['number'] = (int) $new_instance['number'];
		return $instance;
	}
}
function register_newsnoibat_widget() {
    register_widget( 'NewsNoibat_Widget' );
}
add_action( 'widgets_init', 'register_newsnoibat_widget' );