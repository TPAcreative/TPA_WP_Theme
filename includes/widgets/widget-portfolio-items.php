<?php

add_action('widgets_init', create_function('', 'register_widget("Portfolio_Items_Widget");'));

class Portfolio_Items_Widget extends WP_Widget {
    function __construct() {
        parent::WP_Widget('portfolio_items_widget', 'Portfolio Items', array('description'=>'Displays items from the portfolio'));
    }
    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        $title  = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $columns  = empty($instance['columns']) ? '' : apply_filters('widget_columns', $instance['columns']);
        $slider  = empty($instance['slider']) ? '' : apply_filters('widget_slider', $instance['slider']);
        $filter  = empty($instance['filter']) ? '' : apply_filters('widget_filter', $instance['filter']);
        $arrows  = empty($instance['arrows']) ? '' : apply_filters('widget_arrows', $instance['arrows']);
        $count  = empty($instance['count']) ? '' : apply_filters('widget_count', $instance['count']);
        $greyscale  = empty($instance['greyscale']) ? '' : apply_filters('widget_greyscale', $instance['greyscale']);
        $order  = empty($instance['order']) ? '' : apply_filters('widget_order', $instance['order']);
        $orderby  = empty($instance['orderby']) ? '' : apply_filters('widget_orderby', $instance['orderby']);

        echo $before_widget;

        // Set slider class if set to yes
        if($slider == 1){
            $scroll = ' scroll-slider';
        } else {
            $scroll = '';
        }

        if($greyscale == 1){
            $e_greyscale = ' greyscale';
        } else {
            $e_greyscale = '';
        }

        $html = '';

        // Arrows
        if($arrows == 'yes'){
            $html .= '<div class="arrows-wrapper">';
            $html .= '<div class="arrows"><div class="arrow prev"></div><div class="arrow next"></div></div>';
        }

        $html .= '<div class="portfolio-items-wrapper">';

        // Check if a title has been specified
        if(!empty($title)){
            // Render a styled heading if it has
            $html .= '<h2>'.$title.'</h2>';
        }

        if($slider == 'yes'){
            $spacing = ' no-spacing margin';
        } else {
            $spacing = '';
        }

        // Starts the portfolio-items wrapper
        $html .= '<div class="portfolio-items col-'.$columns.$scroll.$spacing.' clearfix">';

        // The Query
        $the_query = new WP_Query( array('post_type' => 'portfolio', 'order' => $order,'posts_per_page' => $count, 'orderby' => $orderby) );

        // Grab all the terms associated with 'category_type'
        $services = get_posts( array('post_type' => 'service', 'order' => 'ASC', 'orderby' => 'title', 'posts_per_page' => -1) );

        // Generate a random ID to prevent conflicting portfolio items
        $rand_id = rand(1,9999);

        // Render all terms into a list to be used as a filter
        $count = count($services);
        if ( $count > 0 && $filter == 'yes' ){
            $html .= '<ul id="filter-'.$rand_id.'" class="filter clearfix">';
                $html .= '<li class="active"><a href="#" data-filter="*">All</a></li>';
            foreach ( $services as $service ) {
                $category_type = strtolower(preg_replace("/[\/ !\']/","-", stripslashes($service->post_title)));
                $html .= '<li><a href="#" data-filter=.'.$category_type.'>' . $service->post_title . '</a></li>';
            }
            $html .= '</ul>';
        }

        // Start the list for each portfolio item
        $html .= '<ul id="portfolio-items-'.$rand_id.'" class="clearfix">';

        // The Loop
        $i = 0;
        while ( $the_query->have_posts() ) : $the_query->the_post();

            // If the portfolio item is in the last column, add 'endrow'
            $i++;
            if($i % $columns == 0){
                $endrow = ' endrow';
            } else {
                $endrow = '';
            }

            // Get the image source
            $image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'medium');

            // Get the terms associated with the item
            $category_type = get_post_meta(get_the_ID(), 'spike_services', true);

            // If any terms for the item have been found
            if(!empty($category_type)){

                // Build an array to store the terms
                $category_type_list_slug = array();
                $category_type_list = array();

                foreach ( $category_type as $key ) {

                    // Get the service object
                    $portfolio_obj = get_post($key);

                    //var_dump($portfolio_obj);

                    // Add each category_type to the array and replace special charaters with a '-'
                    $category_type_list_slug[] = preg_replace("/[\/ !\']/","-", stripslashes($portfolio_obj->post_name));
                    $category_type_list[] = $portfolio_obj->post_title;
                }
                                    
                // Join the array data and store as a string, separated by a space
                $category_type_list_slug_combined = strtolower(join( " ", $category_type_list_slug ));
                $category_type_list_combined = join(', ', $category_type_list);

                // Add the category_type to the list item
                $html .= '<li class="project col'.$endrow.' '.$category_type_list_slug_combined.'">';
            } elseif($slider == 'yes'){
                $html .= '<li class="project col" data-slide="'.$i.'">';
                $category_type_list_combined = 'No Category Set';
            } else {
                $html .= '<li class="project col'.$endrow.'">';
                $category_type_list_combined = 'No Category Set';
            }

            $html .=    '<div class="item">';
            $html .=        '<a class="permalink" href="'.get_permalink().'">';
            $html .=            '<div class="caption">';
            $html .=                '<i class="image-rep'.$e_greyscale.'" style="background-image:url(\''.$image[0].'\');"></i>';
            $html .=                '<strong class="title">'.get_the_title().'</strong>';
            $html .=                '<p class="category">'.$category_type_list_combined.'</p>';
            $html .=            '</div>';
            $html .=        '</a>';
            $html .=    '</div>';
            $html .= '</li>';

        endwhile;
        wp_reset_postdata();
        
        // Close the list and wrapper
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';

        if($arrows == 'yes'){
            $html .= '</div>';
        }

        if($filter == 'yes'):

        ?>
            <script>
                jQuery(document).ready(function($) {
                    function equalHeightColumns(elements){
                        var heightArray = $(elements).map( function(){
                            return  $(this).height();
                            }).get();
                        var maxHeight = Math.max.apply( Math, heightArray);
                        $(elements).height(maxHeight);
                    }
                    equalHeightColumns('.portfolio-items .caption');
                    // cache container
                    var $container = $(<?php echo '\''.'#portfolio-items-'.$rand_id.'\''; ?>),
                        $filter = $(<?php echo '\''.'#filter-'.$rand_id.' a\''; ?>);
                    // initialize isotope
                    $container.isotope({
                      // options...
                    });
                    // filter items when filter link is clicked
                    $filter.click(function(){
                      var selector = $(this).attr('data-filter');
                      $filter.parent().removeClass('active');
                      $(this).parent().addClass('active');
                      $container.isotope({ filter: selector });
                      return false;
                    });
                });
            </script>
        <?
        endif;

        echo $html;

        echo $after_widget;
    }
    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['title']  = trim(strip_tags($new_instance['title']));
        $instance['columns']  = trim(strip_tags($new_instance['columns']));
        $instance['slider'] = isset($new_instance['slider']);
        $instance['filter'] = isset($new_instance['filter']);
        $instance['arrows'] = isset($new_instance['arrows']);
        $instance['count']  = trim(strip_tags($new_instance['count']));
        $instance['greyscale']  = trim(strip_tags($new_instance['greyscale']));
        $instance['order']  = trim(strip_tags($new_instance['order']));
        $instance['orderby']  = trim(strip_tags($new_instance['orderby']));

        return $instance;
    }
    function form($instance) {
        $defaults = array(
            'title' => __(''),
            'columns' => __(4),
            'count' => __(-1),
            'greyscale' => __(1),
            'slider' => __(0),
            'arrows' => __(0),
            'filter' => __(1),
            'order' => __('DESC'),
            'orderby' => __('date'),
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        $title = trim(strip_tags($instance['title']));
        $columns = trim(strip_tags($instance['columns']));
        $slider = trim(strip_tags($instance['slider']));
        $filter = trim(strip_tags($instance['filter']));
        $arrows = trim(strip_tags($instance['arrows']));
        $count = trim(strip_tags($instance['count']));
        $greyscale = trim(strip_tags($instance['greyscale']));
        $order = trim(strip_tags($instance['order']));
        $orderby = trim(strip_tags($instance['orderby'])); ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title text</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Number of columns'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>" type="text" value="<?php echo esc_attr($columns); ?>" />
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('slider'); ?>" name="<?php echo $this->get_field_name('slider'); ?>" type="checkbox" value="1" <?php checked($slider ? $slider : 0); ?> />&nbsp;
            <label for="<?php echo $this->get_field_id('slider'); ?>"><?php _e('Enable Slider'); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('arrows'); ?>" name="<?php echo $this->get_field_name('arrows'); ?>" type="checkbox" value="1" <?php checked($arrows ? $arrows : 0); ?> />&nbsp;
            <label for="<?php echo $this->get_field_id('arrows'); ?>"><?php _e('Enable Slider Arrows'); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" value="1" <?php checked($filter ? $filter : 0); ?> />&nbsp;
            <label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Enable Filter'); ?></label>
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
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of items to display. Enter <em>-1</em> to display all.'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" />
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('greyscale'); ?>" name="<?php echo $this->get_field_name('greyscale'); ?>" type="checkbox" value="1" <?php checked($greyscale ? $greyscale : 0); ?> />&nbsp;
            <label for="<?php echo $this->get_field_id('greyscale'); ?>"><?php _e('Enable Greyscale'); ?></label>
        </p>
<?php }
}