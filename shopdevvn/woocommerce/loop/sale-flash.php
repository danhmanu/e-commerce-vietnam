<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $post, $product;
$kieu_chu_giam_gia = get_field('kieu_chu_giam_gia', $product->get_id());
$text_custom_label = get_field('text_custom_label', $product->get_id());
?>
<?php
$onsale_text = '';
if($kieu_chu_giam_gia == 'custom' && $text_custom_label):
    $onsale_text = $text_custom_label;
elseif ($kieu_chu_giam_gia == 'gia_soc'):
    $onsale_text = __('Giá sốc', 'devvn');
elseif ($kieu_chu_giam_gia == 'new'):
    $onsale_text = __('Mới','devvn');
elseif ($kieu_chu_giam_gia == 'km'):
    $onsale_text = __('Khuyến mại','devvn');
endif;
if($onsale_text):
    ?>

    <?php echo '<span class="onsale">' . $onsale_text . '</span>'; ?>

<?php elseif ( $product->is_on_sale() ) : ?>

    <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>

<?php endif;

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
