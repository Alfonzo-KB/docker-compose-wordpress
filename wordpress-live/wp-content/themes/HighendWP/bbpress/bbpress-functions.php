<?php

/**
 * Functions of bbPress's Default theme
 *
 * @package bbPress
 * @subpackage BBP_Theme_Compat
 * @since bbPress (r3732)
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/** Theme Setup ***************************************************************/

if ( !class_exists( 'BBP_Default' ) ) :

/**
 * Loads bbPress Default Theme functionality
 *
 * This is not a real theme by WordPress standards, and is instead used as the
 * fallback for any WordPress theme that does not have bbPress templates in it.
 *
 * To make your custom theme bbPress compatible and customize the templates, you
 * can copy these files into your theme without needing to merge anything
 * together; bbPress should safely handle the rest.
 *
 * See @link BBP_Theme_Compat() for more.
 *
 * @since bbPress (r3732)
 *
 * @package bbPress
 * @subpackage BBP_Theme_Compat
 */
class BBP_Default extends BBP_Theme_Compat {

	/** Functions *************************************************************/

	/**
	 * The main bbPress (Default) Loader
	 *
	 * @since bbPress (r3732)
	 *
	 * @uses BBP_Default::setup_globals()
	 * @uses BBP_Default::setup_actions()
	 */
	public function __construct( $properties = array() ) {

		parent::__construct( bbp_parse_args( $properties, array(
			'id'      => 'default',
			'name'    => __( 'bbPress Default', 'hbthemes' ),
			'version' => bbp_get_version(),
			'dir'     => trailingslashit( bbpress()->themes_dir . 'default' ),
			'url'     => trailingslashit( bbpress()->themes_url . 'default' ),
		), 'default_theme' ) );

		$this->setup_actions();
	}

	/**
	 * Setup the theme hooks
	 *
	 * @since bbPress (r3732)
	 * @access private
	 *
	 * @uses add_filter() To add various filters
	 * @uses add_action() To add various actions
	 */
	private function setup_actions() {

		/** Scripts ***********************************************************/

		add_action( 'bbp_enqueue_scripts',         array( $this, 'enqueue_styles'          ) ); // Enqueue theme CSS
		add_action( 'bbp_enqueue_scripts',         array( $this, 'enqueue_scripts'         ) ); // Enqueue theme JS
		add_filter( 'bbp_enqueue_scripts',         array( $this, 'localize_topic_script'   ) ); // Enqueue theme script localization
		add_action( 'bbp_ajax_favorite',           array( $this, 'ajax_favorite'           ) ); // Handles the topic ajax favorite/unfavorite
		add_action( 'bbp_ajax_subscription',       array( $this, 'ajax_subscription'       ) ); // Handles the topic ajax subscribe/unsubscribe
		add_action( 'bbp_ajax_forum_subscription', array( $this, 'ajax_forum_subscription' ) ); // Handles the forum ajax subscribe/unsubscribe

		/** Template Wrappers *************************************************/

		add_action( 'bbp_before_main_content',  array( $this, 'before_main_content'   ) ); // Top wrapper HTML
		add_action( 'bbp_after_main_content',   array( $this, 'after_main_content'    ) ); // Bottom wrapper HTML

		/** Override **********************************************************/

		do_action_ref_array( 'bbp_theme_compat_actions', array( &$this ) );
	}

	/**
	 * Inserts HTML at the top of the main content area to be compatible with
	 * the Twenty Twelve theme.
	 *
	 * @since bbPress (r3732)
	 */
	public function before_main_content() {
	?>

		<div id="bbp-container">
			<div id="bbp-content" role="main">

	<?php
	}

	/**
	 * Inserts HTML at the bottom of the main content area to be compatible with
	 * the Twenty Twelve theme.
	 *
	 * @since bbPress (r3732)
	 */
	public function after_main_content() {
	?>

			</div><!-- #bbp-content -->
		</div><!-- #bbp-container -->

	<?php
	}

	/**
	 * Load the theme CSS
	 *
	 * @since bbPress (r3732)
	 *
	 * @uses wp_enqueue_style() To enqueue the styles
	 */
	public function enqueue_styles() {

		// Setup styles array
		$styles = array();

		// LTR
		$styles['bbp-default'] = array(
			'file'         => 'assets/css/bbpress.css',
			'dependencies' => array()
		);

		// RTL helpers
		if ( is_rtl() ) {
			$styles['bbp-default-rtl'] = array(
				'file'         => 'assets/css/bbpress-rtl.css',
				'dependencies' => array( 'bbp-default' )
			);
		}

		// Filter the scripts
		$styles = apply_filters( 'bbp_default_styles', $styles );

		// Enqueue the styles
		foreach ( $styles as $handle => $attributes ) {
			bbp_enqueue_style( $handle, $attributes['file'], $attributes['dependencies'], $this->version, 'screen' );
		}
	}

	/**
	 * Enqueue the required Javascript files
	 *
	 * @since bbPress (r3732)
	 *
	 * @uses bbp_is_single_forum() To check if it's the forum page
	 * @uses bbp_is_single_topic() To check if it's the topic page
	 * @uses bbp_thread_replies() To check if threaded replies are enabled
	 * @uses bbp_is_single_user_edit() To check if it's the profile edit page
	 * @uses wp_enqueue_script() To enqueue the scripts
	 */
	public function enqueue_scripts() {

		// Setup scripts array
		$scripts = array();

		// Always pull in jQuery for TinyMCE shortcode usage
		if ( bbp_use_wp_editor() ) {
			$scripts['bbpress-editor'] = array(
				'file'         => 'js/editor.js',
				'dependencies' => array( 'jquery' )
			);
		}

		// Forum-specific scripts
		if ( bbp_is_single_forum() ) {
			$scripts['bbpress-forum'] = array(
				'file'         => 'js/forum.js',
				'dependencies' => array( 'jquery' )
			);
		}

		// Topic-specific scripts
		if ( bbp_is_single_topic() ) {

			// Topic favorite/unsubscribe
			$scripts['bbpress-topic'] = array(
				'file'         => 'js/topic.js',
				'dependencies' => array( 'jquery' )
			);

			// Hierarchical replies
			if ( bbp_thread_replies() ) {
				$scripts['bbpress-reply'] = array(
					'file'         => 'js/reply.js',
					'dependencies' => array( 'jquery' )
				);
			}
		}

		// User Profile edit
		if ( bbp_is_single_user_edit() ) {
			$scripts['bbpress-user'] = array(
				'file'         => 'js/user.js',
				'dependencies' => array( 'user-query' )
			);
		}

		// Filter the scripts
		$scripts = apply_filters( 'bbp_default_scripts', $scripts );

		// Enqueue the scripts
		foreach ( $scripts as $handle => $attributes ) {
			bbp_enqueue_script( $handle, $attributes['file'], $attributes['dependencies'], $this->version, 'screen' );
		}
	}

	/**
	 * Load localizations for topic script
	 *
	 * These localizations require information that may not be loaded even by init.
	 *
	 * @since bbPress (r3732)
	 *
	 * @uses bbp_is_single_forum() To check if it's the forum page
	 * @uses bbp_is_single_topic() To check if it's the topic page
	 * @uses is_user_logged_in() To check if user is logged in
	 * @uses bbp_get_current_user_id() To get the current user id
	 * @uses bbp_get_forum_id() To get the forum id
	 * @uses bbp_get_topic_id() To get the topic id
	 * @uses bbp_get_favorites_permalink() To get the favorites permalink
	 * @uses bbp_is_user_favorite() To check if the topic is in user's favorites
	 * @uses bbp_is_subscriptions_active() To check if the subscriptions are active
	 * @uses bbp_is_user_subscribed() To check if the user is subscribed to topic
	 * @uses bbp_get_topic_permalink() To get the topic permalink
	 * @uses wp_localize_script() To localize the script
	 */
	public function localize_topic_script() {

		// Single forum
		if ( bbp_is_single_forum() ) {
			wp_localize_script( 'bbpress-forum', 'bbpForumJS', array(
				'bbp_ajaxurl'        => bbp_get_ajax_url(),
				'generic_ajax_error' => __( 'Something went wrong. Refresh your browser and try again.', 'hbthemes' ),
				'is_user_logged_in'  => is_user_logged_in(),
				'subs_nonce'         => wp_create_nonce( 'toggle-subscription_' . get_the_ID() )
			) );

		// Single topic
		} elseif ( bbp_is_single_topic() ) {
			wp_localize_script( 'bbpress-topic', 'bbpTopicJS', array(
				'bbp_ajaxurl'        => bbp_get_ajax_url(),
				'generic_ajax_error' => __( 'Something went wrong. Refresh your browser and try again.', 'hbthemes' ),
				'is_user_logged_in'  => is_user_logged_in(),
				'fav_nonce'          => wp_create_nonce( 'toggle-favorite_' .     get_the_ID() ),
				'subs_nonce'         => wp_create_nonce( 'toggle-subscription_' . get_the_ID() )
			) );
		}
	}

	/**
	 * AJAX handler to Subscribe/Unsubscribe a user from a forum
	 *
	 * @since bbPress (r5155)
	 *
	 * @uses bbp_is_subscriptions_active() To check if the subscriptions are active
	 * @uses bbp_is_user_logged_in() To check if user is logged in
	 * @uses bbp_get_current_user_id() To get the current user id
	 * @uses current_user_can() To check if the current user can edit the user
	 * @uses bbp_get_forum() To get the forum
	 * @uses wp_verify_nonce() To verify the nonce
	 * @uses bbp_is_user_subscribed() To check if the forum is in user's subscriptions
	 * @uses bbp_remove_user_subscriptions() To remove the forum from user's subscriptions
	 * @uses bbp_add_user_subscriptions() To add the forum from user's subscriptions
	 * @uses bbp_ajax_response() To return JSON
	 */
	public function ajax_forum_subscription() {

		// Bail if subscriptions are not active
		if ( ! bbp_is_subscriptions_active() ) {
			bbp_ajax_response( false, __( 'Subscriptions are no longer active.', 'hbthemes' ), 300 );
		}

		// Bail if user is not logged in
		if ( ! is_user_logged_in() ) {
			bbp_ajax_response( false, __( 'Please login to subscribe to this forum.', 'hbthemes' ), 301 );
		}

		// Get user and forum data
		$user_id = bbp_get_current_user_id();
		$id      = intval( $_POST['id'] );

		// Bail if user cannot add favorites for this user
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			bbp_ajax_response( false, __( 'You do not have permission to do this.', 'hbthemes' ), 302 );
		}

		// Get the forum
		$forum = bbp_get_forum( $id );

		// Bail if forum cannot be found
		if ( empty( $forum ) ) {
			bbp_ajax_response( false, __( 'The forum could not be found.', 'hbthemes' ), 303 );
		}

		// Bail if user did not take this action
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'toggle-subscription_' . $forum->ID ) ) {
			bbp_ajax_response( false, __( 'Are you sure you meant to do that?', 'hbthemes' ), 304 );
		}

		// Take action
		$status = bbp_is_user_subscribed( $user_id, $forum->ID ) ? bbp_remove_user_subscription( $user_id, $forum->ID ) : bbp_add_user_subscription( $user_id, $forum->ID );

		// Bail if action failed
		if ( empty( $status ) ) {
			bbp_ajax_response( false, __( 'The request was unsuccessful. Please try again.', 'hbthemes' ), 305 );
		}

		// Put subscription attributes in convenient array
		$attrs = array(
			'forum_id' => $forum->ID,
			'user_id'  => $user_id
		);

		// Action succeeded
		bbp_ajax_response( true, bbp_get_forum_subscription_link( $attrs, $user_id, false ), 200 );
	}

	/**
	 * AJAX handler to add or remove a topic from a user's favorites
	 *
	 * @since bbPress (r3732)
	 *
	 * @uses bbp_is_favorites_active() To check if favorites are active
	 * @uses bbp_is_user_logged_in() To check if user is logged in
	 * @uses bbp_get_current_user_id() To get the current user id
	 * @uses current_user_can() To check if the current user can edit the user
	 * @uses bbp_get_topic() To get the topic
	 * @uses wp_verify_nonce() To verify the nonce & check the referer
	 * @uses bbp_is_user_favorite() To check if the topic is user's favorite
	 * @uses bbp_remove_user_favorite() To remove the topic from user's favorites
	 * @uses bbp_add_user_favorite() To add the topic from user's favorites
	 * @uses bbp_ajax_response() To return JSON
	 */
	public function ajax_favorite() {

		// Bail if favorites are not active
		if ( ! bbp_is_favorites_active() ) {
			bbp_ajax_response( false, __( 'Favorites are no longer active.', 'hbthemes' ), 300 );
		}

		// Bail if user is not logged in
		if ( ! is_user_logged_in() ) {
			bbp_ajax_response( false, __( 'Please login to make this topic a favorite.', 'hbthemes' ), 301 );
		}

		// Get user and topic data
		$user_id = bbp_get_current_user_id();
		$id      = !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;

		// Bail if user cannot add favorites for this user
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			bbp_ajax_response( false, __( 'You do not have permission to do this.', 'hbthemes' ), 302 );
		}

		// Get the topic
		$topic = bbp_get_topic( $id );

		// Bail if topic cannot be found
		if ( empty( $topic ) ) {
			bbp_ajax_response( false, __( 'The topic could not be found.', 'hbthemes' ), 303 );
		}

		// Bail if user did not take this action
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'toggle-favorite_' . $topic->ID ) ) {
			bbp_ajax_response( false, __( 'Are you sure you meant to do that?', 'hbthemes' ), 304 );
		}

		// Take action
		$status = bbp_is_user_favorite( $user_id, $topic->ID ) ? bbp_remove_user_favorite( $user_id, $topic->ID ) : bbp_add_user_favorite( $user_id, $topic->ID );

		// Bail if action failed
		if ( empty( $status ) ) {
			bbp_ajax_response( false, __( 'The request was unsuccessful. Please try again.', 'hbthemes' ), 305 );
		}

		// Put subscription attributes in convenient array
		$attrs = array(
			'topic_id' => $topic->ID,
			'user_id'  => $user_id
		);

		// Action succeeded
		bbp_ajax_response( true, bbp_get_user_favorites_link( $attrs, $user_id, false ), 200 );
	}

	/**
	 * AJAX handler to Subscribe/Unsubscribe a user from a topic
	 *
	 * @since bbPress (r3732)
	 *
	 * @uses bbp_is_subscriptions_active() To check if the subscriptions are active
	 * @uses bbp_is_user_logged_in() To check if user is logged in
	 * @uses bbp_get_current_user_id() To get the current user id
	 * @uses current_user_can() To check if the current user can edit the user
	 * @uses bbp_get_topic() To get the topic
	 * @uses wp_verify_nonce() To verify the nonce
	 * @uses bbp_is_user_subscribed() To check if the topic is in user's subscriptions
	 * @uses bbp_remove_user_subscriptions() To remove the topic from user's subscriptions
	 * @uses bbp_add_user_subscriptions() To add the topic from user's subscriptions
	 * @uses bbp_ajax_response() To return JSON
	 */
	public function ajax_subscription() {

		// Bail if subscriptions are not active
		if ( ! bbp_is_subscriptions_active() ) {
			bbp_ajax_response( false, __( 'Subscriptions are no longer active.', 'hbthemes' ), 300 );
		}

		// Bail if user is not logged in
		if ( ! is_user_logged_in() ) {
			bbp_ajax_response( false, __( 'Please login to subscribe to this topic.', 'hbthemes' ), 301 );
		}

		// Get user and topic data
		$user_id = bbp_get_current_user_id();
		$id      = intval( $_POST['id'] );

		// Bail if user cannot add favorites for this user
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			bbp_ajax_response( false, __( 'You do not have permission to do this.', 'hbthemes' ), 302 );
		}

		// Get the topic
		$topic = bbp_get_topic( $id );

		// Bail if topic cannot be found
		if ( empty( $topic ) ) {
			bbp_ajax_response( false, __( 'The topic could not be found.', 'hbthemes' ), 303 );
		}

		// Bail if user did not take this action
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'toggle-subscription_' . $topic->ID ) ) {
			bbp_ajax_response( false, __( 'Are you sure you meant to do that?', 'hbthemes' ), 304 );
		}

		// Take action
		$status = bbp_is_user_subscribed( $user_id, $topic->ID ) ? bbp_remove_user_subscription( $user_id, $topic->ID ) : bbp_add_user_subscription( $user_id, $topic->ID );

		// Bail if action failed
		if ( empty( $status ) ) {
			bbp_ajax_response( false, __( 'The request was unsuccessful. Please try again.', 'hbthemes' ), 305 );
		}

		// Put subscription attributes in convenient array
		$attrs = array(
			'topic_id' => $topic->ID,
			'user_id'  => $user_id
		);

		// Action succeeded
		bbp_ajax_response( true, bbp_get_user_subscribe_link( $attrs, $user_id, false ), 200 );
	}
}
new BBP_Default();
endif;

/** 
 * Custom functions to override BBPress default functionalities
 * ============================================================= */

/** 
 * Modify bbp_get_time_since() output 
 * -------------------------------------------------------------------------------------------*/
add_filter('bbp_get_time_since', 'aq_get_time_since', 10, 3);
function aq_get_time_since( $output, $older_date, $newer_date ) {
	
	// Setup the strings
	$unknown_text   = apply_filters( 'bbp_core_time_since_unknown_text',   __( 'sometime',  'hbthemes' ) );
	$right_now_text = apply_filters( 'bbp_core_time_since_right_now_text', __( 'right now', 'hbthemes' ) );
	$ago_text       = apply_filters( 'bbp_core_time_since_ago_text',       __( '%s ago',    'hbthemes' ) );

	// array of time period chunks
	$chunks = array(
		array( 60 * 60 * 24 * 365 , __( 'year',   'hbthemes' ), __( 'years',   'hbthemes' ) ),
		array( 60 * 60 * 24 * 30 ,  __( 'month',  'hbthemes' ), __( 'months',  'hbthemes' ) ),
		array( 60 * 60 * 24 * 7,    __( 'week',   'hbthemes' ), __( 'weeks',   'hbthemes' ) ),
		array( 60 * 60 * 24 ,       __( 'day',    'hbthemes' ), __( 'days',    'hbthemes' ) ),
		array( 60 * 60 ,            __( 'hour',   'hbthemes' ), __( 'hours',   'hbthemes' ) ),
		array( 60 ,                 __( 'minute', 'hbthemes' ), __( 'minutes', 'hbthemes' ) ),
		array( 1,                   __( 'second', 'hbthemes' ), __( 'seconds', 'hbthemes' ) )
	);

	if ( !empty( $older_date ) && !is_numeric( $older_date ) ) {
		$time_chunks = explode( ':', str_replace( ' ', ':', $older_date ) );
		$date_chunks = explode( '-', str_replace( ' ', '-', $older_date ) );
		$older_date  = gmmktime( (int) $time_chunks[1], (int) $time_chunks[2], (int) $time_chunks[3], (int) $date_chunks[1], (int) $date_chunks[2], (int) $date_chunks[0] );
	}

	// $newer_date will equal false if we want to know the time elapsed
	// between a date and the current time. $newer_date will have a value if
	// we want to work out time elapsed between two known dates.
	$newer_date = ( !$newer_date ) ? strtotime( current_time( 'mysql' ) ) : $newer_date;

	// Difference in seconds
	$since = $newer_date - $older_date;

	// Something went wrong with date calculation and we ended up with a negative date.
	if ( 0 > $since ) {
		$output = $unknown_text;

	// We only want to output two chunks of time here, eg:
	//     x years, xx months
	//     x days, xx hours
	// so there's only two bits of calculation below:
	} else {

		// Step one: the first chunk
		for ( $i = 0, $j = count( $chunks ); $i < $j; ++$i ) {
			$seconds = $chunks[$i][0];

			// Finding the biggest chunk (if the chunk fits, break)
			$count = floor( $since / $seconds );
			if ( 0 != $count ) {
				break;
			}
		}

		// If $i iterates all the way to $j, then the event happened 0 seconds ago
		if ( !isset( $chunks[$i] ) ) {
			$output = $right_now_text;

		} else {

			// Set output var
			$output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];

			// No output, so happened right now
			if ( ! (int) trim( $output ) ) {
				$output = $right_now_text;
			}
		}
	}

	return $output;
}



/** 
 * Adds Status to topic title
 * -------------------------------------------------------------------------------------------*/
remove_action('bbp_theme_before_topic_title', 'bbps_modify_title');
add_action('bbp_theme_before_topic_title', 'aq_modify_before_title', 10, 2);
function aq_modify_before_title($title, $topic_id = 0) {
	$topic_id = bbp_get_topic_id( $topic_id );

	$replies = bbp_get_topic_reply_count($topic_id);
	$statuses = array (1,2,3);
	$status_id = get_post_meta( $topic_id, '_bbps_topic_status', true );

	// Let's not override default closed/sticky status
	if(bbp_is_topic_sticky()) {
		echo '<span class="topic-sticky"> [Sticky] </span>';
	} 
	// Let's not override the default statuses
	elseif(!in_array($status_id, $statuses)) {

		if($replies >= 1) {

			echo '<span class="in-progress"> [In Progress] </span>';

		} else {

			echo '<span class="not-resolved"> [Not Resolved] </span>';

		}
	// Default Statuses
	} else {

		if ($status_id == 1) // Not Resolved
			echo '<span class="not-resolved"> [Not Resolved] </span>';

		if ($status_id == 2) // Not Resolved
			echo '<span class="resolved"> [Resolved] </span>';

		if ($status_id == 3) { // Not Support Question (mark as resolved)
			add_post_meta($topic_id, '_bbps_topic_status', 2);
			echo '<span class="resolved"> [Resolved] </span>';
		}
	}
		
}


/** 
 * Display Topic Status
 * -------------------------------------------------------------------------------------------*/
function aq_display_topic_status($topic_id = 0) {

	$topic_id = $topic_id ? $topic_id : bbp_get_topic_id();

	$statuses = array (1,2,3);
	$status_id = get_post_meta( $topic_id, '_bbps_topic_status', true );

	echo '<div class="aq-topic-status">';

	if(bbp_is_topic_sticky()) {

		echo '<span class="sticky">Sticky</span>';

	} elseif(in_array($status_id, $statuses)) {

		if ($status_id == 1) {
			echo '<span class="not-resolved">Not Resolved</span>';
		}
			
		if ($status_id == 2) {
			echo '<span class="resolved">Resolved</span>';
		}

		if ($status_id == 3) {
			echo '<span class="in-progress">In Progress</span>';
		}

	} elseif(bbp_is_topic_closed()) {

		echo '<span class="sticky">Sticky</span>';
		
	} else {

		echo '<span class="in-progress">In Progress</span>';
		
	}

	echo '</div>';

}

/** Disable admin bar completely for non-admin */
if (!function_exists('disableAdminBar')) {
	function disableAdminBar(){
  	remove_action( 'admin_footer', 'wp_admin_bar_render', 1000 ); // for the admin page
    remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 ); // for the front end
    function remove_admin_bar_style_backend() {  // css override for the admin page
      echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
    }
    add_filter('admin_head','remove_admin_bar_style_backend');
    function remove_admin_bar_style_frontend() { // css override for the frontend
      echo '<style type="text/css" media="screen">
      html { margin-top: 0px !important; }
      * html body { margin-top: 0px !important; }
      </style>';
    }
    add_filter('wp_head','remove_admin_bar_style_frontend', 99);
  }
}

if(!current_user_can('manage_options')) {
	add_action('init','disableAdminBar'); // New version
}

/**
 * Remove topic & reply revision log
 * -------------------------------------------------------------------------------------------*/
remove_filter( 'bbp_get_reply_content', 'bbp_reply_content_append_revisions',  1,  2 );
remove_filter( 'bbp_get_topic_content', 'bbp_topic_content_append_revisions',  1,  2 );

/**
 * Custom function from bbp_get_author_link(), returns only author name
 * -------------------------------------------------------------------------------------------*/
function aq_get_author( $post_id = 0 ) {

	// Confirmed topic
	if ( bbp_is_topic( $post_id ) ) {
		return bbp_get_topic_author( $post_id );

	// Confirmed reply
	} elseif ( bbp_is_reply( $post_id ) ) {
		return bbp_get_reply_author( $post_id );

	// Get the post author and proceed
	} else {
		$user_id = get_post_field( 'post_author', $post_id );
	}

	// Neither a reply nor a topic, so could be a revision
	if ( !empty( $post_id ) ) {

		// Assemble some link bits
		$anonymous  = bbp_is_reply_anonymous( $post_id );

		// Add links if not anonymous
		if ( empty( $anonymous ) && bbp_user_has_profile( $user_id ) ) {

			$author_link = get_the_author_meta( 'display_name', $user_id );

		// No links if anonymous
		} else {
			$author_link = join( '&nbsp;', $author_links );
		}

	// No post so link is empty
	} else {
		$author_link = '';
	}

	return $author_link;
}

/** change "search-posts" to other base (optional) */
add_action( 'init', 'wpse21549_init' );
function wpse21549_init() {
    $GLOBALS['wp_rewrite']->search_base = 'search-posts';
}

/** 
 * Change user roles naming
 * -------------------------------------------------------------------------------------------*/
function aq_custom_bbp_roles( $role, $user_id ) {
	if( $role == 'Key Master' )
		return 'Admin';
 	
 	if( $role == 'Participant' )
 		return 'Member';
 	
	return $role;
}
 
add_filter( 'bbp_get_user_display_role', 'aq_custom_bbp_roles', 10, 2 );