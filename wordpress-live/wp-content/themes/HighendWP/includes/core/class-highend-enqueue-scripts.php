<?php
/**
 * Enqueue and register scripts and styles.
 *
 * @package Highend
 * @since 3.7
 */

/**
 * Enqueue and register scripts and styles.
 *
 * @since 3.5.0
 */
function highend_enqueues() {

	// Main stylesheet.
	wp_enqueue_style(
		'highend-style',
		get_parent_theme_file_uri() . '/style.css',
		false,
		HIGHEND_VERSION,
		'all'
	);

	// Responsive stylesheet.
	if ( highend_option( 'hb_responsive' ) ) {
		wp_enqueue_style(
			'highend_responsive',
			HBTHEMES_URI . '/assets/css/responsive.css',
			false,
			HIGHEND_VERSION,
			'all'
		);
	}

	// Icons.
	wp_enqueue_style(
		'highend_icomoon',
		HBTHEMES_URI . '/assets/css/icons.css',
		false,
		HIGHEND_VERSION,
		'all'
	);

	// Main script.
	wp_enqueue_script(
		'highend_scripts',
		HBTHEMES_URI . '/assets/js/scripts.js',
		array( 'jquery' ),
		HIGHEND_VERSION,
		true
	);

	// Countdown JS.
	wp_register_script(
		'highend-countdown-js',
		HBTHEMES_URI . '/assets/js/jquery.countdown.js',
		array( 'jquery' ),
		HIGHEND_VERSION,
		true
	);

	// PrettyPhoto JS.
	wp_register_script(
		'highend-prettyphoto-js',
		HBTHEMES_URI . '/assets/js/jquery.prettyPhoto.js',
		array( 'jquery' ),
		HIGHEND_VERSION,
		true
	);

	if ( highend_is_module_enabled( 'hb_module_prettyphoto' ) ) {
		wp_enqueue_script( 'highend-prettyphoto-js' );
	}

	// jQuery Pace JS.
	if ( 'ytube-like' === highend_option( 'hb_queryloader' ) ) {
		wp_enqueue_script(
			'highend-jquery-pace-js',
			HBTHEMES_URI . '/assets/js/jquery.pace.js',
			array( 'jquery' ),
			HIGHEND_VERSION,
			true
		);
	}

	if ( ! highend_is_maintenance() ) {

		// Google jsapi.
		wp_register_script(
			'highend-google-jsapi',
			'//www.google.com/jsapi',
			null,
			HIGHEND_VERSION,
			true
		);

		wp_register_script(
			'highend-google-map',
			HBTHEMES_URI . '/assets/js/map.js',
			array( 'jquery', 'highend-google-jsapi' ),
			HIGHEND_VERSION,
			true
		);

		wp_enqueue_script( 'highend_flexslider', HBTHEMES_URI . '/assets/js/jquery.flexslider.js', array( 'jquery' ), HIGHEND_VERSION, true );
		wp_enqueue_script( 'highend_validate', HBTHEMES_URI . '/assets/js/jquery.validate.js', array( 'jquery' ), HIGHEND_VERSION, true );
		wp_enqueue_script( 'highend_carousel', HBTHEMES_URI . '/assets/js/responsivecarousel.min.js', array( 'jquery' ), HIGHEND_VERSION, true );
		wp_enqueue_script( 'highend_owl_carousel', HBTHEMES_URI . '/assets/js/jquery.owl.carousel.min.js', array( 'jquery' ), HIGHEND_VERSION, true );

		// Easy Char.
		wp_register_script(
			'highend-easychart-js',
			HBTHEMES_URI . '/assets/js/jquery.easychart.js',
			array( 'jquery' ),
			HIGHEND_VERSION,
			true
		);

		if ( highend_option( 'hb_ajax_search' ) ) {
			wp_enqueue_script( 'jquery-ui-autocomplete' );
		}

		if ( 'hb-bokeh-effect' === vp_metabox( 'featured_section.hb_featured_section_effect' ) ) {
			wp_enqueue_script( 'highend_fs_effects', HBTHEMES_URI . '/assets/js/canvas-effects.js', array( 'jquery' ), HIGHEND_VERSION, true );
		} elseif ( 'hb-clines-effect' === vp_metabox( 'featured_section.hb_featured_section_effect' ) ) {
			wp_enqueue_script( 'highend_cl_effects', HBTHEMES_URI . '/assets/js/canvas-lines.js', array( 'jquery' ), HIGHEND_VERSION, true );
		}

		wp_localize_script( 'highend-google-map', 'hb_gmap', highend_map_json() );
	}

	if ( highend_is_page_template( 'presentation-fullwidth' ) ) {
		wp_enqueue_script( 'highend_fullpage', HBTHEMES_URI . '/assets/js/jquery.fullpage.js', array( 'jquery' ), HIGHEND_VERSION, true );
	}

	wp_enqueue_script( 'highend_jquery_custom', HBTHEMES_URI . '/assets/js/jquery.custom.js', array( 'jquery' ), HIGHEND_VERSION, true );

	$highend_vars = array(
		'ajaxurl'              => admin_url( 'admin-ajax.php' ),
		'nonce'                => wp_create_nonce( 'highend_nonce' ),
		'paged'                => get_query_var( 'paged' ) ? get_query_var( 'paged' ) + 1 : 2,
		'search_header'        => intval( highend_option( 'hb_search_in_menu' ) ),
		'cart_url'             => '',
		'cart_count'           => '',
		'responsive'           => highend_option( 'hb_responsive' ),
		'header_height'        => highend_option( 'hb_regular_header_height' ),
		'sticky_header_height' => highend_option( 'hb_sticky_header_height' ),
		'texts'                => array(
			'load-more'     => esc_html__( 'Load More Posts', 'hbthemes' ),
			'no-more-posts' => esc_html__( 'No More Posts', 'hbthemes' ),
			'day'           => esc_html__( 'day', 'hbthemes' ),
			'days'          => esc_html__( 'days', 'hbthemes' ),
			'hour'          => esc_html__( 'hour', 'hbthemes' ),
			'hours'         => esc_html__( 'hours', 'hbthemes' ),
			'minute'        => esc_html__( 'minute', 'hbthemes' ),
			'minutes'       => esc_html__( 'minutes', 'hbthemes' ),
			'second'        => esc_html__( 'second', 'hbthemes' ),
			'seconds'       => esc_html__( 'seconds', 'hbthemes' ),
		),
	);
	$highend_vars = apply_filters( 'highend_custom_js_localized', $highend_vars );

	wp_localize_script( 'highend_jquery_custom', 'highend_vars', $highend_vars );

	if ( is_singular() && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Add additional theme styles.
	do_action( 'highend_enqueue_scripts' );
	
}
add_action( 'wp_enqueue_scripts', 'highend_enqueues' );

/**
 * Skip link focus fix for IE11.
 *
 * @since 3.7
 *
 * @return void
 */
function highend_skip_link_focus_fix() {
	?>
	<script>
	!function(){var e=-1<navigator.userAgent.toLowerCase().indexOf("webkit"),t=-1<navigator.userAgent.toLowerCase().indexOf("opera"),n=-1<navigator.userAgent.toLowerCase().indexOf("msie");(e||t||n)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var e,t=location.hash.substring(1);/^[A-z0-9_-]+$/.test(t)&&(e=document.getElementById(t))&&(/^(?:a|select|input|button|textarea)$/i.test(e.tagName)||(e.tabIndex=-1),e.focus())},!1)}();
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'highend_skip_link_focus_fix' );