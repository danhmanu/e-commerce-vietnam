<?php
// Register Custom Post Type
function videos_func() {

	$labels = array(
		'name'                  => _x( 'Videos', 'Post Type General Name', 'devvn' ),
		'singular_name'         => _x( 'Video', 'Post Type Singular Name', 'devvn' ),
		'menu_name'             => __( 'Videos', 'devvn' ),
		'name_admin_bar'        => __( 'Videos', 'devvn' ),
		'archives'              => __( 'Videos', 'devvn' ),
		'parent_item_colon'     => __( 'Parent Item:', 'devvn' ),
		'all_items'             => __( 'All Items', 'devvn' ),
		'add_new_item'          => __( 'Add New Item', 'devvn' ),
		'add_new'               => __( 'Add Video', 'devvn' ),
		'new_item'              => __( 'New Item', 'devvn' ),
		'edit_item'             => __( 'Edit Item', 'devvn' ),
		'update_item'           => __( 'Update Item', 'devvn' ),
		'view_item'             => __( 'View Item', 'devvn' ),
		'search_items'          => __( 'Search Item', 'devvn' ),
		'not_found'             => __( 'Not found', 'devvn' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'devvn' ),
		'featured_image'        => __( 'Featured Image', 'devvn' ),
		'set_featured_image'    => __( 'Set featured image', 'devvn' ),
		'remove_featured_image' => __( 'Remove featured image', 'devvn' ),
		'use_featured_image'    => __( 'Use as featured image', 'devvn' ),
		'insert_into_item'      => __( 'Insert into item', 'devvn' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'devvn' ),
		'items_list'            => __( 'Items list', 'devvn' ),
		'items_list_navigation' => __( 'Items list navigation', 'devvn' ),
		'filter_items_list'     => __( 'Filter items list', 'devvn' ),
	);
	$args = array(
		'label'                 => __( 'Video', 'devvn' ),
		'description'           => __( 'Post Type Description', 'devvn' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-video-alt3',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'videos', $args );

}
add_action( 'init', 'videos_func', 0 );

// Register Custom Taxonomy
function danhmuc_videos_func() {

	$labels = array(
		'name'                       => _x( 'Danh mục videos', 'Taxonomy General Name', 'devvn' ),
		'singular_name'              => _x( 'Danh mục videos', 'Taxonomy Singular Name', 'devvn' ),
		'menu_name'                  => __( 'Danh mục videos', 'devvn' ),
		'all_items'                  => __( 'All Items', 'devvn' ),
		'parent_item'                => __( 'Parent Item', 'devvn' ),
		'parent_item_colon'          => __( 'Parent Item:', 'devvn' ),
		'new_item_name'              => __( 'New Item Name', 'devvn' ),
		'add_new_item'               => __( 'Add New Item', 'devvn' ),
		'edit_item'                  => __( 'Edit Item', 'devvn' ),
		'update_item'                => __( 'Update Item', 'devvn' ),
		'view_item'                  => __( 'View Item', 'devvn' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'devvn' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'devvn' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'devvn' ),
		'popular_items'              => __( 'Popular Items', 'devvn' ),
		'search_items'               => __( 'Search Items', 'devvn' ),
		'not_found'                  => __( 'Not Found', 'devvn' ),
		'no_terms'                   => __( 'No items', 'devvn' ),
		'items_list'                 => __( 'Items list', 'devvn' ),
		'items_list_navigation'      => __( 'Items list navigation', 'devvn' ),
	);
	$rewrite = array(
		'slug'                       => 'danhmuc-videos',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'danhmuc_videos', array( 'videos' ), $args );

}
add_action( 'init', 'danhmuc_videos_func', 0 );

class WP_Widget_Recent_Videos_Custom_SVL extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries_svl', 'description' => __( "Your site&#8217;s most recent Videos.") );
		parent::__construct('recent-videos-svl', __('[DevVN] Recent Videos'), $widget_ops);
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
			if ( false === ( $recent_videos_svl = get_transient( $transientName ) ) ) {
				ob_start();		
				if ( ! isset( $args['widget_id'] ) ) {
					$args['widget_id'] = $this->id;
				}
		
				$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Videos' );	
				$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );	
				$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;			
				$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		
				$r = new WP_Query( apply_filters( 'widget_posts_args', array(
					'posts_per_page'      	=> $number,
					'no_found_rows'       	=> true,
					'post_status'         	=> 'publish',
					'ignore_sticky_posts' 	=> true,
					'post_type'				=>	'videos'
				) ) );
		
				if ($r->have_posts()) :
				?>
				<?php echo $args['before_widget']; ?>
				<?php if ( $title ) {
					echo $args['before_title'] . $title . $args['after_title'];
				} ?>
				<ul class="recent-video-ul">
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
				$recent_videos_svl = ob_get_clean();
				set_transient( $transientName, $recent_videos_svl );
			}
			echo $recent_videos_svl;
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
		$transientName = 'recent_videos_svl'.$args['widget_id'];
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
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display video date?' ); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" <?php checked( $show_thumb ); ?> id="<?php echo $this->get_field_id( 'show_thumb' ); ?>" name="<?php echo $this->get_field_name( 'show_thumb' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_thumb' ); ?>"><?php _e( 'Display video thumbnail?' ); ?></label></p>
<?php
	}
}
function wp_widgets_init_recent_videos_svl() {
	if ( !is_blog_installed() )
		return;
	
	register_widget('WP_Widget_Recent_Videos_Custom_SVL');
	
	do_action( 'widgets_init' );
}
add_action('init', 'wp_widgets_init_recent_videos_svl', 1);

add_filter('manage_videos_posts_columns', 'devvn_columns_head_only_videos', 10);
add_action('manage_videos_posts_custom_column', 'devvn_columns_content_only_videos', 10, 2);
function devvn_columns_head_only_videos($defaults) {
    $defaults['thumbnail'] = 'Thumbnail';
    return $defaults;
}
function devvn_columns_content_only_videos($column_name, $post_ID) {
    if ($column_name == 'thumbnail') {
        echo '<div style="max-width: 100px">';
		the_post_thumbnail('thumbnail',array(
			'style'	=>	'width: 100%; height: auto;'
		));
		echo '</div>';
    }
}