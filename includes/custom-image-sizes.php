<?php

function tpa_add_image_sizes() {
    add_image_size( 'news_featured_image', 610, 99999, false );
    add_image_size( 'icon', 27, 99999, false );
}
add_action( 'init', 'tpa_add_image_sizes' );

function tpa_show_image_sizes($sizes) {
    $sizes['news_featured_image'] = __( 'News Featured Image', 'pippin' );

    return $sizes;
}
add_filter('image_size_names_choose', 'tpa_show_image_sizes');

?>
