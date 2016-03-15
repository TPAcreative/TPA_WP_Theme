<?php
/*---------------------------------------------------------
    [ Register Custom Post Type ]
----------------------------------------------------------*/
function tpa_create_post_type() {
    register_post_type( 'people',
        array(
            'labels' => array(
                'name' => __( 'People' ),
                'singular_name' => __( 'Person' ),
                'add_new' => __( 'Add Person' ),
                'add_new_item' => __( 'Add Person' ),
                'edit_item' => __( 'Edit Person' ),
            ),
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
            'public' => true,
            'rewrite' => array('slug' => 'our-people'),
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'tpa_create_post_type' );

?>
