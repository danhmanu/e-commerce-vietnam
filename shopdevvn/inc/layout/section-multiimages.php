<?php
$list_multi_img = get_sub_field('list_multi_img');
$count_img = count($list_multi_img);
if($count_img == 2){
    $colums = 6;
    $colums_xs = 6;
}elseif($count_img == 3){
    $colums = 4;
    $colums_xs = 4;
}else{
    $colums = 3;
    $colums_xs = 6;
}
if(have_rows('list_multi_img')):?>
<div class="section_multi_images">
    <div class="row row-padding-15">
        <?php while(have_rows('list_multi_img')):the_row();
        $image = get_sub_field('image');
        $link_to = get_sub_field('link_to');
        $target = get_sub_field('target');
        ?>
            <div class="col-sm-<?php echo $colums;?> col-xs-<?php echo $colums_xs;?>">
                <div class="multi_img_box">
                    <?php if($link_to):?><a href="<?php echo esc_url($link_to);?>" title="" <?php echo ($target)?'target="_blank"':'';?>><?php endif;?>
                    <?php if($image):?><?php echo wp_get_attachment_image($image,'full');?><?php endif;?>
                    <?php if($link_to):?></a><?php endif;?>
                </div>
            </div>
        <?php endwhile;?>
    </div>
</div>
<?php endif;?>