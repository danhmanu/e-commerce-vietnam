<?php
/*Add Tips widget*/
class DevVN_Mega_Memu extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'danhmuc_widget',
			'description' => 'Hiển thị mega menu',
		);
		parent::__construct( 'mega_menu_widget', 'A Mega menu', $widget_ops );
	}
	public function widget( $args, $instance ) {
		if(!show_megamenu_sidebar()) return;
		echo $args['before_widget'];
		$widgetID = 'widget_'.$args['widget_id'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) .'<i class="fa fa-bars"></i>' . $args['after_title'];
		}
		do_action('devvn_content_danhmuc');
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
function register_devvn_megamenu_widget() {
    register_widget( 'DevVN_Mega_Memu' );
}
add_action( 'widgets_init', 'register_devvn_megamenu_widget' );

add_action('devvn_content_danhmuc', 'devvn_content_danhmuc_func');
function devvn_content_danhmuc_func(){
    if(has_nav_menu('menu_doc')){
        echo '<div class="devvn_mega_menu_wrap"><div class="devvn_mega_menu_box">';
        wp_nav_menu(array('theme_location'  => 'menu_doc','container'=> '', 'menu_class' => 'menu_doc_style'));
        echo '</div></div>';
    }else{
        $transientName = 'devvn_mega_menu';
        //delete_transient('devvn_mega_menu');
        if ( false === ( $mega_menu_results = get_transient( $transientName ) ) ) {
            ob_start();
            if(have_rows('list_danh_muc','option')):
            ?>
            <div class="devvn_mega_menu_wrap">
                <div class="devvn_mega_menu_box">
                    <ul class="devvn_mega_menu_ul">
                        <?php while (have_rows('list_danh_muc','option')):the_row();
                        $icon = get_sub_field('icon');
                        $label = get_sub_field('label');
                        $link_to = get_sub_field('link_to');
                        $have_mega = get_sub_field('have_mega');
                        $column_menu = get_sub_field('column_menu');
                        $background_mega_menu = get_sub_field('background_mega_menu');
                        ?>
                        <li class="<?php echo (!$have_mega)?'no-have-mega':'';?>">
                            <a href="<?php echo esc_url($link_to)?>" title="<?php echo esc_attr($label)?>"><i class="devvn_mega_menu_icon" style="background: url(<?php echo wp_get_attachment_image_url($icon)?>) no-repeat center center;"></i><?php echo esc_attr($label)?></a>
                            <?php if(!$have_mega && have_rows('sub_menu')):?>
                            <i class="click_open_sub_megamenu">+</i>
                            <ul class="devvn_sub_menu">
                                <?php while (have_rows('sub_menu')):the_row();
                                $label_submenu = get_sub_field('label_submenu');
                                $link_to_submenu = get_sub_field('link_to_submenu');
                                ?>
                                <li>
                                    <a href="<?php echo esc_url($link_to_submenu)?>" title="<?php echo esc_attr($label_submenu)?>"><?php echo esc_attr($label_submenu)?></a>
                                </li>
                                <?php endwhile;?>
                            </ul>
                            <?php elseif($have_mega && have_rows('sub_mega_menu')) :
                            switch ($column_menu){
                                case 2:
                                    $class = "devvn-mega-sub-column-2";
                                    break;
                                case 3:
                                    $class = "devvn-mega-sub-column-3";
                                    break;
                                default:
                                    $class = "devvn-mega-sub-column-1";
                                    break;
                            }
                            ?>
                            <i class="click_open_sub_megamenu">+</i>
                            <div class="devvn_sub_mega_content" style="background: #fff url(<?php echo $background_mega_menu;?>) no-repeat right bottom;">
                                <div class="devvn_sub_mega_content_box devvn-megamenu-<?php echo $column_menu;?>-column">
                                    <?php while (have_rows('sub_mega_menu')):the_row();?>
                                        <div class="<?php echo $class;?> devvn_sub_mega_content_column">
                                        <?php if(get_row_layout() == 'menu_title'):?>
                                            <?php include 'mega-menu-title.php';?>
                                        <?php elseif (get_row_layout() == 'image'):?>
                                            <?php include 'mega-image.php';?>
                                        <?php endif;?>
                                        </div>
                                    <?php endwhile;?>
                                </div>
                            </div>
                            <?php endif;?>
                        </li>
                        <?php endwhile;?>
                    </ul>
                </div>
            </div>
            <?php
            endif;
            $mega_menu_results = ob_get_clean();
            set_transient( $transientName, $mega_menu_results );
        }
        echo $mega_menu_results;
	}
}

//Delete transient when update theme option
function delete_devvn_mega_menu_transient( $post_id ) {    
    if( empty($_POST['acf']) ) {
        return;
    }
    delete_transient('devvn_mega_menu');
}
add_action('acf/save_post', 'delete_devvn_mega_menu_transient', 1);