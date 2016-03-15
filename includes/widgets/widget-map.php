<?php

add_action('widgets_init', create_function('', 'register_widget("Map_Widget");'));

class Map_Widget extends WP_Widget {
    function __construct() {
        parent::__construct('map_widget', 'Map', array('description'=>'Renders a Google Map, plotted with a specified co-ordinate. Optional contact info caption.'));
    }
    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        $location_name  = empty($instance['location_name']) ? '' : apply_filters('widget_location_name', $instance['location_name']);
        $map_address  = empty($instance['map_address']) ? '' : apply_filters('widget_map_address', $instance['map_address']);
        $latlng  = empty($instance['latlng']) ? '' : apply_filters('widget_latlng', $instance['latlng']);
        $markerinfo  = empty($instance['markerinfo']) ? '' : apply_filters('widget_markerinfo', $instance['markerinfo']);
        $zoom   = empty($instance['zoom']) ? '' : apply_filters('widget_zoom', $instance['zoom']);
        $height  = empty($instance['height']) ? '' : apply_filters('widget_height', $instance['height']);

        echo $before_widget;

        // Select a random number as the ID selector for the map
        $rand_id = rand(1,9999);

        //wp_enqueue_script( 'render-map' );

        $map_address = str_replace(array("\r\n", "\r"), "\n", $map_address);
        $lines = explode("\n", $map_address);
        $new_lines = array();

        foreach ($lines as $i => $line) {
            if(!empty($line)){
               $new_lines[] = trim($line . ', ');
           }
        }

        $address_singleline = addslashes(implode($new_lines));

        // If there isn't any marker info, use the address as text
        if(empty($markerinfo)){
            $markerinfo = $address_singleline;
        }


        $map_data = array(
            'map_address' => __( addslashes($map_address) ),
            'address_singleline' => $address_singleline,
            'latlng' => $latlng,
            'marker_info' => __( addslashes($markerinfo) ),
            'mapid' => __( '#gmap_'.intval($rand_id) ),
            'zoom' => intval($zoom)
        );

        $address_singleline = $map_data['address_singleline'];
        $latlng = $map_data['latlng'];
        $marker_info = $map_data['marker_info'];
        $zoom = $map_data['zoom'];
        $mapid = $map_data['mapid'];


        // wp_localize_script( 'render-map', 'map_data', $map_data );

        $html = "<script>
                jQuery(document).ready(function($) {
                    var latlng = '$latlng',
                        mapid = '$mapid',
                        zoom = $zoom;
                    if(latlng.length > 0) {
                        latlng = latlng.split(',');
                        var latitude = latlng[0],
                            longitude = latlng[1];
                        $(mapid).gmap().bind('init', function(ev, map) {
                            $(mapid).gmap('addMarker', { 'position': latitude+','+longitude, 'bounds': true});
                            $(mapid).gmap('option', 'zoom', zoom);
                        });
                    }
                });
            </script>";

        $html .= str_replace( array( '<p></p>' ), '', $html );

        $html .= '<div class="map-container">';
        $html .= '<div id="gmap_'.$rand_id.'" class="map" style="width:100%; height:'.$height.'px;"><span class="loading">Loading</span></div>';

        if(!empty($map_address) || !empty($location_name)){
            $html .= '<div class="caption">';

            if(!empty($location_name)){
                $html .= '<p><strong>'.$location_name.'</strong></p>';
            }
            if(!empty($map_address)){
                $html .= '<p>'.nl2br($map_address).'</p>';
            }
            $html .= '</div>';
        }

        $html .= '</div>';

        echo $html;

        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['location_name']  = trim(strip_tags($new_instance['location_name']));
        $instance['map_address']  = trim(strip_tags($new_instance['map_address']));
        $instance['latlng']  = trim(strip_tags($new_instance['latlng']));
        $instance['markerinfo']  = trim(strip_tags($new_instance['markerinfo']));
        $instance['zoom']  = trim(strip_tags($new_instance['zoom']));
        $instance['height']   = trim(strip_tags($new_instance['height']));
        return $instance;
    }
    function form($instance) {
        $defaults = array(
            'location_name' => __(''),
            'map_address' => __(''),
            'latlng' => __(''),
            'markerinfo' => __(''),
            'zoom' => __(17),
            'height' => __(230),
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        $location_name = trim(strip_tags($instance['location_name']));
        $map_address = trim(strip_tags($instance['map_address']));
        $latlng = trim(strip_tags($instance['latlng']));
        $markerinfo = trim(strip_tags($instance['markerinfo']));
        $zoom = trim(strip_tags($instance['zoom']));
        $height = trim(strip_tags($instance['height'])); ?>

        <p>
            <label for="<?php echo $this->get_field_id('location_name'); ?>">Location Name</label>
            <input class="widefat" id="<?php echo $this->get_field_id('location_name'); ?>" name="<?php echo $this->get_field_name('location_name'); ?>" type="text" value="<?php echo esc_attr($location_name); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('map_address'); ?>"><?php _e('Address'); ?></label>
            <textarea rows="8" class="widefat" id="<?php echo $this->get_field_id('map_address'); ?>" name="<?php echo $this->get_field_name('map_address'); ?>"><?php echo esc_attr($map_address); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('latlng'); ?>"><?php _e('Latitude and Longitude<br /><em>You can find this co-ordinates by clicking <a href="https://maps.google.com/">here</a>, right clicking a point on the map, then selecting "What\'s this?". The co-ordinates will appear in Google\'s search bar. Please copy and paste these values.</em>'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('latlng'); ?>" name="<?php echo $this->get_field_name('latlng'); ?>" type="text" value="<?php echo esc_attr($latlng); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('markerinfo'); ?>"><?php _e('Marker Label <em>Note: By default, the address is populated in the Google Maps label.</em>'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('markerinfo'); ?>" name="<?php echo $this->get_field_name('markerinfo'); ?>" type="text" value="<?php echo esc_attr($markerinfo); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('zoom'); ?>"><?php _e('Zoom <em>Note: Enter a value between 1 and 21. The higher the number, the more magnified the map.</em>'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('zoom'); ?>" name="<?php echo $this->get_field_name('zoom'); ?>" type="text" value="<?php echo esc_attr($zoom); ?>" />
        </p>
        <p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('height text for height archive'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr($height); ?>" />
        </p>
<?php }
}
