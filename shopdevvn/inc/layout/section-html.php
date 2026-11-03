<?php 
$code_html = get_sub_field('code_html');
$title = get_sub_field('title');
if($code_html):
?>
<div class="section_html tinymce">
    <?php if($title):?>
    <div class="section_cvp_title section_cvp_title_html">
        <h3><span><?php echo $title;?></span></h3>
    </div>
    <?php endif;?>
	<?php echo $code_html;?>
</div>
<?php
endif;