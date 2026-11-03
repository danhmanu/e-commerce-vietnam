<?php
/**
 * Recent_Posts widget class
 *
 * Author Lê Văn Toản - https://levantoan.com
 */
class WP_Widget_Recent_Posts_Custom_SVL extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries_svl', 'description' => __( "Your site&#8217;s most recent Posts Custom view.") );
		parent::__construct('recent-posts_svl', __('[DevVN] Recent Posts Custom view'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries_svl';

		add_action( 'save_post', array($this, 'flush_widget_transient') );
		add_action( 'deleted_post', array($this, 'flush_widget_transient') );
		add_action( 'switch_theme', array($this, 'flush_widget_transient') );
	}

	public function widget($args, $instance) {						
		$show_page = ( ! empty( $instance['show_page'] ) ) ? $instance['show_page'] : '';		
		$show_thumb = ( ! empty( $instance['show_thumb'] ) ) ? $instance['show_thumb'] : '';		
		$show_all = isset( $instance['show_all'] ) ? $instance['show_all'] : false;
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}
						
		if(is_page( $show_page) || $show_all == true){
			$transientName = 'devvn_cache_svl_'.$args['widget_id'];
			if ( false === ( $recent_posts_svl = get_transient( $transientName ) ) ) {
				ob_start();		
				if ( ! isset( $args['widget_id'] ) ) {
					$args['widget_id'] = $this->id;
				}
		
				$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );	
				$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );	
				$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;			
				$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		
				$r = new WP_Query( apply_filters( 'widget_posts_args', array(
					'posts_per_page'      => $number,
					'no_found_rows'       => true,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true
				) ) );
		
				if ($r->have_posts()) :
				?>
				<?php echo $args['before_widget']; ?>
				<?php if ( $title ) {
					echo $args['before_title'] . $title . $args['after_title'];
				} ?>
				<ul>
				<?php while ( $r->have_posts() ) : $r->the_post(); ?>
					<li class="<?=(has_post_thumbnail() && $show_thumb)?'rcp_svl_has_thumb':''?>">
						<?php if(has_post_thumbnail() && $show_thumb):?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title()?>" class="link_recent_thumb">
								<?php the_post_thumbnail('thumbnail');?>
							</a>
						<?php endif;?>
						<div class="rcp_svl_content">
							<a href="<?php the_permalink();?>" title="<?php the_title()?>">
								<?php the_title();?>
							</a>
							<?php if ( $show_date ) : ?>
								<span class="date"><?php echo get_the_date('d/m/Y'); ?></span>
							<?php endif; ?>
						</div>
					</li>
				<?php endwhile; ?>
				</ul>
				<?php echo $args['after_widget']; ?>
				<?php wp_reset_postdata();	
				endif;
				$recent_posts_svl = ob_get_clean();
				set_transient( $transientName, $recent_posts_svl );
			}
			echo $recent_posts_svl;
		};//Ket thuc tuy chon hien thi
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		
		$instance['show_page'] = $new_instance['show_page'];		
		
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_thumb'] = isset( $new_instance['show_thumb'] ) ? (bool) $new_instance['show_thumb'] : false;
		$instance['show_all'] = isset( $new_instance['show_all'] ) ? (bool) $new_instance['show_all'] : false;
		$this->flush_widget_transient();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries_svl']) )
			delete_option('widget_recent_entries_svl');

		return $instance;
	}

	public function flush_widget_transient() {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}
		$transientName = 'recent_posts_svl'.$args['widget_id'];
		delete_transient($transientName);
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$show_thumb = isset( $instance['show_thumb'] ) ? (bool) $instance['show_thumb'] : false;
		$show_all = isset( $instance['show_all'] ) ? (bool) $instance['show_all'] : true;
		
		$show_page    = isset( $instance['show_page'] ) ? $instance['show_page'] : array();
		
		$pages = get_pages();
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
		
		<p>
			<label for="<?php echo $this->get_field_id('show_page'); ?>"><?php _e('ID Page to view:'); ?></label>
			
			<select style="width: 100%;min-height: 150px;" multiple="multiple" id="<?php echo $this->get_field_id('show_page'); ?>" name="<?php echo $this->get_field_name('show_page'); ?>[]">
				
				<option value="0"><?php _e( '&mdash; Select &mdash;' ) ?></option>
				 <?php 
				  foreach ( $pages as $page ) {
				  	printf('<option value="%s" %s>%s</option>',
				  		$page->ID,
				  		( in_array( $page->ID, $show_page) ) ? 'selected="selected"' : '',
				  		$page->post_title
				  	);
				  }
				 ?>
			</select>
		</p>		
		
		<p><input class="checkbox" type="checkbox" <?php checked( $show_all ); ?> id="<?php echo $this->get_field_id( 'show_all' ); ?>" name="<?php echo $this->get_field_name( 'show_all' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_all' ); ?>"><?php _e( 'Display all page?' ); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" <?php checked( $show_thumb ); ?> id="<?php echo $this->get_field_id( 'show_thumb' ); ?>" name="<?php echo $this->get_field_name( 'show_thumb' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_thumb' ); ?>"><?php _e( 'Display post thumbnail?' ); ?></label></p>
<?php
	}
}
function wp_widgets_init_recent_post_svl() {
	if ( !is_blog_installed() )
		return;
	
	register_widget('WP_Widget_Recent_Posts_Custom_SVL');
	
	do_action( 'widgets_init' );
}
add_action('init', 'wp_widgets_init_recent_post_svl', 1);