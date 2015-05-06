<?php

/*---------------------------------------------------------
	[ Set up Theme Options ]
----------------------------------------------------------*/
// Hides the 'New Layout' section
add_filter( 'ot_show_new_layout', '__return_false' );

/**
 * Required: set 'ot_theme_mode' filter to true.
 */
add_filter( 'ot_theme_mode', '__return_true' );

/**
 * Required: include OptionTree.
 */
include_once( 'option-tree/ot-loader.php' );


/*---------------------------------------------------------
    [ Set up Theme Defaults ]
----------------------------------------------------------*/
function tpa_setup_theme() {

    // Enable Thumbnail Support
    add_theme_support( 'post-thumbnails' );
    if ( function_exists( 'add_theme_support' ) ) {
        add_image_size( 'icon', 27, 99999, false );
    }

    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'widgets' );
    add_theme_support('post-formats', array('video', 'gallery'));

    // Add menu locations
    register_nav_menus( array(
        'main-navigation' => __( 'Main Navigation', 'tpa' ),
        'footer-left' => __( 'Footer Left', 'tpa' ),
        'footer-right' => __( 'Footer Right', 'tpa' ),
        'language-menu' => __( 'Language Menu', 'tpa' )
    ) );
}
add_action( 'after_setup_theme', 'tpa_setup_theme' );


/*---------------------------------------------------------
    [ Register and Load Front-end Scripts ]
----------------------------------------------------------*/
function tpa_register_scripts(){

    wp_register_script(
        'google-maps-api',
        'http://maps.google.com/maps/api/js?sensor=false',
        array('jquery'),
        '',
        true
    );
    wp_register_script(
        'gmap',
        get_template_directory_uri().'/js/gmap.min.js',
        array('jquery'),
        '',
        true
    );
    wp_register_script(
        'custom-scripts',
        get_template_directory_uri().'/js/scripts.js',
        array('jquery'),
        '',
        true
    );

    wp_enqueue_script('google-maps-api');
    wp_enqueue_script('gmap');
    wp_enqueue_script('custom-scripts');

}
add_action('wp_enqueue_scripts', 'tpa_register_scripts');


/*---------------------------------------------------------
    [ Load Admin Files ]
----------------------------------------------------------*/
// function tpa_admin_files() {
//    wp_enqueue_script('jquery');
// }
// add_action('admin_head', 'tpa_admin_files');

/*---------------------------------------------------------
    [ Register Custom Post Types ]
----------------------------------------------------------*/
// include_once('includes/custom-post-types.php');

/*---------------------------------------------------------
    [ Register Custom Meta Boxes ]
----------------------------------------------------------*/
// include_once('includes/custom-meta-boxes.php');

/*---------------------------------------------------------
    [ Breadcrumbs ]
----------------------------------------------------------*/
include_once('includes/breadcrumbs.php');

/*---------------------------------------------------------
    [ Helper Functions ]
----------------------------------------------------------*/
include_once('includes/helpers.php');

/*---------------------------------------------------------
    [ Custom Image Sizes ]
----------------------------------------------------------*/
include_once('includes/custom-image-sizes.php');

/*---------------------------------------------------------
    [ Fixes ]
----------------------------------------------------------*/
// include_once('includes/fixes/child-nav.php');

/*---------------------------------------------------------
    [ Tidy up Wordpress output ]
----------------------------------------------------------*/
include_once('includes/clean-up.php');

/*---------------------------------------------------------
    [ Widgets ]
----------------------------------------------------------*/
// include_once('includes/widgets/tpa-widgets.php');
// include_once('includes/widgets/widget-map.php');
// include_once('includes/widgets/widget-portfolio-items.php');
// include_once('includes/widgets/widget-posts.php');
// include_once('includes/widgets/widget-space.php');
// include_once('includes/widgets/widget-twitter.php');
