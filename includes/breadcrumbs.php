<?php 

function tpa_breadcrumbs() {

    /* === OPTIONS === */
    $text['home']     = 'Home'; // text for the 'Home' link
    $text['blog']     = get_the_title( get_option('page_for_posts', true) ); // text for the 'Blog' link
    $text['category'] = '%s'; // text for a category page
    $text['search']   = 'Search'; // text for a search results page
    $text['tag']      = '%s'; // text for a tag page
    $text['author']   = '%'; // text for an author page
    $text['404']      = 'Error 404'; // text for the 404 page

    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $showOnHome  = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter   = '<span class="separator"> \\\\ </span>'; // delimiter between crumbs
    $before      = '<span class="current">'; // tag before the current crumb
    $after       = '</span>'; // tag after the current crumb
    $charLimit   = 80;
    /* === END OF OPTIONS === */

    global $post;
    $homeLink = get_bloginfo('url') . '/';
    $postsLink = get_permalink( get_option('page_for_posts') );
    $linkBefore = '<span>';
    $linkAfter = '</span>';
    $linkAttr = '';
    $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;

    echo '<div class="breadcrumbs">';

    if(is_front_page()){
        if ($showOnHome == 1) echo '<a class="home" href="' . $homeLink . '">' . $text['home'] . '</a>';
    }
    elseif(is_home()){
        if ($showOnHome == 1) echo '<a class="home" href="' . $homeLink . '">' . $text['home'] . '</a>';
        if ($showOnHome == 1) echo $delimiter . '<a href="' . $postsLink . '">' . $text['blog'] . '</a>';
    }
    else {

        echo '<a class="home" href="' . $homeLink . '">' . $text['home'] . '</a>' . $delimiter;

        if(isset($post->post_type) && $post->post_type == 'post'){
            echo sprintf($link, $postsLink, $text['blog']) . $delimiter;
        }


        if ( is_category() ) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo $cats;
            }
            echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

        } elseif ( is_search() ) {
            echo $before . sprintf($text['search'], $_GET['s']) . $after;

        } elseif ( is_day() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
            echo $before . get_the_time('d') . $after;

        } elseif ( is_month() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo $before . get_the_time('F') . $after;

        } elseif ( is_year() ) {
            echo $before . get_the_time('Y') . $after;

        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {

                $post_type = get_post_type_object(get_post_type());

                $args = array(
                  'name' => $post_type->rewrite['slug'],
                  'post_type' => 'page',
                  'post_status' => 'publish',
                  'posts_per_page' => 1
                );

                $post_type_obj = get_posts($args);
                $post_type_obj = $post_type_obj[0];

                //var_dump($post_type_obj);

                $slug = $post_type->rewrite;
                printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type_obj->post_title);

                //printf($link, $homeLink . '/' . $post_type_obj->post_name . '/');
                if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo $cats;

                $post_title = get_the_title();
                if(strlen($post_title) > $charLimit){
                $post_title = substr($post_title,0,$charLimit).'...';
            }
                if ($showCurrent == 1) echo $before . $post_title . $after;
            }

        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name. $after;

        } elseif ( is_attachment() ) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            $cats = get_category_parents($cat, TRUE, $delimiter);
            $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
            $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
            echo $cats;
            printf($link, get_permalink($parent), $parent->post_title);
            if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;

        } elseif ( is_page() && !$post->post_parent ) {
            if ($showCurrent == 1) echo $before . get_the_title() . $after;

        } elseif ( is_page() && $post->post_parent ) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo $breadcrumbs[$i];
                if ($i != count($breadcrumbs)-1) echo $delimiter;
            }
            if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;

        } elseif ( is_tag() ) {
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;

        } elseif ( is_404() ) {
            echo $before . $text['404'] . $after;
        }

        if ( get_query_var('paged') ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
            echo __('Page') . ' ' . get_query_var('paged');
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
        }

    }

    echo '</div>';
}

?>
