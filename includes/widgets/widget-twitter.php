<?php

add_action('widgets_init', create_function('', 'register_widget("Twitter");'));

class Twitter extends WP_Widget {
    function __construct() {
        parent::__construct('twitter_widget', 'Twitter', array('description'=>'Displays a list of Tweets'));
    }
    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        $title  = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        $count  = empty($instance['count']) ? ' ' : apply_filters('widget_count', $instance['count']);

        echo $before_widget;

        // Get options from Back-end
        $twitteraccount = ot_get_option( 'spike_twitter_account_name' );
        $consumer_key = ot_get_option( 'spike_twitter_consumer_key' );
        $consumer_secret = ot_get_option( 'spike_twitter_consumer_secret' );
        $access_token = ot_get_option( 'spike_twitter_access_token' );
        $access_token_secret = ot_get_option( 'spike_twitter_access_token_secret' );

        // Set transient name
        $transient_name = 'tweets';

        // Set cache time (60 * mins)
        $cache_time = 0.5;

        // Define Twitter Response as Transient
        //$twitter_response = get_transient($transient_name);

        // If transient cant be found..
        if(false == ($twitter_response = get_transient($transient_name))){

            // Load Twitter oAuth
            require(get_template_directory().'/includes/twitter-oauth/twitteroauth.php');

            // Connect and Authenticate to Twitter API
            $twitter_connect = new TwitterOAuth(
                $consumer_key, // Consumer Key
                $consumer_secret, // Consumer Secret
                $access_token, // Access Token
                $access_token_secret // Access Token Secret
            );

            // Get data back from Twitter API
            $twitter_response = $twitter_connect->get(
                'statuses/user_timeline',
                array(
                    'screen_name' => $twitteraccount,
                    'count' => $count,
                    'include_rts' => true,
                    'exclude_replies' => true,
                    'include_entities' => true
                )

            );

            // Get a new transient
            delete_transient($transient_name);
            set_transient($transient_name, $twitter_response, 60 * $cache_time);
        }

        // Beginning of the Widget
        $html = '<div class="twitter">';

        if (!empty($title)) {
            $html .= '<h2>'.$title.'</h2>';
        }

        // Check for Twitter Response for errors
        if(is_null($twitter_response)){
            $html .= 'There was a problem. Please try again.';
        } elseif(array_key_exists('errors', $twitter_response)){
            foreach ($twitter_response['errors'] as $key => $value) {
                $html .= '<p>Error: Code '.$value['code'].' - '.$value['message'].'</p>';
            }
        } else {

            // Loop through object to build HTML
            $html .= '<ul class="tweets">';
            foreach ($twitter_response as $tweet) {

                $status_id = $tweet['id_str'];
                $date = strtotime($tweet['created_at']);

                $status_url = 'http://twitter.com/'.$twitteraccount.'/statuses/'.$status_id;

                $text = $tweet['text'];

                // Replace Retweet User with an anchor tag

                if(!empty($tweet['retweeted_status'])){
                    $retweet  = $tweet['retweeted_status'];
                    $screen_name = $retweet['user']['screen_name'];
                    $text = str_replace('RT @'.$screen_name, 'RT <a href="https://twitter.com/'.$screen_name.'" target="_blank">@'.$screen_name.'</a>', $text);
                }

                // Replace all tweet URLs with anchor tags
                $urls  = $tweet['entities']['urls'];
                if(!empty($urls)){
                    foreach($urls as $url){
                        $text = str_replace($url['url'], '<a href="'.$url['expanded_url'].'" target="_blank">'.$url['url'].'</a>', $text);
                    }
                }

                // Replace all hashtags with anchor tags
                $hashtags  = $tweet['entities']['hashtags'];
                if(!empty($hashtags)){
                    foreach($hashtags as $hashtag){
                        $hashtag = $hashtag['text'];
                        $text = str_replace('#'.$hashtag, '<a href="https://twitter.com/search?q=%23'.$hashtag.'&src=hash" target="_blank">'.'#'.$hashtag.'</a>', $text);
                    }
                }

                // Replace all user mentions with anchor tags
                $user_mentions  = $tweet['entities']['user_mentions'];
                if(!empty($user_mentions)){
                    foreach($user_mentions as $user_mention){
                        $screen_name = $user_mention['screen_name'];
                        $text = str_replace('@'.$screen_name, '<a href="https://twitter.com/'.$screen_name.'" target="_blank">@'.$screen_name.'</a>', $text);
                    }
                }

                $html .= '<li class="tweet">';
                $html .= '<p>'.$text.'</p>';
                $html .= '<a class="time-ago" href="'.$status_url.'" target="_blank"><span>'.human_time_diff( $date, current_time('timestamp') ).' ago</span></a>';
                $html .= '</li>';
            }
            $html .= '</ul>';

        }

        $html .= '<a href="https://twitter.com/'.$twitteraccount.'" class="twitter-follow-button social" data-show-count="false" data-show-screen-name="false">Follow</a>';
        $html .= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>';

        // End of the Widget
        $html .= '</div>';

        echo $html;

        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title']  = trim(strip_tags($new_instance['title']));
        $instance['count']  = trim(strip_tags($new_instance['count']));
        return $instance;
    }
    function form($instance) {
        $defaults = array(
            'title' => __('Twitter'),
            'count' => __('2'),
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        $title = trim(strip_tags($instance['title']));
        $count = trim(strip_tags($instance['count']));?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title text</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of Tweets'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" />
        </p>
<?php }
}
