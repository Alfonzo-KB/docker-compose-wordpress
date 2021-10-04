<?php
/*
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'hb_twitter_widgets' );

/*
 * Register widget.
 */
function hb_twitter_widgets() {
	register_widget( 'HB_Twitter_Widget' );
}

/**
 * Highend Twitter Widget.
 */
class hb_twitter_widget extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		$widget_ops = array( 
			'classname' => 'widget_twitter', 
			'description' => 'A widget that displays your latest tweets.' 
		);

		parent::__construct( 'twitter', '[HB-Themes] Twitter Feed', $widget_ops );
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @since 1.0.0
	 * @param array $args An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 */
	function widget( $args, $instance ) {

		extract( $args );

		$title 		= isset( $instance['title'] ) 		? sanitize_text_field( $instance['title'] )	 : '';
		$username 	= isset( $instance['username'] )	? sanitize_text_field( $instance['username'] ) : '';
		$skin 		= isset( $instance['skin'] ) 		? sanitize_text_field( $instance['skin'] ) : '';
		$count 		= isset( $instance['count'] ) 		? absint( intval( sanitize_text_field( $instance['count'] ) ) ) : 1;

		$consumer_key 			= highend_option( 'hb_twitter_consumer_key' );
		$consumer_secret 		= highend_option( 'hb_twitter_consumer_secret' );
		$access_token 			= highend_option( 'hb_twitter_access_token' );
		$access_token_secret 	= highend_option( 'hb_twitter_access_token_secret' );

		$transient_name = 'hbthemes_tweets_' . $args['widget_id'];

		if ( $count > 30 ) {
			$count = 30;
		}

		if ( $consumer_key && $consumer_secret && $access_token && $access_token_secret ) {
		
			echo $before_widget;
		
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}

			// delete_transient( $transient_name );

			if ( false === ( $twitter_data = get_transient( $transient_name ) ) ) {

				@require_once 'twitteroauth/twitteroauth.php';

				$twitter_connection = new TwitterOAuth(
					$consumer_key,
					$consumer_secret,
					$access_token,
					$access_token_secret
				);

			    $twitter_data = $twitter_connection->get(
					'statuses/user_timeline',
					array(
						'screen_name'		=> $username,
						'count'				=> $count,
						'exclude_replies'	=> false
					)
				);

				if ( $twitter_connection->http_code != 200 ) {
					$twitter_data = get_transient( $transient_name );
				}

				set_transient( $transient_name, $twitter_data, 30 * 10 );
			}

			$twitter = get_transient( $transient_name );

			if ( $twitter && is_array( $twitter ) ) { ?>

				<div id="tweets_<?php echo $args['widget_id']; ?>">
					<ul class="hb-tweet-list <?php echo $skin; ?>">
						<?php foreach ( $twitter as $tweet ): ?>
							<li>
								<span class="tweet-text">
									<?php
									$latestTweet = $tweet->text;
									$latestTweet = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '&nbsp;<a href="http://$1" target="_blank">http://$1</a>&nbsp;', $latestTweet);
									$latestTweet = preg_replace('/https:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '&nbsp;<a href="https://$1" target="_blank">http://$1</a>&nbsp;', $latestTweet);
									$latestTweet = preg_replace('/@([a-z0-9_]+)/i', '&nbsp;<a href="https://twitter.com/$1" target="_blank">@$1</a>&nbsp;', $latestTweet);
									echo $latestTweet;
									?>
								</span>
								<?php
									$twitterTime = strtotime( $tweet->created_at );
									$timeAgo = human_time_diff( $twitterTime, current_time( 'timestamp' ) ) . ' ' . esc_html__( 'ago', 'hbthemes' );
								?>
							</li>
							<span class="tweet-time">
								<a href="https://twitter.com/<?php echo $tweet->user->screen_name; ?>/statuses/<?php echo $tweet->id_str; ?>">
									<i class="hb-moon-twitter"></i><?php echo $timeAgo; ?>
								</a>
							</span>
						<?php endforeach; ?>
					</ul>
				</div>

				<p>
					<?php _e('Follow ', 'hbthemes'); ?>
					<a href="https://twitter.com/<?php echo $tweet->user->screen_name; ?>" target="_blank">@<?php echo $tweet->user->screen_name; ?></a>
				</p>
			<?php }

			echo $after_widget;

		} else {
			echo '<p>'. __('[Twitter Widget Error] You need to authenticate your Twitter App first. Go to Highend Options > Social Links. <a href="https://documentation.hb-themes.com/highend/#twitter">Read the documentation</a> to find out more.', 'hbthemes') .'</p>';
		}
	}
	
	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 *
	 * @since 1.0.0
	 * @param array $new_instance An array of new settings as submitted by the admin.
	 * @param array $old_instance An array of the previous settings.
	 * @return array The validated and (if necessary) amended settings
	 */
	function update( $new_instance, $old_instance ) {

		$instance 				= $old_instance;
		$instance['title'] 		= strip_tags( $new_instance['title'] );
		$instance['username'] 	= strip_tags( $new_instance['username'] );
		$instance['skin'] 		= $new_instance['skin'];
		$instance['count'] 		= (int) $new_instance['count'];

		return $instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @since 1.0.0
	 * @param array $instance An array of the current settings for this widget.
	 * @return void
	 */
	function form( $instance ) {

		$title 		= isset( $instance['title'] ) 		? esc_attr( $instance['title'] ) : '';
		$username 	= isset( $instance['username'] ) 	? esc_attr( $instance['username'] ) : '';
		$skin 		= isset( $instance['skin'] ) 		? esc_attr( $instance['skin'] ) : 'light';
		$count 		= isset( $instance['count'] ) 		? absint( $instance['count'] ) : 1;
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>">Username:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo $username; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'skin' ); ?>">Skin:</label>
			<select name="<?php echo $this->get_field_name( 'skin' ); ?>" id="<?php echo $this->get_field_id( 'skin' ); ?>" class="widefat">
				<option value="dark"<?php selected( $skin, 'dark');?>>Dark</option>
				<option value="light"<?php selected( $skin, 'light');?>>Light</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>">Count</label>
			<input id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo $count; ?>" size="3" />
		</p>
	
		<small><?php _e( 'You have to fill in your Twitter Info in Highend Options > Social Links > Twitter API Settings, in order to authenticate.', 'hbthemes' ); ?></small>
	<?php
	}
}