<?php

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

?>
