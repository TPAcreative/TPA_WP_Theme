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
        'easing',
        get_template_directory_uri().'/js/jquery.easing.min.js',
        array('jquery'),
        '',
        true
    );
    wp_register_script(
        'fitvid',
        get_template_directory_uri().'/js/jquery.fitvids.min.js',
        array('jquery'),
        '',
        true
    );
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
    wp_enqueue_script('jquery');
    wp_enqueue_script('easing');
    wp_enqueue_script('google-maps-api');
    wp_enqueue_script('gmap');
    wp_enqueue_script('fitvid');
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
// function tpa_add_image_sizes() {
//     add_image_size( 'news_featured_image', 610, 99999, false );
//     add_image_size( 'icon', 27, 99999, false );
// }
// add_action( 'init', 'tpa_add_image_sizes' );

// function tpa_show_image_sizes($sizes) {
//     $sizes['news_featured_image'] = __( 'News Featured Image', 'pippin' );

//     return $sizes;
// }
// add_filter('image_size_names_choose', 'tpa_show_image_sizes');

include_once('includes/custom-image-sizes.php');

/*---------------------------------------------------------
    [ Clean up Menus ]
----------------------------------------------------------*/
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
  return is_array($var) ? array_intersect($var, array('current-menu-item')) : '';
}

/*---------------------------------------------------------
    [ Bump Header ]
----------------------------------------------------------*/
// add_action('get_header', 'my_filter_head');

//   function my_filter_head() {
//     remove_action('wp_head', '_admin_bar_bump_cb');
//   }


/*---------------------------------------------------------
    [ Clean Header ]
----------------------------------------------------------*/
remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version


/*---------------------------------------------------------
    [ Widgets ]
----------------------------------------------------------*/
// include_once('includes/widgets/tpa-widgets.php');
// include_once('includes/widgets/widget-map.php');
// include_once('includes/widgets/widget-portfolio-items.php');
// include_once('includes/widgets/widget-posts.php');
// include_once('includes/widgets/widget-space.php');
// include_once('includes/widgets/widget-twitter.php');
