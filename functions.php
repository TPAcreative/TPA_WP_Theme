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
    [ Register Widgets ]
----------------------------------------------------------*/
// function tpa_widgets() {

//     // Footer Widgets
//     register_sidebar( array(
//         'name' => __( 'Footer Slot #1' ),
//         'id' => 'footer-slot-1',
//         'description' => __( 'L2R: First position in the footer' ),
//         'before_widget' => '<div class="col widget">',
//         'after_widget' => '</div>',
//         'before_title' => '<h3>',
//         'after_title' => '</h3>',
//     ) );
//     register_sidebar( array(
//         'name' => __( 'Footer Slot #2' ),
//         'id' => 'footer-slot-2',
//         'description' => __( 'L2R: Second position in the footer' ),
//         'before_widget' => '<div class="col widget">',
//         'after_widget' => '</div>',
//         'before_title' => '<h3>',
//         'after_title' => '</h3>',
//     ) );
//     register_sidebar( array(
//         'name' => __( 'Footer Slot #3' ),
//         'id' => 'footer-slot-3',
//         'description' => __( 'L2R: Third position in the footer' ),
//         'before_widget' => '<div class="col widget">',
//         'after_widget' => '</div>',
//         'before_title' => '<h3>',
//         'after_title' => '</h3>',
//     ) );
//     register_sidebar( array(
//         'name' => __( 'Footer Slot #4' ),
//         'id' => 'footer-slot-4',
//         'description' => __( 'L2R: Fourth position in the footer' ),
//         'before_widget' => '<div class="col endrow widget">',
//         'after_widget' => '</div>',
//         'before_title' => '<h3>',
//         'after_title' => '</h3>',
//     ) );

// }
// add_action( 'widgets_init', 'tpa_widgets' );


/*---------------------------------------------------------
    [ Register Custom Post Type ]
----------------------------------------------------------*/
// function tpa_create_post_type() {
//     register_post_type( 'people',
//         array(
//             'labels' => array(
//                 'name' => __( 'People' ),
//                 'singular_name' => __( 'Person' ),
//                 'add_new' => __( 'Add Person' ),
//                 'add_new_item' => __( 'Add Person' ),
//                 'edit_item' => __( 'Edit Person' ),
//             ),
//             'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
//             'public' => true,
//             'rewrite' => array('slug' => 'our-people'),
//             'hierarchical' => true,
//         )
//     );
// }
// add_action( 'init', 'tpa_create_post_type' );


/*---------------------------------------------------------
    [ Register Meta Boxes ]
----------------------------------------------------------*/
// function tpa_meta_boxes() {

//     $prefix = 'tpa_';

//     // Page Options
//     $page_options_meta = array(
//         'id' => 'page-options',
//         'title' => 'Page Options',
//         'pages' => array('page'),
//         'context' => 'normal',
//         'priority' => 'low',
//         'fields' => array(
//             array(
//                 'label' => 'Summary',
//                 'desc' => 'For child pages using boxed navigation',
//                 'id' => $prefix . 'page_summary',
//                 'type' => 'text'
//             ),
//             array(
//                 'label' => 'Insert Slider',
//                 'desc' => 'Select a slider to display in the header',
//                 'id' => $prefix . 'revolution_slider',
//                 'type' => 'revolution_slider'
//             ),
//             array(
//                 'label' => 'Website Feedback',
//                 'desc' => 'For use by the Contact page only',
//                 'id' => $prefix . 'page_website_feedback',
//                 'type' => 'text'
//             ),
//             array(
//                 'label' => 'Child Page Columns',
//                 'desc' => 'Enter the number of columns allocated to the boxed navigation of child pages',
//                 'id' => $prefix . 'child_col_num',
//                 'type' => 'text'
//             )
//         )
//     );

//     // People Options
//     $people_options_meta = array(
//         'id' => 'people-options',
//         'title' => 'People Options',
//         'pages' => array('people'),
//         'context' => 'normal',
//         'priority' => 'low',
//         'fields' => array(
//             array(
//                 'id' => $prefix . 'person_role',
//                 'label' => 'Role',
//                 'desc' => 'Select a role for this person',
//                 'type' => 'role',
//             ),
//             array(
//                 'id' => $prefix . 'person_partner_salaried',
//                 'label' => 'Is the Partner Salaried?',
//                 'desc' => 'Only applicable if the role is set to Partner',
//                 'type' => 'select',
//                 'choices' => array(
//                     array(
//                         'label' => 'No',
//                         'value' => 'no'
//                     ),
//                     array(
//                         'label' => 'Yes',
//                         'value' => 'yes'
//                     )
//                 )
//             ),
//             array(
//                 'id' => $prefix . 'person_job_title',
//                 'label' => 'Job Title',
//                 'desc' => 'If this person\'s Job Title is different to their role, enter it here.',
//                 'type' => 'text',
//             ),
//             array(
//                 'id' => $prefix . 'people_banner_text',
//                 'label' => 'Banner Summary',
//                 'desc' => 'Enter a short summary for this person. This text will appear in the banner.',
//                 'type' => 'textarea',
//             ),
//             array(
//                 'id' => $prefix . 'people_practice_areas',
//                 'label' => 'Practice Areas',
//                 'desc' => 'Enter the practice areas relevant to this person. Note: Each new line represents an item in the list',
//                 'type' => 'textarea',
//             ),
//             array(
//                 'id' => $prefix . 'people_email',
//                 'label' => 'Email Address',
//                 'desc' => 'Enter the email address to contact this person.',
//                 'type' => 'text',
//             ),
//             array(
//                 'id' => $prefix . 'people_telephone_ddi',
//                 'label' => 'Telephone (DDi)',
//                 'desc' => 'Enter a telephone number to contact this person.',
//                 'type' => 'text',
//             ),
//             array(
//                 'id' => $prefix . 'people_telephone',
//                 'label' => 'Telephone',
//                 'desc' => 'Enter a DDI (Direct Dial in) number to contact this person.',
//                 'type' => 'text',
//             ),
//             array(
//                 'id' => $prefix . 'people_telephone_additional',
//                 'label' => 'Telephone (Additional)',
//                 'desc' => 'Enter a telephone number to contact this person. Append a description in brackets to the number e.g. +27 21 880 9391 (South Africa)',
//                 'type' => 'text',
//             ),
//             array(
//                 'id' => $prefix . 'people_mobile',
//                 'label' => 'Mobile',
//                 'desc' => 'Enter a mobile number to contact this person.',
//                 'type' => 'text',
//             ),
//             // array(
//             //     'id' => $prefix . 'people_location',
//             //     'label' => 'Location',
//             //     'desc' => 'Select a location for the individual. This list is populated by each location created within the "Locations" section',
//             //     'type' => 'location',
//             // ),
//             array(
//                 'id' => $prefix . 'people_location_checkbox',
//                 'label' => 'Location',
//                 'desc' => 'Select a location for the individual. This list is populated by each location created within the "Locations" section',
//                 'type' => 'location-checkbox',
//             ),
//             array(
//                 'id' => $prefix . 'people_alt_image',
//                 'label' => 'Alternative Banner Image',
//                 'desc' => 'Upload a file to replace the current banner image for this individual within their profile. <strong>IMPORTANT:</strong> When inserting the image, ensure the image ID is inserted, not the URL (Media File Link).',
//                 'type' => 'upload',
//             )
//         )
//     );

//     ot_register_meta_box( $page_options_meta );
//     ot_register_meta_box( $people_options_meta );

// }
// add_action( 'admin_init', 'tpa_meta_boxes' );


/*---------------------------------------------------------
    [ Breadcrumbs ]
----------------------------------------------------------*/
include('includes/breadcrumbs.php');

/*---------------------------------------------------------
    [ Helper Functions ]
----------------------------------------------------------*/
function get_slug($post_id) {
    $post_data = get_post($post_id, ARRAY_A);
    $slug = $post_data['post_name'];
    return $slug;
}
function is_parent(){
    global $post;
    $children = wp_list_pages('title_li=&echo=0&child_of=' . $post->ID);
    if(!empty($children) || $children == true){
        return true;
    }
}
function is_child(){
    global $post;
    if($post->post_parent != 0){
        return true;
    }
}
function limit_content($limit, $heading=true) {
    if($heading == true){
        $heading_count = str_word_count(get_the_title(), 0);

        $limit = $limit - $heading_count;

        $content = explode(' ', get_the_content(), $limit);
    } else {
        $content = explode(' ', get_the_content(), $limit);
    }

    if (count($content)>=$limit) {
        array_pop($content);
        $content = implode(" ",$content).'...';
    } else {
        $content = implode(" ",$content);
    }
    $content = preg_replace('/\[.+\]/','', $content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}
function limit_text($limit, $heading=true) {
    if($heading == true){
        $heading_count = str_word_count(get_the_title(), 0);

        $limit = $limit - $heading_count;

        $content = explode(' ', get_the_content(), $limit);
    } else {
        $content = explode(' ', get_the_content(), $limit);
    }

    if (count($content)>=$limit) {
        array_pop($content);
        $content = implode(" ",$content).'...';
    } else {
        $content = implode(" ",$content);
    }
    return $content;
}


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


/*---------------------------------------------------------
    [ Fix WP Nav Parents for Custom Post Type children ]
----------------------------------------------------------*/
// function remove_parent_classes($class)
// {
//   // check for current page classes, return false if they exist.
//     return ($class == 'current_page_item' || $class == 'current_page_parent' || $class == 'current_page_ancestor'  || $class == 'current-menu-item') ? FALSE : TRUE;
// }

// function add_class_to_wp_nav_menu($classes)
// {
//     $people_menu_id = ot_get_option('hatstone_people_menu_id');
//     $news_menu_id = ot_get_option('hatstone_news_menu_id');
//     switch (get_post_type())
//     {
//         case 'people':
//             if(!empty($people_menu_id)):
//                 // we're viewing a custom post type, so remove the 'current_page_xxx and current-menu-item' from all menu items.
//                 $classes = array_filter($classes, "remove_parent_classes");

//                 // add the current page class to a specific menu item (replace ###).
//                 if (in_array('menu-item-'.$people_menu_id, $classes))
//                 {
//                    $classes[] = 'current-menu-item';
//                 }
//             endif;
//         break;

//         case 'post':
//             if(!empty($news_menu_id)):
//                 // we're viewing a custom post type, so remove the 'current_page_xxx and current-menu-item' from all menu items.
//                 $classes = array_filter($classes, "remove_parent_classes");

//                 // add the current page class to a specific menu item (replace ###).
//                 if (in_array('menu-item-'.$news_menu_id, $classes))
//                 {
//                    $classes[] = 'current-menu-item';
//                 }
//             endif;
//         break;

//     }
//     return $classes;
// }
// add_filter('nav_menu_css_class', 'add_class_to_wp_nav_menu');

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
    [ Includes ]
----------------------------------------------------------*/
include('includes/widget-map.php');
include('includes/widget-portfolio-items.php');
include('includes/widget-posts.php');
include('includes/widget-space.php');
include('includes/widget-twitter.php');
