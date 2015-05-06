<?php

function tpa_widgets() {

    // Footer Widgets
    register_sidebar( array(
        'name' => __( 'Footer Slot #1' ),
        'id' => 'footer-slot-1',
        'description' => __( 'L2R: First position in the footer' ),
        'before_widget' => '<div class="col widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
    register_sidebar( array(
        'name' => __( 'Footer Slot #2' ),
        'id' => 'footer-slot-2',
        'description' => __( 'L2R: Second position in the footer' ),
        'before_widget' => '<div class="col widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
    register_sidebar( array(
        'name' => __( 'Footer Slot #3' ),
        'id' => 'footer-slot-3',
        'description' => __( 'L2R: Third position in the footer' ),
        'before_widget' => '<div class="col widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
    register_sidebar( array(
        'name' => __( 'Footer Slot #4' ),
        'id' => 'footer-slot-4',
        'description' => __( 'L2R: Fourth position in the footer' ),
        'before_widget' => '<div class="col endrow widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );

}

add_action( 'widgets_init', 'tpa_widgets' );

?>
