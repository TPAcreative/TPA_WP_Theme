<?php

add_action('widgets_init', create_function('', 'register_widget("Posts_Widget");'));

class Posts_Widget extends WP_Widget {
    function __construct() {
        parent::__construct('posts_widget', 'Posts', array('description'=>'Displays the most recent posts'));
    }
    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        $title  = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $count  = empty($instance['count']) ? '' : apply_filters('widget_count', $instance['count']);
        $order  = empty($instance['order']) ? '' : apply_filters('widget_order', $instance['order']);
        $orderby  = empty($instance['orderby']) ? '' : apply_filters('widget_orderby', $instance['orderby']);
        $limit_words  = empty($instance['limit_words']) ? '' : apply_filters('widget_limit_words', $instance['limit_words']);

        echo $before_widget;

        $html = '<div class="latest-posts clearfix">';

        if(!empty($title)){
            $html .= '<div class="heading">';
            $html .= '<h2>'.$title.'</h2>';
            $html .= '</div>';
        }
        $html .= '<div class="slides-wrapper">';
        $html .= '<ul class="post-list clearfix">';

        // The Query
        $the_query = new WP_Query( array('post_type' => 'post', 'order' => $order, 'orderby' => $orderby, 'posts_per_page' => $count) );

        $i = 1;

        // The Loop
        while ( $the_query->have_posts() ) : $the_query->the_post();

            global $post;

            $image = get_the_post_thumbnail( get_the_ID(), 'grid_square' );

            $html .= '<li class="post active" data-slide="'.$i.'">';
            $html .=    '<article class="clearfix">';
            $html .=        '<div class="post-content-wrapper">';
            $html .=            '<h2><a href="'.get_permalink().'">'.get_the_title().'</a></h2>';
            $html .=            limit_content($limit_words, true);
            $html .=            '<p><a class="read-more" href="'.get_permalink().'">Read more</a></p>';
            $html .=        '</div>';
            $html .=    '</article>';
            $html .= '</li>';

            $i++;

        endwhile;

        $html .= '</ul>';
        $html .= '</div><!-- end .slides-wrapper -->';

        $html .=    '<div class="pagination-ele">';
        $html .=        '<!-- pagination inserted here -->';
        $html .=    '</div>';

        $html .= '</div><!-- end .latest-posts -->';

        wp_reset_postdata();

        echo $html;

        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title']  = trim(strip_tags($new_instance['title']));
        $instance['count']  = trim(strip_tags($new_instance['count']));
        $instance['order']  = trim(strip_tags($new_instance['order']));
        $instance['orderby']  = trim(strip_tags($new_instance['orderby']));
        $instance['limit_words']  = trim(strip_tags($new_instance['limit_words']));

        return $instance;
    }
    function form($instance) {
        $defaults = array(
            'title' => __(''),
            'count' => __(5),
            'order' => __('DESC'),
            'orderby' => __('date'),
            'limit_words' => __(20),
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        $title = trim(strip_tags($instance['title']));
        $count = trim(strip_tags($instance['count']));
        $order = trim(strip_tags($instance['order']));
        $orderby = trim(strip_tags($instance['orderby']));
        $limit_words = trim(strip_tags($instance['limit_words'])); ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title text</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of posts to display. Enter <em>-1</em> to display all.'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>"><?php _e('Sort'); ?></label>
            <select name="<?php echo $this->get_field_name('order'); ?>">
                <option value="ASC"<?php selected( $instance['order'], 'ASC' ); ?>>Ascending</option>
                <option value="DESC"<?php selected( $instance['order'], 'DESC' ); ?>>Descending</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order by'); ?></label>
            <select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>">
                <option value="ID"<?php selected( $instance['orderby'], 'ID' ); ?>>ID</option>
                <option value="author"<?php selected( $instance['orderby'], 'author' ); ?>>Author</option>
                <option value="date"<?php selected( $instance['orderby'], 'date' ); ?>>Date</option>
                <option value="menu_order"<?php selected( $instance['orderby'], 'menu_order' ); ?>>Menu Order</option>
                <option value="rand"<?php selected( $instance['orderby'], 'rand' ); ?>>Random</option>
            </select>
        </p>
        <p><label for="<?php echo $this->get_field_id('limit_words'); ?>"><?php _e('Limit the number of words (includes words in post heading)'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('limit_words'); ?>" name="<?php echo $this->get_field_name('limit_words'); ?>" type="text" value="<?php echo esc_attr($limit_words); ?>" />
        </p>
<?php }
}
