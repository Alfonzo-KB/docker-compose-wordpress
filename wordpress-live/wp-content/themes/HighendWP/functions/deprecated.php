<?php
/**
 * Deprecated functions to be removed in a future version.
 *
 * @package Highend
 * @since   3.5.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'hb_get_user_socials' ) ) {
	/**
	 * Retrieve user social network links from profile.
	 * 
	 * @deprecated 3.5.0
	 */
	function hb_get_user_socials( $user_id ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_get_user_socials is deprecated since Highend version 3.5.0. Use highend_get_user_social_links instead.', E_USER_DEPRECATED );
		}

		return highend_get_user_social_links( $user_id );
	}
}

if ( ! function_exists( 'hb_module_enabled' ) ) {
	/**
	 * Check if a module is enabled.
	 * 
	 * @deprecated 3.5.0
	 * @param string $module Module name
	 */
	function hb_module_enabled( $module_name ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_module_enabled is deprecated since Highend version 3.5.0. Use highend_is_module_enabled instead.', E_USER_DEPRECATED );
		}

		return highend_is_module_enabled( $module_name );
	}
}

if ( ! function_exists( 'hb_resize' ) ) {
	/**
	 * Deprecated resizing function.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_resize( $attach_id = null, $url = null, $width = NULL, $height = NULL, $crop = true, $retina = false ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_resize is deprecated since Highend version 3.5.0. Use highend_resize instead.', E_USER_DEPRECATED );
		}

		$url = $attach_id ? $attach_id : $url;

		if ( ! $url ) {
			return;
		}

		return highend_resize( $url, $width, $height, $crop );
	}
}

if ( ! function_exists( 'get_image_dimensions' ) ) {
	/**
	 * Get image dimension based on orientation, ratio and width.
	 *
	 * @deprecated 3.5.0
	 */
	function get_image_dimensions( $orientation = 'landscape', $ratio = 'ratio1', $width = 600 ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method get_image_dimensions is deprecated since Highend version 3.5.0. Use highend_get_image_dimensions instead.', E_USER_DEPRECATED );
		}

		return highend_get_image_dimensions( $orientation, $ratio, $width );
	}
}

if ( ! function_exists( 'hb_pagination_standard' ) ) {
	/**
	 * Display standard WP pagination.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_pagination_standard( $pages = '', $range = 4, $query = null ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_pagination_standard is deprecated since Highend version 3.5.0. Use highend_pagination_standard instead.', E_USER_DEPRECATED );
		}

		return highend_pagination_standard();
	}
}

if ( ! function_exists( 'hb_testimonial_box' ) ) {
	/**
	 * Display Testimonial boxed style.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_testimonial_box( $post_id ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_testimonial_box is deprecated since Highend version 3.5.0. Use highend_testimonial_box instead.', E_USER_DEPRECATED );
		}

		return highend_testimonial_box( $post_id );
	}
}

if ( ! function_exists( 'hb_testimonial_quote' ) ) {
	/**
	 * Display Testimonial quote style.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_testimonial_quote( $post_id ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_testimonial_quote is deprecated since Highend version 3.5.0. Use highend_testimonial_quote instead.', E_USER_DEPRECATED );
		}

		return highend_testimonial_quote( $post_id );
	}
}

if ( ! function_exists( 'hb_team_member_box' ) ) {
	/**
	 * Display Team Member box.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_team_member_box( $post_id , $style = '', $excerpt_length = 20 ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_team_member_box is deprecated since Highend version 3.5.0. Use highend_team_member_box instead.', E_USER_DEPRECATED );
		}

		return highend_team_member_box(
			array(
				'style'          => $style,
				'excerpt_length' => $excerpt_length
			)
		);
	}
}

if ( ! function_exists( 'hb_color' ) ) {
	/**
	 * Convert hexdec color string to rgb(a) string.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_color( $color, $alpha ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_color is deprecated since Highend version 3.5.0. Use highend_hex2rgba instead.', E_USER_DEPRECATED );
		}

		return highend_hex2rgba( $color, $alpha );
	}
}

if ( ! function_exists( 'hb_darken_color' ) ) {
	/**
	 * Adjust color brightness.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_darken_color( $color, $steps ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_darken_color is deprecated since Highend version 3.5.0. Use highend_darken_color instead.', E_USER_DEPRECATED );
		}

		return highend_darken_color( $color, $steps );
	}
}

if ( ! function_exists( 'hb_nl2p' ) ) {
	/**
	 * Convert line break (new line) to paragraphs.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_nl2p( $string, $line_breaks = false, $xml = true ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_nl2p is deprecated since Highend version 3.5.0. Use highend_nl2p instead.', E_USER_DEPRECATED );
		}

		return highend_nl2p( $string, $line_breaks, $xml );
	}
}

if ( ! function_exists( 'hb_mobile_menu' ) ) {
	/**
	 * Display Mobile (Hamburger) menu.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_mobile_menu() {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_mobile_menu is deprecated since Highend version 3.5.0. Use highend_mobile_menu instead.', E_USER_DEPRECATED );
		}

		return highend_mobile_menu();
	}
}

if ( ! function_exists( 'hb_time_ago' ) ) {
	/**
	 * Determines the difference between $time and current time. 
	 * The difference is returned in a human readable format such as "1 hour", "5 mins", "2 days".
	 *
	 * @deprecated 3.5.0
	 * 
	 * @param string $time Timestamp.
	 */
	function hb_time_ago( $time ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_time_ago is deprecated since Highend version 3.5.0. Use human_time_diff instead.', E_USER_DEPRECATED );
		}

		return human_time_diff( $time, current_time( 'timestamp' ) ) . ' ' . esc_html__( 'ago', 'hbthemes' );
	}
}

if ( ! function_exists( 'hb_get_comment_excerpt' ) ) {
	/**
	 * Get comment excerpt.
	 *
	 * @deprecated 3.5.0
	 * 
	 * @param int $comment_ID Comment ID.
	 * @param int $num_words  Number of words in excerpt.
	 */
	function hb_get_comment_excerpt( $comment_ID = 0, $num_words = 20 ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_get_comment_excerpt is deprecated since Highend version 3.5.0. Use get_comment_excerpt with filtered content instead.', E_USER_DEPRECATED );
		}

		return;
	}
}

if ( ! function_exists( 'hb_get_excerpt' ) ) {
	/**
	 * Get short excerpt based on character count.
	 *
	 * @deprecated 3.5.0
	 * 
	 * @param int $comment_ID Comment ID.
	 * @param int $num_words  Number of words in excerpt.
	 */
	function hb_get_excerpt( $text, $len ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_get_excerpt is deprecated since Highend version 3.5.0. Use highend_get_short_excerpt instead.', E_USER_DEPRECATED );
		}

		return highend_get_short_excerpt( $text, $len );
	}
}

if ( ! function_exists( 'hb_is_maintenance' ) ) {
	/**
	 * Check if a maintenance mode is enabled.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_is_maintenance() {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_is_maintenance is deprecated since Highend version 3.5.0. Use highend_is_maintenance instead.', E_USER_DEPRECATED );
		}

		return highend_is_maintenance();
	}
}

if ( ! function_exists( 'hb_seo_plugin_installed' ) ) {
	/**
	 * Check if SEO plugin is installed & activated.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_seo_plugin_installed() {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_seo_plugin_installed is deprecated since Highend version 3.5.0. Use highend_is_seo_plugin_installed instead.', E_USER_DEPRECATED );
		}

		return highend_is_seo_plugin_installed();
	}
}

if ( ! function_exists( 'hb_generate_dynamic_css' ) ) {
	/**
	 * Generate dynamic CSS.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_generate_dynamic_css() {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_generate_dynamic_css is deprecated since Highend version 3.5.0.', E_USER_DEPRECATED );
		}

		return;
	}
}

if ( ! function_exists( 'hb_dynamic_styles_output' ) ) {
	/**
	 * Print generated dynamic CSS.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_dynamic_styles_output() {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_dynamic_styles_output is deprecated since Highend version 3.5.0.', E_USER_DEPRECATED );
		}

		return;
	}
}

if ( ! function_exists( 'hb_simple_minify_css' ) ) {
	/**
	 * CSS minification.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_simple_minify_css() {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_simple_minify_css is deprecated since Highend version 3.5.0.', E_USER_DEPRECATED );
		}

		return;
	}
}

if ( ! function_exists( 'hb_clean_cache' ) ) {
	/**
	 * Clean cache.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_clean_cache() {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_clean_cache is deprecated since Highend version 3.5.0.', E_USER_DEPRECATED );
		}

		return;
	}
}

if ( ! function_exists( 'hb_pagination_ajax' ) ) {
	/**
	 * Ajax pagination.
	 *
	 * @deprecated 3.5.0
	 */
	function hb_pagination_ajax( $loop_file ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_pagination_ajax is deprecated since Highend version 3.5.0. Use highend_pagination_ajax instead.', E_USER_DEPRECATED );
		}

		return highend_pagination_ajax( $loop_file );
	}
}

if ( ! function_exists( 'hb_countdown' ) ) {
	/**
	 * Countdown shortcode.
	 *
	 * @deprecated 3.6.3
	 */
	function hb_countdown( $params = array() ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_countdown is deprecated since Highend version 3.6.3. Use highend_countdown_shortcode instead.', E_USER_DEPRECATED );
		}

		return highend_countdown_shortcode( $params );
	}
}

if ( ! function_exists( 'hb_skill_shortcode' ) ) {
	/**
	 * Skill shortcode.
	 *
	 * @deprecated 3.6.3
	 */
	function hb_skill_shortcode( $params = array() ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_skill_shortcode is deprecated since Highend version 3.6.3. Use highend_skill_shortcode instead.', E_USER_DEPRECATED );
		}

		return highend_skill_shortcode( $params );
	}
}

if ( ! function_exists( 'hb_faq_shortcode' ) ) {
	/**
	 * FAQ shortcode.
	 *
	 * @deprecated 3.6.5
	 */
	function hb_faq_shortcode( $params = array() ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_faq_shortcode is deprecated since Highend version 3.6.5. Use highend_faq_shortcode instead.', E_USER_DEPRECATED );
		}

		return highend_faq_shortcode( $params );
	}
}

if ( ! function_exists( 'hx_custom_fields' ) ) {
	/**
	 * Custom comment fields.
	 *
	 * @deprecated 3.7
	 */
	function hx_custom_fields( $fields ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hx_custom_fields is deprecated since Highend version 3.7. Use highend_custom_fields instead.', E_USER_DEPRECATED );
		}

		return highend_custom_fields( $fields );
	}
}

if ( ! function_exists( 'hb_search_filter' ) ) {
	/**
	 * Search query.
	 *
	 * @deprecated 3.7
	 */
	function hb_search_filter( $query ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_search_filter is deprecated since Highend version 3.7. Use highend_search_filter instead.', E_USER_DEPRECATED );
		}

		return highend_search_filter( $query );
	}
}

if ( ! function_exists( 'hb_options' ) ) {
	/**
	 * Options.
	 *
	 * @deprecated 3.7
	 */
	function hb_options( $name, $default = '' ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_options is deprecated since Highend version 3.7. Use highend_option instead.', E_USER_DEPRECATED );
		}

		return highend_option( $name, $default = '' );
	}
}

if ( ! function_exists( 'hb_teaser_shortcode' ) ) {
	/**
	 * Teaser shortcode.
	 *
	 * @deprecated 3.7.3
	 */
	function hb_teaser_shortcode( $params = array(), $content = null ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_teaser_shortcode is deprecated since Highend version 37.3. Use highend_teaser_shortcode instead.', E_USER_DEPRECATED );
		}

		return highend_teaser_shortcode( $params, $content );
	}
}

if ( ! function_exists( 'hb_owl_blog_carousel_shortcode' ) ) {
	/**
	 * Blog Carousel shortcode.
	 *
	 * @deprecated 3.7.3
	 */
	function hb_owl_blog_carousel_shortcode( $params = array() ) {

		if ( highend_display_notices() ) {
			trigger_error( 'Method hb_owl_blog_carousel_shortcode is deprecated since Highend version 3.7.3. Use highend_blog_carousel_shortcode instead.', E_USER_DEPRECATED );
		}

		return highend_blog_carousel_shortcode( $params );
	}
}
