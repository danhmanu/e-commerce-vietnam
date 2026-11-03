<?php if(show_megamenu_sidebar()):?>
    <div class="widget danhmuc_widget">
        <div class="title-sidebar"><?php _e('Danh mục sản phẩm','devvn');?><i class="fa fa-bars"></i></div>
        <?php do_action('devvn_content_danhmuc');?>
    </div>
<?php endif;?>