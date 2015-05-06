<?php

function remove_parent_classes($class)
{
  // check for current page classes, return false if they exist.
    return ($class == 'current_page_item' || $class == 'current_page_parent' || $class == 'current_page_ancestor'  || $class == 'current-menu-item') ? FALSE : TRUE;
}

function add_class_to_wp_nav_menu($classes)
{
    $people_menu_id = ot_get_option('hatstone_people_menu_id');
    $news_menu_id = ot_get_option('hatstone_news_menu_id');
    switch (get_post_type())
    {
        case 'people':
            if(!empty($people_menu_id)):
                // we're viewing a custom post type, so remove the 'current_page_xxx and current-menu-item' from all menu items.
                $classes = array_filter($classes, "remove_parent_classes");

                // add the current page class to a specific menu item (replace ###).
                if (in_array('menu-item-'.$people_menu_id, $classes))
                {
                   $classes[] = 'current-menu-item';
                }
            endif;
        break;

        case 'post':
            if(!empty($news_menu_id)):
                // we're viewing a custom post type, so remove the 'current_page_xxx and current-menu-item' from all menu items.
                $classes = array_filter($classes, "remove_parent_classes");

                // add the current page class to a specific menu item (replace ###).
                if (in_array('menu-item-'.$news_menu_id, $classes))
                {
                   $classes[] = 'current-menu-item';
                }
            endif;
        break;

    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_class_to_wp_nav_menu');

?>
