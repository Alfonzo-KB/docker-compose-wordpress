<?php
/**
 * Common functions used in backend and frontend of the theme.
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

if ( ! function_exists( 'highend_get_social_networks_array' ) ) :

	/**
	 * Return array of available social networks.
	 * 
	 * @since 3.5.0
	 */
	function highend_get_social_networks_array() {

		$links = array(
			'envelop'     => 'Mail',
			'dribbble'    => 'Dribbble',
			'facebook'    => 'Facebook',
			'flickr'      => 'Flickr',
			'forrst'      => 'Forrst',
			'google-plus' => 'Google Plus',
			'html5'       => 'HTML 5',
			'cloud'       => 'iCloud',
			'lastfm'      => 'LastFM',
			'linkedin'    => 'LinkedIn',
			'paypal'      => 'PayPal',
			'pinterest'   => 'Pinterest',
			'reddit'      => 'Reddit',
			'feed-2'      => 'RSS',
			'skype'       => 'Skype',
			'stumbleupon' => 'StumbleUpon',
			'tumblr'      => 'Tumblr',
			'twitter'     => 'Twitter',
			'vimeo'       => 'Vimeo',
			'wordpress'   => 'WordPress',
			'yahoo'       => 'Yahoo',
			'youtube'     => 'YouTube',
			'github'      => 'Github',
			'yelp'        => 'Yelp',
			'mail'        => 'Mail',
			'instagram'   => 'Instagram',
			'foursquare'  => 'Foursquare',
			'xing'        => 'Xing',
			'vk'          => 'VKontakte',
			'behance'     => 'Behance',
			'twitch'      => 'Twitch',
			'sn500px'     => '500px',
			'weibo'       => 'Weibo',
			'tripadvisor' => 'Trip Advisor',
		);

		return apply_filters( 'highend_social_networks_array', $links );
	}
endif;

if ( ! function_exists( 'highend_is_module_enabled' ) ) :

	/**
	 * Check if a module is enabled.
	 * 
	 * @since 3.5.0
	 * @param string $module Module name
	 */
	function highend_is_module_enabled( $module ) {

		$enabled = true;

		if ( highend_option( 'hb_control_modules' ) && ! highend_option( $module ) ) {
			$enabled = false;
		}

		return $enabled;
	}
endif;

if ( ! function_exists( 'highend_hex2rgba' ) ) :
	/**
	 * Convert hexdec color string to rgb(a) string.
	 *
	 * @since  3.5.0
	 * @param  string           $color Hex color code.
	 * @param  string | boolean $opacity opacity value.
	 * @return string color in rgba format.
	 */
	function highend_hex2rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';

		// Return default if no color provided.
		if ( empty( $color ) ) {
			return $default;
		}

		// Sanitize $color if "#" is provided.
		if ( '#' === $color[0] ) {
			$color = substr( $color, 1 );
		}

		// Check if color has 6 or 3 characters and get values.
		if ( 6 === strlen( $color ) ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( 3 === strlen( $color ) ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		// Convert hexadec to rgb.
		$rgb = array_map( 'hexdec', $hex );

		// Check if opacity is set(rgba or rgb).
		if ( $opacity ) {

			if ( abs( $opacity ) > 1 ) {
				$opacity = 1;
			}

			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}

		// Return rgb(a) color string.
		return $output;
	}
endif;

if ( ! function_exists( 'highend_darken_color' ) ) :
	/**
	 * Adjust color brightness.
	 *
	 * @since  3.5.0
	 * @param  string $color Hex color code.
	 * @param  string $steps opacity value.
	 * @return string color in rgba format.
	 */
	function highend_darken_color( $hex, $steps ) {

		// Steps should be between -255 and 255. Negative = darker, positive = lighter.
		$steps = max( -255, min( 255, $steps ) );

		// Format the hex color string.
		$hex = str_replace( '#', '', $hex );
		if ( 3 === strlen( $hex ) ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr ( $hex, 2, 1 ), 2 );
		}

		// Get decimal values.
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		// Adjust number of steps and keep it inside 0 to 255.
		$r = max( 0, min( 255, $r + $steps ) );
		$g = max( 0, min( 255, $g + $steps ) );  
		$b = max( 0, min( 255, $b + $steps ) );

		// Convert to hex.
		$r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
		$g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
		$b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

		return '#' . $r_hex . $g_hex . $b_hex;
	}
endif;

if ( ! function_exists( 'highend_display_notices' ) ) :
	/**
	 * Display notices.
	 *
	 * @deprecated 3.5.0
	 */
	function highend_display_notices() {
		return defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG || defined( 'WP_DEBUG' ) && WP_DEBUG;
	}
endif;

if ( ! function_exists( 'highend_is_maintenance' ) ) :
	/**
	 * Check if a maintenance mode is enabled.
	 * 
	 * @since 3.5.0
	 */
	function highend_is_maintenance() {

		$is_maintenance = false;

		if ( highend_option( 'hb_enable_maintenance' ) && ( ! is_user_logged_in() || ! current_user_can( 'edit_themes' ) ) ) {
			$is_maintenance = true;
		}

		return apply_filters( 'highend_is_maintenance', $is_maintenance );
	}
endif;

if ( ! function_exists( 'highend_get_the_id' ) ) :
	/**
	 * Get post ID.
	 *
	 * @since  3.6.2
	 * @return string Current post/page ID.
	 */
	function highend_get_the_id() {

		$post_id = 0;

		if ( is_singular() ) {
			$post_id = get_the_ID();
		} elseif ( 'page' === get_option( 'show_on_front' ) ) {
			if ( is_home() ) {
				$post_id = get_option( 'page_for_posts' );
			} elseif ( is_front_page() ) {
				$post_id = get_option( 'page_on_front' );
			}
		}

		return apply_filters( 'highend_get_the_id', $post_id );
	}
endif;

if ( ! function_exists( 'highend_get_the_title' ) ) :
	/**
	 * Get page title. Adds support for non-singular pages.
	 *
	 * @since  3.6.4
	 * @param  int  $post_id Optional, default to 0. Post id.
	 * @param  bool $echo    Optional, default to false. Whether to display or return.
	 * @return string|void  String if $echo parameter is false.
	 */
	function highend_get_the_title( $post_id = 0, $echo = false ) {

		$title = '';

		if ( $post_id || is_singular() ) {

			if ( is_singular( 'faq' ) ) {
				// FAQ single.
				$title = apply_filters( 'highend_single_faq_page_title', esc_html__( 'Frequently Asked Questions', 'hbthemes' ) );
			} elseif ( is_singular( 'hb_testimonials' ) ) {
				// Testimonials single.
				$title = apply_filters( 'highend_single_testimonials_page_title', esc_html__( 'Testimonial', 'hbthemes' ) );
			} else {
				// Metabox title.
				if ( vp_metabox( 'general_settings.hb_page_title_h1', null, $post_id ) ) {
					$title = vp_metabox( 'general_settings.hb_page_title_h1', null, $post_id );
				} else {
					$title = get_the_title();
				}
			}
		} else {

			if ( is_front_page() && is_home() ) {
				// Homepage.
				$title = apply_filters( 'highend_home_page_title', esc_html__( 'Home', 'hbthemes' ) );
			} elseif ( is_home() ) {
				// Blog page.
				$title = apply_filters( 'highend_blog_page_title', get_the_title( get_option( 'page_for_posts', true ) ) );
			} elseif ( is_404() ) {
				// 404 page - title always display.
				$title = apply_filters( 'highend_404_page_title', esc_html__( 'This page doesn&rsquo;t seem to exist.', 'hbthemes' ) );
			} elseif ( is_search() ) {
				// Search page - title always display.
				$title = apply_filters( 'highend_search_page_title', esc_html__( 'Search Results', 'hbthemes' ) );
			} elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
				// Woocommerce product category.
				$title = apply_filters( 'highend_woocommerce_product_category_title', single_cat_title( '', false ) );
			} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
				// Woocommerce.
				$title = woocommerce_page_title( false );
			} else if ( class_exists('bbPress') && bbp_is_forum_archive() ) {
				// BBP forum archive title.
				$title = apply_filters( 'highend_bbp_forum_archive_title', esc_html__( 'Forums', 'hbthemes' ) );
			} elseif ( is_author() ) {
				// Author post archive.
				$title = apply_filters( 'highend_author_page_title', esc_html__( 'Posts by', 'hbthemes' ) . ' ' . esc_html( get_the_author() ) );
			} elseif ( is_category() || is_tag() || is_tax() ) {
				// Category, tag and custom taxonomy archive.
				$title = single_term_title( '', false );
			} elseif ( is_archive() ) {
				// Archive.
				$title = highend_option( 'hb_archives_title' );
			}
		}

		if ( $echo ) {
			echo wp_kses( $title, highend_get_allowed_html_tags() );
		} else {
			return $title;
		}
	}
endif;

if ( ! function_exists( 'highend_get_the_description' ) ) :
	/**
	 * Get page description/subtitle. Adds support for non-singular pages.
	 *
	 * @since  3.6.5
	 * @param  int  $post_id Optional, default to 0. Post id.
	 * @param  bool $echo    Optional, default to false. Whether to display or return.
	 * @return string|void  String if $echo parameter is false.
	 */
	function highend_get_the_description( $post_id = 0, $echo = false ) {

		$description = '';

		if ( $post_id || is_singular() ) {
			// Singular pages.
			$description = vp_metabox( 'general_settings.hb_page_subtitle', null, $post_id );
		} else {

			if ( is_search() ) {
				// Search page - description.
				global $wp_query;

				$found_posts = $wp_query->found_posts;

				if ( $found_posts > 0 ) {
					// Translators: $s number of found results.
					$description = sprintf( _n( '%s result found', '%s results found', $found_posts, 'hbthemes' ), number_format_i18n( $found_posts ) );
				} else {
					$description = esc_html__( 'No results found', 'hbthemes' );
				}

				$description = apply_filters( 'highend_search_page_description', $description );
			} elseif ( is_author() ) {
				$description = '';
			} elseif ( is_day() ) {
				$description = sprintf( esc_html__( 'Daily Archive for %s', 'hbthemes' ), get_the_time( 'F jS, Y' ) );
				$description = apply_filters( 'highend_archive_day_description', $description );
			} elseif ( is_month() ) {
				$description = sprintf( esc_html__( 'Monthly Archive for %s', 'hbthemes' ), get_the_time( 'F, Y' ) );
				$description = apply_filters( 'highend_archive_month_description', $description );
			} elseif ( is_year() ) {
				$description = sprintf( esc_html__( 'Yearly Archive for %s', 'hbthemes' ), get_the_time( 'Y' ) );
				$description = apply_filters( 'highend_archive_year_description', $description );
			}
		}

		if ( $echo ) {
			echo wp_kses( $description, highend_get_allowed_html_tags() );
		} else {
			return $description;
		}
	}
endif;
if ( ! function_exists( 'highend_get_post_meta' ) ) :
	/**
	 * Get metabox values.
	 *
	 * @since  3.6.2
	 * @param  string $key     Meta field ID.
	 * @param  mixed  $default Default value.
	 * @param  mixed  $post_id Post ID or object.
	 * @return mixed           Metabox value.
	 */
	function highend_get_post_meta( $key, $default = null, $post_id = null ) {
		
		if ( is_null( $post_id ) ) {
			$post_id = highend_get_the_id();
		}

		return vp_metabox( $key, $default, $post_id );
	}
endif;

if ( ! function_exists( 'highend_get_prop' ) ) :

	/**
	 * Get a specific property of an array without needing to check if that property exists.
	 *
	 * Provide a default value if you want to return a specific value if the property is not set.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $array   Array from which the property's value should be retrieved.
	 * @param string $prop    Name of the property to be retrieved.
	 * @param string $default Optional. Value that should be returned if the property is not set or empty. Defaults to null.
	 *
	 * @return null|string|mixed The value
	 */
	function highend_get_prop( $array, $prop, $default = null ) {

		if ( ! is_array( $array ) && ! ( is_object( $array ) && $array instanceof ArrayAccess ) ) {
			return $default;
		}

		if ( isset( $array[ $prop ] ) ) {
			$value = $array[ $prop ];
		} else {
			$value = '';
		}

		return empty( $value ) && null !== $default ? $default : $value;
	}
endif;

if ( ! function_exists( 'highend_option' ) ) :
	/**
	 * Returns the value for option.
	 *
	 * @since 3.7
	 *
	 * @param  string $name Option ID.
	 * @param  string $default Default value
	 * @return mixed Option value.
	 */
	function highend_option( $name, $default = '' ) {

		if ( function_exists( 'vp_option' ) ) {
			return apply_filters( 'highend_options_value', vp_option( 'hb_highend_option.' . $name, $default ), $name );
		}
		return;
	}
endif;

if ( ! function_exists( 'highend_default_font_faces' ) ) :
	function highend_default_font_faces() {

		$fonts_path = HBTHEMES_URI . '/assets/css/fonts/';

		$font_faces = array(
			'sourcesanspro-regular-webfont'  => array(
				'font-family'  => 'source_sans_proregular',
				'src_fallback' => 'url("' . $fonts_path . 'sourcesanspro-regular-webfont.eot")',
				'src'          => 'url("' . $fonts_path . 'sourcesanspro-regular-webfont.eot?#iefix") format("embedded-opentype"), url("' . $fonts_path . 'sourcesanspro-regular-webfont.woff2") format("woff2"), url("' . $fonts_path . 'sourcesanspro-regular-webfont.woff") format("woff"), url("' . $fonts_path . 'sourcesanspro-regular-webfont.ttf") format("truetype"), url("' . $fonts_path . 'sourcesanspro-regular-webfont.svg#source_sans_proregular") format("svg")',
				'font-weight'  => 'normal',
				'font-style'   => 'normal',
				'font-display' => 'swap',
			),
			'sourcesanspro-bold-webfont'     => array(
				'font-family'  => 'source_sans_probold',
				'src_fallback' => 'url("' . $fonts_path . 'sourcesanspro-bold-webfont.eot")',
				'src'          => 'url("' . $fonts_path . 'sourcesanspro-bold-webfont.eot?#iefix") format("embedded-opentype"), url("' . $fonts_path . 'sourcesanspro-bold-webfont.woff2") format("woff2"), url("' . $fonts_path . 'sourcesanspro-bold-webfont.woff") format("woff"), url("' . $fonts_path . 'sourcesanspro-bold-webfont.ttf") format("truetype"), url("' . $fonts_path . 'sourcesanspro-bold-webfont.svg#source_sans_probold") format("svg")',
				'font-weight'  => 'normal',
				'font-style'   => 'normal',
				'font-display' => 'swap',
			),
			'SourceSansPro-Semibold' => array(
				'font-family'  => 'source_sans_prosemibold',
				'src_fallback' => 'url("' . $fonts_path . 'SourceSansPro-Semibold.eot")',
				'src'          => 'url("' . $fonts_path . 'SourceSansPro-Semibold.eot?#iefix") format("embedded-opentype"), url("' . $fonts_path . 'SourceSansPro-Semibold.woff2") format("woff2"), url("' . $fonts_path . 'SourceSansPro-Semibold.woff") format("woff"), url("' . $fonts_path . 'SourceSansPro-Semibold.ttf") format("truetype"), url("' . $fonts_path . 'SourceSansPro-Semibold.svg#SourceSansPro-Semibold") format("svg")',
				'font-weight'  => 'normal',
				'font-style'   => 'normal',
				'font-display' => 'swap',
			),
			'Museo500-Regular-webfont'         => array(
				'font-family'  => 'Museo500Regular',
				'src_fallback' => 'url("' . $fonts_path . 'Museo500-Regular-webfont.eot")',
				'src'          => 'url("' . $fonts_path . 'Museo500-Regular-webfont.eot?#iefix") format("embedded-opentype"), url("' . $fonts_path . 'Museo500-Regular-webfont.woff2") format("woff2"), url("' . $fonts_path . 'Museo500-Regular-webfont.woff") format("woff"), url("' . $fonts_path . 'Museo500-Regular-webfont.ttf") format("truetype"), url("' . $fonts_path . 'Museo500-Regular-webfont.svg#Museo300Regular") format("svg")',
				'font-weight'  => 'normal',
				'font-style'   => 'normal',
				'font-display' => 'swap',
			),
		);

		return apply_filters( 'highend_default_font_faces', $font_faces );
	}
endif;

if ( ! function_exists( 'highend_get_allowed_html_tags' ) ) {
	/**
	 * Array of allowed HTML Tags.
	 *
	 * @since 3.7.4
	 * @param string $type predefined HTML tags group name.
	 * @return array, allowed HTML tags.
	 */
	function highend_get_allowed_html_tags( $type = 'post' ) {

		$tags = wp_kses_allowed_html( 'post' );

		$tags = array_merge(
			$tags,
			array(
				'svg'     => array(
					'class'       => true,
					'xmlns'       => true,
					'width'       => true,
					'height'      => true,
					'viewbox'     => true,
					'aria-hidden' => true,
					'role'        => true,
					'focusable'   => true,
				),
				'path'    => array(
					'fill'      => true,
					'fill-rule' => true,
					'd'         => true,
					'transform' => true,
				),
				'polygon' => array(
					'fill'      => true,
					'fill-rule' => true,
					'points'    => true,
					'transform' => true,
					'focusable' => true,
				),
				'title'   => array(),
			)
		);

		return apply_filters( 'highend_allowed_html_tags', $tags, $type );
	}
}