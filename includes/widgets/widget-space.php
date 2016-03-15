<?php

add_action('widgets_init', create_function('', 'register_widget("Space_Widget");'));

class Space_Widget extends WP_Widget {
    function __construct() {
        parent::__construct('space_widget', 'Space', array('description'=>'Renders a space within the space - either blank or with a line'));
    }
    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        $type  = empty($instance['type']) ? ' ' : apply_filters('widget_type', $instance['type']);
        $margin  = empty($instance['margin']) ? ' ' : apply_filters('widget_margin', $instance['margin']);

        echo $before_widget;

        $html = '<div class="'.$type.' space" style="margin-top:'.$margin.'px;margin-bottom:'.$margin.'px"></div>';

        echo $html;

        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['type']  = trim(strip_tags($new_instance['type']));
        $instance['margin']  = trim(strip_tags($new_instance['margin']));
        return $instance;
    }
    function form($instance) {
        $defaults = array(
            'type' => __('line'),
            'margin' => __('20'),
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        $type = trim(strip_tags($instance['type']));
        $margin = trim(strip_tags($instance['margin']));?>

        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>" id="<?php echo $this->get_field_id('type'); ?>"><?php _e('Type'); ?></label>
            <select name="<?php echo $this->get_field_name('type'); ?>">
                <option value="line"<?php selected( $instance['type'], 'line' ); ?>>Line</option>
                <option value="invisible"<?php selected( $instance['type'], 'invisible' ); ?>>Invisible</option>
            </select>
        </p>

        <p><label for="<?php echo $this->get_field_id('margin'); ?>"><?php _e('Margin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('margin'); ?>" name="<?php echo $this->get_field_name('margin'); ?>" type="text" value="<?php echo esc_attr($margin); ?>" />
        </p>
<?php }
}
